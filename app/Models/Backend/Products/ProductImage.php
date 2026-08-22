<?php

namespace App\Models\Backend\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_color_id', 'image_path', 'is_primary', 'sort_order',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function color()
    {
        return $this->belongsTo(ProductColor::class, 'product_color_id');
    }

    // image_path stores a path relative to /public (e.g. "uploads/products/12/xxx.jpg")
    public function getUrlAttribute(): string
    {
        return asset($this->image_path);
    }
}
