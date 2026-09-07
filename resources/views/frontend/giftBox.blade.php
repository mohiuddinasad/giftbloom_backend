@extends('frontend.layout')

@section('frontend_title', 'Custom Gift Box')

@push('frontend_css')
    <style>
        :root {
            /* Base / Background */
            --color-bg: #FDE6D8;
            --color-bg-soft: #ffecde;
            --color-bg-light: #fff8f3;

            /* Primary (Text & Logo Accent) */
            --color-primary: #1A1A1A;
            --color-primary-soft: #333333;

            /* Secondary Accent (Rose / Blush) */
            --color-secondary: #E8A99C;
            --color-secondary-dark: #D4877A;

            /* CTA / Buttons */
            --color-cta: #C86B4A;
            --color-cta-hover: #B45A3A;

            /* Highlight / Premium */
            --color-highlight: #D4AF37;

            /* Borders / Dividers */
            --color-border: #E8D5C4;

            /* Text */
            --color-text: #333333;
            --color-text-light: #6B6B6B;
            --color-text-on-dark: #FFF8F3;

            --primary-font: 'Poppins', sans-serif;
        }

        .gbb-wrap {
            font-family: var(--primary-font);
            color: var(--color-text);
            background: #ffffff;
            padding: 40px 0 80px;
        }

        /* ---------- Progress steps ---------- */
        .gbb-steps {
            display: flex;
            align-items: flex-start;
            justify-content: center;
            gap: 0;
            margin-bottom: 40px;
            position: relative;
        }

        .gbb-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            position: relative;
            flex: 1;
            max-width: 180px;
            cursor: pointer;
            background: none;
            border: none;
        }

        .gbb-step::before {
            content: '';
            position: absolute;
            top: 24px;
            left: -50%;
            width: 100%;
            height: 1px;
            background: var(--color-border);
            z-index: 0;
        }

        .gbb-step:first-child::before {
            display: none;
        }

        .gbb-step-circle {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: 1px solid var(--color-border);
            background: var(--color-bg-light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: var(--color-text-light);
            position: relative;
            z-index: 1;
            transition: all .2s ease;
        }

        .gbb-step-label {
            font-size: 14px;
            letter-spacing: .3px;
            color: var(--color-text-light);
        }

        .gbb-step.done .gbb-step-circle {
            background: var(--color-cta);
            border-color: var(--color-cta);
            color: #fff;
        }

        .gbb-step.active .gbb-step-circle {
            border-color: var(--color-cta);
            color: var(--color-cta);
        }

        .gbb-step.done .gbb-step-label,
        .gbb-step.active .gbb-step-label {
            color: var(--color-cta);
            font-weight: 600;
        }

        /* ---------- Step content ---------- */
        .gbb-step-content {
            display: none;
        }

        .gbb-step-content.active {
            display: block;
        }

        .gbb-title {
            text-align: center;
            font-weight: 700;
            font-size: 26px;
            color: var(--color-primary);
            margin-bottom: 6px;
            text-transform: uppercase;
        }

        .gbb-subtitle {
            text-align: center;
            color: var(--color-text-light);
            font-size: 14px;
            letter-spacing: .5px;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .gbb-divider {
            border-bottom: 2px solid var(--color-primary);
            margin-bottom: 30px;
        }

        /* ---------- Gift box for (Her/Him) ---------- */
        .gbb-target-card {
            border: 1px solid var(--color-border);
            border-radius: 10px;
            padding: 24px;
            margin-bottom: 24px;
        }

        .gbb-target-title {
            font-weight: 600;
            margin-bottom: 12px;
        }

        .gbb-target-options {
            display: flex;
            gap: 10px;
        }

        .gbb-toggle-btn {
            border: 1px solid var(--color-cta);
            background: #fff;
            color: var(--color-cta);
            padding: 10px 24px;
            border-radius: 6px;
            font-weight: 600;
            transition: all .15s ease;
        }

        .gbb-toggle-btn.active {
            background: var(--color-cta);
            border-color: var(--color-cta);
            color: #fff;
        }

        /* ---------- Extras sub tabs ---------- */
        .gbb-subtabs {
            display: flex;
            justify-content: center;
            gap: 24px;
            margin-bottom: 24px;
        }

        .gbb-subtab {
            background: none;
            border: none;
            color: var(--color-text-light);
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .gbb-subtab.active {
            color: var(--color-cta);
            font-weight: 700;
            text-decoration: underline;
            text-underline-offset: 4px;
        }

        /* ---------- Product grid ---------- */
        .gbb-product-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
        }

        @media (max-width: 991px) {
            .gbb-sidebar {
                position: static;
                top: auto;
                margin-top: 40px;
            }
            .gbb-product-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .gbb-product-card {
            cursor: pointer;
        }

        .gbb-product-thumb {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            background: var(--color-bg-soft);
            aspect-ratio: 1/1;
        }

        .gbb-product-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .3s ease;
        }

        .gbb-product-card:hover .gbb-product-thumb img {
            transform: scale(1.05);
        }

        .gbb-stock-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            margin-top: 12px;
        }

        .gbb-stock-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }

        .gbb-stock-dot.in-stock {
            background: #2fa84f;
        }

        .gbb-stock-dot.limited {
            background: var(--color-highlight);
        }

        .gbb-product-name {
            font-weight: 600;
            margin: 4px 0;
            font-size: 15px;
        }

        .gbb-product-price {
            color: var(--color-text-light);
            font-size: 14px;
            margin-bottom: 10px;
        }

        .gbb-add-btn {
            width: 100%;
            border: 1px solid var(--color-cta);
            background: #fff;
            color: var(--color-cta);
            border-radius: 30px;
            padding: 8px 16px;
            font-weight: 600;
            font-size: 14px;
            transition: all .15s ease;
        }

        .gbb-add-btn:hover {
            background: var(--color-cta);
            color: #fff;
        }

        .gbb-add-btn.added {
            background: var(--color-cta);
            border-color: var(--color-cta);
            color: #fff;
        }

        .gbb-add-btn:disabled {
            opacity: .4;
            cursor: not-allowed;
        }

        /* ---------- Cards step recipient fields ---------- */
        .gbb-recipient-card {
            border: 1px solid var(--color-border);
            border-radius: 10px;
            padding: 24px;
            margin-top: 30px;
        }

        .gbb-recipient-card label {
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
        }

        .gbb-recipient-card .form-control {
            border: 1px solid var(--color-border);
            border-radius: 8px;
            padding: 12px 14px;
        }

        .gbb-recipient-card .form-control:focus {
            border-color: var(--color-secondary-dark);
            box-shadow: none;
        }

        /* ---------- Sidebar / cart summary ---------- */
        .gbb-sidebar {
            border: 1px solid var(--color-border);
            border-radius: 10px;
            overflow: hidden;
            position: sticky;
            top: 20px;
        }

        .gbb-sidebar-banner {
            background: var(--color-bg-soft);
            padding: 16px 20px;
            font-weight: 700;
            font-size: 15px;
            color: var(--color-primary);
        }

        .gbb-sidebar-banner.is-complete {
            background: #eaf7ee;
            color: #206b36;
        }

        .gbb-sidebar-body {
            min-height: 260px;
            max-height: 420px;
            overflow-y: auto;
            padding: 20px;
        }

        .gbb-sidebar-empty {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 220px;
            color: var(--color-text-light);
        }

        .gbb-cart-item {
            display: flex;
            gap: 12px;
            align-items: center;
            margin-bottom: 16px;
        }

        .gbb-cart-item img {
            width: 56px;
            height: 56px;
            object-fit: cover;
            border-radius: 8px;
            background: var(--color-bg-soft);
        }

        .gbb-cart-item-name {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 2px;
        }

        .gbb-cart-item-meta {
            font-size: 13px;
            color: var(--color-text-light);
        }

        .gbb-cart-item-remove {
            border: none;
            background: none;
            color: var(--color-text-light);
            font-size: 18px;
            line-height: 1;
        }

        .gbb-cart-item-remove:hover {
            color: var(--color-cta);
        }

        .gbb-sidebar-footer {
            border-top: 1px solid var(--color-border);
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            font-weight: 700;
        }

        .gbb-sidebar-nav {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 16px 20px;
            border-top: 1px solid var(--color-border);
        }

        .gbb-btn-back {
            border: 1px solid var(--color-border);
            background: #fff;
            color: var(--color-text);
            border-radius: 30px;
            padding: 10px 28px;
            font-weight: 600;
        }

        .gbb-btn-next {
            border: 1px solid var(--color-cta);
            background: var(--color-cta);
            color: #fff;
            border-radius: 30px;
            padding: 10px 28px;
            font-weight: 600;
        }

        .gbb-btn-next:hover {
            background: var(--color-cta-hover);
            border-color: var(--color-cta-hover);
        }

        .gbb-btn-next:disabled {
            opacity: .4;
            cursor: not-allowed;
        }

        /* ---------- Box style step (step 1) — identical visual style to product cards ---------- */
        .gbb-box-card.selected {
            outline: 2px solid var(--color-cta);
            outline-offset: 4px;
            border-radius: 10px;
        }

        /* ---------- Quick view modal ---------- */
        #gbbQuickViewModal .modal-content {
            border-radius: 14px;
            border: none;
            font-family: var(--primary-font);
        }

        #gbbQuickViewModal .modal-header {
            border-bottom: 1px solid var(--color-border);
        }

        .gbb-modal-thumb-nav {
            display: flex;
            flex-direction: row;
            gap: 8px;
            margin-top: 10px;
        }

        .gbb-modal-thumb-nav img {
            width: 56px;
            height: 56px;
            object-fit: cover;
            border-radius: 6px;
            cursor: pointer;
            border: 1px solid var(--color-border);
        }

        .gbb-modal-main-img {
            width: 100%;
            aspect-ratio: 1/1;
            object-fit: cover;
            border-radius: 10px;
            background: var(--color-bg-soft);
        }

        .gbb-modal-price {
            color: var(--color-cta);
            font-weight: 700;
            font-size: 20px;
            margin: 8px 0;
        }

        .gbb-color-options {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 10px 0 16px;
        }

        .gbb-color-btn {
            border: 1px solid var(--color-border);
            border-radius: 20px;
            padding: 6px 16px;
            font-size: 13px;
            background: #fff;
            cursor: pointer;
        }

        .gbb-color-btn.active {
            border-color: var(--color-cta);
            background: var(--color-cta);
            color: #fff;
        }

        .gbb-qty-selector {
            display: inline-flex;
            align-items: center;
            border: 1px solid var(--color-border);
            border-radius: 30px;
            overflow: hidden;
        }

        .gbb-qty-selector button {
            border: none;
            background: none;
            width: 36px;
            height: 36px;
            font-size: 18px;
        }

        .gbb-qty-selector .gbb-qty-value {
            width: 32px;
            text-align: center;
            font-weight: 600;
        }

        .gbb-modal-confirm-btn {
            width: 100%;
            margin-top: 20px;
            background: var(--color-cta);
            border: none;
            color: #fff;
            padding: 12px;
            border-radius: 30px;
            font-weight: 700;
        }

        .gbb-modal-confirm-btn:hover {
            background: var(--color-cta-hover);
            color: #fff;
        }
    </style>
