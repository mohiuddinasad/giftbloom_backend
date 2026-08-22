@extends('backend.layout')
@section('backend_title', 'Categories')
@section('backend_content')
@include('backend.products._styles')

<div class="cp-wrap">
    <div class="cp-header">
        <div>
            <div class="cp-breadcrumb"><a href="{{ route('dashboard') }}">Dashboard</a> / Categories</div>
            <h1>Categories</h1>
            <div class="cp-subtitle">Organize your products into categories and sub-categories.</div>
        </div>
        <a href="{{ route('dashboard.categories.create') }}" class="cp-btn cp-btn-primary">
            <i class="bi bi-plus-lg"></i> Add Category
        </a>
    </div>

    @if (session('success'))
        <div class="cp-alert cp-alert-success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="cp-alert cp-alert-danger"><i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}</div>
    @endif

    <div class="cp-card">
        @if ($categories->isEmpty())
            <div class="cp-empty">
                <i class="bi bi-folder2-open"></i>
                <p>No categories yet.</p>
                <a href="{{ route('dashboard.categories.create') }}" class="cp-btn cp-btn-primary">
                    <i class="bi bi-plus-lg"></i> Add your first category
                </a>
            </div>
        @else
            <div class="cp-table-scroll">
                <table class="cp-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Products</th>
                            <th>Status</th>
                            <th style="text-align:right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>
                                    @if ($category->image)
                                        <img src="{{ $category->image_url }}" class="cp-thumb">
                                    @else
                                        <span class="cp-thumb-placeholder"><i class="bi bi-image"></i></span>
                                    @endif
                                </td>
                                <td><strong>{{ $category->name }}</strong></td>
                                <td>{{ $category->products_count }}</td>
                                <td>
                                    @if ($category->status)
                                        <span class="cp-badge cp-badge-green"><span class="cp-badge-dot"></span> Active</span>
                                    @else
                                        <span class="cp-badge cp-badge-gray"><span class="cp-badge-dot"></span> Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="cp-row-actions" style="justify-content:flex-end">
                                        <a href="{{ route('dashboard.categories.edit', $category) }}" class="cp-btn cp-btn-outline cp-btn-sm">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('dashboard.categories.destroy', $category) }}" method="POST"
                                              onsubmit="return confirm('Delete \'{{ $category->name }}\'? This will also delete all its sub-categories and products.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="cp-btn cp-btn-danger cp-btn-sm">
                                                <i class="bi bi-trash3"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            @foreach ($category->children as $child)
                                <tr class="cp-row-child">
                                    <td></td>
                                    <td><i class="bi bi-arrow-return-right" style="color:#c7cbd4"></i> {{ $child->name }}</td>
                                    <td>{{ $child->products()->count() }}</td>
                                    <td>
                                        @if ($child->status)
                                            <span class="cp-badge cp-badge-green"><span class="cp-badge-dot"></span> Active</span>
                                        @else
                                            <span class="cp-badge cp-badge-gray"><span class="cp-badge-dot"></span> Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="cp-row-actions" style="justify-content:flex-end">
                                            <a href="{{ route('dashboard.categories.edit', $child) }}" class="cp-btn cp-btn-outline cp-btn-sm">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <form action="{{ route('dashboard.categories.destroy', $child) }}" method="POST"
                                                  onsubmit="return confirm('Delete sub-category \'{{ $child->name }}\'? This will also delete its products.')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="cp-btn cp-btn-danger cp-btn-sm">
                                                    <i class="bi bi-trash3"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="d-flex justify-content-center">
        {{ $categories->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
