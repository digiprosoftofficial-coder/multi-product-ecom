@extends('admin.layouts.master')

@section('title', 'Child Category Details')
@section('page-title', 'Child Category Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-1">{{ $childcategory->name }}</h5>
        <span class="badge bg-secondary">ID: {{ $childcategory->id }}</span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.childcategories.edit', ['childcategory' => $childcategory->id]) }}" class="btn btn-primary btn-sm">
            <i class="fas fa-edit"></i> Edit
        </a>
        <a href="{{ route('admin.childcategories.index') }}" class="btn btn-secondary btn-sm">
            Back
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Summary</h6>
                
                @if($childcategory->image)
                    <div class="mb-3 text-center">
                        <img src="{{ asset('uploads/categories/medium/' . $childcategory->image) }}" 
                             alt="{{ $childcategory->name }}" 
                             class="img-fluid rounded"
                             style="max-width: 100%; height: auto; max-height: 300px; object-fit: cover;">
                    </div>
                @else
                    <div class="mb-3 text-center bg-light rounded p-4">
                        <i class="fas fa-image fa-3x text-muted"></i>
                        <p class="text-muted small mt-2 mb-0">No Image</p>
                    </div>
                @endif
                
                <p class="mb-1"><strong>Subcategory:</strong> 
                    <span class="badge bg-info">{{ $childcategory->subCategory->name ?? 'N/A' }}</span>
                </p>
                <p class="mb-1"><strong>Category:</strong> 
                    <span class="badge bg-secondary">{{ $childcategory->subCategory->category->name ?? 'N/A' }}</span>
                </p>
                <p class="mb-1"><strong>Products:</strong> {{ $childcategory->products->count() }}</p>
                <p class="mb-1"><strong>Status:</strong>
                    <span class="badge bg-{{ $childcategory->status ? 'success' : 'danger' }}">
                        {{ $childcategory->status ? 'Active' : 'Inactive' }}
                    </span>
                </p>
                @if($childcategory->description)
                    <p class="mt-3 text-muted">{{ $childcategory->description }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Products ({{ $childcategory->products->count() }})</h6>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-outline-primary">Add Product</a>
                </div>
                @forelse($childcategory->products as $product)
                    <div class="d-flex justify-content-between align-items-center border rounded p-2 mb-2">
                        <div>
                            <div class="fw-semibold">{{ $product->name }}</div>
                            <small class="text-muted">SKU: {{ $product->sku }}</small>
                        </div>
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                @empty
                    <p class="text-muted mb-0">No products found.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

