@extends('backend.layout')
@section('backend_title', 'Edit Product')
@section('backend_content')
    @include('backend.products._styles')

    <div class="cp-wrap">
        <div class="cp-header">
            <div>
                <div class="cp-breadcrumb">
                    <a href="{{ route('dashboard') }}">Dashboard</a> /
                    <a href="{{ route('dashboard.products.index') }}">Products</a> / Edit
                </div>
                <h1>Edit Product</h1>
            </div>
        </div>

        @if ($errors->any())
            <div class="cp-alert cp-alert-danger">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('dashboard.products.update', $product) }}" method="POST" enctype="multipart/form-data"
            id="productForm">
            @csrf @method('PUT')

            <div class="cp-card">
                <div class="cp-card-header">
                    <h2><i class="bi bi-box-seam"></i> Basic Information</h2>
                </div>
                <div class="cp-card-body">
                    <div class="cp-grid cp-grid-2">
                        <div>
                            <label class="cp-label">Product Name</label>
                            <input type="text" name="name" class="cp-input" value="{{ old('name', $product->name) }}"
                                required>
                        </div>
                        <div>
                            <label class="cp-label">Category</label>
                            <select name="category_id" class="cp-select" required>
                                @foreach ($categories as $cat)
                                    @if (!$cat->parent_id)
                                        <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id) == $cat->id)>{{ $cat->name }}
                                        </option>
                                        @foreach ($cat->children as $child)
                                            <option value="{{ $child->id }}" @selected(old('category_id', $product->category_id) == $child->id)>&nbsp;&nbsp;—
                                                {{ $child->name }}</option>
                                        @endforeach
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="cp-grid cp-grid-3" style="margin-top:1rem">
                        <div>
                            <label class="cp-label">Product Type</label>
                            <select name="type" class="cp-select" required>
                                @foreach ($types as $value => $label)
                                    <option value="{{ $value }}" @selected(old('type', $product->type) == $value)>{{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="cp-label">Gift For <span class="cp-optional">(optional)</span></label>
                            <select name="gift_for" class="cp-select">
                                <option value="">-- N/A --</option>
                                @foreach ($giftFor as $value => $label)
                                    <option value="{{ $value }}" @selected(old('gift_for', $product->gift_for) == $value)>{{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="cp-label">Quantity</label>
                            <input type="number" name="qty" class="cp-input" value="{{ old('qty', $product->qty) }}"
                                min="0" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cp-card">
                <div class="cp-card-header">
                    <h2><i class="bi bi-tag"></i> Pricing</h2>
                </div>
                <div class="cp-card-body">
                    <div class="cp-grid cp-grid-2">
                        <div>
                            <label class="cp-label">Price</label>
                            <input type="number" step="0.01" name="price" class="cp-input"
                                value="{{ old('price', $product->price) }}" required>
                        </div>
                        <div>
                            <label class="cp-label">Discount Price <span class="cp-optional">(optional)</span></label>
                            <input type="number" step="0.01" name="discount_price" class="cp-input"
                                value="{{ old('discount_price', $product->discount_price) }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="cp-card">
                <div class="cp-card-header">
                    <h2><i class="bi bi-card-text"></i> Description</h2>
                </div>
                <div class="cp-card-body">
                    <label class="cp-label">Short Description</label>
                    <input type="text" name="short_description" class="cp-input" maxlength="500"
                        value="{{ old('short_description', $product->short_description) }}">

                    <div style="margin-top:1rem">
                        <label class="cp-label">Full Description</label>
                        <div class="cp-editor-wrap">
                            <div id="editor" style="height:220px;background:#fff;">{!! old('description', $product->description) !!}</div>
                        </div>
                        <input type="hidden" name="description" id="description-input">
                    </div>
                </div>
            </div>

            <div class="cp-card">
                <div class="cp-card-header">
                    <h2> Images</h2>
                    {{-- <span class="cp-hint">Optional — add new images below if needed</span> --}}
                </div>
                <div class="cp-card-body">
                    @if ($product->colors->isNotEmpty())
                        <label class="cp-label">Existing Images</label>
                        @foreach ($product->colors as $color)
                            <div class="cp-existing-color">
                                <strong>{{ $color->color_name ?? 'Untitled group' }}</strong>
                                @if ($color->color_code)
                                    <span class="cp-swatch" style="background: {{ $color->color_code }}"></span>
                                @endif
                                <div style="display:flex; gap:.5rem; margin-top:.6rem; flex-wrap:wrap">
                                    @forelse ($color->images as $img)
                                        <img src="{{ $img->url }}" class="cp-thumb" style="width:56px;height:56px">
                                    @empty
                                        <span class="cp-subtitle" style="font-size:.8rem">No images uploaded</span>
                                    @endforelse
                                </div>
                            </div>
                        @endforeach
                        <div style="margin:1rem 0"></div>
                    @endif

                    <label class="cp-label">Add New Images <span class="cp-optional">(optional)</span></label>
                    <div id="colorRows"></div>
                    <button type="button" class="cp-btn cp-btn-outline cp-btn-sm" id="addColorRow">
                        <i class="bi bi-plus-lg"></i> Add Image
                    </button>
                </div>
            </div>

            <div class="cp-card">
                <div class="cp-card-header">
                    <h2><i class="bi bi-search"></i> SEO</h2>
                </div>
                <div class="cp-card-body">
                    <div class="cp-grid cp-grid-3">
                        <div>
                            <label class="cp-label">Meta Title</label>
                            <input type="text" name="meta_title" class="cp-input"
                                value="{{ old('meta_title', $product->meta_title) }}">
                        </div>
                        <div>
                            <label class="cp-label">Meta Keywords</label>
                            <input type="text" name="meta_keywords" class="cp-input"
                                value="{{ old('meta_keywords', $product->meta_keywords) }}">
                        </div>
                        <div>
                            <label class="cp-label">Meta Description</label>
                            <input type="text" name="meta_description" class="cp-input"
                                value="{{ old('meta_description', $product->meta_description) }}">
                        </div>
                    </div>

                    <div style="display:flex; gap:2rem; margin-top:1.25rem; flex-wrap:wrap">
                        <div class="cp-switch">
                            <input type="checkbox" name="status" id="status" value="1"
                                @checked(old('status', $product->status))>
                            <label for="status">Published</label>
                        </div>
                        <div class="cp-switch">
                            <input type="checkbox" name="is_featured" id="is_featured" value="1"
                                @checked(old('is_featured', $product->is_featured))>
                            <label for="is_featured">Featured</label>
                        </div>
                    </div>

                    <div class="cp-form-actions">
                        <a href="{{ route('dashboard.products.index') }}" class="cp-btn cp-btn-outline">Cancel</a>
                        <button type="submit" class="cp-btn cp-btn-primary">
                            <i class="bi bi-check2"></i> Update Product
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('backend_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.min.js"></script>
    <script>
        const quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{
                        header: [1, 2, 3, false]
                    }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{
                        list: 'ordered'
                    }, {
                        list: 'bullet'
                    }],
                    ['link'],
                    ['clean'],
                ],
            },
        });

        document.getElementById('productForm').addEventListener('submit', function() {
            document.getElementById('description-input').value = quill.root.innerHTML;
        });

        let colorIndex = 0;
        const colorRows = document.getElementById('colorRows');

        function addColorRow() {
            const i = colorIndex++;
            const row = document.createElement('div');
            row.className = 'cp-color-row';
            row.innerHTML = `
            <button type="button" class="cp-btn cp-btn-danger cp-btn-icon remove-color" title="Remove">
                <i class="bi bi-x-lg"></i>
            </button>
            <div class="cp-grid" style="grid-template-columns:2fr 1fr 3fr;">
                <div>
                    <label class="cp-label">Image Name <span class="cp-optional">(optional)</span></label>
                    <input type="text" name="colors[${i}][image_name]" class="cp-input" placeholder="e.g. Front View">
                </div>
                
                <div>
                    <label class="cp-label">Images</label>
                    <input type="file" name="colors[${i}][images][]" class="cp-input" accept="image/*" multiple>
                </div>
            </div>`;
            row.querySelector('.remove-color').addEventListener('click', () => row.remove());
            colorRows.appendChild(row);
        }

        document.getElementById('addColorRow').addEventListener('click', addColorRow);
    </script>
@endpush
