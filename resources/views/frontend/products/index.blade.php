@extends('layouts.app')

@section('title', __('storefront.shop').' – '.site_name())

@section('seo')
@include('frontend.partials.seo-meta', [
    'title' => __('storefront.shop'),
    'description' => 'Browse our full product catalog at '.site_name().'. Find quality products with fast delivery.',
    'url' => route('products.index'),
    'jsonLd' => \App\Support\Seo::breadcrumbJsonLd([
        ['name' => __('storefront.home'), 'url' => route('home')],
        ['name' => __('storefront.shop'), 'url' => route('products.index')],
    ]),
])
@endsection

@section('content')
@include('frontend.products.partials.shop-layout', [
    'title' => __('storefront.shop'),
    'breadcrumb' => [
        ['name' => __('storefront.home'), 'url' => route('home')],
        ['name' => __('storefront.shop'), 'url' => null],
    ],
    'products' => $products,
    'categories' => $categories,
    'currentCategory' => null,
    'priceBounds' => $priceBounds ?? null,
])
@endsection
