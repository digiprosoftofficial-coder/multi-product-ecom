@extends('layouts.app')

@section('title', $product->seoTitle().' – '.site_name())

@push('head')
    @include('frontend.partials.product-seo')
@endpush

@section('content')
<section class="py-5">
    <div class="container-lg">
        @php
            $crumbs = [
                ['name' => 'Home', 'url' => route('home')],
                ['name' => 'Shop', 'url' => route('products.index')],
            ];
            if ($product->category) {
                $crumbs[] = ['name' => $product->category->name, 'url' => route('products.category', $product->category)];
            }
            $crumbs[] = ['name' => $product->name, 'url' => null];
        @endphp
        @include('frontend.components.breadcrumb', ['items' => $crumbs])

        <div class="row g-5">
            <div class="col-md-6">
                @if($product->images->count() > 0)
                    <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner border rounded-3 overflow-hidden">
                            @foreach($product->images as $index => $image)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ asset('uploads/products/' . $image->filename) }}"
                                         class="d-block w-100"
                                         alt="{{ $product->name }}"
                                         style="height: 480px; object-fit: contain; background: #f8f9fa;">
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
                    <img src="{{ $product->thumbnail_url }}" class="img-fluid border rounded-3" alt="{{ $product->name }}">
                @else
                    <div class="bg-light border rounded-3 d-flex align-items-center justify-content-center" style="height: 420px;">
                        <span class="text-muted">No Image Available</span>
                    </div>
                @endif
            </div>

            <div class="col-md-6">
                <h1 class="display-6">{{ $product->name }}</h1>
                <p class="text-muted">SKU: {{ $product->sku }}</p>

                <div class="d-flex align-items-center gap-2 mb-4">
                    @if($product->discount_price)
                        <del class="text-muted">{{ money($product->price) }}</del>
                        <span class="text-dark fw-semibold fs-3">{{ money($product->discount_price) }}</span>
                    @else
                        <span class="text-dark fw-semibold fs-3">{{ money($product->price) }}</span>
                    @endif
                    @if(compare_price_enabled() && $product->compare_price && $product->compare_price > $product->final_price)
                        <span class="badge border border-dark-subtle rounded-0 fw-normal px-1 fs-7 lh-1 text-body-tertiary">
                            {{ round((($product->compare_price - $product->final_price) / $product->compare_price) * 100) }}% OFF
                        </span>
                    @endif
                </div>

                @if($product->hasDescription())
                    <div class="mb-4 product-description">
                        {!! $product->description_html !!}
                    </div>
                @endif

                <p class="mb-1"><strong>Category:</strong>
                    @if($product->category)
                        <a href="{{ route('products.category', $product->category) }}">{{ $product->category->pathName() }}</a>
                    @else
                        -
                    @endif
                </p>
                <p class="mb-4">
                    <strong>Stock:</strong>
                    @if($product->isInStock())
                        <span class="text-success">{{ $product->stock }} available</span>
                    @else
                        <span class="text-danger">Out of Stock</span>
                    @endif
                </p>

                @if($product->isInStock())
                    @include('frontend.partials.add-to-cart-actions', ['product' => $product, 'showQty' => true])
                @else
                    <button class="btn btn-secondary btn-lg" disabled>Out of Stock</button>
                @endif
            </div>
        </div>

        @if($relatedProducts->count() > 0)
            <div class="mt-5 pt-4">
                <div class="section-header d-flex flex-wrap justify-content-between my-4">
                    <h2 class="section-title">Related products</h2>
                </div>
                <div class="product-grid row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                    @foreach($relatedProducts as $relatedProduct)
                        <div class="col">
                            @include('frontend.components.product-card', ['product' => $relatedProduct])
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
