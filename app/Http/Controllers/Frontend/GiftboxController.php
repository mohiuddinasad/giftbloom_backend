<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Backend\Products\Product;
use Illuminate\Http\Request;

class GiftboxController extends Controller
{
    /**
     * Build the custom gift-box page, feeding it real products grouped
     * by the "type" values you already added on the products table.
     */
    public function index()
    {
        $with = ['colors.images'];

        $boxes = Product::active()->type(Product::TYPE_GIFT_BOX)->with($with)->get();
        $wrapings = Product::active()->type(Product::TYPE_WRAPING)->with($with)->get();
        $giftsForHer = Product::active()->type(Product::TYPE_GIFT_ITEM)->giftFor(Product::GIFT_FOR_WOMEN)->with($with)->get();
        $giftsForHim = Product::active()->type(Product::TYPE_GIFT_ITEM)->giftFor(Product::GIFT_FOR_MAN)->with($with)->get();
        $extras = Product::active()->type(Product::TYPE_EXTAR_GIFT)->with($with)->get();
        $sweets = Product::active()->type(Product::TYPE_SWEET)->with($with)->get();
        $cards = Product::active()->type(Product::TYPE_LETTER)->with($with)->get();

        return view('frontend.giftBox', compact(
            'boxes', 'wrapings', 'giftsForHer', 'giftsForHim', 'extras', 'sweets', 'cards'
        ));
    }

    /**
     * Final "Checkout" step of the gift-box builder.
     * Pushes every chosen item into the same session cart used by
     * CartController, stores the recipient/letter text, and hands
     * back the normal checkout URL to redirect to.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.color_id' => 'nullable|integer|exists:product_colors,id',
            'items.*.qty' => 'required|integer|min:1',
            'recipient_name' => 'nullable|string|max:255',
            'card_message' => 'nullable|string|max:2000',
        ]);

        // Make sure at least 3 real gift items were chosen (mirrors the
        // frontend guard, but the backend must not trust the frontend).
        $giftItemQty = 0;
        foreach ($validated['items'] as $row) {
            $product = Product::find($row['product_id']);
            if ($product && $product->type === Product::TYPE_GIFT_ITEM) {
                $giftItemQty += $row['qty'];
            }
        }

        if ($giftItemQty < 3) {
            return response()->json([
                'success' => false,
                'message' => 'Please choose at least 3 gift items before checking out.',
            ], 422);
        }

        $cart = session()->get('cart', []);

        foreach ($validated['items'] as $row) {
            $product = Product::with('colors.images')->find($row['product_id']);
            if (! $product) {
                continue;
            }

            $color = $row['color_id'] ?? null
                ? $product->colors->firstWhere('id', $row['color_id'])
                : $product->colors->first();

            $cartKey = $product->id.'_'.($color->id ?? 0);

            if (isset($cart[$cartKey])) {
                $cart[$cartKey]['qty'] += $row['qty'];
            } else {
                $image = $color?->images->first()?->image_path;

                $cart[$cartKey] = [
                    'product_id' => $product->id,
                    'color_id' => $color->id ?? null,
                    'name' => $product->name,
                    'price' => $product->price,
                    'qty' => $row['qty'],
                    'color_name' => $color?->color_name,
                    'image' => $image ? asset($image) : null,
                ];
            }
        }

        session()->put('cart', $cart);

        // Store the recipient name + letter/card message so the
        // checkout page (or order creation) can attach it to the order.
        session()->put('gift_box_letter', [
            'recipient_name' => $validated['recipient_name'] ?? null,
            'message' => $validated['card_message'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'redirect' => route('frontend.checkout'),
        ]);
    }
}
