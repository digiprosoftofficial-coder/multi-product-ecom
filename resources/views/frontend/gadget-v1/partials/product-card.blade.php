@php
  $product = $product ?? null;
  if (!$product) return;
  $url = route('products.show', $product->slug);
  $img = $product->images->first();
  $thumb = $product->thumbnail;
  $price = $product->final_price;
  $name = $product->name;
@endphp
<div class="card gadget-card h-100 border-secondary product-card-img">
  <a href="{{ $url }}" class="text-decoration-none text-reset">
    <div class="position-relative overflow-hidden" style="aspect-ratio: 1;">
      @if($img)
        <img src="{{ asset('uploads/products/' . $img->filename) }}" class="card-img-top object-fit-cover" alt="{{ $name }}" style="height: 100%; object-fit: cover;">
      @elseif($thumb)
        <img src="{{ asset('uploads/products/thumbnails/' . $thumb) }}" class="card-img-top object-fit-cover" alt="{{ $name }}" style="height: 100%; object-fit: cover;">
      @else
        <div class="d-flex align-items-center justify-content-center bg-dark h-100">
          <i class="fas fa-image fa-3x text-secondary"></i>
        </div>
      @endif
      <span class="position-absolute top-0 end-0 m-2 badge bg-primary">${{ number_format($price, 2) }}</span>
    </div>
    <div class="card-body">
      <h6 class="card-title text-truncate">{{ $name }}</h6>
      <p class="card-text text-muted small mb-0">SKU: {{ $product->sku }}</p>
    </div>
  </a>
  <div class="card-footer border-secondary bg-transparent">
    @if($product->stock > 0)
      <form action="{{ route('cart.add', $product) }}" method="POST" class="js-add-to-cart">
        @csrf
        <input type="hidden" name="quantity" value="1">
        <div class="product-card-actions">
          <button type="submit" class="btn btn-sm btn-cart-action">
            <i class="fa-solid fa-cart-shopping"></i>
            <span>Add to Cart</span>
          </button>
          <button type="submit" name="buy_now" value="1" class="btn btn-sm btn-order-action">
            <i class="fa-solid fa-bolt"></i>
            <span>Order Now</span>
          </button>
        </div>
      </form>
    @else
      <button class="btn btn-sm btn-secondary w-100" disabled>Out of Stock</button>
    @endif
  </div>
</div>
