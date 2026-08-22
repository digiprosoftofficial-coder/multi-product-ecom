@php
    $showQty = $showQty ?? false;
    $compact = $compact ?? false;
@endphp
<form action="{{ route('cart.add', $product) }}" method="POST"
      class="{{ $compact ? 'w-100' : 'row g-2 align-items-center' }} js-add-to-cart"
      data-product-id="{{ $product->id }}"
      data-product-name="{{ $product->name }}"
      data-product-image="{{ $product->thumbnail_url }}">
    @csrf
    @if($showQty)
        <div class="col-3 col-sm-2">
            <input type="number" name="quantity" class="form-control border-dark-subtle quantity" value="1" min="1" max="{{ $product->stock }}" required>
        </div>
        <div class="col-9 col-sm-10">
            <div class="product-card-actions product-card-actions--lg">
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
