<div class="product-item h-100">
    <figure>
        <a href="{{ route('products.show', $product->slug) }}">
            <img
                src="{{ $product->thumbnail_url ?: asset('images/product-placeholder.svg') }}"
                alt="{{ $product->name }}"
                class="tab-image"
            >
        </a>
    </figure>
    <div class="d-flex flex-column text-center">
        <h3 class="fs-6 fw-normal product-title">
            <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark">{{ $product->name }}</a>
        </h3>

        @php
            $compare = compare_price_enabled() ? $product->compare_price : null;
            $final = $product->final_price;
            $hasDiscount = $compare && $compare > $final;
            $discountPercent = $hasDiscount ? round((($compare - $final) / $compare) * 100) : null;
        @endphp
        <div class="d-flex justify-content-center align-items-center gap-2">
            @if($hasDiscount)
                <del>{{ money($compare) }}</del>
            @elseif($product->discount_price)
                <del>{{ money($product->price) }}</del>
            @endif
            <span class="text-dark fw-semibold">{{ money($final) }}</span>
            @if($hasDiscount && $discountPercent > 0)
                <span class="badge border border-dark-subtle rounded-0 fw-normal px-1 fs-7 lh-1 text-body-tertiary">
                    {{ $discountPercent }}% OFF
                </span>
            @endif
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
