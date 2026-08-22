<?php

namespace App\Models\Backend\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    // product types
    public const TYPE_GIFT_ITEM = 'gift_item';
    public const TYPE_GIFT_PACKAGE = 'gift_package';
    public const TYPE_LETTER = 'letter';

    public const TYPES = [
        self::TYPE_GIFT_ITEM => 'Gift Item',
        self::TYPE_GIFT_PACKAGE => 'Gift Package',
        self::TYPE_LETTER => 'Letter',
    ];

    // gift for
    public const GIFT_FOR_MAN = 'man';
    public const GIFT_FOR_WOMEN = 'women';

    public const GIFT_FOR = [
        self::GIFT_FOR_MAN => 'Man',
        self::GIFT_FOR_WOMEN => 'Women',
    ];

    protected $fillable = [
        'category_id', 'name', 'slug', 'sku', 'type', 'gift_for',
        'price', 'discount_price', 'description', 'short_description',
        'qty', 'meta_title', 'meta_description', 'meta_keywords',
        'status', 'is_featured',
    ];

    protected $casts = [
        'status' => 'boolean',
        'is_featured' => 'boolean',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
    ];

    public static function boot()
    {
        parent::boot();

        static::saving(function (Product $product) {
            if (empty($product->slug)) {
                $product->slug = static::generateUniqueSlug($product->name, $product->id);
            }
            if (empty($product->sku)) {
                $product->sku = static::generateSku();
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

    public static function generateSku(): string
    {
        do {
            $sku = 'PRD-'.strtoupper(Str::random(8));
        } while (static::where('sku', $sku)->exists());

        return $sku;
    }

    // ---- relationships ----

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function colors()
    {
        return $this->hasMany(ProductColor::class);
    }

    public function images()
    {
        return $this->hasManyThrough(ProductImage::class, ProductColor::class);
    }

    // ---- scopes ----

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeGiftFor($query, string $giftFor)
    {
        return $query->where('gift_for', $giftFor);
    }

    // ---- accessors ----

    public function getInStockAttribute(): bool
    {
        return $this->qty > 0;
    }

    public function getFinalPriceAttribute()
    {
        return $this->discount_price ?? $this->price;
    }

    /**
     * Delete this product along with every uploaded image file that
     * belongs to its colors (colors/images rows themselves cascade via
     * the DB foreign keys, but the actual files on disk need to be
     * removed manually since they live in /public, not a Storage disk).
     */
    public function deleteWithFiles(): void
    {
        foreach ($this->colors as $color) {
            foreach ($color->images as $image) {
                $path = public_path($image->image_path);
                if (file_exists($path)) {
                    @unlink($path);
                }
            }
        }

        $this->delete();
    }
}
