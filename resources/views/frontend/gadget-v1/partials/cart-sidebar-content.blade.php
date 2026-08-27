<h6 class="d-flex justify-content-between align-items-center mb-3">
  <span class="text-accent">Your cart</span>
  <span class="badge bg-primary rounded-pill" id="sidebar-cart-count">{{ count($cartItems ?? []) }}</span>
</h6>
<div id="cart-items-container">
  @if(count($cartItems ?? []) > 0)
    <ul class="list-group list-group-flush mb-3">
      @foreach($cartItems as $cartItem)
        <li class="list-group-item bg-transparent border-secondary d-flex justify-content-between lh-sm gap-2 min-w-0">
          <div class="flex-grow-1 min-w-0 overflow-hidden">
            <h6 class="my-0 small text-break" style="overflow-wrap: anywhere; word-break: break-word;">{{ $cartItem['product']->name }}</h6>
            <small class="text-muted">Qty: {{ $cartItem['quantity'] }}</small>
          </div>
          <div class="d-flex align-items-center gap-2 flex-shrink-0">
            <span class="text-muted small">${{ number_format($cartItem['subtotal'], 2) }}</span>
            <form action="{{ route('cart.remove', $cartItem['product']) }}" method="POST" class="d-inline js-remove-from-cart">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-link text-danger p-0"><i class="fas fa-trash small"></i></button>
            </form>
          </div>
        </li>
      @endforeach
      <li class="list-group-item bg-transparent border-secondary d-flex justify-content-between">
        <span>Total</span>
        <strong id="cart-total">${{ number_format($cartTotal ?? 0, 2) }}</strong>
      </li>
    </ul>
  @else
    <div class="text-center py-4">
      <i class="fas fa-shopping-cart fa-2x text-muted mb-2"></i>
      <p class="text-muted small mb-0">Your cart is empty</p>
    </div>
  @endif
</div>
@if(count($cartItems ?? []) > 0)
  <a href="{{ route('cart.index') }}" class="btn btn-primary w-100">View cart</a>
  <a href="{{ route('checkout.index') }}" class="btn btn-outline-primary w-100 mt-2">Checkout</a>
@else
  <a href="{{ route('products.index') }}" class="btn btn-primary w-100">Shop now</a>
@endif
