@php
    $currentCategory = $currentCategory ?? null;
    $shopAction = $currentCategory
        ? route('products.category', $currentCategory)
        : route('products.index');
    $sort = request('sort', 'latest');
    $search = request('search');
    $minPrice = request()->has('min_price') && request('min_price') !== '' ? request('min_price') : null;
    $maxPrice = request()->has('max_price') && request('max_price') !== '' ? request('max_price') : null;
    $priceBounds = $priceBounds ?? null;
    $showPriceFilter = setting('active_frontend_theme', 'organic-v1') === 'organic-v1'
        && is_array($priceBounds)
        && (int) ($priceBounds['max'] ?? 0) > 0;
    $boundMin = (int) ($priceBounds['min'] ?? 0);
    $boundMax = (int) ($priceBounds['max'] ?? 0);
    $selectedMin = $minPrice !== null ? (int) $minPrice : $boundMin;
    $selectedMax = $maxPrice !== null ? (int) $maxPrice : $boundMax;
    if ($selectedMin < $boundMin) {
        $selectedMin = $boundMin;
    }
    if ($selectedMax > $boundMax || $selectedMax < $selectedMin) {
        $selectedMax = $boundMax;
    }
@endphp

<aside class="shop-sidebar">
    @if($showPriceFilter)
        <form method="GET" action="{{ $shopAction }}" class="shop-price-filter shop-price-filter-top" data-min="{{ $boundMin }}" data-max="{{ $boundMax }}">
            @if($search)
                <input type="hidden" name="search" value="{{ $search }}">
            @endif
            @if($sort !== 'latest')
                <input type="hidden" name="sort" value="{{ $sort }}">
            @endif

            <h5 class="widget-title mb-2">Price</h5>
            <p class="shop-price-value mb-2" aria-live="polite">৳{{ number_format($selectedMin) }} – ৳{{ number_format($selectedMax) }}</p>
            <div class="shop-price-slider">
                <div class="shop-price-track">
                    <div class="shop-price-range-fill"></div>
                </div>
                <input type="range"
                       class="shop-price-range shop-price-range-min"
                       min="{{ $boundMin }}"
                       max="{{ $boundMax }}"
                       step="1"
                       value="{{ $selectedMin }}"
                       aria-label="Minimum price">
                <input type="range"
                       class="shop-price-range shop-price-range-max"
                       min="{{ $boundMin }}"
                       max="{{ $boundMax }}"
                       step="1"
                       value="{{ $selectedMax }}"
                       aria-label="Maximum price">
            </div>
            <div class="shop-price-inputs">
                <label class="shop-price-box">
                    <span>৳</span>
                    <input type="number"
                           name="min_price"
                           min="{{ $boundMin }}"
                           max="{{ $boundMax }}"
                           value="{{ $selectedMin }}"
                           aria-label="Minimum price">
                </label>
                <span class="shop-price-dash" aria-hidden="true">–</span>
                <label class="shop-price-box">
                    <span>৳</span>
                    <input type="number"
                           name="max_price"
                           min="{{ $boundMin }}"
                           max="{{ $boundMax }}"
                           value="{{ $selectedMax }}"
                           aria-label="Maximum price">
                </label>
            </div>
            <button type="submit" class="btn shop-price-apply w-100">Apply</button>
        </form>
    @endif

    <h5 class="widget-title mb-3">Categories</h5>
    <ul class="shop-cat-list list-unstyled mb-0">
        <li>
            <a href="{{ route('products.index', array_filter(['search' => $search, 'sort' => $sort !== 'latest' ? $sort : null, 'min_price' => $minPrice, 'max_price' => $maxPrice], fn ($value) => $value !== null && $value !== '')) }}"
               class="shop-cat-link {{ ! $currentCategory ? 'is-active' : '' }}">
                All products
            </a>
        </li>
        @foreach($categories as $navCategory)
            @include('frontend.products.partials.category-tree', [
                'category' => $navCategory,
                'currentCategory' => $currentCategory,
                'search' => $search,
                'sort' => $sort,
                'min_price' => $minPrice,
                'max_price' => $maxPrice,
            ])
        @endforeach
    </ul>
</aside>
