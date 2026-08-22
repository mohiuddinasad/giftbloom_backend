<?php

namespace App\Http\Controllers\Backend\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Products\StoreProductRequest;
use App\Http\Requests\Backend\Products\UpdateProductRequest;
use App\Models\Backend\Products\Category;
use App\Models\Backend\Products\Product;
use App\Models\Backend\Products\ProductColor;
use App\Models\Backend\Products\ProductImage;
use Illuminate\Http\UploadedFile; 
use Illuminate\Support\Facades\DB;

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

            // colors are optional now - only sync if the user actually added any
            if (! empty($data['colors'])) {
                $this->syncColorsAndImages($product, $data['colors']);
            }

            return $product;
        });

        return redirect()
            ->route('dashboard.products.index')
            ->with('success', "Product \"{$product->name}\" created.");
    }

    // $product resolved by slug
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
            // qty is part of $data now, so it updates directly here
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
        $name = $product->name;

        $product->deleteWithFiles();

        return back()->with('success', "Product \"{$name}\" deleted.");
    }

    /**
     * Persist "colors[i][color_name/color_code/images[]]" as
     * ProductColor + ProductImage rows for the given product.
     * color_name may be empty - it's just used as a container for images
     * when the product doesn't really have color variants.
     */
    private function syncColorsAndImages(Product $product, array $colors): void
    {
        foreach ($colors as $index => $colorData) {
            // skip a completely empty row (no name, no images) that may
            // come through from the dynamic form if the user added then
            // emptied a row without removing it
            if (empty($colorData['color_name']) && empty($colorData['images'])) {
                continue;
            }

            $color = ProductColor::create([
                'product_id' => $product->id,
                'color_name' => $colorData['color_name'] ?? null,
                'color_code' => $colorData['color_code'] ?? null,
                'sort_order' => $index,
            ]);

            foreach (($colorData['images'] ?? []) as $imgIndex => $file) {
                $path = $this->uploadImage($file, 'uploads/products/'.$product->id);

                ProductImage::create([
                    'product_color_id' => $color->id,
                    'image_path' => $path,
                    'is_primary' => $imgIndex === 0,
                    'sort_order' => $imgIndex,
                ]);
            }
        }
    }

    private function uploadImage(UploadedFile $file, string $folder): string
    {
        $filename = uniqid().'_'.time().'.'.$file->getClientOriginalExtension();
        $file->move(public_path($folder), $filename);

        return $folder.'/'.$filename;
    }
}
