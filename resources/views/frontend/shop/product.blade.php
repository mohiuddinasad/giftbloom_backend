@extends('frontend.layout')
@section('frontend_title', 'Shop')
@section('frontend_content')
    <main>

        <section id="header" class="pt-4">
            <div class="container">
                <h2>{{ $category->name ?? 'Our Products' }}</h2>
            </div>
        </section>
        <!-- products -->
        <section id="products">
            <div class="container">

                <div class="row g-4">

                    @foreach ($products as $product)
                        @php
                        $hasColors = $product->colors->filter(fn($c) => filled($c->color_name))->isNotEmpty();
                    @endphp
                    <div class="col-lg-3 col-6">
                        <div class="product-card">
                            <div class="product-image">
                                <a href="{{ route('frontend.product.details', $product->slug) }}">
                                    <img src="{{ $product->colors->first()?->images->first()?->url }}"
                                        alt="{{ $product->name }}">
                                </a>
                                @unless ($hasColors)
                                    <button class="cart-btn" aria-label="Add to cart" data-product-id="{{ $product->id }}"
                                        data-color-id="{{ $product->colors->first()?->id }}">
                                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect x="3" y="7" width="18" height="12" rx="2" stroke="currentColor"
                                                stroke-width="1.6" />
                                            <path d="M8 7V5.5A1.5 1.5 0 0 1 9.5 4h5A1.5 1.5 0 0 1 16 5.5V7"
                                                stroke="currentColor" stroke-width="1.6" />
                                            <path d="M3 12h18" stroke="currentColor" stroke-width="1.6" />
                                        </svg>
                                    </button>
                                @endunless
                            </div>
                            <div class="product-info">
                                <h3 class="product-title">{{ $product->name }}</h3>
                                <p class="product-price">Tk {{ number_format($product->price, 2) }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach



                </div>
            </div>
        </section>
    </main>

@endsection
