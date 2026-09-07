@extends('frontend.layout')
@section('frontend_title', 'Shop')
@section('frontend_content')
    <main>

        <section id="header" class="pt-4">
            <div class="container">
                <h2>{{ $category->name ?? 'Our Products' }}</h2>
            </div>
        </section>

        <!-- filter bar -->
        <section id="shop-filter-bar" class="py-3">
            <div class="container">
                <form action="{{ url()->current() }}" method="GET" id="filterForm">
                    <div class="filter-bar-inner d-flex justify-content-between align-items-center flex-wrap gap-3">

                        {{-- ============ DESKTOP: Availability + Price dropdowns ============ --}}
                        <div class="d-none d-md-flex align-items-center gap-2 flex-wrap filter-group-desktop">
                            {{-- Availability --}}
                            <div class="dropdown">
                                <button class="btn btn-filter dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Availability
                                </button>
                                <div class="dropdown-menu filter-dropdown p-3">
                                    @foreach (['in_stock' => 'In stock', 'out_of_stock' => 'Out of stock'] as $val => $label)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="availability[]"
                                                value="{{ $val }}" id="avail_desktop_{{ $val }}"
                                                {{ in_array($val, request('availability', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label"
                                                for="avail_desktop_{{ $val }}">{{ $label }}</label>
                                        </div>
                                    @endforeach
                                    <button type="submit" class="btn btn-apply mt-2 w-100">Apply</button>
                                </div>
                            </div>

                            {{-- Price --}}
                            <div class="dropdown">
                                <button class="btn btn-filter dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Price
                                </button>
                                <div class="dropdown-menu filter-dropdown p-3">
                                    <div class="d-flex gap-2 mb-2">
                                        <input type="number" name="price_min" class="form-control form-control-sm"
                                            placeholder="Min" value="{{ request('price_min') }}">
                                        <input type="number" name="price_max" class="form-control form-control-sm"
                                            placeholder="Max" value="{{ request('price_max') }}">
                                    </div>
                                    <button type="submit" class="btn btn-apply w-100">Apply</button>
                                </div>
                            </div>
                        </div>

                        {{-- ============ MOBILE: single Filter trigger (opens offcanvas) ============ --}}
                        <button type="button" class="btn btn-mobile-filter d-flex d-md-none align-items-center gap-2"
                            data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                                <path d="M4 6h16M7 12h10M10 18h4" stroke="currentColor" stroke-width="1.8"
                                    stroke-linecap="round" />
                            </svg>
                            <span>Filter</span>
                        </button>

                        {{-- ============ RIGHT SIDE: item count + sort (desktop only) + grid toggle (always) ============ --}}
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <span class="item-count d-none d-md-inline">{{ $products->total() ?? $products->count() }}
                                items</span>

                            {{-- Sort (desktop) --}}
                            <select name="sort" class="form-select form-select-sm sort-select d-none d-md-block"
                                onchange="document.getElementById('filterForm').submit()">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low
                                    to High</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price:
                                    High to Low</option>
                            </select>

                            {{-- Grid layout toggle (visible on all breakpoints) --}}
                            <div class="grid-toggle">
                                <button type="button" class="btn btn-icon active" id="gridCompact"
                                    aria-label="Compact grid">
                                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none">
                                        <rect x="3" y="3" width="8" height="8" rx="1.5"
                                            fill="currentColor" />
                                        <rect x="13" y="3" width="8" height="8" rx="1.5"
                                            fill="currentColor" />
                                        <rect x="3" y="13" width="8" height="8" rx="1.5"
                                            fill="currentColor" />
                                        <rect x="13" y="13" width="8" height="8" rx="1.5"
                                            fill="currentColor" />
                                    </svg>
                                </button>
                                <button type="button" class="btn btn-icon" id="gridLarge" aria-label="Large grid">
                                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none">
                                        <rect x="2" y="4" width="4.5" height="16" rx="1"
                                            fill="currentColor" />
                                        <rect x="9.75" y="4" width="4.5" height="16" rx="1"
                                            fill="currentColor" />
                                        <rect x="17.5" y="4" width="4.5" height="16" rx="1"
                                            fill="currentColor" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- ============ MOBILE OFFCANVAS: Availability + Price + Sort ============ --}}
                    <div class="offcanvas offcanvas-start filter-offcanvas" tabindex="-1" id="filterOffcanvas">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title">Filters</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body filter-group-mobile">

                            <div class="mb-4">
                                <h6 class="filter-group-title">Availability</h6>
                                @foreach (['in_stock' => 'In stock', 'out_of_stock' => 'Out of stock'] as $val => $label)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="availability[]"
                                            value="{{ $val }}" id="avail_mobile_{{ $val }}"
                                            {{ in_array($val, request('availability', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label"
                                            for="avail_mobile_{{ $val }}">{{ $label }}</label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mb-4">
                                <h6 class="filter-group-title">Price</h6>
                                <div class="d-flex gap-2">
                                    <input type="number" name="price_min" class="form-control form-control-sm"
                                        placeholder="Min" value="{{ request('price_min') }}">
                                    <input type="number" name="price_max" class="form-control form-control-sm"
                                        placeholder="Max" value="{{ request('price_max') }}">
                                </div>
                            </div>

                            <div class="mb-4">
                                <h6 class="filter-group-title">Sort by</h6>
                                <select name="sort" class="form-select form-select-sm">
                                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest
                                    </option>
                                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>
                                        Price: Low to High</option>
                                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>
                                        Price: High to Low</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-apply w-100">Apply Filters</button>
                        </div>
                    </div>

                </form>
            </div>
        </section>

        <!-- products -->
        <section id="products" class="py-4">
            <div class="container">

                <div class="row g-4" id="productGrid">

                    @foreach ($products as $product)
                        @php
                            $hasColors = $product->colors->filter(fn($c) => filled($c->color_name))->isNotEmpty();
                        @endphp
                        <div class="col-lg-3 col-6 product-col">
                            <div class="product-card">
                                <div class="product-image">
                                    <a href="{{ route('frontend.product.details', $product->slug) }}">
                                        <img src="{{ $product->colors->first()?->images->first()?->url }}"
                                            alt="{{ $product->name }}">
                                    </a>
                                    @unless ($hasColors)
                                        <button class="cart-btn" aria-label="Add to cart"
                                            data-product-id="{{ $product->id }}"
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

                @if (method_exists($products, 'links'))
                    <div class="mt-4">
                        {{ $products->links() }}
                    </div>
                @endif

            </div>
        </section>
    </main>
@endsection

@push('frontend_css')
    <style>
        .filter-bar-inner {
            background: var(--color-bg-light);
            border: 1px solid var(--color-border);
            border-radius: 14px;
            padding: 14px 20px;
            font-family: var(--primary-font);
        }

        /* Availability / Price buttons (desktop) */
        .btn-filter {
            background: #fff;
            border: 1px solid var(--color-border);
            color: var(--color-text);
            border-radius: 30px;
            font-size: 14px;
            font-weight: 500;
            padding: 8px 18px;
            transition: all 0.2s ease;
        }

        .btn-filter:hover,
        .btn-filter:focus {
            border-color: var(--color-secondary-dark);
            background: var(--color-bg-soft);
            color: var(--color-primary);
        }

        .btn-filter.dropdown-toggle::after {
            margin-left: 8px;
            vertical-align: middle;
        }

        .btn-filter.show {
            background: var(--color-secondary);
            border-color: var(--color-secondary-dark);
            color: #fff;
        }

        /* Dropdown panel (desktop) */
        .filter-dropdown {
            border: 1px solid var(--color-border);
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(26, 26, 26, 0.08);
            min-width: 230px;
        }

        .filter-dropdown .form-check,
        .filter-group-mobile .form-check {
            padding-left: 1.8em;
        }

        .filter-dropdown .form-check-input,
        .filter-group-mobile .form-check-input {
            border-color: var(--color-secondary-dark);
        }

        .filter-dropdown .form-check-input:checked,
        .filter-group-mobile .form-check-input:checked {
            background-color: var(--color-cta);
            border-color: var(--color-cta);
        }

        .filter-dropdown .form-check-label,
        .filter-group-mobile .form-check-label {
            color: var(--color-text);
            font-size: 14px;
        }

        .filter-dropdown .form-control:focus,
        .filter-group-mobile .form-control:focus,
        .filter-group-mobile .form-select:focus {
            border-color: var(--color-secondary-dark);
            box-shadow: 0 0 0 0.2rem rgba(232, 169, 156, 0.25);
        }

        /* Apply button */
        .btn-apply {
            background: var(--color-cta);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            padding: 9px 0;
            transition: background 0.2s ease;
        }

        .btn-apply:hover {
            background: var(--color-cta-hover);
            color: #fff;
        }

        /* Item count */
        .item-count {
            color: var(--color-text-light);
            font-size: 14px;
            white-space: nowrap;
        }

        /* Sort select (desktop) */
        .sort-select {
            width: auto;
            border: 1px solid var(--color-border);
            border-radius: 30px;
            background-color: #fff;
            color: var(--color-text);
            font-size: 14px;
            padding: 7px 30px 7px 16px;
        }

        .sort-select:focus {
            border-color: var(--color-secondary-dark);
            box-shadow: 0 0 0 0.2rem rgba(232, 169, 156, 0.25);
        }

        /* Grid toggle */
        .grid-toggle {
            display: flex;
            gap: 4px;
            background: #fff;
            border: 1px solid var(--color-border);
            border-radius: 10px;
            padding: 3px;
        }

        .btn-icon {
            border: none;
            background: transparent;
            padding: 6px 9px;
            color: var(--color-text-light);
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .btn-icon:hover {
            color: var(--color-primary);
        }

        .btn-icon.active {
            background: var(--color-cta);
            color: #fff;
        }

        /* Mobile filter trigger button */
        .btn-mobile-filter {
            background: #fff;
            border: 1px solid var(--color-border);
            color: var(--color-text);
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            padding: 10px 16px;
            flex: 1;
        }

        .btn-mobile-filter svg {
            color: var(--color-cta);
        }

        /* Offcanvas (mobile filter sidebar) */
        .filter-offcanvas {
            width: 300px;
            font-family: var(--primary-font);
        }

        .filter-offcanvas .offcanvas-header {
            border-bottom: 1px solid var(--color-border);
        }

        .filter-offcanvas .offcanvas-title {
            color: var(--color-primary);
            font-weight: 600;
        }

        .filter-group-title {
            color: var(--color-primary);
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        /* Mobile layout tweaks */
        @media (max-width: 767.98px) {
            .filter-bar-inner {
                padding: 10px 14px;
                gap: 10px !important;
            }
        }
    </style>
@endpush

@push('frontend_js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /* ---------- Grid toggle: 4/row <-> 6/row ---------- */
            const compactBtn = document.getElementById('gridCompact');
            const largeBtn = document.getElementById('gridLarge');
            const cols = document.querySelectorAll('#productGrid .product-col');

            function setGrid(mode) {
                cols.forEach(col => {
                    if (mode === 'large') {
                        col.classList.remove('col-lg-3', 'col-6');
                        col.classList.add('col-lg-2', 'col-4'); // desktop 6/row, mobile 3/row
                    } else {
                        col.classList.remove('col-lg-2', 'col-4');
                        col.classList.add('col-lg-3', 'col-6'); // desktop 4/row, mobile 2/row
                    }
                });
                compactBtn.classList.toggle('active', mode === 'compact');
                largeBtn.classList.toggle('active', mode === 'large');
                localStorage.setItem('shopGridMode', mode);
            }

            compactBtn.addEventListener('click', () => setGrid('compact'));
            largeBtn.addEventListener('click', () => setGrid('large'));

            const savedGrid = localStorage.getItem('shopGridMode');
            if (savedGrid === 'large') setGrid('large');

            /* ---------- Avoid duplicate desktop/mobile fields being submitted together ----------
               Before submit, disable whichever group (desktop dropdowns or mobile offcanvas)
               is not currently visible, so only the active one's values go in the query string. */
            const filterForm = document.getElementById('filterForm');
            const desktopGroup = document.querySelector('.filter-group-desktop');
            const mobileGroup = document.querySelector('.filter-group-mobile');

            filterForm.addEventListener('submit', function() {
                const isDesktop = window.matchMedia('(min-width: 768px)').matches;
                const groupToDisable = isDesktop ? mobileGroup : desktopGroup;

                if (groupToDisable) {
                    groupToDisable.querySelectorAll('input, select').forEach(el => {
                        el.disabled = true;
                    });
                }
            });
        });
    </script>
@endpush
