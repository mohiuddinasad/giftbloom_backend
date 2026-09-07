<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Backend\Products\Category;
use App\Models\Backend\Products\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::with('parent')
            ->withCount('products')
            ->parents()
            ->get();

        $query = Product::where('status', true)
            ->with(['colors.images']);

        $this->applyFilters($query, $request);

        $products = $query->paginate(12)->withQueryString();

        return view('frontend.shop.product', compact('products', 'categories'));
    }

    public function categoryWiseProduct(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $query = Product::where('category_id', $category->id)
            ->where('status', true)
            ->with(['colors.images']);

        $this->applyFilters($query, $request);

        $products = $query->paginate(12)->withQueryString();

        return view('frontend.shop.product', compact('products', 'category'));
    }

    public function giftPackages(Request $request)
    {
        $categories = Category::with('parent')
            ->withCount('products')
            ->parents()
            ->get();

        $query = Product::where('status', true)
            ->where('type', 'gift_package')
            ->with(['colors.images']);

        $this->applyFilters($query, $request);

        $products = $query->paginate(12)->withQueryString();

        return view('frontend.shop.product', compact('products', 'categories'));
    }

    public function giftFor(Request $request, $giftFor)
    {
        // Only allow values defined in Product model
        if (!array_key_exists($giftFor, Product::GIFT_FOR)) {
            abort(404);
        }

        $query = Product::active()
            ->giftFor($giftFor)
            ->with(['colors.images']);

        $this->applyFilters($query, $request);

        $products = $query->paginate(12)->withQueryString();

        $giftForName = Product::GIFT_FOR[$giftFor];

        return view('frontend.shop.product', compact(
            'products',
            'giftFor',
            'giftForName'
        ));
    }

    /**
     * Apply price / availability / sort filters shared across all shop pages.
     */
    protected function applyFilters($query, Request $request)
    {
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        if ($request->filled('availability')) {
            $availability = $request->availability;
            $query->where(function ($q) use ($availability) {
                if (in_array('in_stock', $availability)) {
                    $q->orWhere('stock', '>', 0); // আপনার actual stock/quantity field নাম বসান
                }
                if (in_array('out_of_stock', $availability)) {
                    $q->orWhere('stock', '<=', 0);
                }
            });
        }

        match ($request->sort) {
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            default => $query->latest(),
        };
    }
}
