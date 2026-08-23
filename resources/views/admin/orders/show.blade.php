@extends('admin.layouts.master')

@section('title', 'Order Details')
@section('page-title', 'Order #' . $order->order_number)

@section('content')
<div class="order-action-bar card mb-3">
    <div class="card-body py-3">
        <div class="d-flex flex-wrap align-items-end justify-content-between gap-3">
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.orders.invoice', $order) }}" class="btn btn-primary">
                    <i class="fas fa-print me-1"></i> Print invoice
                </a>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Back to Orders</a>
            </div>
            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="order-status-form d-flex flex-wrap align-items-end gap-2">
                @csrf
                @method('PATCH')
                <div>
                    <label for="order_status" class="form-label small mb-1">Order status</label>
                    <select name="order_status" id="order_status" class="form-select form-select-sm @error('order_status') is-invalid @enderror" required>
                        <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ $order->order_status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ $order->order_status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('order_status')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="payment_status" class="form-label small mb-1">Payment status</label>
                    <select name="payment_status" id="payment_status" class="form-select form-select-sm @error('payment_status') is-invalid @enderror" required>
                        <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="cancelled" {{ $order->payment_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                    @error('payment_status')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Update Status</button>
            </form>
        </div>
    </div>
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
                                    <td>{{ money($item->price) }}</td>
                                    <td>{{ money($item->total) }}</td>
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
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0">Order Summary</h5>
                <div class="d-flex flex-wrap gap-1">
                    <span class="badge bg-{{ $order->status_badge }}">{{ ucfirst($order->order_status) }}</span>
                    <span class="badge bg-{{ $order->payment_badge }}">{{ ucfirst($order->payment_status) }}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span>{{ money($order->subtotal) }}</span>
                </div>
                @if($order->tax > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax:</span>
                        <span>{{ money($order->tax) }}</span>
                    </div>
                @endif
                @if($order->vat > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span>VAT:</span>
                        <span>{{ money($order->vat) }}</span>
                    </div>
                @endif
                <hr>
                <div class="d-flex justify-content-between">
                    <strong>Total:</strong>
                    <strong>{{ money($order->total) }}</strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-2">
                    <span>Payment method:</span>
                    <span>{{ $order->paymentMethodLabel() }}</span>
                </div>
                @if($order->payment_reference)
                    <div class="d-flex justify-content-between mb-2">
                        <span>Transaction ID:</span>
                        <span>{{ $order->payment_reference }}</span>
                    </div>
                @endif
                @if($order->payment_sender_phone)
                    <div class="d-flex justify-content-between mb-2">
                        <span>Sender number:</span>
                        <span>{{ $order->payment_sender_phone }}</span>
                    </div>
                @endif
                <div class="d-flex justify-content-between">
                    <span>Payment status:</span>
                    <span class="badge bg-{{ $order->payment_badge }}">{{ ucfirst($order->payment_status) }}</span>
                </div>
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

@push('styles')
<style>
    .order-action-bar {
        border-color: #e8eee9;
    }
    .order-status-form select {
        min-width: 140px;
    }
    .order-status-form .btn {
        height: 31px;
    }
</style>
@endpush

