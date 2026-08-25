@extends('layouts.app')

@section('title', 'Order placed')

@section('seo')
@include('frontend.partials.seo-meta', ['robots' => 'noindex, nofollow'])
@endsection

@section('content')
<div class="container-lg py-5">
    @include('frontend.components.checkout-steps', ['active' => 'done'])

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5 text-center">
                    <div class="mb-3 text-success">
                        <i class="fas fa-check-circle fa-4x"></i>
                    </div>
                    <h1 class="h3 mb-2">Thank you for your order</h1>
                    <p class="text-muted mb-4">
                        @if($order->payment_method === 'cash_on_delivery')
                            We received your order and will contact you shortly for cash on delivery.
                        @elseif(\App\Support\PaymentMethod::isMobileWallet($order->payment_method))
                            We received your order. We will verify your {{ $order->paymentMethodLabel() }} payment and contact you soon.
                        @else
                            We received your order and will contact you shortly.
                        @endif
                    </p>

                    <div class="bg-light rounded p-3 mb-3 text-start">
                        <div class="small text-muted text-uppercase">Payment method</div>
                        <div class="fw-semibold">{{ $order->paymentMethodLabel() }}</div>
                        @if($order->payment_reference)
                            <div class="small text-muted mt-2">Transaction ID: {{ $order->payment_reference }}</div>
                        @endif
                    </div>

                    <div class="bg-light rounded p-3 mb-4">
                        <div class="text-muted small text-uppercase">Order number</div>
                        <div class="fs-4 fw-semibold">{{ $order->order_number }}</div>
                        <div class="text-muted mt-1">{{ money($order->total) }} &middot; {{ $order->created_at->format('M d, Y H:i') }}</div>
                    </div>

                    <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
                        <a href="{{ $invoiceUrl }}" class="btn btn-primary">
                            <i class="fas fa-print me-1"></i> Print invoice
                        </a>
                        @auth
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-secondary">View order</a>
                        @endauth
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Continue shopping</a>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Order items</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0">
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
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    if (window.StorefrontTracking) {
        window.StorefrontTracking.purchase(@json(\App\Support\Tracking::orderPayload($order), JSON_HEX_TAG | JSON_HEX_APOS | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
</script>
@endpush
