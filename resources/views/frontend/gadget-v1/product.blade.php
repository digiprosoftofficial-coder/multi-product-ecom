<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $product->seoTitle() }} – {{ config('app.name') }}</title>
  @include('frontend.partials.seo-meta', [
      'title' => $product->seoTitle(),
      'description' => $product->seoDescription(),
      'url' => route('products.show', $product),
      'type' => 'product',
      'image' => $product->seoImageUrl(),
      'price' => $product->final_price,
      'jsonLd' => $product->jsonLd(),
  ])
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
  <link rel="stylesheet" href="{{ asset('gadget-v1/style.css') }}">
  <style>
    .product-description h2 { font-size: 1.25rem; margin: 1rem 0 .5rem; }
    .product-description h3 { font-size: 1.1rem; margin: .85rem 0 .4rem; }
    .product-description ul, .product-description ol { padding-left: 1.25rem; }
    .product-description table { width: 100%; border-collapse: collapse; }
    .product-description th, .product-description td { border: 1px solid #444; padding: .4rem .6rem; }
    .product-discount-badge {
      position: absolute; top: 10px; right: 10px; z-index: 2;
      display: inline-flex; align-items: center; justify-content: center;
      min-width: 2.6rem; padding: .35rem .55rem; border-radius: 999px;
      background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
      color: #fff; font-size: .78rem; font-weight: 700; line-height: 1;
      box-shadow: 0 6px 16px rgba(220, 38, 38, .35); pointer-events: none;
    }
    .product-discount-badge--lg { top: 14px; right: 14px; min-width: 3.25rem; padding: .5rem .75rem; font-size: .95rem; }
    .product-discount-badge--inline { position: static; pointer-events: auto; box-shadow: none; font-size: .85rem; padding: .4rem .7rem; }
    .product-detail-pricing { display: flex; flex-wrap: wrap; align-items: flex-end; gap: .75rem 1rem; }
    .product-detail-prices { display: flex; flex-direction: column; gap: .2rem; line-height: 1.25; }
    .product-detail-price-old { color: #94a3b8; font-size: 1.05rem; }
    .product-detail-price-current { font-weight: 700; font-size: 1.5rem; }
    .cart-qty-control { display: inline-flex; align-items: center; border: 1px solid #444; border-radius: 10px; overflow: hidden; background: #111; }
    .cart-qty-btn { width: 36px; height: 36px; border: 0; background: #1a1a1a; color: #eee; font-size: 1.05rem; line-height: 1; display: inline-flex; align-items: center; justify-content: center; padding: 0; cursor: pointer; }
    .cart-qty-input { width: 48px; height: 36px; border: 0; border-left: 1px solid #444; border-right: 1px solid #444; text-align: center; font-size: .95rem; font-weight: 600; color: #fff; background: #111; padding: 0; -moz-appearance: textfield; appearance: textfield; }
    .cart-qty-input::-webkit-outer-spin-button, .cart-qty-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
  </style>
</head>
<body class="gadget-v1">
  @include('frontend.gadget-v1.partials.header')

  <main class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
      <ol class="breadcrumb text-muted">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="link-accent">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="link-accent">Shop</a></li>
        <li class="breadcrumb-item active">{{ $product->name }}</li>
      </ol>
    </nav>

    <div class="row">
      <div class="col-md-6 mb-4">
        @php $discountPercent = $product->discountPercent(); @endphp
        @if($product->images->count() > 0)
        <div id="productCarousel" class="carousel slide position-relative" data-bs-ride="carousel">
          <div class="carousel-inner rounded overflow-hidden border border-secondary">
            @foreach($product->images as $index => $image)
            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
              <img src="{{ $image->image_url }}" class="d-block w-100" alt="{{ $product->name }}" style="aspect-ratio: 1; object-fit: cover;">
            </div>
            @endforeach
          </div>
          @if($discountPercent)
            <span class="product-discount-badge product-discount-badge--lg">−{{ $discountPercent }}%</span>
          @endif
          @if($product->images->count() > 1)
          <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
          </button>
          @endif
        </div>
        @else
        <div class="bg-dark border border-secondary rounded d-flex align-items-center justify-content-center position-relative" style="aspect-ratio: 1;">
          <i class="fas fa-image fa-4x text-secondary"></i>
          @if($discountPercent)
            <span class="product-discount-badge product-discount-badge--lg">−{{ $discountPercent }}%</span>
          @endif
        </div>
        @endif
      </div>
      <div class="col-md-6">
        <h1 class="h4 mb-2">{{ $product->name }}</h1>
        <p class="text-muted small">SKU: {{ $product->sku }}</p>
        @php $listPrice = $product->listPriceForDiscount(); @endphp
        <div class="product-detail-pricing mb-3">
          <div class="product-detail-prices">
            @if($listPrice)
              <del class="product-detail-price-old">{{ money($listPrice) }}</del>
            @endif
            <p class="product-detail-price-current text-accent mb-0">{{ money($product->final_price) }}</p>
          </div>
          @if($discountPercent)
            <span class="product-discount-badge product-discount-badge--inline">−{{ $discountPercent }}% OFF</span>
          @endif
        </div>
        @if($product->hasDescription())
        <div class="product-description text-muted">{!! $product->description_html !!}</div>
        @endif
        <p class="small text-muted">Category: {{ $product->category->name ?? '-' }}</p>
        <p class="small text-muted">Stock: {{ $product->stock }}</p>

        @if($product->stock > 0)
        <form action="{{ route('cart.add', $product) }}" method="POST" class="d-flex align-items-center gap-2 flex-wrap js-add-to-cart">
          @csrf
          <div class="cart-qty-control product-order-qty-control" role="group" aria-label="Quantity">
            <button type="button" class="cart-qty-btn js-product-qty-minus" aria-label="Decrease">−</button>
            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="cart-qty-input js-product-qty-input" required>
            <button type="button" class="cart-qty-btn js-product-qty-plus" aria-label="Increase">+</button>
          </div>
          <div class="product-card-actions product-card-actions--lg flex-grow-1">
            <button type="submit" class="btn btn-cart-action">
              <i class="fa-solid fa-cart-shopping"></i>
              <span>Add to Cart</span>
            </button>
            <button type="submit" name="buy_now" value="1" class="btn btn-order-action">
              <i class="fa-solid fa-bolt"></i>
              <span>Order Now</span>
            </button>
          </div>
        </form>
        @else
        <button class="btn btn-secondary" disabled>Out of Stock</button>
        @endif
      </div>
    </div>

    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
    <section class="mt-5 pt-4 border-top border-secondary">
      <h2 class="h6 mb-3">You may also like</h2>
      <div class="row g-3">
        @foreach($relatedProducts as $p)
        <div class="col-6 col-md-3">
          @include('frontend.gadget-v1.partials.product-card', ['product' => $p])
        </div>
        @endforeach
      </div>
    </section>
    @endif
  </main>

  @include('frontend.gadget-v1.partials.footer')

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script>
    document.querySelectorAll('.js-add-to-cart').forEach(function (form) {
      var input = form.querySelector('.js-product-qty-input');
      var minus = form.querySelector('.js-product-qty-minus');
      var plus = form.querySelector('.js-product-qty-plus');
      if (!input || !minus || !plus) return;
      function clamp() {
        var min = Number(input.min || 1);
        var max = Number(input.max || 99);
        var value = Number(input.value || 1);
        if (Number.isNaN(value)) value = min;
        input.value = Math.max(min, Math.min(max, value));
      }
      minus.addEventListener('click', function () { input.value = Number(input.value || 1) - 1; clamp(); });
      plus.addEventListener('click', function () { input.value = Number(input.value || 1) + 1; clamp(); });
      input.addEventListener('change', clamp);
    });
  </script>
</body>
</html>
