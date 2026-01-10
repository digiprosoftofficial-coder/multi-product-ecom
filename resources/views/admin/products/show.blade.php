@extends('admin.layouts.master')

@section('title', 'Product Details')
@section('page-title', 'Product Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-1">{{ $product->name }}</h5>
        <span class="badge bg-secondary">ID: {{ $product->id }}</span>
        <span class="badge bg-info ms-2">SKU: {{ $product->sku }}</span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary btn-sm">
            <i class="fas fa-edit"></i> Edit
        </a>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm">
            Back
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Product Image</h6>
                
                @if($product->thumbnail)
                    <div class="mb-3 text-center">
                        <img src="{{ asset('uploads/products/thumbnails/' . $product->thumbnail) }}" 
                             alt="{{ $product->name }}" 
                             class="img-fluid rounded"
                             style="max-width: 100%; height: auto; max-height: 300px; object-fit: cover;">
                    </div>
                @else
                    <div class="mb-3 text-center bg-light rounded p-4">
                        <i class="fas fa-image fa-3x text-muted"></i>
                        <p class="text-muted small mt-2 mb-0">No Thumbnail</p>
                    </div>
                @endif

                @if($product->images->count() > 0)
                    <div class="mt-3">
                        <h6 class="fw-bold mb-2">Additional Images ({{ $product->images->count() }})</h6>
                        <div class="row g-2">
                            @foreach($product->images as $image)
                                <div class="col-4">
                                    <img src="{{ asset('uploads/products/thumbnails/' . $image->filename) }}" 
                                         alt="{{ $product->name }}" 
                                         class="img-fluid rounded border"
                                         style="width: 100%; height: 80px; object-fit: cover;">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="row g-4">
            <!-- Product Information -->
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Product Information</h6>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Category:</strong> 
                                    <span class="badge bg-info">{{ $product->category->name ?? 'N/A' }}</span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Subcategory:</strong> 
                                    <span class="badge bg-secondary">{{ $product->subCategory->name ?? 'N/A' }}</span>
                                </p>
                            </div>
                        </div>

                        @if($product->childCategory)
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Child Category:</strong> 
                                    <span class="badge bg-success">{{ $product->childCategory->name }}</span>
                                </p>
                            </div>
                        </div>
                        @endif

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <p class="mb-1"><strong>Price:</strong> 
                                    <span class="text-success fw-bold">${{ number_format($product->price, 2) }}</span>
                                </p>
                            </div>
                            @if($product->compare_price)
                            <div class="col-md-4">
                                <p class="mb-1"><strong>Compare Price:</strong> 
                                    <span class="text-decoration-line-through text-muted">${{ number_format($product->compare_price, 2) }}</span>
                                </p>
                            </div>
                            @endif
                            @if($product->discount_price)
                            <div class="col-md-4">
                                <p class="mb-1"><strong>Discount Price:</strong> 
                                    <span class="text-danger fw-bold">${{ number_format($product->discount_price, 2) }}</span>
                                </p>
                            </div>
                            @endif
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <p class="mb-1"><strong>Stock:</strong> 
                                    <span class="badge bg-{{ $product->stock > 0 ? 'success' : 'danger' }}">
                                        {{ $product->stock }} Units
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-1"><strong>Status:</strong>
                                    <span class="badge bg-{{ $product->status ? 'success' : 'danger' }}">
                                        {{ $product->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-1"><strong>Slug:</strong> 
                                    <code class="small">{{ $product->slug }}</code>
                                </p>
                            </div>
                        </div>

                        @if($product->description)
                        <div class="mb-3">
                            <p class="mb-1"><strong>Description:</strong></p>
                            <div class="text-muted">{!! nl2br(e($product->description)) !!}</div>
                        </div>
                        @endif

                        @if($product->meta_title || $product->meta_description)
                        <div class="mt-3 pt-3 border-top">
                            <h6 class="fw-bold mb-2">SEO Information</h6>
                            @if($product->meta_title)
                            <p class="mb-1"><strong>Meta Title:</strong> {{ $product->meta_title }}</p>
                            @endif
                            @if($product->meta_description)
                            <p class="mb-0"><strong>Meta Description:</strong> {{ $product->meta_description }}</p>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
