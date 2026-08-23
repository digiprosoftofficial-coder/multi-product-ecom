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

        <div class="card order-customer-card">
            <div class="card-header">
                <h5 class="mb-0">Customer Information</h5>
            </div>
            <div class="card-body">
                <ul class="order-customer-list list-unstyled mb-0">
                    <li class="order-customer-item">
                        <span class="order-customer-icon order-customer-icon--name"><i class="fas fa-user"></i></span>
                        <div class="order-customer-body">
                            <span class="order-customer-label">Name</span>
                            <span class="order-customer-value">{{ $order->customer_name }}</span>
                        </div>
                    </li>
                    <li class="order-customer-item">
                        <span class="order-customer-icon order-customer-icon--email"><i class="fas fa-envelope"></i></span>
                        <div class="order-customer-body">
                            <span class="order-customer-label">Email</span>
                            <a href="mailto:{{ $order->customer_email }}" class="order-customer-value order-customer-link">{{ $order->customer_email }}</a>
                        </div>
                    </li>
                    @if($order->customer_phone)
                        <li class="order-customer-item">
                            <span class="order-customer-icon order-customer-icon--phone"><i class="fas fa-phone"></i></span>
                            <div class="order-customer-body">
                                <span class="order-customer-label">Phone</span>
                                <a href="tel:{{ preg_replace('/\s+/', '', $order->customer_phone) }}" class="order-customer-value order-customer-link">{{ $order->customer_phone }}</a>
                            </div>
                        </li>
                    @endif
                </ul>
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
    .order-customer-card {
        border-color: #dbeafe;
        box-shadow: 0 8px 22px rgba(37, 99, 235, 0.08);
    }
    .order-customer-card .card-header {
        background: linear-gradient(180deg, #eff6ff 0%, #f8fbff 100%);
        border-bottom-color: #dbeafe;
    }
    .order-customer-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .order-customer-item {
        display: flex;
        align-items: center;
        gap: .85rem;
        padding: .85rem .95rem;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #fff;
    }
    .order-customer-icon {
        flex-shrink: 0;
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: .95rem;
    }
    .order-customer-icon--name { background: #16a34a; }
    .order-customer-icon--email { background: #2563eb; }
    .order-customer-icon--phone { background: #ea580c; }
    .order-customer-body {
        min-width: 0;
    }
    .order-customer-label {
        display: block;
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .05em;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: .15rem;
    }
    .order-customer-value {
        display: block;
        font-size: 1rem;
        font-weight: 700;
        line-height: 1.4;
        color: #0f172a;
        word-break: break-word;
    }
    .order-customer-link {
        text-decoration: none;
    }
    .order-customer-link:hover {
        color: #16a34a;
        text-decoration: underline;
    }
</style>
@endpush

