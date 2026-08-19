@extends('admin.layouts.master')

@section('title', 'Sales report')
@section('page-title', 'Sales report')

@section('content')
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="row g-3 align-items-end">
            <div class="col-12">
                <label class="form-label mb-2">Period</label>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($periods as $key => $label)
                        <a href="{{ route('admin.reports.index', array_filter(['period' => $key, 'payment_status' => $paymentStatus !== 'all' ? $paymentStatus : null, 'order_status' => $orderStatus !== 'all' ? $orderStatus : null])) }}"
                           class="btn btn-sm {{ $period === $key ? 'btn-primary' : 'btn-outline-primary' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="col-md-3">
                <label for="from" class="form-label">From</label>
                <input type="date" name="from" id="from" class="form-control"
                       value="{{ $from->toDateString() }}">
            </div>
            <div class="col-md-3">
                <label for="to" class="form-label">To</label>
                <input type="date" name="to" id="to" class="form-control"
                       value="{{ $to->toDateString() }}">
            </div>
            <input type="hidden" name="period" id="periodField" value="{{ $period }}">

            <div class="col-md-3">
                <label for="payment_status" class="form-label">Payment</label>
                <select name="payment_status" id="payment_status" class="form-select">
                    <option value="all" @selected($paymentStatus === 'all')>All</option>
                    <option value="paid" @selected($paymentStatus === 'paid')>Paid</option>
                    <option value="pending" @selected($paymentStatus === 'pending')>Pending</option>
                    <option value="cancelled" @selected($paymentStatus === 'cancelled')>Cancelled</option>
                    <option value="refunded" @selected($paymentStatus === 'refunded')>Refunded</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="order_status" class="form-label">Order status</label>
                <select name="order_status" id="order_status" class="form-select">
                    <option value="all" @selected($orderStatus === 'all')>All</option>
                    <option value="pending" @selected($orderStatus === 'pending')>Pending</option>
                    <option value="processing" @selected($orderStatus === 'processing')>Processing</option>
                    <option value="shipped" @selected($orderStatus === 'shipped')>Shipped</option>
                    <option value="delivered" @selected($orderStatus === 'delivered')>Delivered</option>
                    <option value="cancelled" @selected($orderStatus === 'cancelled')>Cancelled</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">Apply filter</button>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.reports.print', [
                    'period' => $period,
                    'from' => $from->toDateString(),
                    'to' => $to->toDateString(),
                    'payment_status' => $paymentStatus,
                    'order_status' => $orderStatus,
                ]) }}" class="btn btn-outline-secondary w-100" target="_blank">
                    <i class="fas fa-print me-1"></i> Print / PDF
                </a>
            </div>
        </form>
        <p class="text-muted small mb-0 mt-3">
            Showing {{ $from->format('M d, Y') }} – {{ $to->format('M d, Y') }}.
            Sell and income use order date. Income and profit count paid orders only.
        </p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Sell</div>
                <h2 class="mb-0">{{ $stats['sell'] }}</h2>
                <small class="text-muted">Orders (excluding cancelled)</small>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 bg-success text-white">
            <div class="card-body">
                <div class="small opacity-75">Income</div>
                <h2 class="mb-0">{{ money($stats['income']) }}</h2>
                <small class="opacity-75">{{ $stats['paid_orders'] }} paid orders</small>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 bg-dark text-white">
            <div class="card-body">
                <div class="small opacity-75">Profit</div>
                <h2 class="mb-0">{{ money($stats['profit']) }}</h2>
                <small class="opacity-75">Selling minus purchase price</small>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Paid orders</div>
                <h2 class="mb-0">{{ $stats['paid_orders'] }}</h2>
                <small class="text-muted">Payment status = Paid</small>
            </div>
        </div>
    </div>
</div>

<div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Orders in this period</h5>
                <a href="{{ route('admin.reports.print', [
                    'period' => $period,
                    'from' => $from->toDateString(),
                    'to' => $to->toDateString(),
                    'payment_status' => $paymentStatus,
                    'order_status' => $orderStatus,
                ]) }}" class="btn btn-sm btn-outline-secondary" target="_blank">
                    <i class="fas fa-print me-1"></i> Print / PDF
                </a>
            </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Order Number</th>
                        <th>Customer</th>
                        <th>Product</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>
                                <div>{{ $order->customer_name }}</div>
                                <small class="text-muted">{{ $order->customer_email }}</small>
                            </td>
                            <td>
                                @forelse($order->items as $item)
                                    <div>
                                        {{ $item->product_name }}
                                        @if($item->quantity > 1)
                                            <small class="text-muted">× {{ $item->quantity }}</small>
                                        @endif
                                    </div>
                                @empty
                                    <span class="text-muted">—</span>
                                @endforelse
                            </td>
                            <td>{{ money($order->total) }}</td>
                            <td>
                                <span class="badge bg-{{ $order->payment_badge }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $order->status_badge }}">
                                    {{ ucfirst($order->order_status) }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No orders in this period.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var periodField = document.getElementById('periodField');
    ['from', 'to'].forEach(function (id) {
        var input = document.getElementById(id);
        if (input && periodField) {
            input.addEventListener('change', function () {
                periodField.value = 'custom';
            });
        }
    });
});
</script>
@endpush
