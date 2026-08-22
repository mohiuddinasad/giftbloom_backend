@extends('frontend.layout')

@section('frontend_title')
    Giftbloom | Home
@endsection

@section('frontend_content')
    <section id="banner">
        <div class="container-fluid p-0">
            <div class="banner_slide">
                <div class="slider">
                    <img class="img-fluid" src="{{ asset('frontend/asset/image/banner/new_banner_1.webp') }}"
                        alt="Banner Image">
                </div>
                <div class="slider">
                    <img class="img-fluid"
                        src="{{ asset('frontend/asset/image/banner/Delivery_1_fcc80ebc-e766-43be-ab9b-d5c9feba90b6.webp') }}"
                        alt="Banner Image">
                </div>
            </div>
        </div>

    </section>

    <!-- perfrctpicks -->
    <section id="perfect" class="py-4">
        <div class="container">
            <div class="contain">
                <h4>Perfect Picks for Every Relationship
                </h4>
            </div>
            <div class="row">

                <div class="category-row">

                    <a class="category-card" href="#" aria-label="For Her">
                        <div class="card-image">
                            <img src="{{ asset('https://picsum.photos/seed/formom/400/420') }}" alt="For Her">
                        </div>
                        <div class="card-label">For Her</div>
                    </a>

                    <div class="card-divider">
                        <span class="divider-icon">
                            <iconify-icon icon="lets-icons:bag" width="24" height="24"></iconify-icon>
                        </span>
                    </div>

                    <a class="category-card" href="#" aria-label="For Him">
                        <div class="card-image">
                            <img src="{{ asset('https://picsum.photos/seed/forhim/400/420') }}" alt="For Him">
                        </div>
                        <div class="card-label">For Him</div>
                    </a>

                    <div class="card-divider">
                        <span class="divider-icon">
                            <iconify-icon icon="lets-icons:bag" width="24" height="24"></iconify-icon>
                        </span>
                    </div>

                    <a class="category-card" href="#" aria-label="For Mom">
                        <div class="card-image">
                            <img src="https://picsum.photos/seed/formom/400/420" alt="For Mom">
                        </div>
                        <div class="card-label">For Mom</div>
                    </a>

                    <div class="card-divider">
                        <span class="divider-icon">
                            <iconify-icon icon="lets-icons:bag" width="24" height="24"></iconify-icon>
                        </span>
                    </div>

                    <a class="category-card" href="#" aria-label="For dad">
                        <div class="card-image">
                            <img src="https://picsum.photos/seed/fordad/400/420" alt="For dad">
                        </div>
                        <div class="card-label">For dad</div>
                    </a>

                    <div class="card-divider">
                        <span class="divider-icon">
                            <iconify-icon icon="lets-icons:bag" width="24" height="24"></iconify-icon>
                        </span>
                    </div>

                    <a class="category-card" href="#" aria-label="For Best Friend">
                        <div class="card-image">
                            <img src="https://picsum.photos/seed/forbestfriend/400/420" alt="For Best Friend">
                        </div>
                        <div class="card-label">For Friend</div>
                    </a>

                </div>
            </div>
        </div>
    </section>
    <hr>



    <!-- products -->
    <section id="products">
        <div class="container">
            <div class="contain d-flex justify-content-between align-items-center mb-4">
                <h4>Ready To Go Gift Packages</h4>
                <a href="" style="color: #E8A99C;">View All</a>
            </div>
            <div class="row g-4">
                @foreach ($giftPakageProducts as $product)
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

    <!-- gift wraping -->

    <section id="gift_wraping" class="py-4">
        <div class="container p-3">
            <div class="row align-items-center rounded-3 p-4" style="border: 1px solid #FDE6D8;">
                <div class="col-lg-6">
                    <div class="wraping">

                        <img class=""
                            src="https://prezentobd.com/cdn/shop/files/ezgif-70903d5ed8ed67.gif?v=1759782763&width=1000"
                            alt="Gift Wraping">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="gift-wraping-content">
                        <h4>DON'T FORGET TO ADD WRAPPING
                        </h4>
                        <p>Complete your purchase with our elegant gift wrapping — beautifully packaged in a premium box
                            for only 200 ৳. Make every gift extra special!

                        </p>
                        <a href="" class="btn btn-primary">Add Wrapping</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="price_range" class="py-4">
        <div class="container">


            <h2 class="gallery-title mb-4">Shop By Price Range</h2>

            <div class="row g-3">
                <!-- Row 1 -->
                <div class="col-6 col-md-4">
                    <a href="#" class="price-card">
                        <img src="{{ asset('frontend/asset/image/price/1.webp') }}" alt="">
                        <span class="price-badge">৳100–৳300</span>
                    </a>
                </div>
                <div class="col-6 col-md-4">
                    <a href="#" class="price-card">
                        <img src="{{ asset('frontend/asset/image/price/2.webp') }}" alt="">
                        <span class="price-badge">৳300–৳600</span>
                    </a>
                </div>
                <div class="col-12 col-md-4">
                    <a href="#" class="price-card">
                        <img src="{{ asset('frontend/asset/image/price/3.webp') }}" alt="">
                        <span class="price-badge">৳800–৳1000</span>
                    </a>
                </div>

                <!-- Row 2 -->
                <div class="col-12 col-md-6">
                    <a href="#" class="price-card">
                        <img src="{{ asset('frontend/asset/image/price/4.webp') }}" alt="">
                        <span class="ribbon">Luxury</span>
                        <span class="price-badge">৳1500–৳3000</span>
                    </a>
                </div>
                <div class="col-12 col-md-6">
                    <a href="#" class="price-card">
                        <img src="{{ asset('frontend/asset/image/price/5.webp') }}" alt="">
                        <span class="ribbon">Best Seller</span>
                        <span class="price-badge">৳2000–৳5000</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- favorites -->
    <section id="favorites">
        <div class="container">
            <div class="contain d-flex justify-content-between align-items-center mb-4">
                <h4>Pick Your Favorites</h4>
                <a style="color: #E8A99C;" href="">View All</a>
            </div>
            <div class="row g-4">
                @foreach ($giftItemProducts as $product)
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

    <!--------------------- vedio --------------------->
    <section id="vedio" class="py-4 px-2">

        <div class="video-container">

            <div class="video-card">
                <video class="hls-video"
                    data-video="https://cdn.shopify.com/videos/c/vp/ca99d18f29f14ff3b29b0c7ff3288159/ca99d18f29f14ff3b29b0c7ff3288159.m3u8"
                    autoplay muted loop playsinline>
                </video>
            </div>


            <div class="video-card">
                <video class="hls-video"
                    data-video="https://cdn.shopify.com/videos/c/vp/ca99d18f29f14ff3b29b0c7ff3288159/ca99d18f29f14ff3b29b0c7ff3288159.m3u8"
                    autoplay muted loop playsinline>
                </video>
            </div>
            <div class="video-card">
                <video class="hls-video"
                    data-video="https://cdn.shopify.com/videos/c/vp/ca99d18f29f14ff3b29b0c7ff3288159/ca99d18f29f14ff3b29b0c7ff3288159.m3u8"
                    autoplay muted loop playsinline>
                </video>
            </div>
            <div class="video-card">
                <video class="hls-video"
                    data-video="https://cdn.shopify.com/videos/c/vp/ca99d18f29f14ff3b29b0c7ff3288159/ca99d18f29f14ff3b29b0c7ff3288159.m3u8"
                    autoplay muted loop playsinline>
                </video>
            </div>
            <div class="video-card">
                <video class="hls-video"
                    data-video="https://cdn.shopify.com/videos/c/vp/ca99d18f29f14ff3b29b0c7ff3288159/ca99d18f29f14ff3b29b0c7ff3288159.m3u8"
                    autoplay muted loop playsinline>
                </video>
            </div>
            <div class="video-card">
                <video class="hls-video"
                    data-video="https://cdn.shopify.com/videos/c/vp/ca99d18f29f14ff3b29b0c7ff3288159/ca99d18f29f14ff3b29b0c7ff3288159.m3u8"
                    autoplay muted loop playsinline>
                </video>
            </div>

        </div>



        <!-- =========================
                     VIDEO PREVIEW MODAL
                ========================= -->

        <div class="video-modal" id="videoModal">

            <button type="button" class="close-video" id="closeVideo">
                &times;
            </button>

            <video id="previewVideo" controls playsinline loop>
            </video>

        </div>

    </section>
@endsection
