@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">My Dashboard</h2>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>Total Orders</h5>
                    <h3>{{ $recentOrders->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Recent Orders</h5>
        </div>
        <div class="card-body">
            @if($recentOrders->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Order Number</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                                <tr>
                                    <td>{{ $order->order_number }}</td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                    <td>${{ number_format($order->total, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $order->status_badge }}">
                                            {{ ucfirst($order->order_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-primary">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <a href="{{ route('orders.index') }}" class="btn btn-primary">View All Orders</a>
                </div>
            @else
                <p class="text-muted">No orders yet.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">Start Shopping</a>
            @endif
        </div>
    </div>
</div>
@endsection

