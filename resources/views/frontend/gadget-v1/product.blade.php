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
        @if($product->images->count() > 0)
        <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner rounded overflow-hidden border border-secondary">
            @foreach($product->images as $index => $image)
            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
              <img src="{{ $image->image_url }}" class="d-block w-100" alt="{{ $product->name }}" style="aspect-ratio: 1; object-fit: cover;">
            </div>
            @endforeach
          </div>
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
        <div class="bg-dark border border-secondary rounded d-flex align-items-center justify-content-center" style="aspect-ratio: 1;">
          <i class="fas fa-image fa-4x text-secondary"></i>
        </div>
        @endif
      </div>
      <div class="col-md-6">
        <h1 class="h4 mb-2">{{ $product->name }}</h1>
        <p class="text-muted small">SKU: {{ $product->sku }}</p>
        <p class="h5 text-accent mb-3">${{ number_format($product->final_price, 2) }}</p>
        @if($product->hasDescription())
        <div class="product-description text-muted">{!! $product->description_html !!}</div>
        @endif
        <p class="small text-muted">Category: {{ $product->category->name ?? '-' }}</p>
        <p class="small text-muted">Stock: {{ $product->stock }}</p>

        @if($product->stock > 0)
        <form action="{{ route('cart.add', $product) }}" method="POST" class="d-flex align-items-center gap-2 flex-wrap js-add-to-cart">
          @csrf
          <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="form-control" style="width: 80px;">
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
      <h2 class="h6 mb-3">Related</h2>
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
</body>
</html>
