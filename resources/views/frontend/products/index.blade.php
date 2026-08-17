@extends('layouts.app')

@section('title', 'Shop – '.site_name())

@section('content')
<section class="py-5">
    <div class="container-lg">
        <div class="row">
            <div class="col-md-12">
                <div class="section-header d-flex flex-wrap justify-content-between my-4">
                    <h1 class="section-title">Shop</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 mb-4">
                <div class="border rounded-3 p-3">
                    <h5 class="widget-title mb-3">Categories</h5>
                    <ul class="navbar-nav menu-list list-unstyled d-flex flex-column gap-1 mb-0">
                        <li>
                            <a href="{{ route('products.index') }}" class="nav-link {{ !request('search') && !request('category') ? 'fw-bold text-primary' : '' }}">All Products</a>
                        </li>
                        @foreach($categories as $category)
                            <li>
                                <a href="{{ route('products.category', $category) }}" class="nav-link">{{ $category->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="col-lg-9">
                <form method="GET" action="{{ route('products.index') }}" class="search-bar row bg-light p-2 rounded-4 mb-4 mx-0">
                    <div class="col-11">
                        <input type="text" name="search" class="form-control border-0 bg-transparent" placeholder="Search products..." value="{{ request('search') }}">
                    </div>
                    <div class="col-1 d-flex align-items-center justify-content-end">
                        <button type="submit" class="btn p-0 border-0 bg-transparent" aria-label="Search">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                </form>

                <div class="product-grid row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 g-4">
                    @forelse($products as $product)
                        <div class="col">
                            @include('frontend.components.product-card', ['product' => $product])
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <p class="text-muted mb-3">No products found.</p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary rounded-1">Browse all products</a>
                        </div>
                    @endforelse
                </div>

                @if($products->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
