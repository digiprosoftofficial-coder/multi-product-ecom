@extends('admin.layouts.master')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<h6 class="text-muted text-uppercase mb-2">Sales</h6>
<div class="row g-3 mb-3">
    <div class="col-sm-6 col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Today sell</div>
                <h2 class="mb-0">{{ $stats['today_sell'] }}</h2>
                <small class="text-muted">Orders today (excluding cancelled)</small>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="card border-0 shadow-sm h-100 bg-success text-white">
            <div class="card-body">
                <div class="small opacity-75">Today income</div>
                <h2 class="mb-0">{{ money($stats['today_income']) }}</h2>
                <small class="opacity-75">Paid orders today</small>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="card border-0 shadow-sm h-100 bg-dark text-white">
            <div class="card-body">
                <div class="small opacity-75">Today profit</div>
                <h2 class="mb-0">{{ money($stats['today_profit']) }}</h2>
                <small class="opacity-75">Selling minus purchase price</small>
            </div>
        </div>
    </div>
</div>
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Total sell</div>
                <h2 class="mb-0">{{ $stats['total_sell'] }}</h2>
                <small class="text-muted">All orders (excluding cancelled)</small>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="card border-0 shadow-sm h-100 bg-primary text-white">
            <div class="card-body">
                <div class="small opacity-75">Total income</div>
                <h2 class="mb-0">{{ money($stats['total_income']) }}</h2>
                <small class="opacity-75">All paid orders</small>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="card border-0 shadow-sm h-100" style="background:#0f766e;">
            <div class="card-body text-white">
                <div class="small opacity-75">Total profit</div>
                <h2 class="mb-0">{{ money($stats['total_profit']) }}</h2>
                <small class="opacity-75">Paid items with a purchase price</small>
            </div>
        </div>
    </div>
</div>

<h6 class="text-muted text-uppercase mb-2">Inventory</h6>
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">In stock</div>
                <h2 class="mb-0 text-success">{{ $stats['in_stock'] }}</h2>
                <small class="text-muted">Products with stock</small>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Out of stock</div>
                <h2 class="mb-0 text-danger">{{ $stats['out_of_stock'] }}</h2>
                <small class="text-muted">Need restock</small>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Low stock alert</div>
                <h2 class="mb-0 text-warning">{{ $stats['low_stock'] }}</h2>
                <small class="text-muted">{{ $stats['low_stock_limit'] }} or fewer units left</small>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Total products</div>
                <h2 class="mb-0">{{ $stats['total_products'] }}</h2>
                <small class="text-muted">Active: {{ $stats['active_products'] }}</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-md-3">
        <div class="card bg-info text-white h-100">
            <div class="card-body">
                <h5 class="card-title">Total Orders</h5>
                <h2>{{ $stats['total_orders'] }}</h2>
                <small>Pending: {{ $stats['pending_orders'] }}</small>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card bg-warning text-white h-100">
            <div class="card-body">
                <h5 class="card-title">Pending Orders</h5>
                <h2>{{ $stats['pending_orders'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card bg-secondary text-white h-100">
            <div class="card-body">
                <h5 class="card-title">Total Users</h5>
                <h2>{{ $stats['total_users'] }}</h2>
                <small>Last 30 days: {{ $stats['new_users_30d'] }}</small>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card bg-dark text-white h-100">
            <div class="card-body">
                <h5 class="card-title">Categories</h5>
                <h2>{{ $stats['total_categories'] }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Low stock alert</h5>
                <span class="badge bg-warning text-dark">{{ $stats['low_stock'] }}</span>
            </div>
            <div class="card-body">
                @if($stats['low_stock_products']->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Stock</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['low_stock_products'] as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td><span class="badge bg-warning text-dark">{{ $product->stock }}</span></td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary">Restock</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">No low-stock products.</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Out of stock</h5>
                <span class="badge bg-danger">{{ $stats['out_of_stock'] }}</span>
            </div>
            <div class="card-body">
                @if($stats['out_of_stock_products']->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Stock</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['out_of_stock_products'] as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td><span class="badge bg-danger">0</span></td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary">Restock</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">All products are in stock.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Orders</h5>
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
                                        <td>{{ money($order->total) }}</td>
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
                    <p class="text-muted mb-0">No orders yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
