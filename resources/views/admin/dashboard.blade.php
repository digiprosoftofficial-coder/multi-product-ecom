@extends('admin.layouts.master')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        @if(isset($notifications) && $notifications->count() > 0)
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Notifications</h5>
                    <small class="text-muted">{{ $notifications->count() }} new</small>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach($notifications as $notification)
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-0">
                                    <div class="fw-semibold">New Order: {{ $notification->data['order_number'] ?? '' }}</div>
                                    <div class="text-muted small">
                                        {{ $notification->data['customer_name'] ?? 'Customer' }} · ${{ number_format($notification->data['total'] ?? 0, 2) }}
                                    </div>
                                </div>
                                <div class="text-muted small">
                                    {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @else
            <div class="alert alert-info mb-0">No new notifications.</div>
        @endif
    </div>

    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Total Products</h5>
                <h2>{{ $stats['total_products'] }}</h2>
                <small>Active: {{ $stats['active_products'] }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Total Categories</h5>
                <h2>{{ $stats['total_categories'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">Total Orders</h5>
                <h2>{{ $stats['total_orders'] }}</h2>
                <small>Pending: {{ $stats['pending_orders'] }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h5 class="card-title">Pending Orders</h5>
                <h2>{{ $stats['pending_orders'] }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>Recent Orders</h5>
            </div>
            <div class="card-body">
                @if($stats['recent_orders']->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Order Number</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['recent_orders'] as $order)
                                    <tr>
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ $order->customer_name }}</td>
                                        <td>${{ number_format($order->total, 2) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $order->status_badge }}">
                                                {{ ucfirst($order->order_status) }}
                                            </span>
                                        </td>
                                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No orders yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

