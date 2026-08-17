@extends('backend.layout')

@section('backend_title', 'Add Product')



@section('backend_content')
<div class="container-fluid">
    <h4 class="mb-3">Add Product</h4>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('dashboard.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
        @csrf

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Product Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="col-md-6">
                {{-- 1. category (with sub-categories shown indented) --}}
                <label class="form-label">Category</label>
                <select name="category_id" class="form-select" required>
                    <option value="">-- Select --</option>
                    @foreach ($categories as $cat)
                        @if (! $cat->parent_id)
                            <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
                            @foreach ($cat->children as $child)
                                <option value="{{ $child->id }}" @selected(old('category_id') == $child->id)>&nbsp;&nbsp;— {{ $child->name }}</option>
                            @endforeach
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                {{-- 3. product type --}}
                <label class="form-label">Product Type</label>
                <select name="type" class="form-select" required>
                    @foreach ($types as $value => $label)
                        <option value="{{ $value }}" @selected(old('type') == $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                {{-- 4. gift for --}}
                <label class="form-label">Gift For</label>
                <select name="gift_for" class="form-select">
                    <option value="">-- N/A --</option>
                    @foreach ($giftFor as $value => $label)
                        <option value="{{ $value }}" @selected(old('gift_for') == $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                {{-- 7. qty --}}
                <label class="form-label">Opening Quantity</label>
                <input type="number" name="qty" class="form-control" value="{{ old('qty', 0) }}" min="0" required>
            </div>

            <div class="col-md-3">
                {{-- 5. price --}}
                <label class="form-label">Price</label>
                <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price') }}" required>
            </div>

            <div class="col-md-3">
                <label class="form-label">Discount Price <small class="text-muted">(optional)</small></label>
                <input type="number" step="0.01" name="discount_price" class="form-control" value="{{ old('discount_price') }}">
            </div>

            <div class="col-12">
                <label class="form-label">Short Description</label>
                <input type="text" name="short_description" class="form-control" maxlength="500" value="{{ old('short_description') }}">
            </div>

            <div class="col-12">
                {{-- 6. rich text description, MS-Word-style editing with bullet points --}}
                <label class="form-label">Description</label>
                <div id="editor" style="height:220px;background:#fff;">{!! old('description') !!}</div>
                {{-- hidden input actually submitted to the server --}}
                <input type="hidden" name="description" id="description-input">
            </div>

            <div class="col-12"><hr><h6>Colours &amp; Images</h6>
                <small class="text-muted">Add a colour, then upload the photos that belong to that colour (e.g. Red → red shirt photos).</small>
            </div>

            {{-- 2. colour-wise images, dynamically repeatable --}}
            <div class="col-12" id="colorRows"></div>

            <div class="col-12">
                <button type="button" class="btn btn-outline-secondary btn-sm" id="addColorRow">+ Add Colour</button>
            </div>

            <div class="col-12"><hr><h6>SEO</h6></div>

            <div class="col-md-4">
                <label class="form-label">Meta Title</label>
                <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Meta Keywords</label>
                <input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Meta Description</label>
                <input type="text" name="meta_description" class="form-control" value="{{ old('meta_description') }}">
            </div>

            <div class="col-md-6">
                <div class="form-check form-switch">
                    <input type="checkbox" name="status" value="1" class="form-check-input" checked>
                    <label class="form-check-label">Published</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-check form-switch">
                    <input type="checkbox" name="is_featured" value="1" class="form-check-input">
                    <label class="form-check-label">Featured</label>
                </div>
            </div>

            <div class="col-12">
                <button class="btn btn-primary">Save Product</button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('backend_js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.min.js"></script>
<script>
    // 6. Quill toolbar: bold/italic/underline, headings and bullet/numbered
    // lists — the "point the text" requirement — output is saved as HTML.
    const quill = new Quill('#editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ header: [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ list: 'ordered' }, { list: 'bullet' }],
                ['link'],
                ['clean'],
            ],
        },
    });

    document.getElementById('productForm').addEventListener('submit', function () {
        document.getElementById('description-input').value = quill.root.innerHTML;
    });

    // ---- dynamic colour + image rows ----
    let colorIndex = 0;
    const colorRows = document.getElementById('colorRows');

    function addColorRow() {
        const i = colorIndex++;
        const row = document.createElement('div');
        row.className = 'card mb-2';
        row.innerHTML = `
            <div class="card-body row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Colour Name</label>
                    <input type="text" name="colors[${i}][color_name]" class="form-control" placeholder="e.g. Red" required>
                </div>
                
                <div class="col-md-5">
                    <label class="form-label">Images for this colour</label>
                    <input type="file" name="colors[${i}][images][]" class="form-control" accept="image/*" multiple required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-color">Remove</button>
                </div>
            </div>`;
        row.querySelector('.remove-color').addEventListener('click', () => row.remove());
        colorRows.appendChild(row);
    }

    document.getElementById('addColorRow').addEventListener('click', addColorRow);
    addColorRow(); // start with one colour row
</script>
@endpush
