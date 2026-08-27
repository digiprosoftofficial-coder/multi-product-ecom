@php
    $final = $product->final_price;
    $listPrice = $product->listPriceForDiscount();
    $discountPercent = $product->discountPercent();
@endphp
<div class="product-item h-100">
    <figure class="product-card-media">
        <a href="{{ route('products.show', $product->slug) }}" class="product-card-media-link">
            <img
                src="{{ $product->thumbnail_url ?: asset('images/product-placeholder.svg') }}"
                alt="{{ $product->name }}"
                class="tab-image"
            >
        </a>
        @if($discountPercent)
            <span class="product-discount-badge" aria-label="{{ $discountPercent }}% off">
                −{{ $discountPercent }}%
            </span>
        @endif
    </figure>
    <div class="d-flex flex-column text-center">
        <h3 class="fs-6 fw-normal product-title">
            <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark">{{ $product->name }}</a>
        </h3>

        <div class="product-card-prices">
            @if($listPrice)
                <del class="product-card-price-old">{{ money($listPrice) }}</del>
            @endif
            <span class="product-card-price-current">{{ money($final) }}</span>
        </div>

        <div class="button-area px-2 pb-2 pt-1">
            @if($product->isInStock())
                @include('frontend.partials.add-to-cart-actions', ['product' => $product, 'compact' => true])
            @else
                <button class="btn btn-outline-secondary rounded-1 p-2 fs-7 w-100 mt-2" disabled>Out of Stock</button>
            @endif
        </div>
    </div>
</div>
