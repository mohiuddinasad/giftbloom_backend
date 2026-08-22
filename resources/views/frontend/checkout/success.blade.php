{{-- resources/views/frontend/checkout/success.blade.php --}}
@extends('frontend.layout')

@section('frontend_title', 'Order Confirmed')
@section('frontend_content')
<main class="order-success-page">
    <div class="container text-center py-5">
        <i class="fa-solid fa-circle-check" style="font-size:60px;color:#2e7d32;"></i>
        <h1 class="mt-3">Thank you, {{ $order->customer_name }}!</h1>
        <p>Your order has been placed successfully.</p>

        <div class="order-code-box my-4">
            <span>Order Code</span>
            <h2>{{ $order->order_code }}</h2>
        </div>

        <p>Total: Tk {{ number_format($order->total, 2) }}</p>

        <a href="{{ route('frontend.home') }}" class="btn btn-dark mt-3">Continue Shopping</a>
    </div>
</main>
@endsection
