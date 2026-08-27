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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" type="text/css" href="{{ asset('organic-v1/css/vendor.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('organic-v1/style.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&family=Open+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    @stack('head')
    @include('frontend.partials.theme-colors')
    @include('frontend.partials.form-styles')
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
        .preloader-wrapper { z-index: 2000; }
        #footer-bottom { margin-top: auto; text-align: center; padding: 14px 0; }
        #footer-bottom p { margin: 0; }
        .product-item { width: 100%; max-width: 100%; overflow: hidden; }
        .product-item .d-flex.flex-column { min-width: 0; max-width: 100%; }
        .product-item .tab-image { width: 100%; height: 180px; object-fit: contain; }
        .product-card-media {
            position: relative;
            margin: 0;
        }
        .product-discount-badge {
            position: absolute;
            top: -10px;
            right: -10px;
            z-index: 2;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 2.6rem;
            padding: 0.20rem 0.2rem;
            border-radius: 999px;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: #fff;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            line-height: 1;
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
            color: #6BB252;
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
        }
        .product-item .product-title a {
            display: block;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
            max-width: 100%;
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
        }
        #offcanvasCart .product-title a {
            display: block;
            max-width: 100%;
            overflow-wrap: anywhere;
            word-break: break-word;
            white-space: normal;
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
    <div class="preloader-wrapper">
        <div class="preloader"></div>
    </div>

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
    @stack('styles')
    @include('frontend.organic-v1.partials.scripts')
    @stack('scripts')
</body>
</html>
