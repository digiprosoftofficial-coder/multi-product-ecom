@extends('layouts.app')

@section('title', $category->name.' – '.site_name())

@section('content')
<section class="py-5">
    <div class="container-lg">
        @include('frontend.components.breadcrumb', [
            'items' => [
                ['name' => 'Home', 'url' => route('home')],
                ['name' => 'Shop', 'url' => route('products.index')],
                ['name' => $category->name, 'url' => null],
            ]
        ])

        <div class="section-header d-flex flex-wrap align-items-center justify-content-between my-4">
            <h1 class="section-title mb-0">{{ $category->name }}</h1>
        </div>

        @if($children->count() > 0)
            <div class="mb-5">
                <h5 class="widget-title mb-3">Sub Categories</h5>
                <div class="row g-4">
                    @foreach($children as $child)
                        <div class="col-6 col-sm-4 col-md-3 col-xl-2 text-center">
                            <a href="{{ route('products.category', $child) }}" class="nav-link">
                                <img
                                    src="{{ $child->thumbnail_url }}"
                                    class="rounded-circle"
                                    alt="{{ $child->name }}"
                                    style="width: 110px; height: 110px; object-fit: cover;"
                                >
                                <h4 class="fs-6 mt-3 fw-normal category-title">{{ $child->name }}</h4>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="product-grid row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4">
            @forelse($products as $product)
                <div class="col">
                    @include('frontend.components.product-card', ['product' => $product])
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">No products found in this category.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary rounded-1 mt-2">Browse all products</a>
                </div>
            @endforelse
        </div>

        @if($products->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</section>
@endsection
