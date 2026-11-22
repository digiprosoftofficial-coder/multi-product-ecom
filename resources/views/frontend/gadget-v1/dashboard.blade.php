@extends('layouts.gadget')

@section('title', 'Dashboard – '.site_name())

@section('seo')
@include('frontend.partials.seo-meta', ['robots' => 'noindex, nofollow'])
@endsection

@section('content')
<h1 class="h4 mb-4">My dashboard</h1>
<div class="card gadget-card border-secondary mb-4">
  <div class="card-body">
    <div class="text-muted small text-uppercase">Total orders</div>
    <div class="fs-3 fw-semibold">{{ $recentOrders->count() }}</div>
  </div>
</div>
<div class="card gadget-card border-secondary">
  <div class="card-header border-secondary d-flex justify-content-between align-items-center">
    <h2 class="h6 mb-0">Recent orders</h2>
    <a href="{{ route('orders.index') }}" class="small">View all</a>
  </div>
  <div class="card-body">
    @if($recentOrders->count() > 0)
      <div class="table-responsive">
        <table class="table table-dark table-borderless mb-0">
          <thead>
            <tr>
              <th>Order</th>
              <th>Date</th>
              <th>Total</th>
              <th>Status</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach($recentOrders as $order)
              <tr>
                <td>{{ $order->order_number }}</td>
                <td>{{ $order->created_at->format('M d, Y') }}</td>
                <td>{{ money($order->total) }}</td>
                <td><span class="badge bg-{{ $order->status_badge }}">{{ ucfirst($order->order_status) }}</span></td>
                <td><a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-primary">View</a></td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <p class="text-muted mb-3">No orders yet.</p>
      <a href="{{ route('products.index') }}" class="btn btn-primary">Start shopping</a>
    @endif
  </div>
</div>
@endsection
