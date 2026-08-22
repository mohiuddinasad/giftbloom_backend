<?php

namespace App\Models\Backend\Order;

use App\Models\Backend\Products\Product;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'product_id', 'color_id', 'name', 'price', 'qty', 'image',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
