// special instructions toggle
const noteBox = document.getElementById('cartNote');
const noteBtn = document.getElementById('noteToggleBtn');
noteBtn.addEventListener('click', () => {
    noteBox.classList.toggle('open');
    const icon = noteBtn.querySelector('i');
    icon.classList.toggle('fa-plus');
    icon.classList.toggle('fa-minus');
});

// Only the qty number changes on +/-. Price and delete stay fixed/static.
document.getElementById('cartItemsList').addEventListener('click', (e) => {
    const item = e.target.closest('.cart-item');
    if (!item) return;

    const input = item.querySelector('.qty-input');
 
    if (e.target.closest('.qty-plus')) {
        input.value = parseInt(input.value, 10) + 1;
    }

    if (e.target.closest('.qty-minus')) {
        const next = parseInt(input.value, 10) - 1;
        if (next >= 1) input.value = next;
    }
    // .cart-item__remove intentionally left without a handler (fixed/static)
});