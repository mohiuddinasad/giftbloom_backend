@extends('backend.layout')

@section('backend_title', 'Categories')

@section('backend_content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Categories</h4>
        <a href="{{ route('dashboard.categories.create') }}" class="btn btn-primary">+ Add Category</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Sub-categories</th>
                <th>Products</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr>
                    <td>
                        @if ($category->image)
                            <img src="{{ $category->image_url }}" width="40" height="40" style="object-fit:cover;border-radius:4px;">
                        @endif
                    </td>
                    <td>{{ $category->name }}</td>
                    <td>
                        @foreach ($category->children as $child)
                            <span class="badge bg-secondary">{{ $child->name }}</span>
                        @endforeach
                    </td>
                    <td>{{ $category->products_count }}</td>
                    <td>
                        <span class="badge bg-{{ $category->status ? 'success' : 'secondary' }}">
                            {{ $category->status ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        {{-- route() auto-builds a slug URL since getRouteKeyName() = 'slug' --}}
                        <a href="{{ route('dashboard.categories.edit', $category) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="{{ route('dashboard.categories.destroy', $category) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this category?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $categories->links() }}
</div>
@endsection
