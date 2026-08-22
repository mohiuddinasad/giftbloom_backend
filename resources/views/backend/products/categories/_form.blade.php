@csrf
@if (isset($category))
    @method('PUT')
@endif

<div class="cp-card-body">
    <div class="cp-grid cp-grid-2">
        <div>
            <label class="cp-label">Category Name</label>
            <input type="text" name="name" class="cp-input @error('name') is-invalid @enderror"
                   value="{{ old('name', $category->name ?? '') }}" placeholder="e.g. Gift Boxes" required>
            @error('name') <div class="cp-invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div>
            <label class="cp-label">Parent Category <span class="cp-optional">(leave empty for top-level)</span></label>
            <select name="parent_id" class="cp-select @error('parent_id') is-invalid @enderror">
                <option value="">-- None (top level) --</option>
                @foreach ($parentCategories as $parent)
                    <option value="{{ $parent->id }}" @selected(old('parent_id', $category->parent_id ?? '') == $parent->id)>
                        {{ $parent->name }}
                    </option>
                @endforeach
            </select>
            @error('parent_id') <div class="cp-invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="cp-grid cp-grid-2" style="margin-top:1rem">
        <div>
            <label class="cp-label">Image</label>
            <input type="file" name="image" class="cp-input @error('image') is-invalid @enderror" accept="image/*">
            @error('image') <div class="cp-invalid-feedback">{{ $message }}</div> @enderror
            @isset($category)
                @if ($category->image)
                    <img src="{{ $category->image_url }}" class="cp-thumb" style="width:64px;height:64px;margin-top:.6rem">
                @endif
            @endisset
        </div>

        <div>
            <label class="cp-label">Sort Order</label>
            <input type="number" name="sort_order" class="cp-input" value="{{ old('sort_order', $category->sort_order ?? 0) }}">
            <div class="cp-help">Lower numbers show first in the category list.</div>
        </div>
    </div>

    <div style="margin-top:1rem">
        <label class="cp-label">Description</label>
        <textarea name="description" rows="3" class="cp-input">{{ old('description', $category->description ?? '') }}</textarea>
    </div>
</div>

<div class="cp-card-header">
    <h2><i class="bi bi-search"></i> SEO</h2>
    <span class="cp-hint">Helps this category show up in search results</span>
</div>
<div class="cp-card-body">
    <div class="cp-grid cp-grid-3">
        <div>
            <label class="cp-label">Meta Title</label>
            <input type="text" name="meta_title" class="cp-input" value="{{ old('meta_title', $category->meta_title ?? '') }}">
        </div>
        <div>
            <label class="cp-label">Meta Keywords</label>
            <input type="text" name="meta_keywords" class="cp-input" value="{{ old('meta_keywords', $category->meta_keywords ?? '') }}">
        </div>
        <div>
            <label class="cp-label">Meta Description</label>
            <input type="text" name="meta_description" class="cp-input" value="{{ old('meta_description', $category->meta_description ?? '') }}">
        </div>
    </div>

    <div style="margin-top:1.25rem">
        <div class="cp-switch">
            <input type="checkbox" name="status" id="status" value="1" @checked(old('status', $category->status ?? true))>
            <label for="status">Active</label>
        </div>
    </div>

    <div class="cp-form-actions">
        <a href="{{ route('dashboard.categories.index') }}" class="cp-btn cp-btn-outline">Cancel</a>
        <button type="submit" class="cp-btn cp-btn-primary">
            <i class="bi bi-check2"></i> {{ isset($category) ? 'Update' : 'Create' }} Category
        </button>
    </div>
</div>
