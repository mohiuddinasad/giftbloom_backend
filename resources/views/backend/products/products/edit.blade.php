@extends('backend.layout')
@section('backend_title', 'Edit Product')
@section('backend_css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.snow.min.css" rel="stylesheet">
@endsection

@section('backend_content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Edit Product</h4>
        {{-- $product was resolved from the {product:slug} route param --}}
        <a href="{{ route('dashboard.products.stock.index', $product) }}" class="btn btn-outline-dark btn-sm">
            Stock In / Out (current: {{ $product->qty }})
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
        </div>
    @endif

    <form action="{{ route('dashboard.products.update', $product) }}" method="POST" enctype="multipart/form-data" id="productForm">
        @csrf @method('PUT')

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Product Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-select" required>
                    @foreach ($categories as $cat)
                        @if (! $cat->parent_id)
                            <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id) == $cat->id)>{{ $cat->name }}</option>
                            @foreach ($cat->children as $child)
                                <option value="{{ $child->id }}" @selected(old('category_id', $product->category_id) == $child->id)>&nbsp;&nbsp;— {{ $child->name }}</option>
                            @endforeach
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Product Type</label>
                <select name="type" class="form-select" required>
                    @foreach ($types as $value => $label)
                        <option value="{{ $value }}" @selected(old('type', $product->type) == $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Gift For</label>
                <select name="gift_for" class="form-select">
                    <option value="">-- N/A --</option>
                    @foreach ($giftFor as $value => $label)
                        <option value="{{ $value }}" @selected(old('gift_for', $product->gift_for) == $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Quantity</label>
                <input type="number" class="form-control" value="{{ $product->qty }}" disabled>
                <small class="text-muted">Use "Stock In / Out" above to change quantity — keeps the audit log accurate.</small>
            </div>

            <div class="col-md-3">
                <label class="form-label">Price</label>
                <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $product->price) }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Discount Price</label>
                <input type="number" step="0.01" name="discount_price" class="form-control" value="{{ old('discount_price', $product->discount_price) }}">
            </div>

            <div class="col-12">
                <label class="form-label">Short Description</label>
                <input type="text" name="short_description" class="form-control" maxlength="500" value="{{ old('short_description', $product->short_description) }}">
            </div>

            <div class="col-12">
                <label class="form-label">Description</label>
                <div id="editor" style="height:220px;background:#fff;">{!! old('description', $product->description) !!}</div>
                <input type="hidden" name="description" id="description-input">
            </div>

            <div class="col-12"><hr><h6>Existing Colours</h6></div>
            <div class="col-12">
                @foreach ($product->colors as $color)
                    <div class="card mb-2">
                        <div class="card-body">
                            <strong>{{ $color->color_name }}</strong>
                            <span class="badge" style="background: {{ $color->color_code }}">&nbsp;</span>
                            <div class="d-flex gap-2 mt-2 flex-wrap">
                                @foreach ($color->images as $img)
                                    <img src="{{ $img->url }}" width="60" height="60" style="object-fit:cover;border-radius:4px;">
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="col-12"><h6>Add New Colours</h6></div>
            <div class="col-12" id="colorRows"></div>
            <div class="col-12">
                <button type="button" class="btn btn-outline-secondary btn-sm" id="addColorRow">+ Add Colour</button>
            </div>

            <div class="col-12"><hr><h6>SEO</h6></div>
            <div class="col-md-4">
                <label class="form-label">Meta Title</label>
                <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $product->meta_title) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Meta Keywords</label>
                <input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords', $product->meta_keywords) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Meta Description</label>
                <input type="text" name="meta_description" class="form-control" value="{{ old('meta_description', $product->meta_description) }}">
            </div>

            <div class="col-md-6">
                <div class="form-check form-switch">
                    <input type="checkbox" name="status" value="1" class="form-check-input" @checked(old('status', $product->status))>
                    <label class="form-check-label">Published</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-check form-switch">
                    <input type="checkbox" name="is_featured" value="1" class="form-check-input" @checked(old('is_featured', $product->is_featured))>
                    <label class="form-check-label">Featured</label>
                </div>
            </div>

            <div class="col-12">
                <button class="btn btn-primary">Update Product</button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('backend_js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.min.js"></script>
<script>
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
                    <input type="text" name="colors[${i}][color_name]" class="form-control" placeholder="e.g. Red">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Colour Code</label>
                    <input type="color" name="colors[${i}][color_code]" class="form-control form-control-color" value="#000000">
                </div>
                <div class="col-md-5">
                    <label class="form-label">Images for this colour</label>
                    <input type="file" name="colors[${i}][images][]" class="form-control" accept="image/*" multiple>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-color">Remove</button>
                </div>
            </div>`;
        row.querySelector('.remove-color').addEventListener('click', () => row.remove());
        colorRows.appendChild(row);
    }

    document.getElementById('addColorRow').addEventListener('click', addColorRow);
</script>
@endsection
