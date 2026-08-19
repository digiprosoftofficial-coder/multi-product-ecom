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
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary rounded-1 btn-cart flex-fill">
                    <i class="fa-solid fa-cart-shopping me-1"></i> Add to Cart
                </button>
                <button type="submit" name="buy_now" value="1" class="btn btn-outline-dark rounded-1 flex-fill">
                    <i class="fa-solid fa-bolt me-1"></i> Order Now
                </button>
            </div>
        </div>
    @else
        <input type="hidden" name="quantity" value="1">
        <div class="d-flex gap-1 mt-2">
            <button type="submit" class="btn btn-primary rounded-1 p-2 fs-7 btn-cart flex-fill">
                <i class="fa-solid fa-cart-shopping me-1"></i>
                Add to Cart
            </button>
            <button type="submit" name="buy_now" value="1" class="btn btn-outline-dark rounded-1 p-2 fs-7 flex-fill">
                Order Now
            </button>
        </div>
    @endif
</form>
