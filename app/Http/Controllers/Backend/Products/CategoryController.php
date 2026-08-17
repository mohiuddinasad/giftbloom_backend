<?php

namespace App\Http\Controllers\Backend\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Products\StoreCategoryRequest;
use App\Http\Requests\Backend\Products\UpdateCategoryRequest;
use App\Models\Backend\Products\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        // top-level categories with their sub-categories eager loaded,
        // plus a product count for a quick glance in the list view
        $categories = Category::with('children')
            ->withCount('products')
            ->parents()
            ->orderBy('sort_order')
            ->paginate(20);

        return view('backend.products.categories.index', compact('categories'));
    }

    public function create()
    {
        // only top-level categories can be picked as a parent
        $parentCategories = Category::parents()->orderBy('name')->get();

        return view('backend.products.categories.create', compact('parentCategories'));
    }

    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $data['status'] = $request->boolean('status', true);

        $category = Category::create($data);

        return redirect()
            ->route('dashboard.categories.index')
            ->with('success', "Category \"{$category->name}\" created.");
    }

    // $category is resolved by SLUG now (Category::getRouteKeyName() == 'slug')
    public function edit(Category $category)
    {
        $parentCategories = Category::parents()
            ->where('id', '!=', $category->id)
            ->orderBy('name')
            ->get();

        return view('backend.products.categories.edit', compact('category', 'parentCategories'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $data['status'] = $request->boolean('status', true);

        $category->update($data);

        return redirect()
            ->route('dashboard.categories.index')
            ->with('success', "Category \"{$category->name}\" updated.");
    }

    public function destroy(Category $category)
    {
        if ($category->children()->exists()) {
            return back()->with('error', 'Delete or move the sub-categories first.');
        }

        if ($category->products()->exists()) {
            return back()->with('error', 'This category still has products. Move or delete them first.');
        }

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return back()->with('success', 'Category deleted.');
    }
}
