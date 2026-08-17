@extends('admin.layouts.master')

@section('title', 'Order Details')
@section('page-title', 'Order #' . $order->order_number)

@section('content')
<div class="d-flex flex-wrap gap-2 mb-3">
    <a href="{{ route('admin.orders.invoice', $order) }}" class="btn btn-primary">
        <i class="fas fa-print me-1"></i> Print invoice
    </a>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Back to Orders</a>
</div>

<div class="row">
    <div class="col-md-8">
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
                    <h5>Customer notes</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{!! nl2br(e($order->notes)) !!}</p>
                </div>
            </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header">
                <h5>Order Summary</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span>${{ number_format($order->subtotal, 2) }}</span>
                </div>
                @if($order->tax > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax:</span>
                        <span>${{ number_format($order->tax, 2) }}</span>
                    </div>
                @endif
                @if($order->vat > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span>VAT:</span>
                        <span>${{ number_format($order->vat, 2) }}</span>
                    </div>
                @endif
                <hr>
                <div class="d-flex justify-content-between">
                    <strong>Total:</strong>
                    <strong>${{ number_format($order->total, 2) }}</strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-2">
                    <span>Payment method:</span>
                    <span>{{ $order->paymentMethodLabel() }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Payment status:</span>
                    <span class="badge bg-{{ $order->payment_badge }}">{{ ucfirst($order->payment_status) }}</span>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <h5>Update Status</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <select name="order_status" class="form-select @error('order_status') is-invalid @enderror" required>
                            <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->order_status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->order_status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('order_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Update Status</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Customer Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> {{ $order->customer_name }}</p>
                <p><strong>Email:</strong> {{ $order->customer_email }}</p>
                @if($order->customer_phone)
                    <p><strong>Phone:</strong> {{ $order->customer_phone }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

