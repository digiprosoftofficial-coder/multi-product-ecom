@php
    $currentCategory = $currentCategory ?? null;
    $children = $children ?? collect();
    $shopAction = $currentCategory
        ? route('products.category', $currentCategory)
        : route('products.index');
    $sort = request('sort', 'latest');
    $search = request('search');
    $minPrice = request()->has('min_price') && request('min_price') !== '' ? request('min_price') : null;
    $maxPrice = request()->has('max_price') && request('max_price') !== '' ? request('max_price') : null;
    $from = $products->total() ? $products->firstItem() : 0;
    $to = $products->total() ? $products->lastItem() : 0;
    $sortQuery = $sort !== 'latest' ? $sort : null;
    $priceBounds = $priceBounds ?? null;
    $searchClearQuery = array_filter([
        'sort' => $sortQuery,
        'min_price' => $minPrice,
        'max_price' => $maxPrice,
    ], fn ($value) => $value !== null && $value !== '');
    $priceClearQuery = array_filter([
        'search' => $search,
        'sort' => $sortQuery,
    ], fn ($value) => $value !== null && $value !== '');
    $shopQuery = array_filter([
        'search' => $search,
        'sort' => $sortQuery,
        'min_price' => $minPrice,
        'max_price' => $maxPrice,
    ], fn ($value) => $value !== null && $value !== '');
@endphp

@include('frontend.components.page-banner', [
    'page' => 'shop',
    'fallbackTitle' => $title ?? 'Shop',
])

<section class="shop-page py-2">
    <div class="container-lg">
        @include('frontend.components.breadcrumb', ['items' => $breadcrumb])

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <h1 class="section-title mb-0">{{ $title }}</h1>
            <button class="btn btn-outline-dark d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#shopFilters" aria-controls="shopFilters">
                <i class="fa-solid fa-sliders me-1"></i> Filters
            </button>
        </div>

        @if($children->isNotEmpty())
            <div class="shop-subcats d-flex flex-wrap gap-2 mb-4">
                @foreach($children as $child)
                    <a href="{{ route('products.category', $child) }}{{ $shopQuery ? '?'.http_build_query($shopQuery) : '' }}" class="shop-subcat">{{ $child->name }}</a>
                @endforeach
            </div>
        @endif

        <div class="row g-4">
            <div class="col-lg-3 d-none d-lg-block">
                <div class="shop-sidebar-card">
                    @include('frontend.products.partials.shop-sidebar', [
                        'categories' => $categories,
                        'currentCategory' => $currentCategory,
                        'products' => $products,
                        'priceBounds' => $priceBounds,
                    ])
                </div>
            </div>

            <div class="col-lg-9">
                <form method="GET" action="{{ $shopAction }}" class="shop-search search-bar row bg-light p-2 rounded-4 mb-3 mx-0">
                    @if($sortQuery)
                        <input type="hidden" name="sort" value="{{ $sort }}">
                    @endif
                    @if($minPrice !== null)
                        <input type="hidden" name="min_price" value="{{ $minPrice }}">
                    @endif
                    @if($maxPrice !== null)
                        <input type="hidden" name="max_price" value="{{ $maxPrice }}">
                    @endif
                    <div class="col-11">
                        <input type="text" name="search" class="form-control border-0 bg-transparent" placeholder="Search products..." value="{{ $search }}">
                    </div>
                    <div class="col-1 d-flex align-items-center justify-content-end">
                        <button type="submit" class="btn p-0 border-0 bg-transparent" aria-label="Search">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                </form>

                <div class="shop-toolbar d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <span class="text-muted small">
                            @if($products->total())
                                Showing {{ $from }}–{{ $to }} of {{ $products->total() }} products
                            @else
                                No products found
                            @endif
                        </span>
                        @if($search)
                            <a href="{{ $shopAction }}{{ $searchClearQuery ? '?'.http_build_query($searchClearQuery) : '' }}" class="shop-chip">
                                “{{ $search }}”
                                <span aria-hidden="true">&times;</span>
                            </a>
                        @endif
                        @if($minPrice !== null || $maxPrice !== null)
                            <a href="{{ $shopAction }}{{ $priceClearQuery ? '?'.http_build_query($priceClearQuery) : '' }}" class="shop-chip">
                                ৳{{ $minPrice ?? '0' }} – ৳{{ $maxPrice ?? '∞' }}
                                <span aria-hidden="true">&times;</span>
                            </a>
                        @endif
                    </div>
                    <form method="GET" action="{{ $shopAction }}" class="d-flex align-items-center gap-2">
                        @if($search)
                            <input type="hidden" name="search" value="{{ $search }}">
                        @endif
                        @if($minPrice !== null)
                            <input type="hidden" name="min_price" value="{{ $minPrice }}">
                        @endif
                        @if($maxPrice !== null)
                            <input type="hidden" name="max_price" value="{{ $maxPrice }}">
                        @endif
                        <label for="shop-sort" class="small text-muted mb-0">Sort</label>
                        <select id="shop-sort" name="sort" class="form-select form-select-sm shop-sort" onchange="this.form.submit()">
                            <option value="latest" @selected($sort === 'latest')>Latest</option>
                            <option value="price_asc" @selected($sort === 'price_asc')>Price: low to high</option>
                            <option value="price_desc" @selected($sort === 'price_desc')>Price: high to low</option>
                            <option value="name" @selected($sort === 'name')>Name</option>
                        </select>
                    </form>
                </div>

                <div class="product-grid row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 g-3 g-md-4">
                    @forelse($products as $product)
                        <div class="col">
                            @include('frontend.components.product-card', ['product' => $product])
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="shop-empty text-center py-5">
                                <i class="fa-solid fa-bag-shopping fa-2x text-muted mb-3"></i>
                                <p class="text-muted mb-3">No products found{{ $search ? ' for this search.' : '.' }}</p>
                                <a href="{{ route('products.index') }}" class="btn btn-primary rounded-1">Browse all products</a>
                            </div>
                        </div>
                    @endforelse
                </div>

                @if($products->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<div class="offcanvas offcanvas-start" tabindex="-1" id="shopFilters" aria-labelledby="shopFiltersLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="shopFiltersLabel">Filters</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        @include('frontend.products.partials.shop-sidebar', [
            'categories' => $categories,
            'currentCategory' => $currentCategory,
            'products' => $products,
            'priceBounds' => $priceBounds,
        ])
    </div>
