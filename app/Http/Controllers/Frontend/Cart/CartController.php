<?php

namespace App\Http\Controllers\Frontend\Cart;

use App\Http\Controllers\Controller;
use App\Models\Backend\Products\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $product = Product::with('colors.images')->findOrFail($request->product_id);

        $color = $request->color_id
            ? $product->colors->firstWhere('id', $request->color_id)
            : $product->colors->first();

        $cartKey = $request->product_id.'_'.($color->id ?? 0);
        $cart = session()->get('cart', []);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['qty'] += $request->qty ?? 1;
        } else {
            $cart[$cartKey] = [
                'product_id' => $product->id,
                'color_id' => $color->id ?? null,
                'name' => $product->name,
                'price' => $product->price,
                'qty' => $request->qty ?? 1,
                'color_name' => $color?->color_name,
                'image' => $color?->images->first()?->image_path
                    ? asset($color->images->first()->image_path)
                    : null,
            ];
        }

        session()->put('cart', $cart);

        return $this->cartResponse($cart);
    }

    public function removeCart(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return $this->cartResponse($cart);
    }

    public function updateCart(Request $request)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$request->cart_key])) {
            $cart[$request->cart_key]['qty'] = max(1, (int) $request->quantity);
            session()->put('cart', $cart);
        }

        return $this->cartResponse($cart);
    }

    private function cartResponse(array $cart)
    {
        $total = collect($cart)->sum(fn ($item) => $item['price'] * $item['qty']);
        $qty = array_sum(array_column($cart, 'qty'));

        return response()->json([
            'success' => true,
            'items' => $cart,
            'total' => number_format($total, 2),
            'cart_count' => $qty,
        ]);
    }
}
