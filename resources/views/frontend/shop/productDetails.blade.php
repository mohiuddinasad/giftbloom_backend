@extends('frontend.layout')
@section('frontend_title')
    {{ $product->name }}
@endsection

@push('frontend_css')
    <style>
        .color-options {
            display: flex;
            flex-wrap: wrap;
            gap: .6rem;
        }

        .color-radio {
            display: none;
        }

        .color-btn {
            display: inline-flex;
            align-items: center;
            padding: .4rem .9rem;
            border: 1px solid #ddd;
            border-radius: 999px;
            cursor: pointer;
            font-size: .85rem;
            transition: .15s;
        }

        .color-radio:checked+.color-btn {
            border-color: #C86B4A;
            background: #fff3ee;
            color: #C86B4A;
            font-weight: 600;
        }
    </style>
@endpush
@section('frontend_content')
    <main>
        <!-- ========== Start product_details ========== -->
        <section id="product_details">
            <div class="container">

                <div class="row justify-content-between align-items-start">

                    <!-- Product Images -->
                    <div class="col-lg-5 product-sticky-content">

                        <div class="row align-items-center">

                            <!-- Thumbnail -->
                            <div class="col-lg-3 order-2 order-lg-1 slider-nav">

                                @forelse($product->colors as $color)
                                    @foreach ($color->images as $image)
                                        <div class="nav_img" data-color-id="{{ $color->id }}">
                                            <img class="img-fluid" src="{{ asset($image->image_path) }}"
                                                alt="{{ $product->name }}">
                                        </div>
                                    @endforeach
                                @empty
                                    <div class="nav_img">
                                        <img class="img-fluid" src="{{ asset('asset/image/products/1.jpg') }}"
                                            alt="{{ $product->name }}">
                                    </div>
                                @endforelse

                            </div>

                            <!-- Main Image -->
                            <div class="col-lg-9 order-1 order-lg-2 slider-for">

                                @forelse($product->colors as $color)
                                    @foreach ($color->images as $image)
                                        <div data-color-id="{{ $color->id }}">
                                            <img class="img-fluid example" src="{{ asset($image->image_path) }}"
                                                alt="{{ $product->name }}">
                                        </div>
                                    @endforeach
                                @empty
                                    <div>
                                        <img class="img-fluid example" src="{{ asset('asset/image/products/1.jpg') }}"
                                            alt="{{ $product->name }}">
                                    </div>
                                @endforelse

                            </div>

                        </div>

                    </div>


                    <!-- Product Information -->
                    <div class="col-lg-6">

                        <div class="product-info">

                            <div class="">

                                <h2 class="product-title">
                                    {{ $product->name }}
                                </h2>

                                <p class="product-price">
                                    Tk {{ number_format($product->price, 2) }}
                                </p>

                                <div class="product-divider"></div>

                                {{--
                                    DEBUG: color name na dekhale ei line ta ekbar
                                    temporary uncomment kore dekho DB te asholei
                                    color_name ache kina:

                                    {{ dump($product->colors->pluck('color_name', 'id')) }}
                                --}}

                                @php
                                    // shudhu color_name thakle e take ekta variant hisebe dhorbo
                                    $namedColors = $product->colors->filter(fn($c) => filled($c->color_name));
                                @endphp

                                @if ($namedColors->isNotEmpty())
                                    <!-- Color Selector -->
                                    <div class="product-colors">

                                        <h3 class="product-colors-title">
                                            Color
                                        </h3>

                                        <div class="color-options">

                                            @foreach ($namedColors as $color)
                                                <input type="radio" name="product-color" id="color-{{ $color->id }}"
                                                    value="{{ $color->id }}" class="color-radio"
                                                    data-color-id="{{ $color->id }}" @checked($loop->first)>
                                                <label for="color-{{ $color->id }}" class="color-btn">
                                                    {{ $color->color_name }}
                                                </label>
                                            @endforeach

                                        </div>

                                    </div>
                                @endif

                                <!-- Quantity + Add to Cart -->
                                <div class="product-actions">

                                    <div class="qty-selector">
                                        <button type="button" class="qty-btn qty-minus"
                                            aria-label="Decrease quantity">−</button>
                                        <span class="qty-value">1</span>
                                        <button type="button" class="qty-btn qty-plus"
                                            aria-label="Increase quantity">+</button>
                                    </div>


                                    <button class="btn btn-add-cart" data-product-id="{{ $product->id }}">

                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">

                                            <circle cx="9" cy="21" r="1"></circle>
                                            <circle cx="20" cy="21" r="1"></circle>
                                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6">
                                            </path>

                                        </svg>

                                        Add to cart

                                    </button>

                                </div>


                                <!-- Buy Now -->
                                <button class="btn btn-buy-now" data-product-id="{{ $product->id }}">
                                    Buy it now
                                </button>

                            </div>


                            <!-- Description -->
                            @if ($product->short_description || $product->description)
                                <div class="product-list">
                                    <h3 class="product-list-title">
                                        Description
                                    </h3>
                                    @if ($product->short_description)
                                        <p>{{ $product->short_description }}</p>
                                    @endif
                                    @if ($product->description)
                                        <div>{!! $product->description !!}</div>
                                    @endif
                                </div>
                            @endif

                        </div>

                    </div>

                </div>

            </div>
        </section>
        <!-- ========== End product_details ========== -->


        <!-- ========== start related product ========== -->
        <!-- products -->
        <section id="products">
            <div class="container">
                <div class="contain">
                    <h4 class="mb-3">You may also like
                    </h4>
                </div>
                <div class="row g-4">

                    @foreach ($relatedProducts as $related)
                        @php
                            $hasColors = $related->colors->filter(fn($c) => filled($c->color_name))->isNotEmpty();
                        @endphp
                        <div class="col-lg-3 col-6">
                            <div class="product-card">
                                <div class="product-image">
                                    <a href="{{ route('frontend.product.details', $related->slug) }}">
                                        <img src="{{ $related->colors->first()?->images->first()?->url }}"
                                            alt="{{ $related->name }}">
                                    </a>
                                    @unless ($hasColors)
                                        <button class="cart-btn" aria-label="Add to cart" data-product-id="{{ $related->id }}"
                                            data-color-id="{{ $related->colors->first()?->id }}">
                                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect x="3" y="7" width="18" height="12" rx="2"
                                                    stroke="currentColor" stroke-width="1.6" />
                                                <path d="M8 7V5.5A1.5 1.5 0 0 1 9.5 4h5A1.5 1.5 0 0 1 16 5.5V7"
                                                    stroke="currentColor" stroke-width="1.6" />
                                                <path d="M3 12h18" stroke="currentColor" stroke-width="1.6" />
                                            </svg>
                                        </button>
                                    @endunless
                                </div>
                                <div class="product-info">
                                    <h3 class="product-title">{{ $related->name }}</h3>
                                    <p class="product-price">Tk {{ number_format($related->price, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach


                </div>
            </div>
        </section>
        <!-- ========== End related product ========== -->
    </main>


@endsection

@push('frontend_js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const minQty = 1;
            const maxQty = 99;

            document.querySelectorAll('.qty-selector').forEach(function(selector) {
                const minusBtn = selector.querySelector('.qty-minus');
                const plusBtn = selector.querySelector('.qty-plus');
                const qtyValue = selector.querySelector('.qty-value');

                minusBtn.addEventListener('click', function() {
                    let current = parseInt(qtyValue.textContent.trim(), 10);
                    if (current > minQty) qtyValue.textContent = current - 1;
                });

                plusBtn.addEventListener('click', function() {
                    let current = parseInt(qtyValue.textContent.trim(), 10);
                    if (current < maxQty) qtyValue.textContent = current + 1;
                });
            });

            // ---- Color-wise gallery switching (Slick asNavFor pattern) ----
            const colorRadios = document.querySelectorAll('.color-radio');

            colorRadios.forEach(function(radio) {
                radio.addEventListener('change', function() {
                    if (!radio.checked) return;

                    const target = document.querySelector(
                        '.slider-nav .nav_img[data-color-id="' + radio.value + '"]'
                    );

                    if (target) {
                        target.click();
                    }
                });
            });

            // ---- Add to cart (product details page) ----
            const addCartBtn = document.querySelector('.btn-add-cart');

            if (addCartBtn) {
                addCartBtn.addEventListener('click', function() {
                    const productId = this.dataset.productId;
                    const qty = parseInt(document.querySelector('.qty-value').textContent.trim(), 10);

                    const checkedColor = document.querySelector('.color-radio:checked');
                    const colorId = checkedColor ? checkedColor.value : '';

                    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                    addCartBtn.disabled = true;

                    fetch('/cart/add', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                            },
                            body: JSON.stringify({
                                product_id: productId,
                                color_id: colorId,
                                qty: qty,
                            }),
                        })
                        .then(res => res.json())
                        .then(data => {
                            window.refreshCartUI(data);
                            new bootstrap.Offcanvas(document.getElementById('offcanvasRight')).show();
                        })
                        .finally(() => {
                            addCartBtn.disabled = false;
                        });
                });
            }
        });
    </script>
@endpush
