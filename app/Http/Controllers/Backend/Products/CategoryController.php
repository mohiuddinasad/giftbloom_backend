<?php

namespace App\Http\Controllers\Backend\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Products\StoreCategoryRequest;
use App\Http\Requests\Backend\Products\UpdateCategoryRequest;
use App\Models\Backend\Products\Category;
use Illuminate\Http\UploadedFile;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('children')
            ->withCount('products')
            ->parents()
            ->orderBy('sort_order')
            ->paginate(20);

        return view('backend.products.categories.index', compact('categories'));
    }

    public function create()
    {
        $parentCategories = Category::parents()->orderBy('name')->get();

        return view('backend.products.categories.create', compact('parentCategories'));
    }

    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request->file('image'), 'uploads/categories');
        }

        $data['status'] = $request->boolean('status', true);

        $category = Category::create($data);

        return redirect()
            ->route('dashboard.categories.index')
            ->with('success', "Category \"{$category->name}\" created.");
    }

    // $category resolved by slug
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
            $this->deletePublicFile($category->image);
            $data['image'] = $this->uploadImage($request->file('image'), 'uploads/categories');
        }

        $data['status'] = $request->boolean('status', true);

        $category->update($data);

        return redirect()
            ->route('dashboard.categories.index')
            ->with('success', "Category \"{$category->name}\" updated.");
    }

    // deleting ANY category (top-level or sub-category) is allowed;
    // it cascades to its sub-categories and every product under them
    public function destroy(Category $category)
    {
        $name = $category->name;

        $category->deleteWithChildrenAndProducts();

        return redirect()
            ->route('dashboard.categories.index')
            ->with('success', "Category \"{$name}\" and everything under it was deleted.");
    }

    /**
     * Move an uploaded file into /public/{$folder} with a random unique
     * name and return the path relative to /public (stored in the DB).
     */
    private function uploadImage(UploadedFile $file, string $folder): string
    {
        $filename = uniqid().'_'.time().'.'.$file->getClientOriginalExtension();
        $file->move(public_path($folder), $filename);

        return $folder.'/'.$filename;
    }

    private function deletePublicFile(?string $relativePath): void
    {
        if (! $relativePath) {
            return;
        }

        $path = public_path($relativePath);
        if (file_exists($path)) {
            @unlink($path);
        }
    }
}
