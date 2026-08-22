@extends('admin.layouts.master')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    .dash-section-title {
        font-size: .8rem;
        letter-spacing: .08em;
        font-weight: 700;
        color: #64748b;
        margin-bottom: .85rem;
    }
    .dash-section-title span {
        display: inline-block;
        padding-bottom: .15rem;
        border-bottom: 2px solid #bbf7d0;
    }

    .stat-card {
        border: 1px solid #e8eee9;
        border-radius: .9rem;
        background: #fff;
        overflow: hidden;
        position: relative;
        min-height: 128px;
        transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        box-shadow: 0 1px 2px rgba(15, 23, 42, .04);
        color: #0f172a;
        text-decoration: none;
        display: block;
        height: 100%;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(15, 23, 42, .08);
        border-color: #d7e5dc;
        color: #0f172a;
    }
    .stat-card .card-body {
        position: relative;
        z-index: 1;
        padding: 1.1rem 1.15rem;
    }
    .stat-card .stat-label {
        font-size: .95rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: .15rem;
    }
    .stat-card h2 {
        font-size: 1.85rem;
        font-weight: 700;
        letter-spacing: -.02em;
        margin: 0;
        line-height: 1.2;
        color: #0f172a;
    }
    .stat-card .stat-hint {
        font-size: .85rem;
        color: #94a3b8;
        display: block;
        margin-top: .35rem;
    }
    .stat-card .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1rem;
    }
    .stat-card.stat-primary {
        border-color: #d8f3e2;
        box-shadow: 0 4px 14px rgba(22, 163, 74, .08);
    }
    .stat-card.stat-primary h2 {
        font-size: 2rem;
    }

    .tone-green .stat-icon { background: #dcfce7; color: #16a34a; }
    .tone-money .stat-icon { background: #dcfce7; color: #15803d; }
    .tone-neutral .stat-icon { background: #f1f5f4; color: #64748b; }
    .tone-danger .stat-icon { background: #fee2e2; color: #dc2626; }
    .tone-warning .stat-icon { background: #fef3c7; color: #d97706; }
    .tone-info .stat-icon { background: #dcfce7; color: #16a34a; }

    .tone-danger.is-alert {
        border-color: #fecaca;
        background: #fffafa;
    }
    .tone-warning.is-alert {
        border-color: #fde68a;
        background: #fffbeb;
    }

    .stat-secondary {
        min-height: 112px;
    }
    .stat-secondary h2 {
        font-size: 1.55rem;
    }
    .stat-secondary .stat-label {
        font-size: .9rem;
    }

    .panel-card {
        border: 1px solid #e8eee9;
        border-radius: .9rem;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 1px 2px rgba(15, 23, 42, .04);
    }
    .panel-card .card-header {
        border: 0;
        border-bottom: 1px solid #eef2f0;
        padding: .95rem 1.15rem;
        background: #fff;
        color: #0f172a;
        font-weight: 600;
    }
    .panel-card .card-header h5 {
        font-size: 1.05rem;
        font-weight: 700;
        color: #0f172a;
    }
    .panel-card .card-header h5 i {
        color: #16a34a;
    }
    .panel-card .table thead th {
        font-size: .78rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #64748b;
        font-weight: 600;
        border-bottom-color: #e8eee9;
        background: #f8faf9;
    }
    .panel-card .table td {
        vertical-align: middle;
        font-size: .95rem;
    }
    .panel-card .card-body p {
        font-size: .95rem;
    }
    .stock-empty {
        display: flex;
        align-items: center;
        gap: .85rem;
        padding: .35rem 0;
        color: #166534;
        font-size: 1rem;
        font-weight: 600;
    }
    .stock-empty i {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #dcfce7;
        color: #16a34a;
        font-size: 1.1rem;
    }
    .header-count {
        background: #f1f5f4;
        color: #475569;
        font-weight: 600;
        font-size: .82rem;
        padding: .28rem .7rem;
        border-radius: 999px;
    }
</style>
@endpush

@section('content')
@php
    $alertTotal = $stats['out_of_stock'] + $stats['low_stock'];
@endphp

<h6 class="dash-section-title text-uppercase"><span>Today</span></h6>
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-md-4">
        <div class="card stat-card stat-primary tone-green h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="stat-label">Today sell</div>
                    <span class="stat-icon"><i class="fas fa-shopping-bag"></i></span>
                </div>
                <h2>{{ $stats['today_sell'] }}</h2>
                <span class="stat-hint">Orders today (excluding cancelled)</span>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="card stat-card stat-primary tone-money h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="stat-label">Today income</div>
                    <span class="stat-icon"><i class="fas fa-wallet"></i></span>
                </div>
                <h2>{{ money($stats['today_income']) }}</h2>
                <span class="stat-hint">Paid orders today</span>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="card stat-card stat-primary tone-money h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="stat-label">Today profit</div>
                    <span class="stat-icon"><i class="fas fa-chart-line"></i></span>
                </div>
                <h2>{{ money($stats['today_profit']) }}</h2>
                <span class="stat-hint">Selling minus purchase price</span>
            </div>
        </div>
    </div>
</div>

<h6 class="dash-section-title text-uppercase"><span>Totals</span></h6>
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-md-4">
        <div class="card stat-card stat-secondary tone-neutral h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="stat-label">Total sell</div>
                    <span class="stat-icon"><i class="fas fa-receipt"></i></span>
                </div>
                <h2>{{ $stats['total_sell'] }}</h2>
                <span class="stat-hint">All orders (excluding cancelled)</span>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="card stat-card stat-secondary tone-neutral h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="stat-label">Total income</div>
                    <span class="stat-icon"><i class="fas fa-coins"></i></span>
                </div>
                <h2>{{ money($stats['total_income']) }}</h2>
                <span class="stat-hint">All paid orders</span>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="card stat-card stat-secondary tone-neutral h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="stat-label">Total profit</div>
                    <span class="stat-icon"><i class="fas fa-piggy-bank"></i></span>
                </div>
                <h2>{{ money($stats['total_profit']) }}</h2>
                <span class="stat-hint">Paid items with a purchase price</span>
            </div>
        </div>
    </div>
</div>

<h6 class="dash-section-title text-uppercase"><span>Inventory</span></h6>
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-md-3">
        <div class="card stat-card tone-green h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="stat-label">In stock</div>
                    <span class="stat-icon"><i class="fas fa-boxes-stacked"></i></span>
                </div>
                <h2>{{ $stats['in_stock'] }}</h2>
                <span class="stat-hint">Products with stock</span>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <a href="#stock-alerts" class="card stat-card tone-danger h-100 {{ $stats['out_of_stock'] > 0 ? 'is-alert' : '' }}">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="stat-label">Out of stock</div>
                    <span class="stat-icon"><i class="fas fa-box-open"></i></span>
                </div>
                <h2>{{ $stats['out_of_stock'] }}</h2>
                <span class="stat-hint">Need restock</span>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-md-3">
        <a href="#stock-alerts" class="card stat-card tone-warning h-100 {{ $stats['low_stock'] > 0 ? 'is-alert' : '' }}">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="stat-label">Low stock alert</div>
                    <span class="stat-icon"><i class="fas fa-triangle-exclamation"></i></span>
                </div>
                <h2>{{ $stats['low_stock'] }}</h2>
                <span class="stat-hint">{{ $stats['low_stock_limit'] }} or fewer units left</span>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-md-3">
        <a href="{{ route('admin.products.index') }}" class="card stat-card tone-neutral h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="stat-label">Total products</div>
                    <span class="stat-icon"><i class="fas fa-box"></i></span>
                </div>
                <h2>{{ $stats['total_products'] }}</h2>
                <span class="stat-hint">Active: {{ $stats['active_products'] }}</span>
            </div>
        </a>
    </div>
</div>

<h6 class="dash-section-title text-uppercase"><span>Overview</span></h6>
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-md-3">
        <a href="{{ route('admin.orders.index') }}" class="card stat-card tone-neutral h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="stat-label">Total orders</div>
                    <span class="stat-icon"><i class="fas fa-clipboard-list"></i></span>
                </div>
                <h2>{{ $stats['total_orders'] }}</h2>
                <span class="stat-hint">Pending: {{ $stats['pending_orders'] }}</span>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-md-3">
        <a href="{{ route('admin.orders.index') }}" class="card stat-card tone-warning h-100 {{ $stats['pending_orders'] > 0 ? 'is-alert' : '' }}">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="stat-label">Pending orders</div>
                    <span class="stat-icon"><i class="fas fa-clock"></i></span>
                </div>
                <h2>{{ $stats['pending_orders'] }}</h2>
                <span class="stat-hint">Waiting to be processed</span>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card stat-card tone-neutral h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="stat-label">Total users</div>
                    <span class="stat-icon"><i class="fas fa-users"></i></span>
                </div>
                <h2>{{ $stats['total_users'] }}</h2>
                <span class="stat-hint">Last 30 days: {{ $stats['new_users_30d'] }}</span>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <a href="{{ route('admin.categories.index') }}" class="card stat-card tone-neutral h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="stat-label">Categories</div>
                    <span class="stat-icon"><i class="fas fa-folder-open"></i></span>
                </div>
                <h2>{{ $stats['total_categories'] }}</h2>
                <span class="stat-hint">Product categories</span>
            </div>
        </a>
    </div>
</div>

<div class="row g-3" id="stock-alerts">
    <div class="col-12">
        <div class="card panel-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-boxes-stacked me-2"></i>Stock alerts</h5>
                <span class="header-count">{{ $alertTotal }} products</span>
            </div>
            <div class="card-body">
                @if($stats['stock_alerts']->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['stock_alerts'] as $product)
                                    @php $isOut = $product->stock <= 0; @endphp
                                    <tr>
                                        <td class="fw-semibold">{{ $product->name }}</td>
                                        <td>{{ $product->stock }}</td>
                                        <td>
                                            @if($isOut)
                                                <span class="badge bg-danger">Out of stock</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Low stock</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm {{ $isOut ? 'btn-outline-danger' : 'btn-outline-warning' }}">Restock</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="stock-empty">
                        <i class="fas fa-circle-check"></i>
                        All products have enough stock
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card panel-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-clock-rotate-left me-2"></i>Recent orders</h5>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-success">View all</a>
            </div>
            <div class="card-body">
                @if($stats['recent_orders']->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Order Number</th>
                                    <th>Customer</th>
                                    <th>Product</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['recent_orders'] as $order)
                                    <tr>
                                        <td class="fw-semibold">{{ $order->order_number }}</td>
                                        <td>{{ $order->customer_name }}</td>
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
                                            <span class="badge bg-{{ $order->status_badge }}">
                                                {{ ucfirst($order->order_status) }}
                                            </span>
                                        </td>
                                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-success">
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
