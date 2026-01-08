@extends('layouts.app')

@section('title', 'Home')

@section('content')
<!-- Hero Section -->
<section class="bg-primary text-white py-5 mb-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-4 fw-bold">Welcome to {{ config('app.name') }}</h1>
                <p class="lead">Your one-stop shop for all your needs</p>
                <a href="{{ route('products.index') }}" class="btn btn-light btn-lg">Shop Now</a>
            </div>
            <div class="col-md-6">
                <img src="https://via.placeholder.com/600x400" alt="Hero" class="img-fluid rounded">
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Shop by Category</h2>
        <div class="row">
            @foreach($categories as $category)
                <div class="col-md-4 mb-4">
                    @include('frontend.components.category-card', ['category' => $category])
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Featured Products</h2>
        <div class="row">
            @forelse($featuredProducts as $product)
                <div class="col-md-3 mb-4">
                    @include('frontend.components.product-card', ['product' => $product])
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="text-muted">No featured products available.</p>
                </div>
            @endforelse
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('products.index') }}" class="btn btn-primary">View All Products</a>
        </div>
    </div>
</section>
@endsection

