// ---- close dropdowns when clicking outside ----
document.addEventListener('click', function (e) {
    document.querySelectorAll('.filter-dropdown').forEach(function (dd) {
        if (!dd.contains(e.target)) dd.classList.remove('open');
    });
});

function toggleDropdown(id) {
    event.stopPropagation();
    var dd = document.getElementById(id);
    var wasOpen = dd.classList.contains('open');
    document.querySelectorAll('.filter-dropdown').forEach(function (d) { d.classList.remove('open'); });
    if (!wasOpen) dd.classList.add('open');
}

var row = document.getElementById('productsRow');
var cards = Array.prototype.slice.call(row.children);

// ---- availability counts ----
var inCount = cards.filter(function (c) { return c.dataset.avail === 'in'; }).length;
var outCount = cards.filter(function (c) { return c.dataset.avail === 'out'; }).length;
document.getElementById('inStockCount').textContent = inCount;
document.getElementById('outStockCount').textContent = outCount;

// ---- price range slider ----
var minRange = document.getElementById('minRange');
var maxRange = document.getElementById('maxRange');
var minPriceInput = document.getElementById('minPriceInput');
var maxPriceInput = document.getElementById('maxPriceInput');
var rangeFill = document.getElementById('rangeFill');
var sliderMax = 4890;

function updateFill() {
    var minVal = parseInt(minRange.value);
    var maxVal = parseInt(maxRange.value);
    var minPct = (minVal / sliderMax) * 100;
    var maxPct = (maxVal / sliderMax) * 100;
    rangeFill.style.left = minPct + '%';
    rangeFill.style.width = (maxPct - minPct) + '%';
}

function onRangeInput() {
    var minVal = parseInt(minRange.value);
    var maxVal = parseInt(maxRange.value);
    if (minVal > maxVal) { minRange.value = maxVal; minVal = maxVal; }
    minPriceInput.value = minVal;
    maxPriceInput.value = maxVal;
    updateFill();
    applyFilters();
}
minRange.addEventListener('input', onRangeInput);
maxRange.addEventListener('input', onRangeInput);

minPriceInput.addEventListener('change', function () {
    var v = Math.max(0, Math.min(parseInt(minPriceInput.value) || 0, parseInt(maxRange.value)));
    minRange.value = v; minPriceInput.value = v; updateFill(); applyFilters();
});
maxPriceInput.addEventListener('change', function () {
    var v = Math.min(sliderMax, Math.max(parseInt(maxPriceInput.value) || sliderMax, parseInt(minRange.value)));
    maxRange.value = v; maxPriceInput.value = v; updateFill(); applyFilters();
});
updateFill();

// ---- availability checkboxes ----
document.querySelectorAll('.avail-check').forEach(function (cb) {
    cb.addEventListener('change', applyFilters);
});

// ---- sort ----
document.querySelectorAll('.sort-option').forEach(function (opt) {
    opt.addEventListener('click', function () {
        document.querySelectorAll('.sort-option').forEach(function (o) { o.classList.remove('selected'); });
        opt.classList.add('selected');
        sortCards(opt.dataset.sort);
        document.getElementById('sortDropdown').classList.remove('open');
    });
});

function sortCards(mode) {
    var sorted = cards.slice();
    if (mode === 'price-asc') sorted.sort(function (a, b) { return a.dataset.price - b.dataset.price; });
    else if (mode === 'price-desc') sorted.sort(function (a, b) { return b.dataset.price - a.dataset.price; });
    else if (mode === 'name-asc') sorted.sort(function (a, b) { return a.dataset.name.localeCompare(b.dataset.name); });
    else if (mode === 'name-desc') sorted.sort(function (a, b) { return b.dataset.name.localeCompare(a.dataset.name); });
    sorted.forEach(function (c) { row.appendChild(c); });
}

// ---- filters ----
function applyFilters() {
    var minVal = parseInt(minRange.value);
    var maxVal = parseInt(maxRange.value);
    var checkedAvail = Array.prototype.slice.call(document.querySelectorAll('.avail-check:checked')).map(function (c) { return c.value; });

    var visibleCount = 0;
    cards.forEach(function (card) {
        var price = parseInt(card.dataset.price);
        var avail = card.dataset.avail;
        var show = price >= minVal && price <= maxVal && checkedAvail.indexOf(avail) !== -1;
        card.style.display = show ? '' : 'none';
        if (show) visibleCount++;
    });
    document.getElementById('itemCount').textContent = visibleCount;
    document.getElementById('emptyState').style.display = visibleCount === 0 ? 'block' : 'none';
}
applyFilters();

// ---- grid / list view ----
function setView(mode) {
    var products = document.getElementById('products');
    var gridBtn = document.getElementById('gridViewBtn');
    var listBtn = document.getElementById('listViewBtn');
    if (mode === 'list') {
        products.classList.add('list-view');
        listBtn.classList.add('active');
        gridBtn.classList.remove('active');
    } else {
        products.classList.remove('list-view');
        gridBtn.classList.add('active');
        listBtn.classList.remove('active');
    }
}