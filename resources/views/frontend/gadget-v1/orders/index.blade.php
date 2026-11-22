@extends('layouts.gadget')

@section('title', 'My orders – '.site_name())

@section('seo')
@include('frontend.partials.seo-meta', ['robots' => 'noindex, nofollow'])
@endsection

@section('content')
<h1 class="h4 mb-4">My orders</h1>
@if($orders->count() > 0)
  <div class="card gadget-card border-secondary">
    <div class="card-body">
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
            @foreach($orders as $order)
              <tr>
                <td>{{ $order->order_number }}</td>
                <td>{{ $order->created_at->format('M d, Y') }}</td>
                <td>{{ money($order->total) }}</td>
                <td><span class="badge bg-{{ $order->status_badge }}">{{ ucfirst($order->order_status) }}</span></td>
                <td class="d-flex flex-wrap gap-2">
                  <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-primary">View</a>
                  <a href="{{ route('orders.invoice', $order) }}" class="btn btn-sm btn-outline-secondary">Invoice</a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="mt-3">{{ $orders->links() }}</div>
    </div>
  </div>
@else
  <div class="text-center py-5">
    <p class="text-muted">No orders yet.</p>
    <a href="{{ route('products.index') }}" class="btn btn-primary">Browse products</a>
  </div>
@endif
@endsection
