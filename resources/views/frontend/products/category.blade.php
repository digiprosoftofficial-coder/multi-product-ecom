@extends('layouts.app')

@section('title', $category->name)

@section('content')
<div class="container my-5">
    @include('frontend.components.breadcrumb', [
        'items' => [
            ['name' => 'Home', 'url' => route('home')],
            ['name' => 'Categories', 'url' => route('products.index')],
            ['name' => $category->name, 'url' => null],
        ]
    ])

    <div class="d-flex align-items-center gap-3 mb-4">
        <h2 class="mb-0">{{ $category->name }}</h2>
        @if($children->count() > 0)
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    Subcategories ({{ $children->count() }})
                </button>
                <ul class="dropdown-menu">
                    @foreach($children as $child)
                        <li>
                            <a class="dropdown-item" href="{{ route('products.category', $child) }}">{{ $child->name }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    @if($children->count() > 0)
        <div class="mb-4">
            <h5>Sub Categories</h5>
            <div class="row">
                @foreach($children as $child)
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('products.category', $child) }}" class="text-decoration-none">
                            <div class="card">
                                @if($child->image)
                                    <img src="{{ asset('uploads/categories/thumbnails/' . $child->image) }}" class="card-img-top" alt="{{ $child->name }}" style="height: 140px; object-fit: cover;">
                                @endif
                                <div class="card-body text-center">
                                    <h6>{{ $child->name }}</h6>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="row">
        @forelse($products as $product)
            <div class="col-md-3 mb-4">
                @include('frontend.components.product-card', ['product' => $product])
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">No products found in this category.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection
