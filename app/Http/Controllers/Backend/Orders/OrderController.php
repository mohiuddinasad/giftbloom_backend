<?php

namespace App\Http\Controllers\Backend\Orders;

use App\Http\Controllers\Controller;
use App\Models\Backend\Order\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::withCount('items')
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->search, function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($q) use ($search) {
                    $q->where('order_code', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        if ($request->ajax()) {
            $sections = view('backend.orders.orderList', compact('orders'))->renderSections();

            return $sections['orders_table'];
        }

        return view('backend.orders.orderList', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items');

        return view('backend.orders.orderShow', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Order status updated.');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return back()->with('success', 'Order deleted.');
    }
}
