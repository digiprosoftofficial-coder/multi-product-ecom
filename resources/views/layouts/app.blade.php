<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>@yield('title', site_name())</title>
    @include('partials.favicon')
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="{{ site_name() }}">
    @hasSection('seo')
        @yield('seo')
    @else
        @include('frontend.partials.seo-meta')
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;600;700&display=swap" rel="stylesheet">
    @if(request()->routeIs('home', 'products.show'))
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">
    @endif
    @if(request()->routeIs('home'))
        @php
            $preloadSlide = \App\Support\Homepage::slides()[0] ?? null;
            $preloadDesktop = $preloadSlide ? \App\Support\Homepage::slideImageUrl($preloadSlide['image'] ?? null) : null;
            $preloadMobile = $preloadSlide && ! empty($preloadSlide['image_mobile'])
                ? \App\Support\Homepage::slideImageUrl($preloadSlide['image_mobile'])
                : $preloadDesktop;
        @endphp
        @if($preloadDesktop)
            @if($preloadMobile && $preloadMobile !== $preloadDesktop)
                <link rel="preload" as="image" href="{{ $preloadMobile }}" media="(max-width: 767.98px)" fetchpriority="high">
                <link rel="preload" as="image" href="{{ $preloadDesktop }}" media="(min-width: 768px)" fetchpriority="high">
            @else
                <link rel="preload" as="image" href="{{ $preloadDesktop }}" fetchpriority="high">
            @endif
        @endif
    @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('organic-v1/css/icons.css') }}?v={{ filemtime(public_path('organic-v1/css/icons.css')) }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('organic-v1/style.css') }}">
    @stack('head')
    @include('frontend.partials.theme-colors')
    @include('frontend.partials.form-styles')
    @php $facebookDomainVerification = \App\Support\Tracking::facebookDomainVerification(); @endphp
    @if($facebookDomainVerification)
        <meta name="facebook-domain-verification" content="{{ $facebookDomainVerification }}">
    @endif
    @include('frontend.partials.tracking-scripts')
    <style>
        html { height: auto; }
        body { min-height: 100vh; display: flex; flex-direction: column; background-color: #fff; }
        main { flex: 1 0 auto; background-color: #fff; }
        .site-header {
            position: sticky;
            top: 0;
            z-index: 1020;
        }
        .header-menu-toggle {
            color: inherit;
            line-height: 1;
        }
        .header-menu-toggle:focus {
            box-shadow: none;
        }
<<<<<<< HEAD
        .preloader-wrapper { z-index: 2000; }
=======
>>>>>>> main
        #footer-bottom { margin-top: auto; text-align: center; padding: 14px 0; }
        #footer-bottom p { margin: 0; }
        .product-item { width: 100%; max-width: 100%; overflow: hidden; }
        .product-item .d-flex.flex-column { min-width: 0; max-width: 100%; }
        .product-item .tab-image { width: 100%; height: 180px; object-fit: contain; }
        .product-card-media {
            position: relative;
            margin: 0 0 6px;
        }
        .product-discount-badge {
            position: absolute;
            top: 8px;
            right: 8px;
            z-index: 2;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 2.6rem;
            padding: 0.28rem 0.5rem;
            border-radius: 999px;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: #fff;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            line-height: 1.15;
            box-shadow: 0 6px 16px rgba(220, 38, 38, 0.35);
            pointer-events: none;
        }
        .product-discount-badge--lg {
            top: 14px;
            right: 14px;
            min-width: 3.25rem;
            padding: 0.5rem 0.75rem;
            font-size: 0.95rem;
        }
        .product-discount-badge--inline {
            position: static;
            pointer-events: auto;
            box-shadow: none;
            font-size: 0.85rem;
            padding: 0.4rem 0.7rem;
        }
        .product-free-delivery-badge {
            position: absolute;
            top: 8px;
            left: 8px;
            z-index: 2;
            display: inline-flex;
            align-items: center;
            max-width: calc(100% - 16px);
            padding: 0.28rem 0.5rem;
            border-radius: 999px;
            background: #16a34a;
            color: #fff;
            font-size: 0.72rem;
            font-weight: 700;
            line-height: 1.15;
            pointer-events: none;
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.35);
        }
        .product-free-delivery-badge--lg {
            top: 14px;
            left: 14px;
            font-size: 0.85rem;
            padding: 0.45rem 0.7rem;
        }
        .product-free-delivery-badge--inline {
            position: static;
            max-width: none;
            gap: 0.4rem;
            margin-bottom: 1rem;
            font-size: 0.95rem;
            padding: 0.45rem 0.85rem;
            pointer-events: auto;
        }
        .product-card-media > .product-discount-badge,
        .product-card-media > .product-free-delivery-badge {
            top: 8px;
            height: 20px;
            box-sizing: border-box;
            font-size: 0.58rem;
            line-height: 1;
            padding: 0 0.35rem;
            white-space: nowrap;
        }
        .product-card-media > .product-discount-badge {
            right: 6px;
        }
        .product-card-media > .product-free-delivery-badge {
            left: 6px;
            max-width: none;
        }
        .product-card-prices {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.15rem;
            margin-bottom: 0.15rem;
            line-height: 1.25;
        }
        .product-card-price-old {
            color: #94a3b8;
            font-size: 0.85rem;
        }
        .product-card-price-current {
            color: #0f172a;
            font-weight: 700;
            font-size: 1.05rem;
        }
        .cart-qty-control,
        .product-order-qty-control {
            display: inline-flex;
            align-items: center;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            background: #fff;
        }
        .cart-qty-btn {
            width: 36px;
            height: 36px;
            border: 0;
            background: #f8fafc;
            color: #334155;
            font-size: 1.05rem;
            line-height: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            cursor: pointer;
        }
        .cart-qty-btn:hover {
            background: #eef2ea;
            color: var(--store-accent);
        }
        .cart-qty-input {
            width: 48px;
            height: 36px;
            border: 0;
            border-left: 1px solid #e2e8f0;
            border-right: 1px solid #e2e8f0;
            text-align: center;
            font-size: 0.95rem;
            font-weight: 600;
            color: #1f2937;
            padding: 0;
            -moz-appearance: textfield;
            appearance: textfield;
        }
        .cart-qty-input::-webkit-outer-spin-button,
        .cart-qty-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .product-order-qty-row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.75rem 1rem;
        }
        .product-order-buttons {
            flex: 1 1 220px;
            min-width: 0;
        }
        .product-detail-pricing {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            gap: 0.75rem 1rem;
        }
        .product-detail-prices {
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
            line-height: 1.25;
        }
        .product-detail-price-old {
            color: #94a3b8;
            font-size: 1.05rem;
        }
        .product-detail-price-current {
            color: #0f172a;
            font-weight: 700;
            font-size: clamp(1.6rem, 2.8vw, 2rem);
        }
        .product-item .product-title {
            overflow: hidden;
            min-width: 0;
            max-width: 100%;
            text-transform: capitalize;
        }
        .product-item .product-title a {
            display: block;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
            max-width: 100%;
            text-transform: capitalize;
        }
        #offcanvasCart .cart-sidebar-list,
        #offcanvasCart .cart-sidebar-item {
            max-width: 100%;
            overflow: hidden;
        }
        #offcanvasCart .cart-sidebar-item .d-flex {
            min-width: 0;
        }
        #offcanvasCart .product-title {
            min-width: 0;
            max-width: 100%;
            overflow: hidden;
            text-transform: capitalize;
        }
        #offcanvasCart .product-title a {
            display: block;
            max-width: 100%;
            overflow-wrap: anywhere;
            word-break: break-word;
            white-space: normal;
            text-transform: capitalize;
        }
        .page-content { text-align: left; }
        .page-content img { max-width: 100%; height: auto; }
        .section-header {
            align-items: center !important;
            gap: .75rem;
            margin-top: 0 !important;
            margin-bottom: 1rem !important;
        }
        .section-header .section-title {
            margin-bottom: 0;
            line-height: 1.25;
            text-transform: capitalize;
        }
        .section-title,
        .product-detail-title {
            text-transform: capitalize;
        }
        .section-header .btn {
            align-self: center;
            line-height: 1.2;
        }
        @media (max-width: 767.98px) {
            .product-item .tab-image { height: 140px; }
            .site-header { box-shadow: 0 1px 0 rgba(0,0,0,.06); }
        }
        @media (min-width: 768px) and (max-width: 991.98px) {
            .product-item .tab-image { height: 170px; }
        }
    </style>
</head>
<body @class([
    'auth-page' => request()->routeIs('login', 'register'),
    'has-mobile-bottom-nav' => true,
])>
    @include('frontend.partials.tracking-noscript')

    @include('frontend.organic-v1.partials.offcanvas')
    @include('frontend.organic-v1.partials.header')

    @if(session('success'))
        <div class="container-lg mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="container-lg mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    @include('frontend.organic-v1.partials.footer')
    @include('frontend.organic-v1.partials.mobile-bottom-nav')
    @include('frontend.partials.store-toast')
    @stack('styles')
    @include('frontend.organic-v1.partials.scripts')
    @stack('scripts')
</body>
</html>
