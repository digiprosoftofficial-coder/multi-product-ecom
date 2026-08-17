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
    <meta name="description" content="{{ setting('footer_text', site_name()) }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" type="text/css" href="{{ asset('organic-v1/css/vendor.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('organic-v1/style.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&family=Open+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    @stack('head')
    @stack('styles')
    <style>
        html { height: auto; }
        body { min-height: 100vh; display: flex; flex-direction: column; }
        main { flex: 1 0 auto; }
        .site-header {
            position: sticky;
            top: 0;
            z-index: 1020;
            background: #fff;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        }
        .preloader-wrapper { z-index: 2000; }
        #footer-bottom { margin-top: auto; background: #6BB252; color: #fff; text-align: center; padding: 14px 0; }
        #footer-bottom p { margin: 0; color: #fff; }
        .product-item { width: 100%; max-width: 100%; overflow: hidden; }
        .product-item .d-flex.flex-column { min-width: 0; max-width: 100%; }
        .product-item .tab-image { width: 100%; height: 180px; object-fit: contain; }
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
        #offcanvasCart .product-title {
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
            min-width: 0;
        }
        .page-content img { max-width: 100%; height: auto; }
    </style>
</head>
<body>
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
    @include('frontend.organic-v1.partials.scripts')
    @stack('scripts')
</body>
</html>
