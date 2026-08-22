@extends('backend.layout')
@section('backend_title', 'Products')
@section('backend_content')
@include('backend.products._styles')

<div class="cp-wrap">
    <div class="cp-header">
        <div>
            <div class="cp-breadcrumb"><a href="{{ route('dashboard') }}">Dashboard</a> / Products</div>
            <h1>Products</h1>
            <div class="cp-subtitle">Manage your catalog, pricing, and stock quantity.</div>
        </div>
        <a href="{{ route('dashboard.products.create') }}" class="cp-btn cp-btn-primary">
            <i class="bi bi-plus-lg"></i> Add Product
        </a>
    </div>

    @if (session('success'))
        <div class="cp-alert cp-alert-success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
    @endif

    <div class="cp-card">
        @if ($products->isEmpty())
            <div class="cp-empty">
                <i class="bi bi-box-seam"></i>
                <p>No products yet.</p>
                <a href="{{ route('dashboard.products.create') }}" class="cp-btn cp-btn-primary">
                    <i class="bi bi-plus-lg"></i> Add your first product
                </a>
            </div>
        @else
            <div class="cp-table-scroll">
                <table class="cp-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Qty</th>
                            {{-- <th>Status</th> --}}
                            <th style="text-align:right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            @php $firstImage = $product->colors->first()?->images->first(); @endphp
                            <tr>
                                <td>
                                    @if ($firstImage)
                                        <img src="{{ $firstImage->url }}" class="cp-thumb">
                                    @else
                                        <span class="cp-thumb-placeholder"><i class="bi bi-image"></i></span>
                                    @endif
                                </td>
                                <td>
                                    <div><strong>{{ $product->name }}</strong></div>
                                    <div class="cp-subtitle" style="font-size:.76rem">{{ $product->sku }}</div>
                                </td>
                                <td>{{ $product->category->name }}</td>
                                <td>
                                    <strong>{{ number_format($product->price, 2) }}</strong>
                                    @if ($product->discount_price)
                                        <div class="cp-subtitle" style="font-size:.76rem;text-decoration:line-through">{{ number_format($product->discount_price, 2) }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if ($product->qty > 0)
                                        <span class="cp-badge cp-badge-green">{{ $product->qty }}</span>
                                    @else
                                        <span class="cp-badge cp-badge-red">Out of stock</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="cp-row-actions" style="justify-content:flex-end">
                                        <a href="{{ route('dashboard.products.edit', $product) }}" class="cp-btn cp-btn-outline cp-btn-sm">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('dashboard.products.destroy', $product) }}" method="POST"
                                              onsubmit="return confirm('Delete this product?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="cp-btn cp-btn-danger cp-btn-sm">
                                                <i class="bi bi-trash3"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="d-flex justify-content-center">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
