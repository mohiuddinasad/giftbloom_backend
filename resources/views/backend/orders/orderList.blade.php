@extends('backend.layout')

@section('backend_title', 'Order List')

@section('backend_content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Orders</h4>
        <span class="text-muted">Total: {{ $orders->total() }}</span>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center gap-2">

            <div class="position-relative" style="width:260px;">
                <i class="fa-solid fa-magnifying-glass position-absolute top-50 translate-middle-y ms-2 text-muted"></i>
                <input type="text" id="orderSearch" class="form-control form-control-sm ps-4"
                       placeholder="Search by code, name, phone..."
                       value="{{ request('search') }}">
            </div>

            <select name="status" id="statusFilter" class="form-select form-select-sm" style="width:170px;">
                <option value="">All Statuses</option>
                @foreach (\App\Models\Backend\Order\Order::STATUSES as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div id="ordersTableWrapper">
            @section('orders_table')
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Order Code</th>
                                <th>Customer</th>
                                <th>Phone</th>
                                <th class="text-center">Items</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                <tr>
                                    <td class="fw-semibold">{{ $order->order_code }}</td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td>{{ $order->phone }}</td>
                                    <td class="text-center">{{ $order->items_count }}</td>
                                    <td>Tk {{ number_format($order->total, 2) }}</td>
                                    <td>
                                        @php
                                            $badgeMap = [
                                                'pending' => 'warning',
                                                'processing' => 'info',
                                                'shipped' => 'primary',
                                                'delivered' => 'success',
                                                'cancelled' => 'danger',
                                            ];
                                        @endphp
                                        <span class="badge bg-primary text-primary-secondary rounded-pill me-1">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="text-muted small">{{ $order->created_at->format('d M Y, h:i A') }}</td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-1">
                                            <a href="{{ route('dashboard.orders.show', $order) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <form action="{{ route('dashboard.orders.destroy', $order) }}" method="POST"
                                                  onsubmit="return confirm('Delete order {{ $order->order_code }}? This cannot be undone.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">No orders found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($orders->hasPages())
                    <div class="card-footer bg-white">
                        {{ $orders->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            @endsection
            @yield('orders_table')
        </div>
    </div>

</div>
@endsection

@push('backend_js')
<script>
    const wrapper = document.getElementById('ordersTableWrapper');
    const searchInput = document.getElementById('orderSearch');
    const statusSelect = document.getElementById('statusFilter');
    const baseUrl = "{{ route('dashboard.orders.index') }}";
    let debounceTimer;

    function fetchOrders(url) {
        wrapper.style.opacity = 0.5;
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.text())
            .then(html => {
                wrapper.innerHTML = html;
                wrapper.style.opacity = 1;
                history.replaceState(null, '', url);
            })
            .catch(() => {
                wrapper.style.opacity = 1;
            });
    }

    function buildUrlAndFetch(page = null) {
        const params = new URLSearchParams();
        if (searchInput.value.trim()) params.set('search', searchInput.value.trim());
        if (statusSelect.value) params.set('status', statusSelect.value);
        if (page) params.set('page', page);
        fetchOrders(baseUrl + '?' + params.toString());
    }

    searchInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => buildUrlAndFetch(), 400);
    });

    statusSelect.addEventListener('change', function () {
        buildUrlAndFetch();
    });

    wrapper.addEventListener('click', function (e) {
        const link = e.target.closest('.pagination a');
        if (link) {
            e.preventDefault();
            fetchOrders(link.href);
        }
    });
</script>
@endpush
