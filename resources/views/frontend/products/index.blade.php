@extends('layouts.app')

@section('title', 'Products')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5>Categories</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li><a href="{{ route('products.index') }}" class="text-decoration-none">All Products</a></li>
                        @foreach($categories as $category)
                            <li class="mt-2">
                                <a href="{{ route('products.category', $category) }}" class="text-decoration-none">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="mb-4">
                <form method="GET" action="{{ route('products.index') }}" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Search products..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>

            <div class="row">
                @forelse($products as $product)
                    <div class="col-md-4 mb-4">
                        @include('frontend.components.product-card', ['product' => $product])
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">No products found.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

