@extends('admin.layouts.master')

@section('title', 'Subcategory Details')
@section('page-title', 'Subcategory Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-1">{{ $subcategory->name }}</h5>
        <span class="badge bg-secondary">ID: {{ $subcategory->id }}</span>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editSubcategoryModal{{ $subcategory->id }}">
            <i class="fas fa-edit"></i> Edit
        </button>
        <a href="{{ route('admin.subcategories.index') }}" class="btn btn-secondary btn-sm">
            Back
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Summary</h6>
                
                @if($subcategory->image)
                    <div class="mb-3 text-center">
                        <img src="{{ asset('uploads/categories/medium/' . $subcategory->image) }}" 
                             alt="{{ $subcategory->name }}" 
                             class="img-fluid rounded"
                             style="max-width: 100%; height: auto; max-height: 300px; object-fit: cover;">
                    </div>
                @else
                    <div class="mb-3 text-center bg-light rounded p-4">
                        <i class="fas fa-image fa-3x text-muted"></i>
                        <p class="text-muted small mt-2 mb-0">No Image</p>
                    </div>
                @endif
                
                <p class="mb-1"><strong>Category:</strong> 
                    <span class="badge bg-info">{{ $subcategory->category->name ?? 'N/A' }}</span>
                </p>
                <p class="mb-1"><strong>Child Categories:</strong> {{ $subcategory->childCategories->count() }}</p>
                <p class="mb-1"><strong>Products:</strong> {{ $subcategory->products->count() }}</p>
                <p class="mb-1"><strong>Status:</strong>
                    <span class="badge bg-{{ $subcategory->status ? 'success' : 'danger' }}">
                        {{ $subcategory->status ? 'Active' : 'Inactive' }}
                    </span>
                </p>
                @if($subcategory->description)
                    <p class="mt-3 text-muted">{{ $subcategory->description }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="row g-4">
            <!-- Child Categories Section -->
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0">Child Categories ({{ $subcategory->childCategories->count() }})</h6>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createChildCategoryModal">Add Child Category</button>
                        </div>
                        @forelse($subcategory->childCategories as $childCategory)
                            <div class="d-flex justify-content-between align-items-center border rounded p-2 mb-2">
                                <div>
                                    <div class="fw-semibold">{{ $childCategory->name }}</div>
                                    <small class="text-muted">
                                        Products: {{ $childCategory->products_count ?? 0 }} | 
                                        Status: 
                                        <span class="badge bg-{{ $childCategory->status ? 'success' : 'danger' }} badge-sm">
                                            {{ $childCategory->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </small>
                                </div>
                                <a href="{{ route('admin.childcategories.show', ['childcategory' => $childCategory->id]) }}" class="btn btn-sm btn-info text-white">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        @empty
                            <p class="text-muted mb-0">No child categories found.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Products Section -->
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0">Products ({{ $subcategory->products->count() }})</h6>
                            <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-outline-primary">Add Product</a>
                        </div>
                        @forelse($subcategory->products as $product)
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
    </div>
</div>

@include('admin.subcategories.partials.edit-modal', ['subcategory' => $subcategory, 'categories' => $categories])
@include('admin.childcategories.partials.create-modal', [
    'subCategories' => $subCategories,
    'defaultSubCategoryId' => $subcategory->id,
])
@endsection

@push('scripts')
    @include('admin.partials.open-form-modal')
@endpush
