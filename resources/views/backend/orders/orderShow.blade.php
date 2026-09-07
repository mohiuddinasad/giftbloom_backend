@extends('backend.layout')

@section('backend_title', 'Order Details')

@section('backend_content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Order {{ $order->order_code }}</h4>
            <span class="text-muted small">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</span>
        </div>
        <a href="{{ route('dashboard.orders.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fa-solid fa-arrow-left"></i> Back to Orders
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-4">

        <!-- LEFT: customer + items -->
        <div class="col-lg-8">

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-semibold">Customer & Delivery Info</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="text-muted small">Full Name</div>
                            <div class="fw-semibold">{{ $order->customer_name }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Phone</div>
                            <div class="fw-semibold">{{ $order->phone }}</div>
                        </div>
                        <div class="col-12">
                            <div class="text-muted small">Address</div>
                            <div class="fw-semibold">{{ $order->address }}, {{ $order->area }}, {{ $order->city }}</div>
                        </div>
                        @if ($order->note)
                            <div class="col-12">
                                <div class="text-muted small">Order Note</div>
                                <div>{{ $order->note }}</div>
                            </div>
                        @endif
                        <div class="col-md-6">
                            <div class="text-muted small">Shipping Method</div>
                            <div class="fw-semibold text-capitalize">{{ $order->shipping_method }} Chattogram</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Payment Method</div>
                            <div class="fw-semibold text-uppercase">{{ $order->payment_method }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-semibold">Order Items</div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Price</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td class="d-flex align-items-center gap-2">
                                        @if ($item->image)
                                            <img src="{{ $item->image }}" alt="{{ $item->name }}"
                                                 class="rounded" style="width:40px;height:40px;object-fit:cover;">
                                        @endif
                                        {{ $item->name }}
                                    </td>
                                    <td class="text-center">{{ $item->qty }}</td>
                                    <td class="text-end">Tk {{ number_format($item->price, 2) }}</td>
                                    <td class="text-end">Tk {{ number_format($item->price * $item->qty, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($order->gift_recipient_name || $order->gift_message)
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header bg-white fw-semibold">
                        <i class="fa-solid fa-envelope me-1"></i> Gift Message
                    </div>
                    <div class="card-body">
                        @if ($order->gift_recipient_name)
                            <div class="text-muted small">To</div>
                            <div class="fw-semibold mb-2">{{ $order->gift_recipient_name }}</div>
                        @endif
                        @if ($order->gift_message)
                            <div class="text-muted small">Message</div>
                            <div style="white-space: pre-line;">{{ $order->gift_message }}</div>
                        @endif
                    </div>
                </div>
            @endif

        </div>

        <!-- RIGHT: status + totals -->
        <div class="col-lg-4">

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-semibold">Order Status</div>
                <div class="card-body">
                    <form action="{{ route('dashboard.orders.update-status', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <select name="status" class="form-select mb-2">
                            @foreach (\App\Models\Backend\Order\Order::STATUSES as $status)
                                <option value="{{ $status }}" @selected($order->status === $status)>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary w-100">Update Status</button>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-semibold">Order Summary</div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span>Tk {{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Shipping</span>
                        <span>Tk {{ number_format($order->shipping_cost, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Total</span>
                        <span>Tk {{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
