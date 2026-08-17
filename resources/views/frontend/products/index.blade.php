@extends('layouts.app')

@section('title', 'Shop – '.site_name())

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
