@extends('admin.layouts.master')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    .dash-section-title {
        font-size: 1.2rem;
        letter-spacing: .05em;
        font-weight: 700;
        color: #334155;
        margin-bottom: .9rem;
    }
    .stat-card {
        border: 0;
        border-radius: 1rem;
        overflow: hidden;
        position: relative;
        min-height: 148px;
        transition: transform .18s ease, box-shadow .18s ease;
        box-shadow: 0 8px 20px rgba(15, 23, 42, .08);
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 28px rgba(15, 23, 42, .14);
    }
    .stat-card .card-body {
        position: relative;
        z-index: 1;
        padding: 1.25rem 1.3rem;
    }
    .stat-card .stat-label {
        font-size: 1.1rem;
        font-weight: 600;
        opacity: .92;
        margin-bottom: .25rem;
    }
    .stat-card h2 {
        font-size: 2.05rem;
        font-weight: 700;
        letter-spacing: -.02em;
        margin: 0;
        line-height: 1.2;
    }
    .stat-card .stat-hint {
        font-size: .95rem;
        opacity: .82;
        display: block;
        margin-top: .4rem;
    }
    .stat-card .stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, .22);
        flex-shrink: 0;
        font-size: 1.15rem;
    }
    .stat-card::after {
        content: '';
        position: absolute;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        right: -28px;
        top: -36px;
        background: rgba(255, 255, 255, .12);
        pointer-events: none;
    }
    .stat-indigo { background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); color: #fff; }
    .stat-green  { background: linear-gradient(135deg, #15803d 0%, #22c55e 100%); color: #fff; }
    .stat-teal   { background: linear-gradient(135deg, #0f766e 0%, #14b8a6 100%); color: #fff; }
    .stat-sky    { background: linear-gradient(135deg, #0369a1 0%, #38bdf8 100%); color: #fff; }
    .stat-blue   { background: linear-gradient(135deg, #1d4ed8 0%, #3b82f6 100%); color: #fff; }
    .stat-emerald{ background: linear-gradient(135deg, #047857 0%, #10b981 100%); color: #fff; }
    .stat-lime   { background: linear-gradient(135deg, #3f6212 0%, #65a30d 100%); color: #fff; }
    .stat-rose   { background: linear-gradient(135deg, #be123c 0%, #f43f5e 100%); color: #fff; }
    .stat-amber  { background: linear-gradient(135deg, #b45309 0%, #f59e0b 100%); color: #fff; }
    .stat-violet { background: linear-gradient(135deg, #6d28d9 0%, #a78bfa 100%); color: #fff; }
    .stat-cyan   { background: linear-gradient(135deg, #0e7490 0%, #22d3ee 100%); color: #fff; }
    .stat-orange { background: linear-gradient(135deg, #c2410c 0%, #fb923c 100%); color: #fff; }
    .stat-purple { background: linear-gradient(135deg, #6b21a8 0%, #c084fc 100%); color: #fff; }
    .stat-slate  { background: linear-gradient(135deg, #334155 0%, #64748b 100%); color: #fff; }

    .panel-card {
        border: 0;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 8px 20px rgba(15, 23, 42, .07);
    }
    .panel-card .card-header {
        border: 0;
        padding: 1rem 1.2rem;
        color: #fff;
        font-weight: 600;
    }
    .panel-card .card-header h5 {
        font-size: 1.25rem;
        font-weight: 600;
    }
    .panel-amber .card-header { background: linear-gradient(135deg, #b45309, #f59e0b); }
    .panel-rose .card-header { background: linear-gradient(135deg, #be123c, #f43f5e); }
    .panel-blue .card-header { background: linear-gradient(135deg, #1d4ed8, #3b82f6); }
    .panel-card .table thead th {
        font-size: .9rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #64748b;
        font-weight: 600;
        border-bottom-color: #e2e8f0;
    }
    .panel-card .table td {
        vertical-align: middle;
        font-size: 1rem;
    }
    .panel-card .card-body p {
        font-size: 1rem;
    }
</style>
@endpush

@section('content')
<h6 class="dash-section-title text-uppercase">Sales</h6>
<div class="row g-3 mb-3">
    <div class="col-sm-6 col-md-4">
        <div class="card stat-card stat-indigo h-100">
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
        <div class="card stat-card stat-green h-100">
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
        <div class="card stat-card stat-teal h-100">
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
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-md-4">
        <div class="card stat-card stat-sky h-100">
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
        <div class="card stat-card stat-blue h-100">
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
        <div class="card stat-card stat-emerald h-100">
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

<h6 class="dash-section-title text-uppercase">Inventory</h6>
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-md-3">
        <div class="card stat-card stat-lime h-100">
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
        <div class="card stat-card stat-rose h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="stat-label">Out of stock</div>
                    <span class="stat-icon"><i class="fas fa-box-open"></i></span>
                </div>
                <h2>{{ $stats['out_of_stock'] }}</h2>
                <span class="stat-hint">Need restock</span>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card stat-card stat-amber h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="stat-label">Low stock alert</div>
                    <span class="stat-icon"><i class="fas fa-triangle-exclamation"></i></span>
                </div>
                <h2>{{ $stats['low_stock'] }}</h2>
                <span class="stat-hint">{{ $stats['low_stock_limit'] }} or fewer units left</span>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card stat-card stat-violet h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="stat-label">Total products</div>
                    <span class="stat-icon"><i class="fas fa-box"></i></span>
                </div>
                <h2>{{ $stats['total_products'] }}</h2>
                <span class="stat-hint">Active: {{ $stats['active_products'] }}</span>
            </div>
        </div>
    </div>
</div>

<h6 class="dash-section-title text-uppercase">Overview</h6>
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-md-3">
        <div class="card stat-card stat-cyan h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="stat-label">Total orders</div>
                    <span class="stat-icon"><i class="fas fa-clipboard-list"></i></span>
                </div>
                <h2>{{ $stats['total_orders'] }}</h2>
                <span class="stat-hint">Pending: {{ $stats['pending_orders'] }}</span>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card stat-card stat-orange h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="stat-label">Pending orders</div>
                    <span class="stat-icon"><i class="fas fa-clock"></i></span>
                </div>
                <h2>{{ $stats['pending_orders'] }}</h2>
                <span class="stat-hint">Waiting to be processed</span>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card stat-card stat-purple h-100">
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
        <div class="card stat-card stat-slate h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="stat-label">Categories</div>
                    <span class="stat-icon"><i class="fas fa-folder-open"></i></span>
                </div>
                <h2>{{ $stats['total_categories'] }}</h2>
                <span class="stat-hint">Product categories</span>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card panel-card panel-amber h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-triangle-exclamation me-2"></i>Low stock alert</h5>
                <span class="badge bg-white text-warning">{{ $stats['low_stock'] }}</span>
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
                                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-warning">Restock</a>
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
        <div class="card panel-card panel-rose h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-box-open me-2"></i>Out of stock</h5>
                <span class="badge bg-white text-danger">{{ $stats['out_of_stock'] }}</span>
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
                                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-danger">Restock</a>
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
        <div class="card panel-card panel-blue">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-clock-rotate-left me-2"></i>Recent orders</h5>
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
