<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Backend\Products\Category;
use App\Models\Backend\Products\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $categories = Category::with('parent')
            ->withCount('products')
            ->parents()
            ->get();
        $products = Product::where('status', true)
            ->with(['colors.images'])
            ->latest()
            ->get();
        return view('frontend.shop.product', compact('products', 'categories'));
    }

    public function categoryWiseProduct($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $products = Product::where('category_id', $category->id)
            ->where('status', true)
            ->with(['colors.images'])
            ->latest()
            ->get();
        return view('frontend.shop.product', compact('products', 'category'));
    }

    public function giftPackages()
    {
        $categories = Category::with('parent')
            ->withCount('products')
            ->parents()
            ->get();
        $products = Product::where('status', true)
            ->where('type', 'gift_package')
            ->with(['colors.images'])
            ->latest()
            ->get();
        return view('frontend.shop.product', compact('products', 'categories'));
    }
}
