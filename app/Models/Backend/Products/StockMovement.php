<?php

namespace App\Models\Backend\Products;

use App\Models\Backend\Products\Product;
use App\Models\User;
use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    public const IN = 'in';

    public const OUT = 'out';

    protected $fillable = [
        'product_id', 'user_id', 'type', 'quantity', 'balance_after', 'reason', 'note',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Record a stock in/out movement and atomically update the product's qty.
     * Use this instead of touching product->qty directly anywhere in the app.
     */
    public static function record(Product $product, string $type, int $quantity, ?string $reason = null, ?string $note = null, ?int $userId = null): self
    {
        if ($quantity <= 0) {
            throw new RuntimeException('Stock movement quantity must be a positive number.');
        }

        return DB::transaction(function () use ($product, $type, $quantity, $reason, $note, $userId) {
            // lock the row so concurrent stock updates can't race each other
            $product = Product::whereKey($product->id)->lockForUpdate()->first();

            if ($type === self::OUT && $product->qty < $quantity) {
                throw new RuntimeException("Not enough stock. Available: {$product->qty}, requested: {$quantity}.");
            }

            $product->qty = $type === self::IN
                ? $product->qty + $quantity
                : $product->qty - $quantity;
            $product->save();

            return self::create([
                'product_id' => $product->id,
                'user_id' => $userId ?? auth()->id(),
                'type' => $type,
                'quantity' => $quantity,
                'balance_after' => $product->qty,
                'reason' => $reason,
                'note' => $note,
            ]);
        });
    }
}
