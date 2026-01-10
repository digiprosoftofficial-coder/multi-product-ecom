@extends('layouts.app')

@section('title', 'Order #' . $order->order_number)

@section('content')
<div class="container my-5">
    <h2 class="mb-4">Order Details</h2>

    <div class="alert alert-success">
        <div class="fw-bold">Thank you for shopping with us.</div>
        <div>আপনার অর্ডারটি নিশ্চিত করা হয়েছে এবং বর্তমানে প্রসেসিং অবস্থায় আছে।</div>
        <div>অর্ডার শিপ হওয়ার সাথে সাথে ট্র্যাকিং তথ্য আপনাকে জানানো হবে।</div>
        <div>আমাদের সাথে থাকার জন্য ধন্যবাদ।</div>
    </div>

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
                    <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                    <p><strong>Payment Status:</strong> {{ ucfirst($order->payment_status) }}</p>
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
            <p>{{ $order->shipping_address }}</p>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">Back to Orders</a>
    </div>
</div>
@endsection

