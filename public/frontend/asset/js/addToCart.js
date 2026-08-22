document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const cartItemsList = document.getElementById('cartItemsList');
    const offcanvasCartCount = document.getElementById('cartCount');   // offcanvas header "Cart 2"
    const navCartCountEls = document.querySelectorAll('.js-cart-count'); // navbar badges (mobile + desktop)
    const cartTotal = document.getElementById('cartTotal');

    // ---------------- special instructions toggle ----------------
    const noteBox = document.getElementById('cartNote');
    // const noteBtn = document.getElementById('noteToggleBtn');
    // noteBtn.addEventListener('click', () => {
    //     noteBox.classList.toggle('open');
    //     const icon = noteBtn.querySelector('i');
    //     icon.classList.toggle('fa-plus');
    //     icon.classList.toggle('fa-minus');
    // });

    // ---------------- cart render helpers ----------------
    function renderCartItem(key, item) {
        return `
        <div class="cart-item" data-cart-key="${key}" data-price="${item.price}">
            <img class="cart-item__img" src="${item.image ?? ''}" alt="${item.name}">
            <div class="cart-item__info">
                <div class="cart-item__top">
                    <div>
                        <p class="cart-item__name">${item.name}</p>
                        <p class="cart-item__unit-price">Tk ${Number(item.price).toFixed(2)}</p>
                    </div>
                    <div class="cart-item__line-total">Tk ${(item.price * item.qty).toFixed(2)}</div>
                </div>
                <div class="cart-item__controls">
                    <div class="qty-stepper">
                        <button type="button" class="qty-minus" aria-label="Decrease quantity">&minus;</button>
                        <input type="text" class="qty-input" value="${item.qty}" readonly>
                        <button type="button" class="qty-plus" aria-label="Increase quantity">+</button>
                    </div>
                    <button type="button" class="cart-item__remove" aria-label="Remove item">
                        <i class="fa-regular fa-trash-can"></i>
                    </button>
                </div>
            </div>
        </div>`;
    }

    function setCartCount(qty) {
        offcanvasCartCount.textContent = qty;
        navCartCountEls.forEach(el => el.textContent = qty);
    }

    function updateCartUI(data) {
        const entries = Object.entries(data.items);

        cartItemsList.innerHTML = entries.length
            ? entries.map(([key, item]) => renderCartItem(key, item)).join('')
            : '<p class="cart-empty">Your cart is empty.</p>';

        setCartCount(data.cart_count);
        cartTotal.textContent = `Tk ${data.total} BDT`;
    }

    window.refreshCartUI = updateCartUI; // <-- এই লাইনটা নতুন — অন্য পেজ থেকে কল করার জন্য

    // recalc header count/total from current DOM state (instant, no server wait)
    function recalcSummaryFromDOM() {
        let qty = 0;
        let total = 0;

        cartItemsList.querySelectorAll('.cart-item').forEach(el => {
            const price = parseFloat(el.dataset.price);
            const q = parseInt(el.querySelector('.qty-input').value, 10);
            qty += q;
            total += price * q;
        });

        setCartCount(qty);
        cartTotal.textContent = `Tk ${total.toFixed(2)} BDT`;
    }

    function postRequest(url, body) {
        return fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: body ? JSON.stringify(body) : null,
        }).then(res => res.json());
    }

    // ---------------- add to cart — product card buttons ----------------
    document.querySelectorAll('.cart-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            postRequest('/cart/add', {
                product_id: this.dataset.productId,
                color_id: this.dataset.colorId,
                qty: 1,
            }).then(data => {
                updateCartUI(data);
                new bootstrap.Offcanvas(document.getElementById('offcanvasRight')).show();
            });
        });
    });

    // ---------------- qty +/- (optimistic, debounced sync) ----------------
    const updateTimers = {};

    function syncQty(key, qty) {
        clearTimeout(updateTimers[key]);
        updateTimers[key] = setTimeout(() => {
            postRequest('/cart/update', { cart_key: key, quantity: qty });
        }, 400);
    }

    // ---------------- remove (optimistic) ----------------
    function removeItem(itemEl, key) {
        itemEl.remove();
        recalcSummaryFromDOM();

        if (!cartItemsList.querySelector('.cart-item')) {
            cartItemsList.innerHTML = '<p class="cart-empty">Your cart is empty.</p>';
        }

        postRequest(`/cart/remove/${key}`);
    }

    cartItemsList.addEventListener('click', function (e) {
        const item = e.target.closest('.cart-item');
        if (!item) return;
        const key = item.dataset.cartKey;

        if (e.target.closest('.qty-plus') || e.target.closest('.qty-minus')) {
            const input = item.querySelector('.qty-input');
            let qty = parseInt(input.value, 10);
            qty = e.target.closest('.qty-plus') ? qty + 1 : Math.max(1, qty - 1);

            input.value = qty;

            const lineTotal = item.querySelector('.cart-item__line-total');
            const price = parseFloat(item.dataset.price);
            lineTotal.textContent = `Tk ${(price * qty).toFixed(2)}`;

            recalcSummaryFromDOM();
            syncQty(key, qty);
        }

        if (e.target.closest('.cart-item__remove')) {
            removeItem(item, key);
        }
    });
});
