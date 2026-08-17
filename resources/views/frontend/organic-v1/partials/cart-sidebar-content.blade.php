<h4 class="d-flex justify-content-between align-items-center mb-3">
  <span class="text-primary">Your cart</span>
  <span class="badge bg-primary rounded-pill" id="sidebar-cart-count">{{ count($cartItems) }}</span>
</h4>

<div id="cart-items-container">
  @if(count($cartItems) > 0)
    <ul class="list-group mb-3">
      @foreach($cartItems as $cartItem)
        <li class="list-group-item d-flex justify-content-between lh-sm">
          <div class="flex-grow-1">
            <h6 class="my-0">{{ $cartItem['product']->name }}</h6>
            <small class="text-body-secondary">Qty: {{ $cartItem['quantity'] }}</small>
          </div>
          <div class="d-flex align-items-center gap-2">
            <span class="text-body-secondary">${{ number_format($cartItem['subtotal'], 2) }}</span>
            <form action="{{ route('cart.remove', $cartItem['product']) }}" method="POST" class="d-inline js-remove-from-cart">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-link text-danger p-0">
                <i class="fa-solid fa-trash"></i>
              </button>
            </form>
          </div>
        </li>
      @endforeach
      <li class="list-group-item d-flex justify-content-between">
        <span>Total (USD)</span>
        <strong id="cart-total">${{ number_format($cartTotal, 2) }}</strong>
      </li>
    </ul>
  @else
    <div class="text-center py-5">
      <i class="fa-solid fa-cart-shopping fa-3x text-muted mb-3"></i>
      <p class="text-muted">Your cart is empty</p>
    </div>
  @endif
</div>

@if(count($cartItems) > 0)
  <a href="{{ route('cart.index') }}" class="w-100 btn btn-primary btn-lg">Continue to checkout</a>
@else
  <a href="{{ route('products.index') }}" class="w-100 btn btn-primary btn-lg">Start Shopping</a>
@endif
