@extends('layouts.app')

@section('title', 'Shop – '.site_name())

@section('seo')
@include('frontend.partials.seo-meta', [
    'title' => 'Shop',
    'description' => 'Browse our full product catalog at '.site_name().'. Find quality products with fast delivery.',
    'url' => route('products.index'),
    'jsonLd' => \App\Support\Seo::breadcrumbJsonLd([
        ['name' => 'Home', 'url' => route('home')],
        ['name' => 'Shop', 'url' => route('products.index')],
    ]),
])
@endsection

@section('content')
@include('frontend.products.partials.shop-layout', [
    'title' => 'Shop',
    'breadcrumb' => [
        ['name' => 'Home', 'url' => route('home')],
        ['name' => 'Shop', 'url' => null],
    ],
    'products' => $products,
    'categories' => $categories,
    'currentCategory' => null,
])
@endsection
