@extends('layouts.app')

@section('title', $product->seoTitle().' – '.config('app.name'))

@push('head')
    @include('frontend.partials.product-seo')
@endpush

@section('content')
<div class="container my-5">
    @include('frontend.components.breadcrumb', [
        'items' => [
            ['name' => 'Home', 'url' => route('home')],
            ['name' => 'Products', 'url' => route('products.index')],
            ['name' => $product->name, 'url' => null],
        ]
    ])

    <div class="row">
        <div class="col-md-6">
            @if($product->images->count() > 0)
                <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach($product->images as $index => $image)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ asset('uploads/products/' . $image->filename) }}" 
                                     class="d-block w-100" 
                                     alt="{{ $product->name }}"
                                     style="height: 500px; object-fit: cover;">
                            </div>
                        @endforeach
                    </div>
                    @if($product->images->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    @endif
                </div>
            @elseif($product->thumbnail)
                <img src="{{ asset('uploads/products/thumbnails/' . $product->thumbnail) }}" 
                     class="img-fluid" 
                     alt="{{ $product->name }}">
            @else
                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 500px;">
                    <span class="text-muted">No Image Available</span>
                </div>
            @endif
        </div>

        <div class="col-md-6">
            <h1>{{ $product->name }}</h1>
            <p class="text-muted">SKU: {{ $product->sku }}</p>

            <div class="mb-3">
                @if($product->discount_price)
                    <span class="text-danger fw-bold fs-3">${{ number_format($product->discount_price, 2) }}</span>
                    <span class="text-muted text-decoration-line-through ms-2 fs-5">${{ number_format($product->price, 2) }}</span>
                    @if(compare_price_enabled() && $product->compare_price)
                        <!-- <span class="text-muted text-decoration-line-through ms-2">${{ number_format($product->compare_price, 2) }}</span> -->
                    @endif
                @else
                    <span class="fw-bold fs-3">${{ number_format($product->price, 2) }}</span>
                    @if(compare_price_enabled() && $product->compare_price)
                        <span class="text-muted text-decoration-line-through ms-2">${{ number_format($product->compare_price, 2) }}</span>
                    @endif
                @endif
            </div>

            @if($product->hasDescription())
                <div class="mb-3">
                    <h5>Description</h5>
                    <div class="product-description">{!! $product->description_html !!}</div>
                </div>
            @endif

            <div class="mb-3">
                <p><strong>Category:</strong> {{ $product->category?->pathName() ?? '-' }}</p>
                <p><strong>Stock:</strong> 
                    @if($product->isInStock())
                        <span class="text-success">{{ $product->stock }} available</span>
                    @else
                        <span class="text-danger">Out of Stock</span>
                    @endif
                </p>
            </div>

            @if($product->isInStock())
                <form action="{{ route('cart.add', $product) }}" method="POST" class="d-flex gap-2 align-items-center">
                    @csrf
                    <div class="input-group" style="width: 150px;">
                        <label class="input-group-text">Qty</label>
                        <input type="number" name="quantity" class="form-control" value="1" min="1" max="{{ $product->stock }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-cart-plus"></i> Add to Cart
                    </button>
                </form>
            @else
                <button class="btn btn-secondary btn-lg" disabled>Out of Stock</button>
            @endif
        </div>
    </div>

    @if($relatedProducts->count() > 0)
        <div class="mt-5">
            <h3>Related Products</h3>
            <div class="row">
                @foreach($relatedProducts as $relatedProduct)
                    <div class="col-md-3 mb-4">
                        @include('frontend.components.product-card', ['product' => $relatedProduct])
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

