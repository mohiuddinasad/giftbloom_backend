@extends('backend.layout')

@section('backend_title', 'Dashboard')

@push('backend_css')
    <style>
        :root {
            --bg-soft: #f6f8fb;
            --border-soft: #edf1f7;
            --text-muted-soft: #8a94a6;
        }


        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 1px 2px rgba(16, 24, 40, .04), 0 1px 3px rgba(16, 24, 40, .03);
        }

        /* ---- Stat cards ---- */
        .stat-card {
            position: relative;
            overflow: hidden;
            border-radius: 16px;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(16, 24, 40, .08);
        }

        .stat-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: #fff;
        }

        .stat-icon.grad-primary {
            background: linear-gradient(135deg, #6a8dff, #3b5bfd);
        }

        .stat-icon.grad-success {
            background: linear-gradient(135deg, #4ade80, #16a34a);
        }

        .stat-icon.grad-warning {
            background: linear-gradient(135deg, #fbbf24, #d97706);
        }

        .stat-icon.grad-info {
            background: linear-gradient(135deg, #38bdf8, #0284c7);
        }

        .stat-label {
            font-size: .78rem;
            color: var(--text-muted-soft);
            font-weight: 500;
            letter-spacing: .01em;
        }

        .stat-value {
            font-size: 1.55rem;
            font-weight: 700;
            color: #1e2433;
            letter-spacing: -.02em;
        }

        .trend-up {
            color: #16a34a;
        }

        .trend-down {
            color: #dc2626;
        }

        /* ---- Mini status cards ---- */
        .mini-stat {
            border-radius: 14px;
            padding: 1rem 1.1rem;
            border-left: 3px solid transparent;
        }

        .mini-stat .mini-value {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1e2433;
        }

        .mini-stat .mini-label {
            font-size: .75rem;
            color: var(--text-muted-soft);
            font-weight: 500;
        }

        .mini-stat.b-pending {
            border-left-color: #f59e0b;
        }

        .mini-stat.b-processing {
            border-left-color: #0ea5e9;
        }

        .mini-stat.b-shipped {
            border-left-color: #6366f1;
        }

        .mini-stat.b-delivered {
            border-left-color: #22c55e;
        }

        /* ---- Section headers ---- */
        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border-soft);
            padding: 1.1rem 1.25rem .9rem;
        }

        .card-header h6 {
            font-weight: 700;
            color: #1e2433;
            letter-spacing: -.01em;
            font-size: .92rem;
        }

        .link-soft {
            font-size: .78rem;
            color: #3b5bfd;
            font-weight: 600;
            text-decoration: none;
        }

        .link-soft:hover {
            text-decoration: underline;
        }

        /* ---- Table ---- */
        .table thead th {
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--text-muted-soft);
            border-bottom: 1px solid var(--border-soft);
            font-weight: 700;
            padding: .85rem 1.25rem;
            background: #fafbfd;
        }

        .table td {
            padding: .85rem 1.25rem;
            font-size: .87rem;
            border-bottom: 1px solid var(--border-soft);
            vertical-align: middle;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table tbody tr {
            transition: background .15s ease;
        }

        .table tbody tr:hover {
            background: #fafbfd;
        }

        .order-id-link {
            color: #1e2433;
            font-weight: 600;
            text-decoration: none;
        }

        .order-id-link:hover {
            color: #3b5bfd;
        }

        .status-pill {
            font-size: .72rem;
            font-weight: 600;
            padding: .32rem .7rem;
            border-radius: 50px;
            text-transform: capitalize;
            display: inline-block;
        }

        /* ---- List groups (low stock / top products) ---- */
        .list-group-item {
            border-color: var(--border-soft);
            padding: .8rem 1.25rem;
            font-size: .85rem;
        }

        .list-group-item:first-child {
            border-top: none;
        }

        .product-name {
            color: #1e2433;
            font-weight: 500;
        }

        .qty-badge {
            font-size: .7rem;
            font-weight: 700;
            padding: .3rem .55rem;
            border-radius: 8px;
        }

        /* ---- Empty states ---- */
        .empty-state {
            text-align: center;
            color: var(--text-muted-soft);
            font-size: .85rem;
            padding: 2.2rem 1rem;
        }

        .empty-state i {
            font-size: 1.6rem;
            display: block;
            margin-bottom: .5rem;
            opacity: .4;
        }

        .page-heading {
            font-weight: 700;
            letter-spacing: -.02em;
            color: #1e2433;
        }

        .page-sub {
            color: var(--text-muted-soft);
            font-size: .87rem;
        }

        .date-pill {
            background: #fff;
            border: 1px solid var(--border-soft);
            border-radius: 50px;
            padding: .45rem 1rem;
            font-size: .8rem;
            color: var(--text-muted-soft);
            font-weight: 500;
        }
    </style>
@endpush

@section('backend_content')
    <div class="dash-wrap py-4 px-md-2">
        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                    <h4 class="page-heading mb-1">Dashboard</h4>
                    <div class="page-sub">Welcome back — here's what's happening today.</div>
                </div>
                <span class="date-pill"><i class="fa fa-calendar me-1"></i> {{ now()->format('l, d M Y') }}</span>
            </div>

            {{-- ===== Stat Cards ===== --}}
            <div class="row g-3 mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card h-100">
                        <div class="card-body d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-label mb-2">Total Revenue</div>
                                <div class="stat-value mb-2">৳{{ number_format($totalRevenue, 2) }}</div>
                                @if ($revenueGrowth >= 0)
                                    <span class="trend-up small fw-semibold"><i
                                            class="fa fa-arrow-up me-1"></i>{{ $revenueGrowth }}%</span>
                                @else
                                    <span class="trend-down small fw-semibold"><i
                                            class="fa fa-arrow-down me-1"></i>{{ abs($revenueGrowth) }}%</span>
                                @endif
                                <span class="text-muted small ms-1">vs last month</span>
                            </div>
                            <div class="stat-icon grad-primary"><i class="fa fa-dollar-sign"></i></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card h-100">
                        <div class="card-body d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-label mb-2">Total Orders</div>
                                <div class="stat-value mb-2">{{ number_format($totalOrders) }}</div>
                                <span class="text-muted small">{{ $todayOrders }} placed today</span>
                            </div>
                            <div class="stat-icon grad-success"><i class="fa fa-shopping-cart"></i></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card h-100">
                        <div class="card-body d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-label mb-2">Total Products</div>
                                <div class="stat-value mb-2">{{ number_format($totalProducts) }}</div>
                                <span class="text-muted small">{{ $lowStockProducts->count() }} running low</span>
                            </div>
                            <div class="stat-icon grad-warning"><i class="fa fa-box"></i></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card h-100">
                        <div class="card-body d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-label mb-2">Total Customers</div>
                                <div class="stat-value mb-2">{{ number_format($totalCustomers) }}</div>
                                <span class="text-muted small">unique phone numbers</span>
                            </div>
                            <div class="stat-icon grad-info"><i class="fa fa-users"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== Order Status Row ===== --}}
            <div class="row g-3 mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card mini-stat b-pending h-100">
                        <div class="mini-value">{{ number_format($pendingOrders) }}</div>
                        <div class="mini-label">Pending</div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card mini-stat b-processing h-100">
                        <div class="mini-value">{{ number_format($processingOrders) }}</div>
                        <div class="mini-label">Processing</div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card mini-stat b-shipped h-100">
                        <div class="mini-value">{{ number_format($shippedOrders) }}</div>
                        <div class="mini-label">Shipped</div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card mini-stat b-delivered h-100">
                        <div class="mini-value">{{ number_format($deliveredOrders) }}</div>
                        <div class="mini-label">Delivered</div>
                    </div>
                </div>
            </div>

            {{-- ===== Charts Row ===== --}}
            <div class="row g-3 mb-4">
                <div class="col-xl-8">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Orders — Last 7 Days</h6>
                            <span class="text-muted small">{{ $ordersTrend->sum('count') }} total</span>
                        </div>
                        <div class="card-body">
                            <canvas id="ordersTrendChart" height="85"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h6 class="mb-0">Order Status</h6>
                        </div>
                        <div class="card-body d-flex flex-column align-items-center justify-content-center">
                            <canvas id="orderStatusChart" height="190"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== Tables Row ===== --}}
            <div class="row g-3">
                <div class="col-xl-7">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Recent Orders</h6>
                            <a href="{{ route('dashboard.orders.index') }}" class="link-soft">View all →</a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Order</th>
                                            <th>Status</th>
                                            <th class="text-end">Total</th>
                                            <th class="text-end">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentOrders as $order)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('dashboard.orders.show', $order) }}"
                                                        class="order-id-link">
                                                        #{{ $order->id }}
                                                    </a>
                                                </td>
                                                <td>
                                                    @php
                                                        $statusColors = [
                                                            'pending' => 'warning',
                                                            'processing' => 'info',
                                                            'shipped' => 'primary',
                                                            'delivered' => 'success',
                                                            'cancelled' => 'danger',
                                                        ];
                                                        $color = $statusColors[$order->status] ?? 'secondary';
                                                    @endphp
                                                    <span
                                                        class="status-pill bg-{{ $color }} bg-opacity-10 text-white">
                                                        {{ $order->status }}
                                                    </span>
                                                </td>
                                                <td class="text-end fw-semibold">৳{{ number_format($order->total, 2) }}
                                                </td>
                                                <td class="text-end text-muted small">
                                                    {{ $order->created_at->format('d M, h:i A') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4">
                                                    <div class="empty-state">
                                                        <i class="fa fa-inbox"></i>
                                                        No orders yet.
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-5">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Low Stock Products</h6>
                                </div>
                                <ul class="list-group list-group-flush">
                                    @forelse($lowStockProducts as $product)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span class="product-name text-truncate"
                                                style="max-width: 65%;">{{ $product->name }}</span>
                                            <span class="qty-badge bg-danger bg-opacity-10 text-white">{{ $product->qty }}
                                                left</span>
                                        </li>
                                    @empty
                                        <li class="list-group-item">
                                            <div class="empty-state py-2">
                                                <i class="fa fa-check-circle"></i>
                                                All stock levels healthy.
                                            </div>
                                        </li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Best Selling Products</h6>
                                </div>
                                <ul class="list-group list-group-flush">
                                    @forelse($topProducts as $product)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span class="product-name text-truncate"
                                                style="max-width: 65%;">{{ $product->name }}</span>
                                            <span
                                                class="qty-badge bg-success bg-opacity-10 text-white">{{ (int) $product->sold_qty }}
                                                sold</span>
                                        </li>
                                    @empty
                                        <li class="list-group-item">
                                            <div class="empty-state py-2">
                                                <i class="fa fa-chart-line"></i>
                                                No sales data yet.
                                            </div>
                                        </li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('backend_js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        Chart.defaults.font.family = "'Inter', 'Segoe UI', sans-serif";

        // Orders trend (line chart)
        const trendLabels = @json($ordersTrend->pluck('label'));
        const trendData = @json($ordersTrend->pluck('count'));

        const trendCtx = document.getElementById('ordersTrendChart').getContext('2d');
        const trendGradient = trendCtx.createLinearGradient(0, 0, 0, 260);
        trendGradient.addColorStop(0, 'rgba(59,91,253,0.25)');
        trendGradient.addColorStop(1, 'rgba(59,91,253,0)');

        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: trendLabels,
                datasets: [{
                    label: 'Orders',
                    data: trendData,
                    borderColor: '#3b5bfd',
                    backgroundColor: trendGradient,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#3b5bfd',
                    pointBorderWidth: 2,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1e2433',
                        padding: 10,
                        cornerRadius: 8,
                        titleFont: {
                            size: 12
                        },
                        bodyFont: {
                            size: 12
                        },
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            color: '#8a94a6',
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            color: '#f0f2f7'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#8a94a6',
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });

        // Order status (donut chart)
        const statusLabels = @json($statusChart->keys());
        const statusData = @json($statusChart->values());

        new Chart(document.getElementById('orderStatusChart'), {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusData,
                    backgroundColor: ['#f59e0b', '#0ea5e9', '#6366f1', '#22c55e', '#ef4444'],
                    borderWidth: 3,
                    borderColor: '#fff',
                    hoverOffset: 6,
                }]
            },
            options: {
                cutout: '72%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 8,
                            boxHeight: 8,
                            padding: 14,
                            font: {
                                size: 11
                            },
                            color: '#4b5468',
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1e2433',
                        padding: 10,
                        cornerRadius: 8,
                    }
                }
            }
        });
    </script>
@endpush
