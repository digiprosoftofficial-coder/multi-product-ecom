@extends('layouts.gadget')

@section('title', 'Order #'.$order->order_number.' – '.site_name())

@section('seo')
@include('frontend.partials.seo-meta', ['robots' => 'noindex, nofollow'])
@endsection

@section('content')
<h1 class="h4 mb-4">Order {{ $order->order_number }}</h1>
<div class="card gadget-card border-secondary mb-3">
  <div class="card-body">
    <div class="row">
      <div class="col-md-6">
        <p class="mb-2"><span class="text-muted">Date:</span> {{ $order->created_at->format('M d, Y H:i') }}</p>
        <p class="mb-0"><span class="text-muted">Status:</span> <span class="badge bg-{{ $order->status_badge }}">{{ ucfirst($order->order_status) }}</span></p>
      </div>
      <div class="col-md-6">
        <p class="mb-2"><span class="text-muted">Payment:</span> {{ $order->paymentMethodLabel() }}</p>
        @if($order->payment_reference)
          <p class="mb-2"><span class="text-muted">Transaction ID:</span> {{ $order->payment_reference }}</p>
        @endif
        <p class="mb-0"><span class="text-muted">Payment status:</span> <span class="badge bg-{{ $order->payment_badge }}">{{ ucfirst($order->payment_status) }}</span></p>
      </div>
    </div>
  </div>
</div>
<div class="card gadget-card border-secondary mb-3">
  <div class="card-header border-secondary"><h2 class="h6 mb-0">Items</h2></div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-dark table-borderless mb-0">
        <thead>
          <tr>
            <th>Product</th>
            <th>Qty</th>
            <th class="text-end">Total</th>
          </tr>
        </thead>
        <tbody>
          @foreach($order->items as $item)
            <tr>
              <td>{{ $item->product_name }}</td>
              <td>{{ $item->quantity }}</td>
              <td class="text-end">{{ money($item->total) }}</td>
            </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr>
            <th colspan="2" class="text-end">Total</th>
            <th class="text-end">{{ money($order->total) }}</th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>
<div class="card gadget-card border-secondary mb-3">
  <div class="card-header border-secondary"><h2 class="h6 mb-0">Shipping address</h2></div>
  <div class="card-body">
    <p class="mb-0">{!! nl2br(e($order->shipping_address)) !!}</p>
  </div>
</div>
@if($order->notes)
  <div class="card gadget-card border-secondary mb-3">
    <div class="card-header border-secondary"><h2 class="h6 mb-0">Notes</h2></div>
    <div class="card-body">
      <p class="mb-0">{!! nl2br(e($order->notes)) !!}</p>
    </div>
  </div>
@endif
<div class="d-flex flex-wrap gap-2">
  <a href="{{ route('orders.invoice', $order) }}" class="btn btn-primary">Print invoice</a>
  <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">Back to orders</a>
</div>
@endsection
