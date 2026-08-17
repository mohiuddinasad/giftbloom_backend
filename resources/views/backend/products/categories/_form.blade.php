@csrf
@if (isset($category))
    @method('PUT')
@endif

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Category Name</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $category->name ?? '') }}" required>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Parent Category <small class="text-muted">(leave empty to make this a top-level category)</small></label>
        <select name="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
            <option value="">-- None (top level) --</option>
            @foreach ($parentCategories as $parent)
                <option value="{{ $parent->id }}" @selected(old('parent_id', $category->parent_id ?? '') == $parent->id)>
                    {{ $parent->name }}
                </option>
            @endforeach
        </select>
        @error('parent_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Image</label>
        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
        @isset($category)
            @if ($category->image)
                <img src="{{ $category->image_url }}" width="60" class="mt-2 rounded">
            @endif
        @endisset
        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Sort Order</label>
        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $category->sort_order ?? 0) }}">
    </div>

    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" rows="3" class="form-control">{{ old('description', $category->description ?? '') }}</textarea>
    </div>

    <div class="col-12"><hr><h6>SEO</h6></div>

    <div class="col-md-4">
        <label class="form-label">Meta Title</label>
        <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $category->meta_title ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Meta Keywords</label>
        <input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords', $category->meta_keywords ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Meta Description</label>
        <input type="text" name="meta_description" class="form-control" value="{{ old('meta_description', $category->meta_description ?? '') }}">
    </div>

    <div class="col-12">
        <div class="form-check form-switch">
            <input type="checkbox" name="status" value="1" class="form-check-input"
                   @checked(old('status', $category->status ?? true))>
            <label class="form-check-label">Active</label>
        </div>
    </div>

    <div class="col-12">
        <button class="btn btn-primary">{{ isset($category) ? 'Update' : 'Create' }} Category</button>
    </div>
</div>
