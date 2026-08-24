<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Checkout – {{ config('app.name') }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
  <link rel="stylesheet" href="{{ asset('gadget-v1/style.css') }}">
</head>
<body class="gadget-v1">
  @include('frontend.gadget-v1.partials.header')

  <main class="container py-4">
    <h1 class="h4 mb-4">Checkout</h1>

    <form action="{{ route('checkout.store') }}" method="POST">
      @csrf
      <div class="row">
        <div class="col-lg-8 mb-4">
          <div class="card gadget-card border-secondary">
            <div class="card-header border-secondary">
              <h5 class="mb-0">Shipping</h5>
            </div>
            <div class="card-body">
              <div class="mb-3">
                <label for="customer_name" class="form-label">Full name <span class="text-danger">*</span></label>
                <input type="text" class="form-control bg-dark border-secondary text-white" id="customer_name" name="customer_name" value="{{ old('customer_name', Auth::check() ? Auth::user()->name : '') }}" required>
                @error('customer_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
              </div>
              <div class="mb-3">
                <label for="customer_email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control bg-dark border-secondary text-white" id="customer_email" name="customer_email" value="{{ old('customer_email', Auth::check() ? Auth::user()->email : '') }}" required>
                @error('customer_email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
              </div>
              <div class="mb-3">
                <label for="customer_phone" class="form-label">Phone <span class="text-danger">*</span></label>
                <input type="text" class="form-control bg-dark border-secondary text-white" id="customer_phone" name="customer_phone" value="{{ old('customer_phone', Auth::check() ? Auth::user()->phone : '') }}" placeholder="01XXXXXXXXX" required>
                @error('customer_phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
              </div>
              <div class="mb-3">
                <label for="shipping_address" class="form-label">Address <span class="text-danger">*</span></label>
                <textarea class="form-control bg-dark border-secondary text-white" id="shipping_address" name="shipping_address" rows="2" required>{{ old('shipping_address') }}</textarea>
                @error('shipping_address')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
              </div>
              <div class="mb-0">
                <label for="notes" class="form-label">Notes</label>
                <textarea class="form-control bg-dark border-secondary text-white" id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="card gadget-card border-secondary sticky-top">
            <div class="card-header border-secondary">Order summary</div>
            <div class="card-body">
              @foreach($cartItems ?? [] as $item)
              <div class="d-flex justify-content-between small mb-2">
                <span>{{ $item['product']->name }} × {{ $item['quantity'] }}</span>
                <span>${{ number_format($item['total'] ?? ($item['product']->final_price * $item['quantity']), 2) }}</span>
              </div>
              @endforeach
              <hr class="border-secondary">
              <div class="d-flex justify-content-between"><span>Subtotal</span><span>${{ number_format($subtotal ?? 0, 2) }}</span></div>
              @if(($tax ?? 0) > 0)<div class="d-flex justify-content-between small text-muted"><span>Tax</span><span>${{ number_format($tax, 2) }}</span></div>@endif
              @if(($vat ?? 0) > 0)<div class="d-flex justify-content-between small text-muted"><span>VAT</span><span>${{ number_format($vat, 2) }}</span></div>@endif
              <hr class="border-secondary">
              <div class="d-flex justify-content-between fw-bold"><span>Total</span><span>${{ number_format($total ?? 0, 2) }}</span></div>
            </div>
            <div class="card-footer border-secondary">
              <button type="submit" class="btn btn-primary w-100">Place order</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </main>

  @include('frontend.gadget-v1.partials.footer')

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script src="{{ asset('js/bangladesh-phone.js') }}"></script>
</body>
</html>
