@extends('frontend.layout')

@section('frontend_title')
    Giftbloom | Home
@endsection
@push('frontend_css')
    <style>
        .perfect-picks .contain h4 {
            font-weight: 700;
            margin-bottom: 10px;
        }

        .pick-card {
            position: relative;
            background: #e7d9c9;
            border-radius: 14px;
            overflow: hidden;
            height: 220px;
            display: flex;
            align-items: center;
        }

        .pick-text {
            position: absolute;
            top: 24px;
            left: 24px;
            z-index: 2;
        }

        .pick-text h5 {
            font-weight: 700;
            font-size: 26px;
            line-height: 1.2;
            color: #1a1a1a;
            margin: 0;
        }

        .pick-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: right center;
        }

        @media (max-width: 767px) {
            .pick-card {
                height: 180px;
            }

            .pick-text h5 {
                font-size: 20px;
            }
        }
    </style>
@endpush
@section('frontend_content')
    <section id="banner">
        <div class="container-fluid p-0">
            <div class="banner_slide">
                @foreach ($imageBanners as $banner)
                    <div class="slider">
                        <img style="width: 100%; object-fit: cover; height: 330px;" class="img-fluid"
                            src="{{ asset($banner->image) }}" alt="Banner Image">
                    </div>
                @endforeach

            </div>
        </div>

    </section>

    <!-- perfrctpicks -->
    <section id="perfect" class="py-4 perfect-picks">
        <div class="container">
            <div class="contain">
                <h4>Perfect Picks for Every Relationship</h4>
            </div>
            <div class="row g-3 mt-2">

                <div class="col-md-4">
                    <a href="{{ route('frontend.gift.for', 'man') }}" class="pick-card d-block text-decoration-none">

                        <img src="{{ asset('frontend/asset/image/1.jpg') }}" alt="Gift For Him" class="pick-img img-fluid">
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="{{ route('frontend.gift.for', 'women') }}" class="pick-card d-block text-decoration-none">

                        <img src="{{ asset('frontend/asset/image/2.jpg') }}" alt="Gift For Her" class="pick-img img-fluid">
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="{{ route('frontend.shop') }}"
                        class="pick-card d-block text-decoration-none">

                        <img src="{{ asset('frontend/asset/image/3.jpg') }}" alt="Gift For Friend" class="pick-img img-fluid">
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
                <a href="{{ route('frontend.gift-packages') }}" style="color: #E8A99C;">View All</a>
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

                        <img class="" src="{{ asset('frontend/asset/image/custom.jpg') }}" alt="Gift Wraping">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="gift-wraping-content">
                        <h4>Customize Your Own Gift
                        </h4>
                        <p>
                            Add a personal touch to your gift with our customizable wrapping options. Choose from a variety
                            of colors, patterns, and materials to create a unique presentation that reflects your style and
                            the recipient's taste. Make your gift truly special with our personalized wrapping services.

                        </p>
                        <a href="{{ route('frontend.gift.build') }}" class="btn btn-primary">Customize Now</a>
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
                <a style="color: #E8A99C;" href="{{ route('frontend.shop') }}">View All</a>
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



            @foreach ($videoBanners as $banner)
                <div class="video-card">
                    <video class="hls-video" data-video="{{ $banner->video_url }}" autoplay muted loop playsinline>
                    </video>
                </div>
            @endforeach


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
