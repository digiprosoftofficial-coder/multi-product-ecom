@php
    $active = $active ?? 'checkout';
@endphp

<div class="checkout-steps mb-4">
    <span class="checkout-step {{ $active === 'cart' ? 'is-active' : '' }}{{ in_array($active, ['checkout', 'done'], true) ? ' is-complete' : '' }}">
        <i class="fa-solid fa-cart-shopping"></i> Cart
    </span>
    <span class="checkout-step-divider"></span>
    <span class="checkout-step {{ $active === 'checkout' ? 'is-active' : '' }}{{ $active === 'done' ? ' is-complete' : '' }}">
        <i class="fa-solid fa-credit-card"></i> Checkout
    </span>
    <span class="checkout-step-divider"></span>
    <span class="checkout-step {{ $active === 'done' ? 'is-active' : '' }}">
        <i class="fa-solid fa-circle-check"></i> Done
    </span>
</div>

@once
@push('styles')
<style>
    .checkout-steps {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: .75rem;
        flex-wrap: wrap;
    }
    .checkout-step {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        font-size: .92rem;
        font-weight: 600;
        color: #94a3b8;
    }
    .checkout-step.is-active {
        color: #6BB252;
    }
    .checkout-step.is-complete:not(.is-active) {
        color: #86b574;
    }
    .checkout-step-divider {
        width: 36px;
        height: 2px;
        background: #e2e8f0;
        border-radius: 999px;
    }
</style>
@endpush
@endonce
