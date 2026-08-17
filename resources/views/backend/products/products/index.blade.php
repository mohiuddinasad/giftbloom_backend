@extends('backend.layout')

@section('backend_title', 'Products')

@section('backend_content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Products</h4>
        <a href="{{ route('dashboard.products.create') }}" class="btn btn-primary">+ Add Product</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Type</th>
                <th>Gift For</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                @php $firstImage = $product->colors->first()?->images->first(); @endphp
                <tr>
                    <td>
                        @if ($firstImage)
                            <img src="{{ $firstImage->url }}" width="40" height="40" style="object-fit:cover;border-radius:4px;">
                        @endif
                    </td>
                    <td>{{ $product->name }}<br><small class="text-muted">{{ $product->sku }}</small></td>
                    <td>{{ $product->category->name }}</td>
                    <td>{{ \App\Models\Backend\Products\Product::TYPES[$product->type] }}</td>
                    <td>{{ $product->gift_for ? \App\Models\Backend\Products\Product::GIFT_FOR[$product->gift_for] : '-' }}</td>
                    <td>{{ number_format($product->price, 2) }}</td>
                    <td>
                        <span class="badge bg-{{ $product->qty > 0 ? 'success' : 'danger' }}">{{ $product->qty }}</span>
                    </td>
                    <td>
                        <span class="badge bg-{{ $product->status ? 'success' : 'secondary' }}">
                            {{ $product->status ? 'Published' : 'Draft' }}
                        </span>
                    </td>
                    <td class="text-nowrap">
                        {{-- route() auto-builds slug URLs since Product::getRouteKeyName() = 'slug' --}}
                        <a href="{{ route('dashboard.products.edit', $product) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <a href="{{ route('dashboard.products.stock.index', $product) }}" class="btn btn-sm btn-outline-dark">Stock</a>
                        <form action="{{ route('dashboard.products.destroy', $product) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this product?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $products->links() }}
</div>
@endsection
