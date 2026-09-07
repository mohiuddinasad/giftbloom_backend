<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Backend\Order\Order;
use App\Models\Backend\Products\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // ---- top stat cards ----
        $totalOrders = Order::count(); 
        $totalRevenue = Order::whereNotIn('status', ['cancelled'])->sum('total');
        $totalProducts = Product::count();
        $totalCustomers = Order::whereNotNull('phone')->distinct('phone')->count('phone');

        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();
        $shippedOrders = Order::where('status', 'shipped')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();

        // ---- revenue comparisons ----
        $todayRevenue = Order::whereNotIn('status', ['cancelled'])
            ->whereDate('created_at', today())
            ->sum('total');

        $monthRevenue = Order::whereNotIn('status', ['cancelled'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');

        $lastMonthRevenue = Order::whereNotIn('status', ['cancelled'])
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total');

        $revenueGrowth = $lastMonthRevenue > 0
            ? round((($monthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : ($monthRevenue > 0 ? 100 : 0);

        $todayOrders = Order::whereDate('created_at', today())->count();

        // ---- order status breakdown (donut chart) ----
        $orderStatusCounts = Order::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $statusChart = collect(Order::STATUSES)->mapWithKeys(
            fn ($status) => [$status => (int) $orderStatusCounts->get($status, 0)]
        );

        // ---- last 7 days orders trend (count) ----
        $ordersTrend = collect(range(6, 0))->map(function ($daysAgo) {
            $date = now()->subDays($daysAgo);

            return [
                'label' => $date->format('M d'),
                'count' => Order::whereDate('created_at', $date->toDateString())->count(),
            ];
        })->values();

        // ---- recent orders ----
        $recentOrders = Order::latest()->take(8)->get();

        // ---- low stock products ----
        $lowStockProducts = Product::where('qty', '<=', 5)
            ->orderBy('qty')
            ->take(6)
            ->get();

        // ---- best selling products ----
        $topProducts = Product::query()
            ->select('products.*')
            ->selectSub(function ($query) {
                $query->from('order_items')
                    ->selectRaw('COALESCE(SUM(order_items.qty), 0)')
                    ->whereColumn('order_items.product_id', 'products.id');
            }, 'sold_qty')
            ->orderByDesc('sold_qty')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalOrders', 'totalRevenue', 'totalProducts', 'totalCustomers',
            'pendingOrders', 'processingOrders', 'shippedOrders', 'deliveredOrders',
            'todayRevenue', 'monthRevenue', 'revenueGrowth', 'todayOrders',
            'statusChart', 'ordersTrend', 'recentOrders',
            'lowStockProducts', 'topProducts',
        ));
    }
}
