@php
    $currentCategory = $currentCategory ?? null;
    $shopAction = $currentCategory
        ? route('products.category', $currentCategory)
        : route('products.index');
    $sort = request('sort', 'latest');
    $search = request('search');
    $from = $products->total() ? $products->firstItem() : 0;
    $to = $products->total() ? $products->lastItem() : 0;
@endphp

<aside class="shop-sidebar">
    <h5 class="widget-title mb-3">Categories</h5>
    <ul class="shop-cat-list list-unstyled mb-0">
        <li>
            <a href="{{ route('products.index', array_filter(['search' => $search, 'sort' => $sort !== 'latest' ? $sort : null])) }}"
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
            ])
        @endforeach
    </ul>
</aside>
