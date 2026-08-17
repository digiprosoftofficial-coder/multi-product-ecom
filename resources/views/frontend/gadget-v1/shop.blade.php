<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Shop – {{ config('app.name') }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
  <link rel="stylesheet" href="{{ asset('gadget-v1/style.css') }}">
</head>
<body class="gadget-v1">
  @include('frontend.gadget-v1.partials.header')

  <main class="container py-4">
    <h1 class="h4 mb-4">Shop</h1>

    <div class="row mb-4">
      <div class="col-md-4 mb-2">
        <form method="GET" action="{{ route('products.index') }}" class="d-flex gap-2">
          <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
          <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
        </form>
      </div>
      @isset($categories)
      <div class="col-md-8">
        <div class="d-flex flex-wrap gap-2">
          <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-primary">All</a>
          @foreach($categories as $cat)
          <a href="{{ route('products.category', $cat->slug) }}" class="btn btn-sm btn-outline-primary">{{ $cat->name }}</a>
          @endforeach
        </div>
      </div>
      @endisset
    </div>

    <div class="row">
      {{-- Sidebar filters (UI only) --}}
      <aside class="col-lg-3 mb-4 mb-lg-0 order-2 order-lg-1">
        <div class="card gadget-card border rounded-3 p-3 sticky-top" style="top: 1rem;">
          <h6 class="text-uppercase small fw-bold text-muted mb-3">Filters</h6>

          <div class="filter-group mb-4">
            <h6 class="small fw-semibold mb-2">Brand</h6>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="brand[]" value="apple" id="brand-apple">
              <label class="form-check-label small" for="brand-apple">Apple</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="brand[]" value="samsung" id="brand-samsung">
              <label class="form-check-label small" for="brand-samsung">Samsung</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="brand[]" value="baseus" id="brand-baseus">
              <label class="form-check-label small" for="brand-baseus">Baseus</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="brand[]" value="anker" id="brand-anker">
              <label class="form-check-label small" for="brand-anker">Anker</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="brand[]" value="jbl" id="brand-jbl">
              <label class="form-check-label small" for="brand-jbl">JBL</label>
            </div>
          </div>

          <div class="filter-group">
            <h6 class="small fw-semibold mb-2">Product Type</h6>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="type[]" value="headphone" id="type-headphone">
              <label class="form-check-label small" for="type-headphone">Headphone</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="type[]" value="charger" id="type-charger">
              <label class="form-check-label small" for="type-charger">Charger</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="type[]" value="cable" id="type-cable">
              <label class="form-check-label small" for="type-cable">Cable</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="type[]" value="gadget" id="type-gadget">
              <label class="form-check-label small" for="type-gadget">Gadget</label>
            </div>
          </div>

          <hr class="my-3">
          <button type="button" class="btn btn-outline-secondary btn-sm w-100 gadget-filter-clear">Clear filters</button>
        </div>
      </aside>

      <div class="col-lg-9 order-1 order-lg-2">
        <div class="row g-3">
          @forelse($products ?? [] as $product)
          <div class="col-6 col-md-4 col-lg-4">
            @include('frontend.gadget-v1.partials.product-card', ['product' => $product])
          </div>
          @empty
          <div class="col-12 text-center py-5 text-muted">No products found.</div>
          @endforelse
        </div>

        @isset($products)
        @if($products->hasPages())
        <div class="d-flex justify-content-center mt-4">
          {{ $products->links() }}
        </div>
        @endif
        @endisset
      </div>
    </div>
  </main>

  @include('frontend.gadget-v1.partials.footer')

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script>
    document.querySelectorAll('.gadget-filter-clear').forEach(function (btn) {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.gadget-card input[type="checkbox"]').forEach(function (cb) { cb.checked = false; });
      });
    });
  </script>
</body>
</html>
