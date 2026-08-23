@extends('layouts.app')

@section('title', 'Order #' . $order->order_number)

@section('seo')
@include('frontend.partials.seo-meta', ['robots' => 'noindex, nofollow'])
@endsection

@section('content')
<div class="container-lg py-5">
    <h2 class="mb-4">Order Details</h2>

    <div class="card mb-3">
        <div class="card-header">
            <h5>Order Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
                    <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge bg-{{ $order->status_badge }}">
                            {{ ucfirst($order->order_status) }}
                        </span>
                    </p>
                </div>
                <div class="col-md-6">
                    <p><strong>Payment Method:</strong> {{ $order->paymentMethodLabel() }}</p>
                    @if($order->payment_reference)
                        <p><strong>Transaction ID:</strong> {{ $order->payment_reference }}</p>
                    @endif
                    <p><strong>Payment Status:</strong>
                        <span class="badge bg-{{ $order->payment_badge }}">{{ ucfirst($order->payment_status) }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            <h5>Order Items</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ $item->product_sku }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>${{ number_format($item->price, 2) }}</td>
                                <td>${{ number_format($item->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end"><strong>Subtotal:</strong></td>
                            <td><strong>${{ number_format($order->subtotal, 2) }}</strong></td>
                        </tr>
                        @if($order->tax > 0)
                            <tr>
                                <td colspan="4" class="text-end"><strong>Tax:</strong></td>
                                <td><strong>${{ number_format($order->tax, 2) }}</strong></td>
                            </tr>
                        @endif
                        @if($order->vat > 0)
                            <tr>
                                <td colspan="4" class="text-end"><strong>VAT:</strong></td>
                                <td><strong>${{ number_format($order->vat, 2) }}</strong></td>
                            </tr>
                        @endif
                        <tr>
                            <td colspan="4" class="text-end"><strong>Total:</strong></td>
                            <td><strong class="fs-5">${{ number_format($order->total, 2) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Shipping Address</h5>
        </div>
        <div class="card-body">
            <p class="mb-0">{!! nl2br(e($order->shipping_address)) !!}</p>
        </div>
    </div>

    @if($order->notes)
        <div class="card mt-3">
            <div class="card-header">
                <h5>Order notes</h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{!! nl2br(e($order->notes)) !!}</p>
            </div>
        </div>
    @endif

    <div class="mt-4 d-flex flex-wrap gap-2">
        <a href="{{ route('orders.invoice', $order) }}" class="btn btn-primary">
            <i class="fas fa-print me-1"></i> Print invoice
        </a>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">Back to Orders</a>
    </div>
</div>
@endsection

