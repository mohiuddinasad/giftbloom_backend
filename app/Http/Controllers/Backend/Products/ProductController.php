<?php

namespace App\Http\Controllers\Backend\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Products\StoreProductRequest;
use App\Http\Requests\Backend\Products\UpdateProductRequest;
use App\Models\Backend\Products\Category;
use App\Models\Backend\Products\Product;
use App\Models\Backend\Products\ProductColor;
use App\Models\Backend\Products\ProductImage;
use App\Models\Backend\Products\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'colors.images'])
            ->latest()
            ->paginate(20);

        return view('backend.products.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::active()->orderBy('name')->get();

        return view('backend.products.products.create', [
            'categories' => $categories,
            'types' => Product::TYPES,
            'giftFor' => Product::GIFT_FOR,
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        $product = DB::transaction(function () use ($data, $request) {
            $product = Product::create([
                ...collect($data)->except('colors')->toArray(),
                'status' => $request->boolean('status', true),
                'is_featured' => $request->boolean('is_featured'),
            ]);

            $this->syncColorsAndImages($product, $data['colors']);

            // initial stock qty logged as an "in" movement so the audit
            // trail always explains where the starting number came from
            if ($product->qty > 0) {
                StockMovement::record(
                    $product, 'in', $product->qty, 'Initial stock'
                );
            }

            return $product;
        });

        return redirect()
            ->route('dashboard.products.index')
            ->with('success', "Product \"{$product->name}\" created.");
    }

    // $product is resolved by SLUG now (Product::getRouteKeyName() == 'slug')
    public function edit(Product $product)
    {
        $product->load('colors.images');
        $categories = Category::active()->orderBy('name')->get();

        return view('backend.products.products.edit', [
            'product' => $product,
            'categories' => $categories,
            'types' => Product::TYPES,
            'giftFor' => Product::GIFT_FOR,
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, $request, $product) {
            $product->update([
                ...collect($data)->except('colors')->toArray(),
                'status' => $request->boolean('status', true),
                'is_featured' => $request->boolean('is_featured'),
            ]);

            if (! empty($data['colors'])) {
                $this->syncColorsAndImages($product, $data['colors']);
            }
        });

        return redirect()
            ->route('dashboard.products.index')
            ->with('success', "Product \"{$product->name}\" updated.");
    }

    public function destroy(Product $product)
    {
        DB::transaction(function () use ($product) {
            foreach ($product->colors as $color) {
                foreach ($color->images as $image) {
                    Storage::disk('public')->delete($image->image_path);
                }
            }
            $product->delete(); // colors/images/stock rows cascade via FK
        });

        return back()->with('success', 'Product deleted.');
    }

    /**
     * Persist "colors[i][color_name/color_code/images[]]" as
     * ProductColor + ProductImage rows for the given product.
     */
    private function syncColorsAndImages(Product $product, array $colors): void
    {
        foreach ($colors as $index => $colorData) {
            $color = ProductColor::create([
                'product_id' => $product->id,
                'color_name' => $colorData['color_name'],
                'color_code' => $colorData['color_code'] ?? null,
                'sort_order' => $index,
            ]);

            foreach (($colorData['images'] ?? []) as $imgIndex => $file) {
                $path = $file->store('products/'.$product->id, 'public');

                ProductImage::create([
                    'product_color_id' => $color->id,
                    'image_path' => $path,
                    'is_primary' => $imgIndex === 0, // first upload = primary
                    'sort_order' => $imgIndex,
                ]);
            }
        }
    }
}
