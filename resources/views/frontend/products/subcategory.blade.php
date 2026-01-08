@extends('layouts.app')

@section('title', $subCategory->name)

@section('content')
<div class="container my-5">
    @include('frontend.components.breadcrumb', [
        'items' => [
            ['name' => 'Home', 'url' => route('home')],
            ['name' => 'Categories', 'url' => route('products.index')],
            ['name' => $subCategory->category->name ?? 'Category', 'url' => $subCategory->category ? route('products.category', $subCategory->category) : null],
            ['name' => $subCategory->name, 'url' => null],
        ]
    ])

    <h2 class="mb-4">{{ $subCategory->name }}</h2>

    <div class="row">
        @forelse($products as $product)
            <div class="col-md-3 mb-4">
                @include('frontend.components.product-card', ['product' => $product])
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">No products found in this subcategory.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection

