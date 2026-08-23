@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
@include('frontend.components.page-banner', [
    'page' => 'checkout',
    'fallbackTitle' => 'Checkout',
])

<div class="container-lg py-5">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Shipping Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('checkout.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="customer_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('customer_name') is-invalid @enderror" 
                                   id="customer_name" name="customer_name" 
                                   value="{{ old('customer_name', Auth::check() ? Auth::user()->name : '') }}" required>
                            @error('customer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="customer_email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('customer_email') is-invalid @enderror" 
                                   id="customer_email" name="customer_email" 
                                   value="{{ old('customer_email', Auth::check() ? Auth::user()->email : '') }}" required>
                            @error('customer_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="customer_phone" class="form-label">Phone</label>
                            <input type="text" class="form-control @error('customer_phone') is-invalid @enderror"
                                   id="customer_phone" name="customer_phone"
                                   value="{{ old('customer_phone', Auth::check() ? Auth::user()->phone : '') }}">
                            @error('customer_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="shipping_address" class="form-label">Shipping Address <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('shipping_address') is-invalid @enderror" 
                                      id="shipping_address" name="shipping_address" rows="3" required>{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Order Notes (Optional)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <strong>Payment Method:</strong> Cash on Delivery
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">Place Order</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Order Summary</h5>
                </div>
                <div class="card-body">
                    @foreach($cartItems as $item)
                        <div class="d-flex justify-content-between mb-2">
                            <div>
                                <strong>{{ $item['product']->name }}</strong>
                                <br>
                                <small class="text-muted">Qty: {{ $item['quantity'] }}</small>
                            </div>
                            <div class="text-end">
                                {{ money($item['total']) }}
                            </div>
                        </div>
                    @endforeach

                    <hr>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>{{ money($subtotal) }}</span>
                    </div>

                    @if($tax > 0)
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax:</span>
                            <span>{{ money($tax) }}</span>
                        </div>
                    @endif

                    @if($vat > 0)
                        <div class="d-flex justify-content-between mb-2">
                            <span>VAT:</span>
                            <span>{{ money($vat) }}</span>
                        </div>
                    @endif

                    <hr>

                    <div class="d-flex justify-content-between">
                        <strong>Total:</strong>
                        <strong class="fs-5">{{ money($total) }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