</div>

<script>
document.querySelectorAll('.shop-sidebar').forEach(function (sidebar) {
  sidebar.addEventListener('click', function (e) {
    var btn = e.target.closest('.shop-cat-toggle');
    if (!btn) return;
    e.preventDefault();
    e.stopPropagation();
    var li = btn.closest('li');
    if (!li) return;
    var kids = li.querySelector(':scope > .shop-cat-children');
    if (!kids) return;
    var open = btn.getAttribute('aria-expanded') === 'true';
    btn.setAttribute('aria-expanded', open ? 'false' : 'true');
    btn.setAttribute('aria-label', open ? btn.getAttribute('aria-label').replace('Collapse', 'Expand') : btn.getAttribute('aria-label').replace('Expand', 'Collapse'));
    kids.classList.toggle('is-open', !open);
  });
});

document.querySelectorAll('.shop-price-filter').forEach(function (form) {
  var boundMin = Number(form.dataset.min);
  var boundMax = Number(form.dataset.max);
  var minRange = form.querySelector('.shop-price-range-min');
  var maxRange = form.querySelector('.shop-price-range-max');
  var minInput = form.querySelector('input[name="min_price"]');
  var maxInput = form.querySelector('input[name="max_price"]');
  var fill = form.querySelector('.shop-price-range-fill');
  var valueLabel = form.querySelector('.shop-price-value');
  if (!minRange || !maxRange || !minInput || !maxInput) return;

  function clamp(value) {
    return Math.min(boundMax, Math.max(boundMin, Number(value) || boundMin));
  }

  function formatPrice(value) {
    return '৳' + Number(value).toLocaleString('en-US');
  }

  function updateFill(min, max) {
    if (!fill || boundMax === boundMin) {
      if (fill) {
        fill.style.left = '0%';
        fill.style.right = '0%';
      }
      return;
    }
    var span = boundMax - boundMin;
    fill.style.left = ((min - boundMin) / span * 100) + '%';
    fill.style.right = ((boundMax - max) / span * 100) + '%';
  }

  function setValues(min, max, source) {
    min = clamp(min);
    max = clamp(max);
    if (min > max) {
      if (source === 'min') {
        min = max;
      } else {
        max = min;
      }
    }
    minRange.value = min;
    maxRange.value = max;
    minInput.value = min;
    maxInput.value = max;
    if (valueLabel) {
      valueLabel.textContent = formatPrice(min) + ' – ' + formatPrice(max);
    }
    updateFill(min, max);
  }

  minRange.addEventListener('input', function () {
    setValues(minRange.value, maxRange.value, 'min');
  });
  maxRange.addEventListener('input', function () {
    setValues(minRange.value, maxRange.value, 'max');
  });
  minInput.addEventListener('change', function () {
    setValues(minInput.value, maxInput.value, 'min');
  });
  maxInput.addEventListener('change', function () {
    setValues(minInput.value, maxInput.value, 'max');
  });

  setValues(minInput.value, maxInput.value);
});
</script>
