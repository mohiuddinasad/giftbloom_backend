<?php

namespace App\Models\Backend\Products;

use App\Models\Backend\Products\Product;
use App\Models\Backend\Products\ProductImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductColor extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'color_name', 'color_code', 'qty', 'sort_order',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // images that belong to this specific color (e.g. Red -> red shirt photos)
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }
}

