@extends('layouts.app')

@section('title', $product->seoTitle().' – '.site_name())

@php
    $crumbs = [
        ['name' => 'Home', 'url' => route('home')],
        ['name' => 'Shop', 'url' => route('products.index')],
    ];
    if ($product->category) {
        $nodes = [];
        $node = $product->category;
        while ($node) {
            array_unshift($nodes, $node);
            $node = $node->parent;
        }
        foreach ($nodes as $node) {
            $crumbs[] = [
                'name' => $node->name,
                'url' => route('products.category', $node),
            ];
        }
    }
    $crumbs[] = ['name' => $product->name, 'url' => null];
@endphp

@section('seo')
@include('frontend.partials.seo-meta', [
    'title' => $product->seoTitle(),
    'description' => $product->seoDescription(),
    'url' => route('products.show', $product),
    'type' => 'product',
    'image' => $product->seoImageUrl(),
    'price' => $product->final_price,
    'jsonLd' => array_filter([
        $product->jsonLd(),
        \App\Support\Seo::breadcrumbJsonLd($crumbs),
    ]),
])
@endsection

@section('content')
<section class="product-detail-page py-4 py-md-5">
    <div class="container-lg">
        @include('frontend.components.breadcrumb', ['items' => $crumbs, 'variant' => 'modern'])

        <div class="row g-5">
            <div class="col-md-6">
                @include('frontend.products.partials.product-gallery', ['product' => $product])
            </div>

            <div class="col-md-6">
                <h1 class="product-detail-title mb-2">{{ $product->name }}</h1>
                <p class="text-muted mb-2">SKU: {{ $product->sku }}</p>

                @php
                    $listPrice = $product->listPriceForDiscount();
                    $discountPercent = $product->discountPercent();
                @endphp
                <div class="product-detail-pricing mb-4">
                    <div class="product-detail-prices">
                        @if($listPrice)
                            <del class="product-detail-price-old">{{ money($listPrice) }}</del>
                        @endif
                        <span class="product-detail-price-current">{{ money($product->final_price) }}</span>
                    </div>
                    @if($discountPercent)
                        <span class="product-discount-badge product-discount-badge--inline" aria-label="{{ $discountPercent }}% off">
                            −{{ $discountPercent }}% OFF
                        </span>
                    @endif
                </div>

                @if($product->hasDescription())
                    <div class="mb-4 product-description">
                        {!! $product->description_html !!}
                    </div>
                @endif

                @if($product->isInStock())
                    @include('frontend.partials.add-to-cart-actions', ['product' => $product, 'showQty' => true])
                @else
                    <button class="btn btn-secondary btn-lg" disabled>Out of Stock</button>
                @endif
            </div>
        </div>

        @if($relatedProducts->count() > 0)
            <div class="mt-5 pt-4">
                <div class="section-header d-flex flex-wrap align-items-center justify-content-between mb-3">
                    <h2 class="section-title mb-0">You may also like</h2>
                </div>
                <div class="product-grid row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                    @foreach($relatedProducts as $relatedProduct)
                        <div class="col">
                            @include('frontend.components.product-card', ['product' => $relatedProduct])
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@endsection

@push('styles')
<style>
    .product-detail-title {
        font-size: clamp(1.6rem, 2.8vw, 2.15rem);
        font-weight: 700;
        line-height: 1.25;
        color: #0f172a;
    }
</style>
@endpush

@push('scripts')
<script>
    if (window.StorefrontTracking) {
        window.StorefrontTracking.viewContent(@json(\App\Support\Tracking::productPayload($product), JSON_HEX_TAG | JSON_HEX_APOS | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    document.querySelectorAll('.js-add-to-cart').forEach(function (form) {
        const input = form.querySelector('.js-product-qty-input');
        const minus = form.querySelector('.js-product-qty-minus');
        const plus = form.querySelector('.js-product-qty-plus');
        if (!input || !minus || !plus) return;

        function clamp() {
            const min = Number(input.min || 1);
            const max = Number(input.max || 99);
            let value = Number(input.value || 1);
            if (Number.isNaN(value)) value = min;
            value = Math.max(min, Math.min(max, value));
            input.value = value;
        }

        minus.addEventListener('click', function () {
            input.value = Number(input.value || 1) - 1;
            clamp();
        });
        plus.addEventListener('click', function () {
            input.value = Number(input.value || 1) + 1;
            clamp();
        });
        input.addEventListener('change', clamp);
    });
</script>
@endpush
