@extends('layouts.app')

@section('title', 'Checkout')

@section('seo')
@include('frontend.partials.seo-meta', ['robots' => 'noindex, nofollow'])
@endsection

@section('content')
@include('frontend.components.page-banner', [
    'page' => 'checkout',
    'fallbackTitle' => 'Checkout',
])

@php
    $paymentMethods = $paymentMethods ?? \App\Support\PaymentMethod::options();
    $selectedMethod = old('payment_method', 'cash_on_delivery');
    $walletNumbers = [
        'bkash' => \App\Support\PaymentMethod::walletNumber('bkash'),
        'nagad' => \App\Support\PaymentMethod::walletNumber('nagad'),
        'rocket' => \App\Support\PaymentMethod::walletNumber('rocket'),
    ];
    $methodLabels = collect($paymentMethods)->mapWithKeys(fn ($m, $k) => [$k => $m['label']]);
@endphp

<div class="container-lg py-4 py-lg-5 checkout-page">
    @include('frontend.components.checkout-steps', ['active' => 'checkout'])

    <div class="row g-4">
            <div class="col-lg-8">
                <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
                    @csrf
                <div class="checkout-panel mb-4">
                    <div class="checkout-panel-head">
                        <span class="checkout-panel-icon"><i class="fa-solid fa-user"></i></span>
                        <div>
                            <h2 class="checkout-panel-title mb-0">Customer details</h2>
                            <p class="checkout-panel-sub mb-0">Who should we contact about this order?</p>
                        </div>
                    </div>
                    <div class="checkout-panel-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="customer_name" class="form-label">Full name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('customer_name') is-invalid @enderror"
                                       id="customer_name" name="customer_name"
                                       value="{{ old('customer_name', Auth::check() ? Auth::user()->name : '') }}" required>
                                @error('customer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="customer_phone" class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('customer_phone') is-invalid @enderror"
                                       id="customer_phone" name="customer_phone"
                                       value="{{ old('customer_phone', Auth::check() ? Auth::user()->phone : '') }}"
                                       placeholder="01XXXXXXXXX" required>
                                @error('customer_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label for="customer_email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('customer_email') is-invalid @enderror"
                                       id="customer_email" name="customer_email"
                                       value="{{ old('customer_email', Auth::check() ? Auth::user()->email : '') }}" required>
                                @error('customer_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="checkout-panel mb-4">
                    <div class="checkout-panel-head">
                        <span class="checkout-panel-icon"><i class="fa-solid fa-truck"></i></span>
                        <div>
                            <h2 class="checkout-panel-title mb-0">Shipping address</h2>
                            <p class="checkout-panel-sub mb-0">Where should we deliver your order?</p>
                        </div>
                    </div>
                    <div class="checkout-panel-body">
                        <label for="shipping_address" class="form-label">Full address <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('shipping_address') is-invalid @enderror"
                                  id="shipping_address" name="shipping_address" rows="3" required
                                  placeholder="House, road, area, district">{{ old('shipping_address') }}</textarea>
                        @error('shipping_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="checkout-panel mb-4">
                    <div class="checkout-panel-head">
                        <span class="checkout-panel-icon"><i class="fa-solid fa-wallet"></i></span>
                        <div>
                            <h2 class="checkout-panel-title mb-0">Payment method</h2>
                            <p class="checkout-panel-sub mb-0">Choose how you want to pay</p>
                        </div>
                    </div>
                    <div class="checkout-panel-body">
                        <div class="payment-options">
                            @foreach($paymentMethods as $value => $method)
                                <label class="payment-option {{ $selectedMethod === $value ? 'is-selected' : '' }}">
                                    <input type="radio" name="payment_method" value="{{ $value }}"
                                           class="payment-option-input"
                                           data-wallet="{{ \App\Support\PaymentMethod::isMobileWallet($value) ? '1' : '0' }}"
                                           {{ $selectedMethod === $value ? 'checked' : '' }} required>
                                    <span class="payment-option-body">
                                        <span class="payment-option-icon" style="--payment-color: {{ $method['color'] }}">
                                            <i class="fa-solid {{ $method['icon'] }}"></i>
                                        </span>
                                        <span class="payment-option-text">
                                            <strong>{{ $method['label'] }}</strong>
                                            <small>{{ $method['hint'] }}</small>
                                        </span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @error('payment_method')<div class="text-danger small mt-2">{{ $message }}</div>@enderror

                        <div id="wallet-payment-fields" class="wallet-payment-fields {{ \App\Support\PaymentMethod::isMobileWallet($selectedMethod) ? '' : 'd-none' }}">
                            <div class="wallet-payment-note" id="wallet-payment-note"></div>
                            <div class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <label for="payment_reference" class="form-label">Transaction ID <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('payment_reference') is-invalid @enderror"
                                           id="payment_reference" name="payment_reference"
                                           value="{{ old('payment_reference') }}"
                                           placeholder="e.g. 8NXXXXXXXX">
                                    @error('payment_reference')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="payment_sender_phone" class="form-label">Sender number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('payment_sender_phone') is-invalid @enderror"
                                           id="payment_sender_phone" name="payment_sender_phone"
                                           value="{{ old('payment_sender_phone') }}"
                                           placeholder="01XXXXXXXXX">
                                    @error('payment_sender_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="checkout-panel mb-4 mb-lg-0">
                    <div class="checkout-panel-head">
                        <span class="checkout-panel-icon"><i class="fa-solid fa-note-sticky"></i></span>
                        <div>
                            <h2 class="checkout-panel-title mb-0">Order notes</h2>
                            <p class="checkout-panel-sub mb-0">Optional delivery instructions</p>
                        </div>
                    </div>
                    <div class="checkout-panel-body">
                        <textarea class="form-control @error('notes') is-invalid @enderror"
                                  id="notes" name="notes" rows="3"
                                  placeholder="Any special instructions for delivery">{{ old('notes') }}</textarea>
                        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                </form>
            </div>

            <div class="col-lg-4">
                <div class="checkout-summary">
                    <h3 class="checkout-summary-title">Order summary</h3>
                    <div class="checkout-summary-items">
                        @foreach($cartItems as $item)
                            @php
                                $product = $item['product'];
                                $qty = (int) $item['quantity'];
                                $max = max(1, (int) $product->stock);
                            @endphp
                            <div class="checkout-summary-item" data-product-id="{{ $product->id }}">
                                <div class="checkout-summary-item-main">
                                    @if($product->thumbnail_url)
                                        <img src="{{ $product->thumbnail_url }}" alt="" class="checkout-summary-thumb">
                                    @endif
                                    <div class="min-w-0">
                                        <div class="checkout-summary-name">{{ $product->name }}</div>
                                        <form action="{{ route('cart.update', $product) }}" method="POST" class="checkout-qty-form mt-1" data-product-id="{{ $product->id }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="checkout-qty-control">
                                                <button type="button" class="checkout-qty-btn js-checkout-qty-minus" aria-label="Decrease">−</button>
                                                <input type="number"
                                                       name="quantity"
                                                       class="checkout-qty-input js-checkout-qty-input"
                                                       value="{{ $qty }}"
                                                       min="1"
                                                       max="{{ $max }}"
                                                       required>
                                                <button type="button" class="checkout-qty-btn js-checkout-qty-plus" aria-label="Increase">+</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="checkout-summary-price js-line-total">{{ money($item['total']) }}</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="checkout-summary-row">
                        <span>Subtotal</span>
                        <span class="js-checkout-subtotal">{{ money($subtotal) }}</span>
                    </div>
                    @if($tax > 0)
                        <div class="checkout-summary-row">
                            <span>Tax</span>
                            <span class="js-checkout-tax">{{ money($tax) }}</span>
                        </div>
                    @endif
                    @if($vat > 0)
                        <div class="checkout-summary-row">
                            <span>VAT</span>
                            <span class="js-checkout-vat">{{ money($vat) }}</span>
                        </div>
                    @endif
                    <div class="checkout-summary-total">
                        <span>Total</span>
                        <strong class="js-checkout-total">{{ money($total) }}</strong>
                    </div>

                    <button type="submit" form="checkout-form" class="btn btn-primary btn-lg w-100 checkout-submit-btn">
                        <i class="fa-solid fa-bag-shopping me-2"></i> Place order
                    </button>

                    <div class="checkout-trust">
                        <span><i class="fa-solid fa-shield-halved"></i> Secure checkout</span>
                        <span><i class="fa-solid fa-truck"></i> Fast delivery</span>
                    </div>
                </div>
            </div>
        </div>
</div>
@endsection

@push('styles')
<style>
    .checkout-panel {
        background: #fff;
        border: 1px solid #e8efe9;
        border-radius: 18px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.04);
        overflow: hidden;
    }
    .checkout-panel-head {
        display: flex;
        align-items: center;
        gap: .9rem;
        padding: 1.15rem 1.25rem;
        border-bottom: 1px solid #eef2ea;
        background: linear-gradient(180deg, #fcfdfb 0%, #fff 100%);
    }
    .checkout-panel-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: rgba(107, 178, 82, 0.12);
        color: #6BB252;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.05rem;
        flex-shrink: 0;
    }
    .checkout-panel-title {
        font-size: 1.08rem;
        font-weight: 700;
        color: #1f2937;
    }
    .checkout-panel-sub {
        font-size: .88rem;
        color: #64748b;
    }
    .checkout-panel-body {
        padding: 1.25rem;
    }
    .payment-options {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: .85rem;
    }
    .payment-option {
        position: relative;
        margin: 0;
        cursor: pointer;
    }
    .payment-option-input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }
    .payment-option-body {
        display: flex;
        align-items: center;
        gap: .8rem;
        height: 100%;
        padding: .95rem 1rem;
        border: 1.5px solid #d8dfd3;
        border-radius: 14px;
        background: #fff;
        transition: border-color .15s ease, box-shadow .15s ease, background-color .15s ease;
    }
    .payment-option.is-selected .payment-option-body,
    .payment-option-input:checked + .payment-option-body {
        border-color: #6BB252;
        background: #f6fbf3;
        box-shadow: 0 0 0 0.18rem rgba(107, 178, 82, 0.14);
    }
    .payment-option-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: color-mix(in srgb, var(--payment-color) 14%, white);
        color: var(--payment-color);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.05rem;
        flex-shrink: 0;
    }
    .payment-option-text {
        display: flex;
        flex-direction: column;
        min-width: 0;
    }
    .payment-option-text strong {
        font-size: .96rem;
        color: #1f2937;
    }
    .payment-option-text small {
        color: #64748b;
        line-height: 1.35;
    }
    .wallet-payment-fields {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px dashed #dbe3d6;
    }
    .wallet-payment-note {
        padding: .85rem 1rem;
        border-radius: 12px;
        background: #fff8eb;
        border: 1px solid #fde6b3;
        color: #7c5a00;
        font-size: .92rem;
        line-height: 1.5;
    }
    .checkout-summary {
        position: sticky;
        top: 96px;
        background: #fff;
        border: 1px solid #e8efe9;
        border-radius: 18px;
        padding: 1.25rem;
        box-shadow: 0 10px 28px rgba(15, 23, 42, 0.06);
    }
    .checkout-summary-title {
        font-size: 1.15rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #1f2937;
    }
    .checkout-summary-items {
        display: flex;
        flex-direction: column;
        gap: .85rem;
        margin-bottom: 1rem;
        max-height: 360px;
        overflow-y: auto;
        padding-right: .15rem;
    }
    .checkout-summary-item {
        display: flex;
        justify-content: space-between;
        gap: .75rem;
        align-items: flex-start;
    }
    .checkout-summary-item-main {
        display: flex;
        gap: .7rem;
        min-width: 0;
    }
    .checkout-summary-thumb {
        width: 52px;
        height: 52px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid #eef2ea;
        flex-shrink: 0;
    }
    .checkout-summary-name {
        font-weight: 600;
        color: #1f2937;
        line-height: 1.35;
    }
    .checkout-summary-qty {
        font-size: .84rem;
        color: #64748b;
    }
    .checkout-qty-control {
        display: inline-flex;
        align-items: center;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
        background: #fff;
    }
    .checkout-qty-btn {
        width: 32px;
        height: 32px;
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
    .checkout-qty-btn:hover {
        background: #eef2ea;
        color: #6BB252;
    }
    .checkout-qty-input {
        width: 42px;
        height: 32px;
        border: 0;
        border-left: 1px solid #e2e8f0;
        border-right: 1px solid #e2e8f0;
        text-align: center;
        font-size: .9rem;
        font-weight: 600;
        color: #1f2937;
        padding: 0;
        -moz-appearance: textfield;
    }
    .checkout-qty-form.is-updating {
        opacity: 0.7;
        pointer-events: none;
    }
    .checkout-qty-form.is-updating .checkout-qty-control {
        opacity: 0.85;
    }
    .checkout-summary-price {
        font-weight: 600;
        white-space: nowrap;
        color: #1f2937;
    }
    .checkout-summary-row,
    .checkout-summary-total {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        padding: .45rem 0;
        color: #475569;
    }
    .checkout-summary-total {
        border-top: 1px solid #eef2ea;
        margin-top: .5rem;
        padding-top: .85rem;
        margin-bottom: 1rem;
        color: #1f2937;
    }
    .checkout-summary-total strong {
        font-size: 1.2rem;
        color: #6BB252;
    }
    .checkout-submit-btn {
        border-radius: 12px;
        min-height: 50px;
        font-weight: 700;
    }
    .checkout-trust {
        display: flex;
        flex-direction: column;
        gap: .45rem;
        margin-top: 1rem;
        font-size: .86rem;
        color: #64748b;
    }
    .checkout-trust span {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
    }
    @media (max-width: 767px) {
        .payment-options {
            grid-template-columns: 1fr;
        }
        .checkout-summary {
            position: static;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    if (window.StorefrontTracking) {
        window.StorefrontTracking.beginCheckout(@json(\App\Support\Tracking::cartPayload($cartItems, (float) $total), JSON_HEX_TAG | JSON_HEX_APOS | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
</script>
<script>
(function () {
    const walletNumbers = @json($walletNumbers);
    let orderTotal = @json(money($total));
    const methodLabels = @json($methodLabels);
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    const fields = document.getElementById('wallet-payment-fields');
    const note = document.getElementById('wallet-payment-note');
    const referenceInput = document.getElementById('payment_reference');
    const senderPhoneInput = document.getElementById('payment_sender_phone');
    const radios = document.querySelectorAll('.payment-option-input');

    function updatePaymentUI() {
        const selected = document.querySelector('.payment-option-input:checked');
        if (!selected) return;

        document.querySelectorAll('.payment-option').forEach(el => el.classList.remove('is-selected'));
        selected.closest('.payment-option')?.classList.add('is-selected');

        const isWallet = selected.dataset.wallet === '1';
        fields.classList.toggle('d-none', !isWallet);
        referenceInput.required = isWallet;
        if (senderPhoneInput) {
            senderPhoneInput.required = isWallet;
            if (!isWallet) {
                senderPhoneInput.classList.remove('is-invalid');
            }
        }

        if (isWallet) {
            const method = selected.value;
            const number = walletNumbers[method];
            const label = methodLabels[method] || method;
            if (number) {
                note.innerHTML = 'Send <strong>' + orderTotal + '</strong> to our <strong>' + label + '</strong> number: <strong>' + number + '</strong>. Then enter the transaction ID below.';
            } else {
                note.textContent = 'Complete your ' + label + ' payment, then enter the transaction ID below. Our team will verify before shipping.';
            }
        }
    }

    radios.forEach(radio => radio.addEventListener('change', updatePaymentUI));
    updatePaymentUI();

    function applyCheckoutTotals(data, form) {
        if (data.lineTotal && form) {
            const row = form.closest('.checkout-summary-item');
            const lineEl = row && row.querySelector('.js-line-total');
            if (lineEl) lineEl.textContent = data.lineTotal;
            const input = form.querySelector('.js-checkout-qty-input');
            if (input && data.quantity != null) input.value = data.quantity;
        }
        const subtotalEl = document.querySelector('.js-checkout-subtotal');
        const taxEl = document.querySelector('.js-checkout-tax');
        const vatEl = document.querySelector('.js-checkout-vat');
        const totalEl = document.querySelector('.js-checkout-total');
        if (subtotalEl && data.subtotal) subtotalEl.textContent = data.subtotal;
        if (taxEl && data.tax) taxEl.textContent = data.tax;
        if (vatEl && data.vat) vatEl.textContent = data.vat;
        if (totalEl && data.total) totalEl.textContent = data.total;
        if (data.totalFormatted || data.total) {
            orderTotal = data.totalFormatted || data.total;
            updatePaymentUI();
        }
        if (typeof data.cartCount !== 'undefined') {
            document.querySelectorAll('.js-cart-count').forEach(function (el) {
                el.textContent = data.cartCount;
                el.style.display = data.cartCount > 0 ? '' : 'none';
            });
        }
        if (data.empty) {
            window.location.href = @json(route('cart.index'));
        }
    }

    document.querySelectorAll('.checkout-qty-form').forEach(function (form) {
        const input = form.querySelector('.js-checkout-qty-input');
        const minus = form.querySelector('.js-checkout-qty-minus');
        const plus = form.querySelector('.js-checkout-qty-plus');
        if (!input) return;

        let pending = false;
        let lastSent = Number(input.value || 1);

        function clamp(next) {
            const min = Number(input.min || 1);
            const max = Number(input.max || 99);
            let value = Number(next);
            if (Number.isNaN(value)) value = min;
            return Math.max(min, Math.min(max, value));
        }

        function updateQty(next) {
            const value = clamp(next);
            if (value === lastSent && pending) return;
            if (value === lastSent && document.activeElement !== input && !pending) {
                input.value = value;
                return;
            }
            input.value = value;
            lastSent = value;
            pending = true;
            form.classList.add('is-updating');

            const body = new FormData(form);
            body.set('quantity', String(value));

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
                },
                body: body,
            })
                .then(async function (res) {
                    const data = await res.json().catch(function () { return {}; });
                    if (!res.ok) {
                        throw new Error(data.message || 'Unable to update quantity.');
                    }
                    applyCheckoutTotals(data, form);
                })
                .catch(function () {
                    // Keep current UI; user can retry
                })
                .finally(function () {
                    pending = false;
                    form.classList.remove('is-updating');
                });
        }

        if (minus) {
            minus.addEventListener('click', function () {
                updateQty(Number(input.value || 1) - 1);
            });
        }
        if (plus) {
            plus.addEventListener('click', function () {
                updateQty(Number(input.value || 1) + 1);
            });
        }
        input.addEventListener('change', function () {
            updateQty(input.value);
        });
    });
})();
</script>
@endpush
