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
                <p class="text-muted mb-2">SKU: <span class="js-product-sku">{{ $product->sku }}</span></p>

                @php
                    $listPrice = $product->listPriceForDiscount();
                    $discountPercent = $product->discountPercent();
                    $shippingInside = setting('shipping_inside_dhaka', 60);
                    $shippingOutside = setting('shipping_outside_dhaka', 120);
                    $helpline = setting('contact_phone');
                    $whatsappDigits = \App\Rules\BangladeshPhone::toWhatsAppDigits(setting('social_whatsapp', ''))
                        ?: \App\Rules\BangladeshPhone::toWhatsAppDigits($helpline);
                    $whatsappUrl = $whatsappDigits
                        ? 'https://wa.me/'.$whatsappDigits.'?text='.rawurlencode(
                            "Hi, I want this product: {$product->name}\n".route('products.show', $product)
                        )
                        : null;
                    $callUrl = $helpline ? 'tel:'.preg_replace('/\s+/', '', $helpline) : null;
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

                @if($product->isInStock())
                    @include('frontend.partials.add-to-cart-actions', ['product' => $product, 'showQty' => true])
                @else
                    <button class="btn btn-secondary btn-lg" disabled>Out of Stock</button>
                @endif

                @if($whatsappUrl || $callUrl)
                    <div class="product-contact-actions {{ ($whatsappUrl && $callUrl) ? '' : 'product-contact-actions--single' }}">
                        @if($whatsappUrl)
                            <a href="{{ $whatsappUrl }}"
                               class="btn product-contact-btn product-contact-btn--whatsapp"
                               target="_blank"
                               rel="noopener noreferrer">
                                <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
                                <span>WhatsApp</span>
                            </a>
                        @endif
                        @if($callUrl)
                            <a href="{{ $callUrl }}" class="btn product-contact-btn product-contact-btn--call">
                                <i class="fa-solid fa-phone" aria-hidden="true"></i>
                                <span>Call Now</span>
                            </a>
                        @endif
                    </div>
                @endif

                <aside class="product-delivery-card mt-4">
                    <h2 class="product-delivery-title">
                        <span aria-hidden="true">🚚</span>
                        Delivery information
                    </h2>
                    <ul class="product-delivery-list">
                        <li>
                            <i class="fa-solid fa-city" aria-hidden="true"></i>
                            <span>Inside Dhaka: {{ money($shippingInside) }} (1–2 days)</span>
                        </li>
                        <li>
                            <i class="fa-solid fa-map-location-dot" aria-hidden="true"></i>
                            <span>Outside Dhaka: {{ money($shippingOutside) }} (2–4 days)</span>
                        </li>
                        @if($helpline)
                            <li>
                                <i class="fa-solid fa-phone" aria-hidden="true"></i>
                                <a href="tel:{{ preg_replace('/\s+/', '', $helpline) }}">Helpline: {{ $helpline }}</a>
                            </li>
                        @endif
                    </ul>
                </aside>
            </div>
        </div>

        @if($product->hasDescription())
            <section class="product-details-block" data-details>
                <h2 class="product-details-heading">
                    <i class="fa-regular fa-file-lines" aria-hidden="true"></i>
                    Product details
                </h2>
                <div class="product-description is-clamped" data-details-body>
                    {!! $product->description_html !!}
                </div>
                <div class="product-details-toggle-wrap">
                    <button type="button" class="product-details-toggle" data-details-toggle aria-expanded="false" hidden>
                        See more details
                    </button>
                </div>
            </section>
        @endif

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
    .product-contact-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-top: 0.85rem;
    }
    .product-contact-actions--single {
        grid-template-columns: 1fr;
    }
    .product-contact-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        min-height: 46px;
        padding: 10px 14px;
        border: none;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 600;
        color: #fff;
        text-decoration: none;
        line-height: 1.1;
    }
    .product-contact-btn:hover,
    .product-contact-btn:focus {
        color: #fff;
    }
    .product-contact-btn--whatsapp {
        background: #25d366;
    }
    .product-contact-btn--whatsapp:hover,
    .product-contact-btn--whatsapp:focus {
        background: #1ebe5a;
        color: #fff;
    }
    .product-contact-btn--call {
        background: #f95f09;
    }
    .product-contact-btn--call:hover,
    .product-contact-btn--call:focus {
        background: #e05308;
        color: #fff;
    }
    .product-delivery-card {
        background: linear-gradient(180deg, #f4fafc 0%, #eef7fb 100%);
        border: 1px solid #cfe8f2;
        border-radius: 18px;
        padding: 1rem 1.05rem 1.05rem;
    }
    .product-delivery-title {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        font-size: 1rem;
        font-weight: 700;
        color: #1e3a5f;
        margin: 0 0 0.75rem;
    }
    .product-delivery-list {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    .product-delivery-list li {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        background: #fff;
        border-radius: 12px;
        padding: 0.7rem 0.85rem;
        color: #334155;
        font-size: 0.92rem;
    }
    .product-delivery-list i {
        width: 1.1rem;
        color: #3b82c4;
        text-align: center;
    }
    .product-delivery-list a {
        color: inherit;
        text-decoration: none;
        font-weight: 600;
    }
    .product-details-block {
        margin-top: 2.5rem;
        padding-top: 0.25rem;
    }
    .product-details-heading {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1.15rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 0.65rem;
        padding-bottom: 0.55rem;
        border-bottom: 2px solid #ef4444;
    }
    .product-details-heading i {
        color: #64748b;
    }
    .product-details-block .product-description {
        color: #475569;
        line-height: 1.7;
    }
    .product-details-block .product-description p {
        margin-bottom: 0.75rem;
    }
    .product-description.is-clamped {
        max-height: 5.1em;
        overflow: hidden;
        position: relative;
    }
    .product-description.is-clamped::after {
        content: "";
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        height: 2.1em;
        background: linear-gradient(transparent, #fff);
        pointer-events: none;
    }
    .product-details-toggle-wrap {
        display: flex;
        justify-content: center;
        margin-top: 1rem;
    }
    .product-details-toggle {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.45rem 1.15rem;
        border: 1px solid #fecaca;
        border-radius: 999px;
        background: #fff;
        color: #ef4444;
        font-weight: 600;
        font-size: 0.92rem;
        line-height: 1.2;
    }
    .product-details-toggle:hover,
    .product-details-toggle:focus {
        background: #fff5f5;
        color: #dc2626;
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

    var detailsBlock = document.querySelector('[data-details]');
    if (detailsBlock) {
        var detailsBody = detailsBlock.querySelector('[data-details-body]');
        var detailsToggle = detailsBlock.querySelector('[data-details-toggle]');
        if (detailsBody && detailsToggle && detailsBody.scrollHeight > detailsBody.clientHeight + 8) {
            detailsToggle.hidden = false;
            detailsToggle.addEventListener('click', function () {
                var expanded = !detailsBody.classList.toggle('is-clamped');
                detailsToggle.textContent = expanded ? 'Show less' : 'See more details';
                detailsToggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');
            });
        }
    }
</script>
@endpush