@endpush

@section('frontend_content')
    <section class="gbb-wrap">
        <div class="container-fluid">

            {{-- ============ Progress steps ============ --}}
            <div class="gbb-steps" id="gbbSteps">
                <button type="button" class="gbb-step active" data-step="1">
                    <span class="gbb-step-circle">1</span>
                    <span class="gbb-step-label">Box</span>
                </button>
                <button type="button" class="gbb-step" data-step="2">
                    <span class="gbb-step-circle">2</span>
                    <span class="gbb-step-label">Wraping</span>
                </button>
                <button type="button" class="gbb-step" data-step="3">
                    <span class="gbb-step-circle">3</span>
                    <span class="gbb-step-label">Gifts</span>
                </button>
                <button type="button" class="gbb-step" data-step="4">
                    <span class="gbb-step-circle">4</span>
                    <span class="gbb-step-label">Extras</span>
                </button>
                <button type="button" class="gbb-step" data-step="5">
                    <span class="gbb-step-circle">5</span>
                    <span class="gbb-step-label">Cards</span>
                </button>
            </div>

            <div class="row">
                {{-- ============ Main step content ============ --}}
                <div class="col-lg-8">

                    {{-- STEP 1: BOX --}}
                    <div class="gbb-step-content active" data-step-content="1">
                        <h2 class="gbb-title">Choose Your Box</h2>
                        <p class="gbb-subtitle">Pick the box style that fits your gift</p>
                        <div class="gbb-divider"></div>

                        <div class="gbb-product-grid" id="gbbBoxGrid">
                            @forelse($boxes as $product)
                                <div class="gbb-product-card gbb-box-card" data-id="{{ $product->id }}"
                                    data-name="{{ $product->name }}" data-price="{{ $product->final_price }}"
                                    data-image="{{ $product->mainImageUrl() }}" data-type="{{ $product->type }}"
                                    data-stock="{{ $product->stock_level }}" data-colors='@json($product->colorOptions())'
                                    data-images='@json($product->imageUrls())'>
                                    <div class="gbb-product-thumb">
                                        <img src="{{ $product->mainImageUrl() }}" alt="{{ $product->name }}">
                                    </div>
                                    @if ($product->stock_level !== 'in_stock')
                                        <div class="gbb-stock-badge">
                                            <span
                                                class="gbb-stock-dot {{ $product->stock_level === 'limited' ? 'limited' : '' }}"></span>
                                            {{ $product->stock_level === 'limited' ? 'Limited Stock' : 'Out of Stock' }}
                                        </div>
                                    @endif
                                    <div class="gbb-product-name">{{ $product->name }}</div>
                                    @if ($product->final_price > 0)
                                        <div class="gbb-product-price">Tk {{ number_format($product->price, 2) }}</div>
                                    @endif
                                    <button type="button" class="gbb-add-btn" data-action="add-to-box"
                                        @disabled($product->stock_level === 'out_of_stock')>
                                        {{ $product->stock_level === 'out_of_stock' ? 'Out of stock' : 'Add to box' }}
                                    </button>
                                </div>
                            @empty
                                <p class="text-muted">No box styles are available right now.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- STEP 2: WRAPING --}}
                    <div class="gbb-step-content" data-step-content="2">
                        <h2 class="gbb-title">Choose Your Wraping Paper</h2>
                        <p class="gbb-subtitle">Pick a wrap for the box</p>
                        <div class="gbb-divider"></div>

                        <div class="gbb-product-grid" id="gbbWrapingGrid">
                            @forelse($wrapings as $product)
                                <div class="gbb-product-card" data-id="{{ $product->id }}"
                                    data-name="{{ $product->name }}" data-price="{{ $product->final_price }}"
                                    data-image="{{ $product->mainImageUrl() }}" data-type="{{ $product->type }}"
                                    data-stock="{{ $product->stock_level }}" data-colors='@json($product->colorOptions())'
                                    data-images='@json($product->imageUrls())'>
                                    <div class="gbb-product-thumb">
                                        <img src="{{ $product->mainImageUrl() }}" alt="{{ $product->name }}">
                                    </div>
                                    @if ($product->stock_level !== 'in_stock')
                                        <div class="gbb-stock-badge">
                                            <span
                                                class="gbb-stock-dot {{ $product->stock_level === 'limited' ? 'limited' : '' }}"></span>
                                            {{ $product->stock_level === 'limited' ? 'Limited Stock' : 'Out of Stock' }}
                                        </div>
                                    @endif
                                    <div class="gbb-product-name">{{ $product->name }}</div>
                                    @if ($product->final_price > 0)
                                        <div class="gbb-product-price">Tk {{ number_format($product->final_price, 2) }}
                                        </div>
                                    @endif
                                    <button type="button" class="gbb-add-btn" data-action="add-to-box"
                                        @disabled($product->stock_level === 'out_of_stock')>
                                        {{ $product->stock_level === 'out_of_stock' ? 'Out of stock' : 'Add to box' }}
                                    </button>
                                </div>
                            @empty
                                <p class="text-muted">No wraping options are available right now.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- STEP 3: GIFTS --}}
                    <div class="gbb-step-content" data-step-content="3">
                        <h2 class="gbb-title">Add Your Gifts</h2>
                        <p class="gbb-subtitle">Choose at least 3 items to complete your gift box</p>
                        <div class="gbb-divider"></div>

                        <div class="gbb-target-card">
                            <div class="gbb-target-title">Gift Box for?</div>
                            <div class="gbb-target-options" id="gbbGenderToggle">
                                <button type="button" class="gbb-toggle-btn active" data-gender="her">For Her</button>
                                <button type="button" class="gbb-toggle-btn" data-gender="him">For Him</button>
                            </div>
                        </div>

                        <div class="gbb-product-grid" id="gbbGiftGrid">
                            @foreach ($giftsForHer as $product)
                                <div class="gbb-product-card" data-id="{{ $product->id }}"
                                    data-name="{{ $product->name }}" data-price="{{ $product->final_price }}"
                                    data-image="{{ $product->mainImageUrl() }}" data-type="{{ $product->type }}"
                                    data-stock="{{ $product->stock_level }}" data-gender="her"
                                    data-colors='@json($product->colorOptions())' data-images='@json($product->imageUrls())'>
                                    <div class="gbb-product-thumb">
                                        <img src="{{ $product->mainImageUrl() }}" alt="{{ $product->name }}">
                                    </div>
                                    @if ($product->stock_level !== 'in_stock')
                                        <div class="gbb-stock-badge">
                                            <span
                                                class="gbb-stock-dot {{ $product->stock_level === 'limited' ? 'limited' : '' }}"></span>
                                            {{ $product->stock_level === 'limited' ? 'Limited Stock' : 'Out of Stock' }}
                                        </div>
                                    @endif
                                    <div class="gbb-product-name">{{ $product->name }}</div>
                                    @if ($product->final_price > 0)
                                        <div class="gbb-product-price">Tk {{ number_format($product->final_price, 2) }}
                                        </div>
                                    @endif
                                    <button type="button" class="gbb-add-btn" data-action="add-to-box"
                                        @disabled($product->stock_level === 'out_of_stock')>
                                        {{ $product->stock_level === 'out_of_stock' ? 'Out of stock' : 'Add to box' }}
                                    </button>
                                </div>
                            @endforeach

                            @foreach ($giftsForHim as $product)
                                <div class="gbb-product-card" data-id="{{ $product->id }}"
                                    data-name="{{ $product->name }}" data-price="{{ $product->final_price }}"
                                    data-image="{{ $product->mainImageUrl() }}" data-type="{{ $product->type }}"
                                    data-stock="{{ $product->stock_level }}" data-gender="him"
                                    data-colors='@json($product->colorOptions())'
                                    data-images='@json($product->imageUrls())'>
                                    <div class="gbb-product-thumb">
                                        <img src="{{ $product->mainImageUrl() }}" alt="{{ $product->name }}">
                                    </div>
                                    @if ($product->stock_level !== 'in_stock')
                                        <div class="gbb-stock-badge">
                                            <span
                                                class="gbb-stock-dot {{ $product->stock_level === 'limited' ? 'limited' : '' }}"></span>
                                            {{ $product->stock_level === 'limited' ? 'Limited Stock' : 'Out of Stock' }}
                                        </div>
                                    @endif
                                    <div class="gbb-product-name">{{ $product->name }}</div>
                                    @if ($product->final_price > 0)
                                        <div class="gbb-product-price">Tk {{ number_format($product->final_price, 2) }}
                                        </div>
                                    @endif
                                    <button type="button" class="gbb-add-btn" data-action="add-to-box"
                                        @disabled($product->stock_level === 'out_of_stock')>
                                        {{ $product->stock_level === 'out_of_stock' ? 'Out of stock' : 'Add to box' }}
                                    </button>
                                </div>
                            @endforeach

                            @if ($giftsForHer->isEmpty() && $giftsForHim->isEmpty())
                                <p class="text-muted">No gift items are available right now.</p>
                            @endif
                        </div>
                    </div>

                    {{-- STEP 4: EXTRAS --}}
                    <div class="gbb-step-content" data-step-content="4">
                        <h2 class="gbb-title">Elevate Your Gift (Optional)</h2>
                        <div class="gbb-subtabs" id="gbbExtrasSubtabs">
                            <button type="button" class="gbb-subtab active" data-extra-group="extras">Extras</button>
                            <button type="button" class="gbb-subtab" data-extra-group="sweet-treats">Sweet
                                Treats</button>
                        </div>
                        <div class="gbb-divider"></div>

                        <div class="gbb-product-grid" id="gbbExtrasGrid">
                            @foreach ($extras as $product)
                                <div class="gbb-product-card" data-id="{{ $product->id }}"
                                    data-name="{{ $product->name }}" data-price="{{ $product->final_price }}"
                                    data-image="{{ $product->mainImageUrl() }}" data-type="{{ $product->type }}"
                                    data-stock="{{ $product->stock_level }}" data-extra-group="extras"
                                    data-colors='@json($product->colorOptions())'
                                    data-images='@json($product->imageUrls())'>
                                    <div class="gbb-product-thumb">
                                        <img src="{{ $product->mainImageUrl() }}" alt="{{ $product->name }}">
                                    </div>
                                    @if ($product->stock_level !== 'in_stock')
                                        <div class="gbb-stock-badge">
                                            <span
                                                class="gbb-stock-dot {{ $product->stock_level === 'limited' ? 'limited' : '' }}"></span>
                                            {{ $product->stock_level === 'limited' ? 'Limited Stock' : 'Out of Stock' }}
                                        </div>
                                    @endif
                                    <div class="gbb-product-name">{{ $product->name }}</div>
                                    @if ($product->final_price > 0)
                                        <div class="gbb-product-price">Tk {{ number_format($product->final_price, 2) }}
                                        </div>
                                    @endif
                                    <button type="button" class="gbb-add-btn" data-action="add-to-box"
                                        @disabled($product->stock_level === 'out_of_stock')>
                                        {{ $product->stock_level === 'out_of_stock' ? 'Out of stock' : 'Add to box' }}
                                    </button>
                                </div>
                            @endforeach

                            @foreach ($sweets as $product)
                                <div class="gbb-product-card" data-id="{{ $product->id }}"
                                    data-name="{{ $product->name }}" data-price="{{ $product->final_price }}"
                                    data-image="{{ $product->mainImageUrl() }}" data-type="{{ $product->type }}"
                                    data-stock="{{ $product->stock_level }}" data-extra-group="sweet-treats"
                                    data-colors='@json($product->colorOptions())'
                                    data-images='@json($product->imageUrls())'>
                                    <div class="gbb-product-thumb">
                                        <img src="{{ $product->mainImageUrl() }}" alt="{{ $product->name }}">
                                    </div>
                                    @if ($product->stock_level !== 'in_stock')
                                        <div class="gbb-stock-badge">
                                            <span
                                                class="gbb-stock-dot {{ $product->stock_level === 'limited' ? 'limited' : '' }}"></span>
                                            {{ $product->stock_level === 'limited' ? 'Limited Stock' : 'Out of Stock' }}
                                        </div>
                                    @endif
                                    <div class="gbb-product-name">{{ $product->name }}</div>
                                    @if ($product->final_price > 0)
                                        <div class="gbb-product-price">Tk {{ number_format($product->final_price, 2) }}
                                        </div>
                                    @endif
                                    <button type="button" class="gbb-add-btn" data-action="add-to-box"
                                        @disabled($product->stock_level === 'out_of_stock')>
                                        {{ $product->stock_level === 'out_of_stock' ? 'Out of stock' : 'Add to box' }}
                                    </button>
                                </div>
                            @endforeach

                            @if ($extras->isEmpty() && $sweets->isEmpty())
                                <p class="text-muted">No extras are available right now.</p>
                            @endif
                        </div>
                    </div>

                    {{-- STEP 5: CARDS --}}
                    <div class="gbb-step-content" data-step-content="5">
                        <h2 class="gbb-title">Choose Your Greeting Card</h2>
                        <div class="gbb-divider"></div>

                        <div class="gbb-product-grid" id="gbbCardsGrid">
                            @forelse($cards as $product)
                                <div class="gbb-product-card" data-id="{{ $product->id }}"
                                    data-name="{{ $product->name }}" data-price="{{ $product->final_price }}"
                                    data-image="{{ $product->mainImageUrl() }}" data-type="{{ $product->type }}"
                                    data-stock="{{ $product->stock_level }}">
                                    <div class="gbb-product-thumb">
                                        <img src="{{ $product->mainImageUrl() }}" alt="{{ $product->name }}">
                                    </div>
                                    @if ($product->stock_level !== 'in_stock')
                                        <div class="gbb-stock-badge">
                                            <span
                                                class="gbb-stock-dot {{ $product->stock_level === 'limited' ? 'limited' : '' }}"></span>
                                            {{ $product->stock_level === 'limited' ? 'Limited Stock' : 'Out of Stock' }}
                                        </div>
                                    @endif
                                    <div class="gbb-product-name">{{ $product->name }}</div>
                                    @if ($product->final_price > 0)
                                        <div class="gbb-product-price">Tk {{ number_format($product->final_price, 2) }}
                                        </div>
                                    @endif
                                    <button type="button" class="gbb-add-btn" data-action="add-to-box"
                                        @disabled($product->stock_level === 'out_of_stock')>
                                        {{ $product->stock_level === 'out_of_stock' ? 'Out of stock' : 'Add to box' }}
                                    </button>
                                </div>
                            @empty
                                <p class="text-muted">No greeting cards are available right now.</p>
                            @endforelse
                        </div>

                        {{-- Recipient / letter fields — always visible below the card grid --}}
                        <div class="gbb-recipient-card" id="gbbRecipientFields">
                            <label for="gbbRecipientName">To</label>
                            <input type="text" class="form-control" id="gbbRecipientName" name="recipient_name"
                                placeholder="Write the recipient name here.">

                            <label for="gbbCardMessage" class="mt-3">Card Message</label>
                            <textarea class="form-control" id="gbbCardMessage" name="card_message" rows="6"
                                placeholder="Write your letter / message here."></textarea>
                        </div>
                    </div>

                </div>

                {{-- ============ Sidebar cart summary ============ --}}
                <div class="col-lg-4">
                    <div class="gbb-sidebar">
                        <div class="gbb-sidebar-banner" id="gbbDiscountBanner">
                            Add 3 more item(s) to get 10% off!
                        </div>
                        <div class="gbb-sidebar-body" id="gbbCartBody">
                            <div class="gbb-sidebar-empty" id="gbbCartEmpty">
                                Your box is empty
                            </div>
                            <div id="gbbCartItems"></div>
                        </div>
                        <div class="gbb-sidebar-footer" id="gbbCartTotalRow" style="display:none;">
                            <span>Subtotal</span>
                            <span id="gbbCartTotal">Tk 0.00</span>
                        </div>
                        <div class="gbb-sidebar-nav">
                            <button type="button" class="gbb-btn-back" id="gbbBackBtn">Back</button>
                            <button type="button" class="gbb-btn-next" id="gbbNextBtn">Next</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ Quick view / Add to box modal ============ --}}
    <div class="modal fade" id="gbbQuickViewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="gbbModalProductName">Product name</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {{-- Images --}}
                        <div class="col-md-5">
                            <img src="" class="gbb-modal-main-img" id="gbbModalMainImg" alt="">
                            <div class="gbb-modal-thumb-nav" id="gbbModalThumbNav"></div>
                        </div>
                        {{-- Info --}}
                        <div class="col-md-7">
                            <div class="gbb-modal-price" id="gbbModalPrice">Tk 0.00</div>

                            <div id="gbbModalColorLabel" class="fw-semibold mb-1" style="display:none;">Color</div>
                            <div class="gbb-color-options" id="gbbModalColorOptions"></div>

                            <div class="d-flex align-items-center gap-3 mb-2">
                                <span class="fw-semibold">Quantity</span>
                                <div class="gbb-qty-selector">
                                    <button type="button" id="gbbModalQtyMinus"
                                        aria-label="Decrease quantity">&minus;</button>
                                    <span class="gbb-qty-value" id="gbbModalQtyValue">1</span>
                                    <button type="button" id="gbbModalQtyPlus" aria-label="Increase quantity">+</button>
                                </div>
                            </div>

                            <p class="text-muted small mb-0" id="gbbModalDescription"></p>

                            <button type="button" class="gbb-modal-confirm-btn" id="gbbModalConfirmBtn">
                                Add to Box
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('frontend_js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            var STEP_COUNT = 5; // Box, Wraping, Gifts, Extras, Cards
            var GIFT_STEP = 3; // the "Gifts" step index — where the min-item guard applies
            var currentStep = 1;
            var cart = []; // {id, productId, colorId, name, price, qty, image, type}
            var FREE_DISCOUNT_THRESHOLD = 6; // items needed for 10% off
            var MIN_GIFT_ITEMS = 3;

            var stepsWrap = document.getElementById('gbbSteps');
            var backBtn = document.getElementById('gbbBackBtn');
            var nextBtn = document.getElementById('gbbNextBtn');
            var cartItemsEl = document.getElementById('gbbCartItems');
            var cartEmptyEl = document.getElementById('gbbCartEmpty');
            var cartTotalRow = document.getElementById('gbbCartTotalRow');
            var cartTotalEl = document.getElementById('gbbCartTotal');
            var discountBanner = document.getElementById('gbbDiscountBanner');

            /* ---------------- Step navigation ---------------- */
            function goToStep(step) {
                step = Math.min(Math.max(step, 1), STEP_COUNT);
                currentStep = step;

                document.querySelectorAll('.gbb-step-content').forEach(function(el) {
                    el.classList.toggle('active', Number(el.dataset.stepContent) === step);
                });
                document.querySelectorAll('.gbb-step').forEach(function(el) {
                    var s = Number(el.dataset.step);
                    el.classList.toggle('active', s === step);
                    el.classList.toggle('done', s < step);
                });

                backBtn.style.visibility = step === 1 ? 'hidden' : 'visible';
                nextBtn.textContent = step === STEP_COUNT ? 'Checkout' : 'Next';
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }

            stepsWrap.querySelectorAll('.gbb-step').forEach(function(el) {
                el.addEventListener('click', function() {
                    goToStep(Number(el.dataset.step));
                });
            });

            backBtn.addEventListener('click', function() {
                goToStep(currentStep - 1);
            });

            nextBtn.addEventListener('click', function() {
                // Guard: require at least 3 gift items before leaving the Gifts step
                if (currentStep === GIFT_STEP) {
                    var giftCount = cart.filter(function(i) {
                            return i.type === 'gift_item';
                        })
                        .reduce(function(sum, i) {
                            return sum + i.qty;
                        }, 0);
                    if (giftCount < MIN_GIFT_ITEMS) {
                        alert('Please choose at least ' + MIN_GIFT_ITEMS + ' gift items to continue.');
                        return;
                    }
                }

                if (currentStep === STEP_COUNT) {
                    submitGiftBox();
                    return;
                }

                goToStep(currentStep + 1);
            });

            /* ---------------- For Her / For Him toggle ---------------- */
            document.getElementById('gbbGenderToggle').addEventListener('click', function(e) {
                var btn = e.target.closest('.gbb-toggle-btn');
                if (!btn) return;
                document.querySelectorAll('#gbbGenderToggle .gbb-toggle-btn').forEach(function(b) {
                    b.classList.remove('active');
                });
                btn.classList.add('active');
                filterGiftGrid();
            });

            function filterGiftGrid() {
                var gender = document.querySelector('#gbbGenderToggle .gbb-toggle-btn.active').dataset.gender;
                document.querySelectorAll('#gbbGiftGrid .gbb-product-card').forEach(function(card) {
                    card.style.display = (card.dataset.gender === gender) ? '' : 'none';
                });
            }

            /* ---------------- Extras / Sweet Treats sub tabs ---------------- */
            document.getElementById('gbbExtrasSubtabs').addEventListener('click', function(e) {
                var tab = e.target.closest('.gbb-subtab');
                if (!tab) return;
                document.querySelectorAll('#gbbExtrasSubtabs .gbb-subtab').forEach(function(t) {
                    t.classList.remove('active');
                });
                tab.classList.add('active');
                var group = tab.dataset.extraGroup;
                document.querySelectorAll('#gbbExtrasGrid .gbb-product-card').forEach(function(card) {
                    card.style.display = (card.dataset.extraGroup === group) ? '' : 'none';
                });
            });

            /* ---------------- Quick view modal ---------------- */
            var modalEl = document.getElementById('gbbQuickViewModal');
            var bsModal = new bootstrap.Modal(modalEl);
            var modalName = document.getElementById('gbbModalProductName');
            var modalMainImg = document.getElementById('gbbModalMainImg');
            var modalThumbNav = document.getElementById('gbbModalThumbNav');
            var modalPrice = document.getElementById('gbbModalPrice');
            var modalColorWrap = document.getElementById('gbbModalColorOptions');
            var modalColorLabel = document.getElementById('gbbModalColorLabel');
            var modalQtyValue = document.getElementById('gbbModalQtyValue');
            var modalQtyMinus = document.getElementById('gbbModalQtyMinus');
            var modalQtyPlus = document.getElementById('gbbModalQtyPlus');
            var modalConfirmBtn = document.getElementById('gbbModalConfirmBtn');
            var activeProduct = null;
            var activeCard = null;

            function openQuickView(card) {
                activeCard = card;
                activeProduct = {
                    id: card.dataset.id,
                    name: card.dataset.name,
                    price: parseFloat(card.dataset.price) || 0,
                    image: card.dataset.image,
                    type: card.dataset.type
                };

                modalName.textContent = activeProduct.name;
                modalMainImg.alt = activeProduct.name;
                modalPrice.textContent = 'Tk ' + activeProduct.price.toFixed(2);
                modalQtyValue.textContent = '1';

                // Colors (with each color's own image set) come straight from the DB
                // via data-colors. Greeting cards (type "letter") never show a color picker.
                modalColorWrap.innerHTML = '';
                var colors = null;
                if (card.dataset.colors) {
                    try {
                        colors = JSON.parse(card.dataset.colors);
                    } catch (err) {
                        colors = null;
                    }
                }

                function renderImagesFor(images) {
                    var list = (images && images.length) ? images : [activeProduct.image];
                    modalMainImg.src = list[0];
                    modalThumbNav.innerHTML = '';
                    list.forEach(function(src) {
                        var img = document.createElement('img');
                        img.src = src;
                        img.addEventListener('click', function() {
                            modalMainImg.src = src;
                        });
                        modalThumbNav.appendChild(img);
                    });
                }

                if (colors && colors.length && activeProduct.type !== 'letter') {
                    // Product has colors: show the color section, default to the
                    // first color, and swap images whenever a color is clicked.
                    modalColorLabel.style.display = 'block';
                    colors.forEach(function(color, idx) {
                        var b = document.createElement('button');
                        b.type = 'button';
                        b.className = 'gbb-color-btn' + (idx === 0 ? ' active' : '');
                        b.textContent = color.name || 'None';
                        b.dataset.colorId = color.id;
                        b.addEventListener('click', function() {
                            modalColorWrap.querySelectorAll('.gbb-color-btn').forEach(function(x) {
                                x.classList.remove('active');
                            });
                            b.classList.add('active');
                            renderImagesFor(color.images);
                        });
                        modalColorWrap.appendChild(b);
                    });
                    renderImagesFor(colors[0].images);
                } else {
                    // No colors on this product: hide the color section entirely
                    // and just show the product's own image(s).
                    modalColorLabel.style.display = 'none';
                    var fallbackImages = null;
                    if (card.dataset.images) {
                        try {
                            fallbackImages = JSON.parse(card.dataset.images);
                        } catch (err) {
                            fallbackImages = null;
                        }
                    }
                    renderImagesFor(fallbackImages);
                }

                bsModal.show();
            }

            modalQtyMinus.addEventListener('click', function() {
                var val = Math.max(1, parseInt(modalQtyValue.textContent, 10) - 1);
                modalQtyValue.textContent = val;
            });
            modalQtyPlus.addEventListener('click', function() {
                var val = parseInt(modalQtyValue.textContent, 10) + 1;
                modalQtyValue.textContent = val;
            });

            modalConfirmBtn.addEventListener('click', function() {
                if (!activeProduct) return;
                var qty = parseInt(modalQtyValue.textContent, 10) || 1;
                var color = modalColorWrap.querySelector('.gbb-color-btn.active');

                // Only one box allowed per gift box — drop any previously chosen box
                if (activeProduct.type === 'gift_box') {
                    cart = cart.filter(function(i) {
                        return i.type !== 'gift_box';
                    });
                    document.querySelectorAll('#gbbBoxGrid .gbb-box-card').forEach(function(c) {
                        c.classList.remove('selected');
                        var b = c.querySelector('.gbb-add-btn');
                        if (b) {
                            b.textContent = 'Add to box';
                            b.classList.remove('added');
                        }
                    });
                    if (activeCard) {
                        activeCard.classList.add('selected');
                        var activeBtn = activeCard.querySelector('.gbb-add-btn');
                        if (activeBtn) {
                            activeBtn.textContent = 'Selected';
                            activeBtn.classList.add('added');
                        }
                    }
                }

                addToCart({
                    id: activeProduct.id + (color ? '-' + color.dataset.colorId : ''),
                    productId: activeProduct.id,
                    colorId: color ? color.dataset.colorId : null,
                    colorName: color ? color.textContent : null,
                    name: activeProduct.name,
                    price: activeProduct.price,
                    image: modalMainImg.src,
                    type: activeProduct.type,
                    qty: qty
                });

                bsModal.hide();
            });

            /* "Add to box" click in the box/wraping/gift/extras grids opens the color+quantity quick view */
            ['gbbBoxGrid', 'gbbWrapingGrid', 'gbbGiftGrid', 'gbbExtrasGrid'].forEach(function(gridId) {
                var grid = document.getElementById(gridId);
                if (!grid) return;
                grid.addEventListener('click', function(e) {
                    var btn = e.target.closest('[data-action="add-to-box"]');
                    if (!btn || btn.disabled) return;
                    var card = e.target.closest('.gbb-product-card');
                    openQuickView(card);
                });
            });

            /* Greeting cards: no color/quantity needed — clicking "Add to box" selects the
               card directly (only one card per gift box) and reveals the letter fields underneath. */
            document.getElementById('gbbCardsGrid').addEventListener('click', function(e) {
                var btn = e.target.closest('[data-action="add-to-box"]');
                if (!btn || btn.disabled) return;
                var card = e.target.closest('.gbb-product-card');

                // Only one greeting card allowed per box — drop any previously chosen card
                cart = cart.filter(function(i) {
                    return i.type !== 'letter';
                });
                document.querySelectorAll('#gbbCardsGrid .gbb-add-btn').forEach(function(b) {
                    b.textContent = 'Add to box';
                    b.classList.remove('added');
                });

                addToCart({
                    id: card.dataset.id,
                    productId: card.dataset.id,
                    colorId: null,
                    name: card.dataset.name,
                    price: parseFloat(card.dataset.price) || 0,
                    image: card.dataset.image,
                    type: 'letter',
                    qty: 1
                });

                btn.textContent = 'Selected';
                btn.classList.add('added');
            });

            /* ---------------- Cart handling ---------------- */
            function addToCart(item) {
                var existing = cart.find(function(i) {
                    return i.id === item.id;
                });
                if (existing) {
                    existing.qty += item.qty;
                } else {
                    cart.push(item);
                }
                renderCart();
            }

            function removeFromCart(id) {
                cart = cart.filter(function(i) {
                    return i.id !== id;
                });
                renderCart();
            }

            function renderCart() {
                var totalQty = cart.reduce(function(sum, i) {
                    return sum + i.qty;
                }, 0);
                var totalPrice = cart.reduce(function(sum, i) {
                    return sum + i.qty * i.price;
                }, 0);

                cartEmptyEl.style.display = cart.length ? 'none' : 'flex';
                cartTotalRow.style.display = cart.length ? 'flex' : 'none';
                cartTotalEl.textContent = 'Tk ' + totalPrice.toFixed(2);

                cartItemsEl.innerHTML = '';
                cart.forEach(function(item) {
                    var row = document.createElement('div');
                    row.className = 'gbb-cart-item';
                    var metaParts = ['Qty ' + item.qty];
                    if (item.colorName) {
                        metaParts.push('Color: ' + item.colorName);
                    }
                    metaParts.push('Tk ' + item.price.toFixed(2));

                    row.innerHTML =
                        '<img src="' + item.image + '" alt="' + item.name + '">' +
                        '<div class="flex-grow-1">' +
                        '<div class="gbb-cart-item-name">' + item.name + '</div>' +
                        '<div class="gbb-cart-item-meta">' + metaParts.join(' &middot; ') + '</div>' +
                        '</div>' +
                        '<button type="button" class="gbb-cart-item-remove" aria-label="Remove">&times;</button>';
                    row.querySelector('.gbb-cart-item-remove').addEventListener('click', function() {
                        removeFromCart(item.id);
                    });
                    cartItemsEl.appendChild(row);
                });

                if (totalQty >= FREE_DISCOUNT_THRESHOLD) {
                    discountBanner.textContent = "You've unlocked 10% off!";
                    discountBanner.classList.add('is-complete');
                } else {
                    var remaining = FREE_DISCOUNT_THRESHOLD - totalQty;
                    discountBanner.textContent = 'Add ' + remaining + ' more item(s) to get 10% off!';
                    discountBanner.classList.remove('is-complete');
                }
            }

            /* ---------------- Final checkout submission ---------------- */
            function submitGiftBox() {
                var items = cart.map(function(item) {
                    return {
                        product_id: item.productId,
                        color_id: item.colorId || null,
                        qty: item.qty
                    };
                });

                var payload = {
                    items: items,
                    recipient_name: document.getElementById('gbbRecipientName').value,
                    card_message: document.getElementById('gbbCardMessage').value
                };

                var tokenMeta = document.querySelector('meta[name="csrf-token"]');
                nextBtn.disabled = true;
                nextBtn.textContent = 'Processing...';

                fetch('{{ route('frontend.gift.build.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': tokenMeta ? tokenMeta.getAttribute('content') : ''
                        },
                        body: JSON.stringify(payload)
                    })
                    .then(function(res) {
                        return res.json();
                    })
                    .then(function(data) {
                        if (data.success) {
                            window.location.href = data.redirect;
                        } else {
                            alert(data.message || 'Something went wrong, please try again.');
                            nextBtn.disabled = false;
                            nextBtn.textContent = 'Checkout';
                        }
                    })
                    .catch(function() {
                        alert('Something went wrong, please try again.');
                        nextBtn.disabled = false;
                        nextBtn.textContent = 'Checkout';
                    });
            }

            /* Init */
            filterGiftGrid();
            renderCart();
            goToStep(1);
        });
    </script>
@endpush
