@php
    $cart = session()->get('cart', []);
    $cartQty = array_sum(array_column($cart, 'qty'));
    $cartTotal = collect($cart)->sum(fn($item) => $item['price'] * $item['qty']);
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('frontend/asset/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/asset/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/asset/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/asset/css/slick.min.css') }}">
    @stack('frontend_css')
    <title>@yield('frontend_title')</title>
</head>

<body>
    <header>


        <!-- navbar -->
        <nav class="navbar navbar-expand-lg">
            <div class="container d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">

                    <button style="border: none; outline: none;" class="navbar-toggler " type="button"
                        data-bs-toggle="offcanvas" href="#offcanvasExample" role="button"
                        aria-controls="offcanvasExample">
                        <iconify-icon icon="fa6-solid:bars" width="18" height="18"></iconify-icon>
                    </button>
                    <a href="" class="search d-lg-none mx-3" type="button" data-bs-toggle="modal"
                        data-bs-target="#exampleModal">
                        <iconify-icon icon="iconamoon:search-light" width="18" height="18"></iconify-icon>
                    </a>
                </div>
                <a class="navbar-brand logo m-0" href="{{ route('frontend.home') }}"><img
                        style="width: 100px; height: auto; object-fit: contain;" src="{{ asset($setting->site_logo) }}"
                        alt=""></a>

                <div class="cart_profile d-lg-none d-flex align-items-center">
                    <a href="{{ route('login') }}" class="user mx-2">
                        <iconify-icon icon="qlementine-icons:user-16" width="24" height="24"></iconify-icon>
                    </a>
                    <a href="" class="cart mx-2" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                        <iconify-icon icon="bi:bag" width="24" height="24"></iconify-icon>

                        <div class="cart_badge">
                            <span class="cart-count js-cart-count">{{ $cartQty }}</span>
                        </div>
                    </a>
                </div>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="{{ route('frontend.shop') }}">All
                                Items</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page"
                                href="{{ route('frontend.gift-packages') }}"> Packages</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="{{ route('frontend.gift.build') }}">
                                Customize Gift</a>
                        </li>

                    </ul>

                    <a href="" class="search mx-2 " type="button" data-bs-toggle="modal"
                        data-bs-target="#exampleModal">
                        <iconify-icon icon="iconamoon:search-light" width="24" height="24"></iconify-icon>
                    </a>
                    <a href="{{ route('login') }}" class="user mx-2">
                        <iconify-icon icon="qlementine-icons:user-16" width="24" height="24"></iconify-icon>
                    </a>
                    <a href="" class="cart mx-2" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                        <iconify-icon icon="bi:bag" width="24" height="24"></iconify-icon>

                        <div class="cart_badge">
                            <span class="cart-count js-cart-count">{{ $cartQty }}</span>
                        </div>
                    </a>

                </div>
            </div>
        </nav>

        <!----------------------- search  modal ----------------------->


        <!-- Modal (id = exampleModal, matches your trigger buttons) -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog ">
                <div class="modal-content">

                    <!-- Search input row (replaces modal-header) -->
                    <div class="search-input-wrap">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="7"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input type="text" id="searchInput" placeholder="Search" autocomplete="off">
                        <button type="button" class="btn-close-search" data-bs-dismiss="modal" aria-label="Close">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                <line x1="4" y1="4" x2="20" y2="20"></line>
                                <line x1="20" y1="4" x2="4" y2="20"></line>
                            </svg>
                        </button>
                    </div>

                    <div class="modal-body-inner">
                        <!-- Search results (shown when typing) -->
                        <div id="searchResultsWrap" style="display:none;">
                            <div class="section-title-row">
                                <h6>Search Results</h6>
                            </div>
                            <div class="row" id="searchResultsList"></div>
                            <p id="searchNoResult" style="display:none; text-align:center; padding: 15px 0;">
                                No results found.
                            </p>
                        </div>

                        <!-- Default: recently viewed / trending -->
                        <div id="defaultProductsWrap">
                            <div class="section-title-row">
                                <h6>products</h6>
                            </div>
                            <div class="row" id="recentlyViewedList">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!---------------- navbar offcanvas ---------------->

        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample"
            aria-labelledby="offcanvasExampleLabel">
            <div class="offcanvas-header">
                <a href="">
                    <img style="width: 150px; height: auto;" src="{{ asset($setting->site_logo) }}" alt="">
                </a>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route('frontend.shop') }}">All
                            Items</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route('frontend.gift-packages') }}">
                            Packages</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route('frontend.gift.build') }}">
                            Customize Gift</a>
                    </li>

                </ul>
            </div>
        </div>
        <!-- cetagories -->
        <section id="cetagory">

            <div class="container">

                <!-- Mobile Category Button -->
                <div class="mobile-cetagory-trigger" onclick="toggleCategory()">
                    <i class="fa-solid fa-bars"></i>
                    <span>Gift Categories</span>
                    <i class="fa-solid fa-chevron-down category-chevron"></i>
                </div>

                <!-- Categories -->
                <div class="d-flex flex-wrap justify-content-center cetagory-list" id="categoryList">
                    @foreach ($categories as $category)
                        <div class="cetagory-item">
                            <a
                                href="{{ route('frontend.category-wise-product', ['slug' => $category->slug]) }}">{{ $category->name }}</a>
                        </div>
                    @endforeach



                </div>

            </div>

        </section>


        <!---------------------- cart offcanvas ---------------------->

        <!---------------------- cart offcanvas ---------------------->


        <div class="cart_offcanvas offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight"
            aria-labelledby="offcanvasRightLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasRightLabel">
                    Cart <span class="cart-count" id="cartCount">{{ $cartQty }}</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>

            <div class="offcanvas-body d-flex flex-column">

                <!-- cart items list -->
                <div id="cartItemsList" class="flex-grow-1 overflow-auto">

                    @forelse ($cart as $key => $item)
                        <div class="cart-item" data-cart-key="{{ $key }}"
                            data-price="{{ $item['price'] }}">
                            <img class="cart-item__img" src="{{ $item['image'] }}" alt="{{ $item['name'] }}">
                            <div class="cart-item__info">
                                <div class="cart-item__top">
                                    <div>
                                        <p class="cart-item__name">{{ $item['name'] }}</p>
                                        <p class="cart-item__unit-price">Tk {{ number_format($item['price'], 2) }}</p>
                                    </div>
                                    <div class="cart-item__line-total">Tk
                                        {{ number_format($item['price'] * $item['qty'], 2) }}</div>
                                </div>
                                <div class="cart-item__controls">
                                    <div class="qty-stepper">
                                        <button type="button" class="qty-minus"
                                            aria-label="Decrease quantity">&minus;</button>
                                        <input type="text" class="qty-input" value="{{ $item['qty'] }}"
                                            readonly>
                                        <button type="button" class="qty-plus"
                                            aria-label="Increase quantity">+</button>
                                    </div>
                                    <button type="button" class="cart-item__remove" aria-label="Remove item">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="cart-empty text-center">Your cart is empty.</p>
                    @endforelse
                </div>

                <div class="cart_footer">
                    <!-- summary -->
                    <div class="cart-summary">
                        <div class="cart-summary__row">
                            <span>Estimated total</span>
                            <span id="cartTotal">Tk {{ number_format($cartTotal, 2) }} BDT</span>
                        </div>
                        <p class="cart-summary__note">Taxes, discounts and shipping calculated at checkout.</p>
                        <a href="{{ route('frontend.checkout') }}" class="cart-checkout-btn">Checkout</a>
                    </div>
                </div>

            </div>
        </div>


    </header>



    @yield('frontend_content')
    <!---------------------- footer  ---------------------->
    {{-- preloader --}}
    <div class="preloader" id="preloader">
        <div class="loader">
            <div class="pulse-ring"></div>
            <div class="pulse-ring"></div>
            <div class="pulse-ring"></div>
            <div class="core"></div>
        </div>
    </div>

    <footer class="site-footer">
        <div class="container">
            <div class="row gy-4">

                <!-- Brand -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="footer-logo mb-3">Giftbloom<span>BD</span></div>
                    <p class="footer-desc">
                        Premium gift hampers, carefully selected for your loved ones — make every moment even more
                        memorable.

                    </p>
                    <div class="social-icons mt-4">
                        <a href="#"><iconify-icon icon="ic:baseline-facebook" width="24"
                                height="24"></iconify-icon></a>
                        <a href="#"><iconify-icon icon="mdi:instagram" width="24"
                                height="24"></iconify-icon></a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-6 col-md-3 col-lg-2">
                    <div class="footer-heading">Quick Links</div>
                    <ul class="list-unstyled footer-links">
                        <li><a href="{{ route('frontend.shop') }}">All Items</a></li>
                        <li><a href="{{ route('frontend.gift-packages') }}">Gift Packages</a></li>
                    </ul>
                </div>

                <!-- Categories -->
                <div class="col-6 col-md-3 col-lg-2">
                    <div class="footer-heading">Information</div>
                    <ul class="list-unstyled footer-links">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Shipping & Return Policy</a></li>
                    </ul>
                </div>

                <!-- Contact + Newsletter -->
                <div class="col-12 col-lg-4">
                    <div class="footer-heading">Contact Us</div>
                    <div class="contact-item">
                        <i class="bi bi-geo-alt-fill"></i>
                        <span>{{ $setting->contact_address ?? 'Chattogram, Bangladesh' }}</span>
                    </div>
                    <div class="contact-item">
                        <i class="bi bi-telephone-fill"></i>
                        <span>{{ $setting->contact_phone ?? '+8801838-554691' }}</span>
                    </div>
                    <div class="contact-item">
                        <i class="bi bi-envelope-fill"></i>
                        <span>{{ $setting->contact_email ?? 'giftbloombd@gmail.com' }}</span>
                    </div>


                </div>

            </div>

            <div
                class="footer-bottom d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 text-center text-md-start">
                <span>&copy; 2026 PrezentBD. All rights reserved.</span>
                <div class="d-flex gap-3">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>
    @stack('frontend_js')
    <script src="{{ asset('frontend/asset/js/script.js') }}"></script>
    <script src="{{ asset('frontend/asset/js/vedio.js') }}"></script>
    <script src="{{ asset('frontend/asset/js/addToCart.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
    <script src="{{ asset('frontend/asset/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('frontend/asset/js/slick.min.js') }}"></script>
    <script src="{{ asset('frontend/asset/js/banner.js') }}"></script>
    <script src="{{ asset('frontend/asset/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://code.iconify.design/iconify-icon/3.0.0/iconify-icon.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const resultsWrap = document.getElementById('searchResultsWrap');
            const resultsList = document.getElementById('searchResultsList');
            const noResultMsg = document.getElementById('searchNoResult');
            const defaultWrap = document.getElementById('defaultProductsWrap');

            let debounceTimer;

            searchInput.addEventListener('input', function() {
                const query = this.value.trim();
                clearTimeout(debounceTimer);

                if (query.length < 2) {
                    resultsWrap.style.display = 'none';
                    defaultWrap.style.display = 'block';
                    return;
                }

                debounceTimer = setTimeout(() => {
                    fetch(`{{ route('frontend.search') }}?query=${encodeURIComponent(query)}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            defaultWrap.style.display = 'none';
                            resultsWrap.style.display = 'block';
                            resultsList.innerHTML = '';

                            if (data.results.length === 0) {
                                noResultMsg.style.display = 'block';
                                return;
                            }

                            noResultMsg.style.display = 'none';

                            data.results.forEach(item => {
                                resultsList.insertAdjacentHTML('beforeend', `
                        <a href="${item.url}" class="col-6 col-md-3 thumb-card" style="text-decoration:none; color:inherit;">
                            <img src="${item.image}" alt="${item.name}">
                            <div class="p-name">${item.name}</div>
                            <div class="p-price">Tk ${Number(item.price).toLocaleString()}</div>
                        </a>
                    `);
                            });
                        })
                        .catch(err => console.error('Search error:', err));
                }, 350); // debounce delay
            });
        });
    </script>

    <script>
        window.addEventListener('load', function() {
            var preloader = document.getElementById('preloader');
            setTimeout(function() {
                preloader.style.opacity = '0';
                preloader.style.transition = 'opacity 0.4s ease';
                setTimeout(function() {
                    preloader.style.display = 'none';
                }, 400);
            }, 600);
        });
    </script>

</body>

</html>
