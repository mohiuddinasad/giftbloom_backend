<?php

namespace App\Models\Backend\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'parent_id', 'name', 'slug', 'description', 'image',
        'meta_title', 'meta_description', 'meta_keywords',
        'status', 'sort_order',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public static function boot()
    {
        parent::boot();

        static::saving(function (Category $category) {
            if (empty($category->slug)) {
                $category->slug = static::generateUniqueSlug($category->name, $category->id);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public static function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;

        $query = fn ($s) => static::where('slug', $s)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists();

        while ($query($slug)) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    // ---- relationships ----

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // ---- scopes ----

    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    // ---- accessors ----

    // image column stores a path relative to /public (e.g. "uploads/categories/xxx.jpg")
    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset($this->image) : null;
    }

    /**
     * Delete this category AND everything under it:
     * - all sub-categories (recursively)
     * - all products in this category and its sub-categories
     *   (each product's own images/colors are cleaned up too, see Product::deleteWithFiles())
     * - this category's own image file
     * - the category row itself
     */
    public function deleteWithChildrenAndProducts(): void
    {
        foreach ($this->children as $child) {
            $child->deleteWithChildrenAndProducts();
        }

        foreach ($this->products as $product) {
            $product->deleteWithFiles();
        }

        if ($this->image) {
            $path = public_path($this->image);
            if (file_exists($path)) {
                @unlink($path);
            }
        }

        $this->delete();
    }
}
