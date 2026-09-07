<?php

namespace App\Models\Backend\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code', 'customer_name', 'phone', 'address', 'area', 'city',
        'note', 'shipping_method', 'shipping_cost', 'subtotal', 'total',
        'payment_method', 'status', 'gift_recipient_name', 'gift_message',
    ];

    public const STATUSES = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function generateOrderCode(): string
    {
        do {
            $code = 'ORD-'.now()->format('ymd').'-'.strtoupper(Str::random(4));
        } while (self::where('order_code', $code)->exists());

        return $code;
    }
}
