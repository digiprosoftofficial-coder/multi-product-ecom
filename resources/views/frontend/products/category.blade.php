@extends('layouts.app')

@section('title', $category->name.' – '.site_name())

@section('content')
@php
    $crumbs = [
        ['name' => 'Home', 'url' => route('home')],
        ['name' => 'Shop', 'url' => route('products.index')],
    ];
    $nodes = [];
    $node = $category;
    while ($node) {
        array_unshift($nodes, $node);
        $node = $node->parent;
    }
    foreach ($nodes as $index => $node) {
        $crumbs[] = [
            'name' => $node->name,
            'url' => $index === count($nodes) - 1 ? null : route('products.category', $node),
        ];
    }
@endphp
@include('frontend.products.partials.shop-layout', [
    'title' => $category->name,
    'breadcrumb' => $crumbs,
    'products' => $products,
    'categories' => $categories,
    'currentCategory' => $category,
    'children' => $children,
])
@endsection
