@if($product->free_delivery)
    <span class="product-free-delivery-badge {{ $badgeClass ?? '' }}">
        @if(!empty($showIcon))
            <i class="fa-solid fa-truck" aria-hidden="true"></i>
        @endif
        {{ __('storefront.free_delivery') }}
    </span>
@endif
