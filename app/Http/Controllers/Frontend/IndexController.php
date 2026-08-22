<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Backend\Order\Order;
use App\Models\Backend\Products\Category;
use App\Models\Backend\Products\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function index()
    {
        $categories = Category::with('parent')
            ->withCount('products')
            ->parents()
            ->get();
        $giftPakageProducts = Product::where('type', 'gift_package')
            ->where('status', true)
            ->with(['colors.images'])
            ->latest()
            ->take(8)
            ->get();
        $giftItemProducts = Product::where('type', 'gift_item')
            ->where('status', true)
            ->with(['colors.images'])
            ->latest()
            ->take(8)
            ->get();

        return view('welcome', compact('giftPakageProducts', 'giftItemProducts', 'categories'));
    }
    //   oreder part

    public function checkout()
    {
        return view('frontend.checkout.checkout');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'note' => 'nullable|string',
            'shipping_method' => 'required|in:inside,near,outside',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return response()->json(['success' => false, 'message' => 'Your cart is empty.'], 422);
        }

        $shippingCosts = ['inside' => 70, 'near' => 120, 'outside' => 150];
        $shippingCost = $shippingCosts[$validated['shipping_method']];
        $subtotal = collect($cart)->sum(fn ($item) => $item['price'] * $item['qty']);
        $total = $subtotal + $shippingCost;

        $order = DB::transaction(function () use ($validated, $cart, $shippingCost, $subtotal, $total) {
            $order = Order::create([
                'order_code' => Order::generateOrderCode(),
                'customer_name' => $validated['full_name'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'area' => $validated['area'],
                'city' => $validated['city'],
                'note' => $validated['note'] ?? null,
                'shipping_method' => $validated['shipping_method'],
                'shipping_cost' => $shippingCost,
                'subtotal' => $subtotal,
                'total' => $total,
                'payment_method' => 'cod',
                'status' => 'pending',
            ]);

            foreach ($cart as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'] ?? null,
                    'color_id' => $item['color_id'] ?? null,
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'qty' => $item['qty'],
                    'image' => $item['image'] ?? null,
                ]);
            }

            return $order;
        });

        session()->forget('cart');

        return response()->json([
            'success' => true,
            'order_code' => $order->order_code,
            'redirect_url' => route('frontend.order.success', $order->order_code),
        ]);
    }

    public function success($order_code)
    {
        $order = Order::with('items')->where('order_code', $order_code)->firstOrFail();

        return view('frontend.checkout.success', compact('order'));
    }

    public function search(Request $request)
    {
        $query = trim($request->get('query', ''));

        if (strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        $products = Product::where('status', true)
            ->where('name', 'like', '%'.$query.'%')
            ->with(['colors.images'])
            ->take(10)
            ->get();

        $results = $products->map(function ($product) {
            $firstColor = $product->colors->first();
            $image = optional(optional($firstColor)->images->first())->image_path;

            return [
                'name' => $product->name,
                'price' => $product->price,
                'image' => $image ? asset($image) : asset('frontend/asset/image/placeholder.png'),
                'url' => route('frontend.product.details', $product->slug),
            ];
        });

        return response()->json(['results' => $results]);
    }
}
