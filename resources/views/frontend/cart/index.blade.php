@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('seo')
@include('frontend.partials.seo-meta', ['robots' => 'noindex, nofollow'])
@endsection

@section('content')
@include('frontend.components.page-banner', [
    'page' => 'cart',
    'fallbackTitle' => 'Shopping Cart',
])

<div class="container-lg py-4 py-lg-5 cart-page">
    @include('frontend.components.checkout-steps', ['active' => 'cart'])

    @if(count($cartItems) > 0)
        {{-- Mobile / tablet cards --}}
        <div class="cart-mobile d-lg-none">
            @foreach($cartItems as $item)
                @php
                    $product = $item['product'];
                    $thumb = $product->thumbnail_url ?: asset('images/product-placeholder.svg');
                    $max = max(1, (int) $product->stock);
                @endphp
                <div class="cart-mobile-card">
                    <div class="cart-mobile-card__main">
                        <a href="{{ route('products.show', $product->slug) }}" class="cart-mobile-card__thumb">
                            <img src="{{ $thumb }}" alt="{{ $product->name }}" loading="lazy">
                        </a>
                        <div class="cart-mobile-card__info min-w-0">
                            <div class="d-flex justify-content-between gap-2 align-items-start">
                                <div class="min-w-0">
                                    <a href="{{ route('products.show', $product->slug) }}" class="cart-mobile-card__name">{{ $product->name }}</a>
                                    @if($product->sku)
                                        <div class="cart-mobile-card__sku">SKU: {{ $product->sku }}</div>
                                    @endif
                                </div>
                                <form action="{{ route('cart.remove', $product) }}" method="POST" class="flex-shrink-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-link text-danger p-0" aria-label="Remove" onclick="return confirm('Remove this item?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                            <div class="cart-mobile-card__price">{{ money($product->final_price) }}</div>
                            <div class="d-flex justify-content-between align-items-center gap-2 mt-2">
                                <form action="{{ route('cart.update', $product) }}" method="POST" class="cart-qty-form">
                                    @csrf
                                    @method('PUT')
                                    <div class="cart-qty-control">
                                        <button type="button" class="cart-qty-btn js-cart-qty-minus" aria-label="Decrease">−</button>
                                        <input type="number"
                                               name="quantity"
                                               class="cart-qty-input js-cart-qty-input"
                                               value="{{ $item['quantity'] }}"
                                               min="1"
                                               max="{{ $max }}"
                                               required>
                                        <button type="button" class="cart-qty-btn js-cart-qty-plus" aria-label="Increase">+</button>
                                    </div>
                                </form>
                                <div class="cart-mobile-card__subtotal">{{ money($item['subtotal']) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="cart-mobile-total">
                <span>Total</span>
                <strong>{{ money($total) }}</strong>
            </div>
        </div>

        {{-- Desktop table --}}
        <div class="table-responsive d-none d-lg-block">
            <table class="table cart-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cartItems as $item)
                        @php
                            $product = $item['product'];
                            $thumb = $product->thumbnail_url ?: asset('images/product-placeholder.svg');
                            $max = max(1, (int) $product->stock);
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $thumb }}"
                                         alt="{{ $product->name }}"
                                         class="cart-table-thumb">
                                    <div>
                                        <h6 class="mb-1">{{ $product->name }}</h6>
                                        @if($product->sku)
                                            <small class="text-muted">SKU: {{ $product->sku }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ money($product->final_price) }}</td>
                            <td>
                                <form action="{{ route('cart.update', $product) }}" method="POST" class="cart-qty-form">
                                    @csrf
                                    @method('PUT')
                                    <div class="cart-qty-control">
                                        <button type="button" class="cart-qty-btn js-cart-qty-minus" aria-label="Decrease">−</button>
                                        <input type="number"
                                               name="quantity"
                                               class="cart-qty-input js-cart-qty-input"
                                               value="{{ $item['quantity'] }}"
                                               min="1"
                                               max="{{ $max }}"
                                               required>
                                        <button type="button" class="cart-qty-btn js-cart-qty-plus" aria-label="Increase">+</button>
                                    </div>
                                </form>
                            </td>
                            <td class="fw-semibold">{{ money($item['subtotal']) }}</td>
                            <td class="text-end">
                                <form action="{{ route('cart.remove', $product) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Remove this item?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end border-0 pt-3"><strong>Total</strong></td>
                        <td class="border-0 pt-3"><strong class="text-success fs-5">{{ money($total) }}</strong></td>
                        <td class="border-0"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="cart-actions mt-4">
            <a href="{{ route('products.index') }}" class="btn btn-outline-dark cart-actions__btn">Continue Shopping</a>
            <form action="{{ route('cart.clear') }}" method="POST" class="cart-actions__form">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-secondary cart-actions__btn w-100" onclick="return confirm('Clear the entire cart?')">
                    Clear Cart
                </button>
            </form>
            @auth
                <a href="{{ route('checkout.index') }}" class="btn btn-primary cart-actions__btn cart-actions__checkout">Proceed to Checkout</a>
            @else
                <a href="{{ route('checkout.index') }}" class="btn btn-outline-primary cart-actions__btn">Guest Checkout</a>
                <a href="{{ route('login') }}" class="btn btn-primary cart-actions__btn cart-actions__checkout">Login &amp; Checkout</a>
            @endauth
        </div>
    @else
        <div class="text-center py-5 cart-empty">
            <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
            <h4>Your cart is empty</h4>
            <p class="text-muted">Start shopping to add items to your cart.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">Browse Products</a>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .cart-page {
        padding-bottom: 1rem;
    }
    .cart-mobile-card {
        background: #fff;
        border: 1px solid #e8efe9;
        border-radius: 14px;
        padding: 0.9rem;
        margin-bottom: 0.75rem;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
    }
    .cart-mobile-card__main {
        display: flex;
        gap: 0.85rem;
    }
    .cart-mobile-card__thumb {
        flex: 0 0 78px;
        width: 78px;
        height: 78px;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #eef2ea;
        background: #f8faf9;
    }
    .cart-mobile-card__thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .cart-mobile-card__name {
        display: block;
        font-weight: 700;
        color: #1f2937;
        text-decoration: none;
        line-height: 1.3;
        font-size: 0.95rem;
    }
    .cart-mobile-card__name:hover {
        color: #6BB252;
    }
    .cart-mobile-card__sku {
        font-size: 0.75rem;
        color: #94a3b8;
        margin-top: 0.15rem;
    }
    .cart-mobile-card__price {
        font-size: 0.9rem;
        color: #475569;
        margin-top: 0.35rem;
    }
    .cart-mobile-card__subtotal {
        font-weight: 700;
        color: #1f2937;
        white-space: nowrap;
    }
    .cart-mobile-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f8faf9;
        border: 1px solid #e8efe9;
        border-radius: 14px;
        padding: 0.95rem 1rem;
        margin-top: 0.5rem;
        font-size: 1.05rem;
    }
    .cart-mobile-total strong {
        color: #6BB252;
        font-size: 1.2rem;
    }
    .cart-qty-control {
        display: inline-flex;
        align-items: center;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
        background: #fff;
    }
    .cart-qty-btn {
        width: 34px;
        height: 34px;
        border: 0;
        background: #f8fafc;
        color: #334155;
        font-size: 1rem;
        line-height: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        cursor: pointer;
    }
    .cart-qty-btn:hover {
        background: #eef2ea;
        color: #6BB252;
    }
    .cart-qty-input {
        width: 44px;
        height: 34px;
        border: 0;
        border-left: 1px solid #e2e8f0;
        border-right: 1px solid #e2e8f0;
        text-align: center;
        font-size: 0.9rem;
        font-weight: 600;
        padding: 0;
        -moz-appearance: textfield;
    }
    .cart-qty-input::-webkit-outer-spin-button,
    .cart-qty-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .cart-table-thumb {
        width: 72px;
        height: 72px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid #eef2ea;
    }
    .cart-actions {
        display: grid;
        gap: 0.65rem;
    }
    .cart-actions__btn {
        width: 100%;
        min-height: 46px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .cart-actions__form {
        margin: 0;
    }
    @media (min-width: 992px) {
        .cart-actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 0.75rem;
        }
        .cart-actions__btn {
            width: auto;
            min-width: 160px;
        }
        .cart-actions__form {
            margin-right: auto;
            margin-left: 0.5rem;
        }
        .cart-actions__checkout {
            margin-left: auto;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.cart-qty-form').forEach(function (form) {
        var input = form.querySelector('.js-cart-qty-input');
        var minus = form.querySelector('.js-cart-qty-minus');
        var plus = form.querySelector('.js-cart-qty-plus');
        if (!input) return;

        function clampAndSubmit(next) {
            var min = Number(input.min || 1);
            var max = Number(input.max || 99);
            var value = Number(next);
            if (Number.isNaN(value)) value = min;
            value = Math.max(min, Math.min(max, value));
            if (value === Number(input.value) && document.activeElement !== input) return;
            input.value = value;
            form.requestSubmit();
        }

        if (minus) {
            minus.addEventListener('click', function () {
                clampAndSubmit(Number(input.value || 1) - 1);
            });
        }
        if (plus) {
            plus.addEventListener('click', function () {
                clampAndSubmit(Number(input.value || 1) + 1);
            });
        }
        input.addEventListener('change', function () {
            clampAndSubmit(input.value);
        });
    });
});
</script>
@endpush
