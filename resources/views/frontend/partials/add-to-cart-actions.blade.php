@php
    $showQty = $showQty ?? false;
    $compact = $compact ?? false;
    $maxQty = max(1, (int) $product->stock);
@endphp
<form action="{{ route('cart.add', $product) }}" method="POST"
      class="{{ $compact ? 'w-100' : 'product-order-actions' }} js-add-to-cart"
      data-product-id="{{ $product->id }}"
      data-product-name="{{ $product->name }}"
      data-product-image="{{ $product->thumbnail_url }}">
    @csrf
    @if($showQty)
        <div class="product-order-qty-row">
            <div class="cart-qty-control product-order-qty-control" role="group" aria-label="Quantity">
                <button type="button" class="cart-qty-btn js-product-qty-minus" aria-label="Decrease">−</button>
                <input type="number"
                       name="quantity"
                       class="cart-qty-input js-product-qty-input"
                       value="1"
                       min="1"
                       max="{{ $maxQty }}"
                       required>
                <button type="button" class="cart-qty-btn js-product-qty-plus" aria-label="Increase">+</button>
            </div>
            <div class="product-card-actions product-card-actions--lg product-order-buttons">
                <button type="submit" class="btn btn-cart-action">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span>Add to Cart</span>
                </button>
                <button type="submit" name="buy_now" value="1" class="btn btn-order-action">
                    <i class="fa-solid fa-bolt"></i>
                    <span>Order Now</span>
                </button>
            </div>
        </div>
    @else
        <input type="hidden" name="quantity" value="1">
        <div class="product-card-actions">
            <button type="submit" class="btn btn-cart-action">
                <i class="fa-solid fa-cart-shopping"></i>
                <span>Add to Cart</span>
            </button>
            <button type="submit" name="buy_now" value="1" class="btn btn-order-action">
                <i class="fa-solid fa-bolt"></i>
                <span>Order Now</span>
            </button>
        </div>
    @endif
</form>
