<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Cart – {{ config('app.name') }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
  <link rel="stylesheet" href="{{ asset('gadget-v1/style.css') }}">
</head>
<body class="gadget-v1">
  @include('frontend.gadget-v1.partials.header')

  <main class="container py-4">
    <h1 class="h4 mb-4">Shopping cart</h1>

    @if(count($cartItems ?? []) > 0)
    <div class="table-responsive">
      <table class="table table-dark table-border-secondary align-middle">
        <thead>
          <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Subtotal</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @foreach($cartItems as $item)
          <tr>
            <td>
              <div class="d-flex align-items-center gap-2">
                @if($item['product']->thumbnail ?? null)
                <img src="{{ asset('uploads/products/thumbnails/' . $item['product']->thumbnail) }}" alt="" style="width: 50px; height: 50px; object-fit: cover;" class="rounded">
                @else
                <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;"><i class="fas fa-image text-muted"></i></div>
                @endif
                <span>{{ $item['product']->name }}</span>
              </div>
            </td>
            <td>${{ number_format($item['product']->final_price, 2) }}</td>
            <td>
              <form action="{{ route('cart.update', $item['product']) }}" method="POST" class="d-inline">
                @csrf
                @method('PUT')
                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $item['product']->stock }}" class="form-control form-control-sm d-inline-block" style="width: 70px;" onchange="this.form.submit()">
              </form>
            </td>
            <td>${{ number_format($item['subtotal'] ?? ($item['product']->final_price * $item['quantity']), 2) }}</td>
            <td>
              <form action="{{ route('cart.remove', $item['product']) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
      <a href="{{ route('products.index') }}" class="btn btn-outline-primary">Continue shopping</a>
      <div class="fw-bold">Total: ${{ number_format($total ?? 0, 2) }}</div>
      <a href="{{ route('checkout.index') }}" class="btn btn-primary">Checkout</a>
    </div>
    <form action="{{ route('cart.clear') }}" method="POST" class="mt-2">
      @csrf
      @method('DELETE')
      <button type="submit" class="btn btn-link text-muted small p-0" onclick="return confirm('Clear cart?')">Clear cart</button>
    </form>
    @else
    <div class="text-center py-5">
      <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
      <p class="text-muted">Your cart is empty.</p>
      <a href="{{ route('products.index') }}" class="btn btn-primary">Shop</a>
    </div>
    @endif
  </main>

  @include('frontend.gadget-v1.partials.footer')

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
