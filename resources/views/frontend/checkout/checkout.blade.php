@php
    $cart = session()->get('cart', []);
    $cartTotal = collect($cart)->sum(fn($item) => $item['price'] * $item['qty']);
@endphp

@extends('frontend.layout')

@section('frontend_title', 'Shop')
@section('frontend_content')
    <!---------------------- checkout page content ---------------------->

    <main class="checkout-page">
        <div class="container">

            <h1 class="checkout-title">Checkout</h1>

            @if (empty($cart))
                <div class="co-empty-cart text-center py-5">
                    <p>Your cart is empty.</p>
                    <a href="{{ route('frontend.shop') }}" class="btn btn-dark">Continue Shopping</a>
                </div>
            @else
                <form id="checkoutForm" action="{{ route('frontend.checkout.store') }}" method="POST">
                    @csrf

                    <div class="row">

                        <!-- LEFT: forms -->
                        <div class="col-lg-7">

                            <!-- Step 1: Delivery details -->
                            <div class="co-card">
                                <div class="co-card__step">
                                    <span class="num">1</span>
                                    <h2>Delivery Details</h2>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="co-field">
                                            <label for="fullName">Full Name</label>
                                            <input type="text" id="fullName" name="full_name"
                                                placeholder="Enter your name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="co-field">
                                            <label for="phone">Phone Number</label>
                                            <input type="tel" id="phone" name="phone" placeholder="01XXX-XXXXXX"
                                                required>
                                        </div>
                                    </div>
                                </div>

                                <div class="co-field">
                                    <label for="address">Full Address</label>
                                    <input type="text" id="address" name="address" placeholder="House, Road, Area"
                                        required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="co-field">
                                            <label for="area">Area / Thana</label>
                                            <input type="text" id="area" name="area" placeholder="e.g. Pahartali"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="co-field">
                                            <label for="city">City</label>
                                            <input type="text" id="city" name="city"
                                                placeholder="e.g. Chattogram" value="" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="co-field">
                                    <label for="note">Order Note (optional)</label>
                                    <textarea id="note" name="note" placeholder="Any delivery instructions..."></textarea>
                                </div>
                            </div>

                            <!-- Step 2: Shipping method -->
                            <div class="co-card">
                                <div class="co-card__step">
                                    <span class="num">2</span>
                                    <h2>Shipping Method</h2>
                                </div>

                                <label class="ship-option active">
                                    <input type="radio" name="shipping" value="inside" checked>
                                    <span class="ship-option__icon"><i class="fa-solid fa-location-dot"></i></span>
                                    <span class="ship-option__text">
                                        <span class="t">Inside Chattogram</span>
                                    </span>
                                    <span class="ship-option__price">Tk 70.00</span>
                                </label>

                                <label class="ship-option">
                                    <input type="radio" name="shipping" value="near">
                                    <span class="ship-option__icon"><i class="fa-solid fa-route"></i></span>
                                    <span class="ship-option__text">
                                        <span class="t">Near Chattogram</span>
                                    </span>
                                    <span class="ship-option__price">Tk 120.00</span>
                                </label>

                                <label class="ship-option">
                                    <input type="radio" name="shipping" value="outside">
                                    <span class="ship-option__icon"><i class="fa-solid fa-truck-fast"></i></span>
                                    <span class="ship-option__text">
                                        <span class="t">Outside Chattogram</span>
                                    </span>
                                    <span class="ship-option__price">Tk 150.00</span>
                                </label>
                            </div>

                            <!-- Step 3: Payment method -->
                            <div class="co-card">
                                <div class="co-card__step">
                                    <span class="num">3</span>
                                    <h2>Payment Method</h2>
                                </div>

                                <div class="pay-option">
                                    <span class="pay-option__icon"><i class="fa-solid fa-money-bill-wave"></i></span>
                                    <div>
                                        <div class="t">Cash on Delivery (COD)</div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- RIGHT: order summary -->
                        <div class="col-lg-5 mt-4 mt-lg-0">
                            <div class="co-summary">
                                <h2>Order Summary</h2>

                                @foreach ($cart as $item)
                                    <div class="co-summary-item">
                                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}">
                                        <div class="co-summary-item__info">
                                            <div class="n">{{ $item['name'] }}</div>
                                            <div class="q">Qty: {{ $item['qty'] }}</div>
                                            <div class="q">Color: {{ $item['color_name'] ?? 'none' }}</div>
                                        </div>
                                        <div class="co-summary-item__price">Tk
                                            {{ number_format($item['price'] * $item['qty'], 2) }}</div>
                                    </div>
                                @endforeach

                                <div class="co-totals">
                                    <div class="co-totals-row">
                                        <span>Subtotal</span>
                                        <span id="subtotal">Tk {{ number_format($cartTotal, 2) }}</span>
                                    </div>
                                    <div class="co-totals-row">
                                        <span>Shipping</span>
                                        <span id="shipCost">Tk 70.00</span>
                                    </div>
                                    <div class="co-totals-row grand">
                                        <span>Total</span>
                                        <span id="grandTotal">Tk {{ number_format($cartTotal + 70, 2) }} BDT</span>
                                    </div>
                                </div>

                                <button type="button" id="placeOrderBtn" class="co-place-btn">Place Order</button>

                                <div class="co-secure">
                                    <i class="fa-solid fa-lock"></i> Your information is safe with us
                                </div>
                            </div>
                        </div>

                    </div>

                </form>
            @endif
        </div>
    </main>

    <!---------------------- footer  ---------------------->
@endsection
@push('frontend_js')
    <script>
        // shipping selection + live total
        const shipOptions = document.querySelectorAll('.ship-option');
        const shipPrices = [70, 120, 150];
        const subtotal = {{ $cartTotal }};
        const shipCostEl = document.getElementById('shipCost');
        const grandTotalEl = document.getElementById('grandTotal');

        shipOptions.forEach((opt, i) => {
            opt.addEventListener('click', () => {
                shipOptions.forEach(o => o.classList.remove('active'));
                opt.classList.add('active');
                opt.querySelector('input[type="radio"]').checked = true;

                const cost = shipPrices[i];
                shipCostEl.textContent = 'Tk ' + cost.toFixed(2);
                grandTotalEl.textContent = 'Tk ' + (subtotal + cost).toFixed(2) + ' BDT';
            });
        });

        // place order
        const placeOrderBtn = document.getElementById('placeOrderBtn');
        if (placeOrderBtn) {
            placeOrderBtn.addEventListener('click', function() {
                const btn = this;
                const activeShip = document.querySelector('.ship-option.active input[type="radio"]');

                const payload = {
                    full_name: document.getElementById('fullName').value.trim(),
                    phone: document.getElementById('phone').value.trim(),
                    address: document.getElementById('address').value.trim(),
                    area: document.getElementById('area').value.trim(),
                    city: document.getElementById('city').value.trim(),
                    note: document.getElementById('note').value.trim(),
                    shipping_method: activeShip ? activeShip.value : 'inside',
                };

                if (!payload.full_name || !payload.phone || !payload.address || !payload.area || !payload.city) {
                    alert('Please fill in all required fields.');
                    return;
                }

                btn.disabled = true;
                btn.textContent = 'Placing order...';

                fetch("{{ route('frontend.checkout.store') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify(payload),
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = data.redirect_url;
                        } else {
                            alert(data.message || 'Something went wrong.');
                            btn.disabled = false;
                            btn.textContent = 'Place Order';
                        }
                    })
                    .catch(() => {
                        alert('Network error. Please try again.');
                        btn.disabled = false;
                        btn.textContent = 'Place Order';
                    });
            });
        }
    </script>
@endpush
