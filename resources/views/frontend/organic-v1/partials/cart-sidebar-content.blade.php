<h4 class="d-flex justify-content-between align-items-center mb-3">
  <span class="text-primary">Your cart</span>
  <span class="badge bg-primary rounded-pill" id="sidebar-cart-count">{{ count($cartItems) }}</span>
</h4>

<div id="cart-items-container">
  @if(count($cartItems) > 0)
    <ul class="list-group mb-3 gap-2 cart-sidebar-list">
      @foreach($cartItems as $cartItem)
        @php
          $product = $cartItem['product'];
          $qty = (int) $cartItem['quantity'];
          $max = max(1, (int) $product->stock);
          $thumb = $product->thumbnail_url ?: asset('images/product-placeholder.svg');
        @endphp
        <li class="list-group-item cart-sidebar-item">
          <div class="d-flex gap-3">
            <a href="{{ route('products.show', $product->slug) }}" class="cart-sidebar-thumb flex-shrink-0">
              <img src="{{ $thumb }}" alt="{{ $product->name }}" loading="lazy">
            </a>
            <div class="flex-grow-1 min-w-0">
              <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                <h6 class="my-0 product-title flex-grow-1">
                  <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark">{{ $product->name }}</a>
                </h6>
                <form action="{{ route('cart.remove', $product) }}" method="POST" class="d-inline js-remove-from-cart">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-link text-danger p-0" aria-label="Remove">
                    <i class="fa-solid fa-trash"></i>
                  </button>
                </form>
              </div>
              <div class="d-flex justify-content-between align-items-center gap-2">
                <form action="{{ route('cart.update', $product) }}" method="POST" class="js-update-cart-qty">
                  @csrf
                  @method('PUT')
                  <div class="input-group input-group-sm" style="width: 118px;">
                    <button type="button" class="btn btn-outline-secondary js-qty-minus" aria-label="Decrease">−</button>
                    <input type="number"
                           name="quantity"
                           class="form-control text-center px-1 js-qty-input"
                           value="{{ $qty }}"
                           min="1"
                           max="{{ $max }}"
                           required>
                    <button type="button" class="btn btn-outline-secondary js-qty-plus" aria-label="Increase">+</button>
                  </div>
                </form>
                <span class="text-body-secondary fw-semibold">{{ money($cartItem['subtotal']) }}</span>
              </div>
            </div>
          </div>
        </li>
      @endforeach
      <li class="list-group-item d-flex justify-content-between">
        <span>Total</span>
        <strong id="cart-total">{{ money($cartTotal) }}</strong>
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
  <div class="d-grid gap-2">
    <a href="{{ route('cart.index') }}" class="w-100 btn btn-outline-primary">View cart</a>
    <a href="{{ route('checkout.index') }}" class="w-100 btn btn-primary btn-lg">Continue to checkout</a>
  </div>
@else
  <a href="{{ route('products.index') }}" class="w-100 btn btn-primary btn-lg">Start Shopping</a>
@endif
