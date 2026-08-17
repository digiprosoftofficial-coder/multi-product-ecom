<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name') }} – Gadgets & Accessories</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
  <link rel="stylesheet" href="{{ asset('gadget-v1/style.css') }}">
</head>
<body class="gadget-v1">
  @include('frontend.gadget-v1.partials.header')

  <main class="container py-4">
    <section class="text-center py-5 mb-4">
      <h1 class="display-5 fw-bold mb-3">Accessories & <span class="text-accent">Gadgets</span></h1>
      <p class="lead text-muted">Tech-focused store for the modern life.</p>
      <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">Shop now</a>
    </section>

    @isset($categories)
    <section class="py-4">
      <h2 class="h5 mb-3">Categories</h2>
      <div class="row g-3">
        @foreach($categories->take(6) as $cat)
        <div class="col-6 col-md-4 col-lg-2">
          <a href="{{ route('products.category', $cat->slug) }}" class="card gadget-card text-decoration-none text-reset h-100">
            <div class="card-body text-center py-4">
              <i class="fas fa-microchip fa-2x text-accent mb-2"></i>
              <h6 class="card-title small mb-0">{{ $cat->name }}</h6>
            </div>
          </a>
        </div>
        @endforeach
      </div>
    </section>
    @endisset

    @isset($bestSellingProducts)
    <section class="py-4">
      <h2 class="h5 mb-3">Featured</h2>
      <div class="row g-3">
        @foreach($bestSellingProducts->take(4) as $product)
        <div class="col-6 col-lg-3">
          @include('frontend.gadget-v1.partials.product-card', ['product' => $product])
        </div>
        @endforeach
      </div>
      <div class="text-center mt-4">
        <a href="{{ route('products.index') }}" class="btn btn-outline-primary">View all</a>
      </div>
    </section>
    @endisset
  </main>

  @include('frontend.gadget-v1.partials.footer')

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
