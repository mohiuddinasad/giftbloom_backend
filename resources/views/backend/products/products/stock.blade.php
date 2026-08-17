@extends('backend.layout')

@section('backend_title', 'Stock — ' . $product->name)

@section('backend_content')
<div class="container-fluid">
    <h4 class="mb-3">Stock — {{ $product->name }} <small class="text-muted">(current: {{ $product->qty }})</small></h4>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('dashboard.products.stock.store', $product) }}" method="POST" class="row g-2 align-items-end">
                @csrf
                <div class="col-md-2">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select" required>
                        <option value="in">Stock In</option>
                        <option value="out">Stock Out</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Quantity</label>
                    <input type="number" name="quantity" min="1" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Reason</label>
                    <input type="text" name="reason" class="form-control" placeholder="e.g. Purchase, Sold, Damaged">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Note</label>
                    <input type="text" name="note" class="form-control">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100">Save</button>
                </div>
            </form>
        </div>
    </div>

    <h6>History</h6>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Qty</th>
                <th>Balance After</th>
                <th>Reason</th>
                <th>By</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($movements as $move)
                <tr>
                    <td>{{ $move->created_at->format('d M Y, h:i A') }}</td>
                    <td>
                        <span class="badge bg-{{ $move->type === 'in' ? 'success' : 'danger' }}">
                            {{ strtoupper($move->type) }}
                        </span>
                    </td>
                    <td>{{ $move->quantity }}</td>
                    <td>{{ $move->balance_after }}</td>
                    <td>{{ $move->reason }}</td>
                    <td>{{ $move->user->name ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $movements->links() }}
</div>
@endsection
