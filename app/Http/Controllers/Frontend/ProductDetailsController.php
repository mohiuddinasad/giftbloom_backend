<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Backend\Products\Product;

class ProductDetailsController extends Controller
{
    public function productDetails($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('status', true)
            ->with(['colors.images'])
            ->firstOrFail();

        $relatedProducts = Product::with(['colors.images'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', true)
            ->latest()
            ->take(4)
            ->get();

        return view('frontend.shop.productDetails', compact('product', 'relatedProducts'));
    }
}
