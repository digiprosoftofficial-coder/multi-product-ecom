@extends('admin.layouts.master')

@section('title', 'Category Details')
@section('page-title', 'Category Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-1">{{ $category->name }}</h5>
        <span class="badge bg-secondary">ID: {{ $category->id }}</span>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category->id }}">
            <i class="fas fa-edit"></i> Edit
        </button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary btn-sm">
            Back
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Summary</h6>
                
                @if($category->image)
                    <div class="mb-3 text-center">
                        <img src="{{ asset('uploads/categories/medium/' . $category->image) }}" 
                             alt="{{ $category->name }}" 
                             class="img-fluid rounded"
                             style="max-width: 100%; height: auto; max-height: 300px; object-fit: cover;">
                    </div>
                @else
                    <div class="mb-3 text-center bg-light rounded p-4">
                        <i class="fas fa-image fa-3x text-muted"></i>
                        <p class="text-muted small mt-2 mb-0">No Image</p>
                    </div>
                @endif
                
                <p class="mb-1"><strong>Subcategories:</strong> {{ $category->subCategories->count() }}</p>
                @php
                    $totalChildCategories = $category->subCategories->flatMap->childCategories->count();
                @endphp
                <p class="mb-1"><strong>Child Categories:</strong> {{ $totalChildCategories }}</p>
                <p class="mb-1"><strong>Products:</strong> {{ $category->products->count() }}</p>
                <p class="mb-1"><strong>Status:</strong>
                    <span class="badge bg-{{ $category->status ? 'success' : 'danger' }}">
                        {{ $category->status ? 'Active' : 'Inactive' }}
                    </span>
                </p>
                @if($category->description)
                    <p class="mt-3 text-muted">{{ $category->description }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Subcategories ({{ $category->subCategories->count() }})</h6>
                    <a href="{{ route('admin.subcategories.create') }}" class="btn btn-sm btn-outline-primary">Add Subcategory</a>
                </div>
                @forelse($category->subCategories as $sub)
                    <div class="d-flex justify-content-between align-items-center border rounded p-2 mb-2">
                        <div>
                            <div class="fw-semibold">{{ $sub->name }}</div>
                            <small class="text-muted">Status: {{ $sub->status ? 'Active' : 'Inactive' }}</small>
                        </div>
                        <form action="{{ route('admin.subcategories.destroy', ['subcategory' => $sub->id]) }}" method="POST" onsubmit="return confirm('Delete subcategory {{ $sub->name }}?')" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                @empty
                    <p class="text-muted mb-0">No subcategories found.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    @php
                        $allChildCategories = $category->subCategories->flatMap->childCategories;
                        $totalChildCategories = $allChildCategories->count();
                    @endphp
                    <h6 class="fw-bold mb-0">Child Categories ({{ $totalChildCategories }})</h6>
                    <a href="{{ route('admin.childcategories.create') }}" class="btn btn-sm btn-outline-primary">Add Child Category</a>
                </div>
                @if($totalChildCategories > 0)
                    <div style="max-height: 400px; overflow-y: auto;">
                        @foreach($category->subCategories as $subCategory)
                            @if($subCategory->childCategories->count() > 0)
                                <div class="mb-3">
                                    <h6 class="fw-semibold mb-2 small">
                                        <span class="badge bg-info">{{ $subCategory->name }}</span>
                                    </h6>
                                    @foreach($subCategory->childCategories as $childCategory)
                                        <div class="d-flex justify-content-between align-items-center border rounded p-2 mb-2">
                                            <div>
                                                <div class="fw-semibold small">{{ $childCategory->name }}</div>
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
                                    @endforeach
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">No child categories found.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-2">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Products ({{ $category->products->count() }})</h6>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-outline-primary">Add Product</a>
                </div>
                @forelse($category->products as $product)
                    <div class="d-flex justify-content-between align-items-center border rounded p-2 mb-2">
                        <div>
                            <div class="fw-semibold">{{ $product->name }}</div>
                            <small class="text-muted">SKU: {{ $product->sku }}</small>
                        </div>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete product {{ $product->name }}?')" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="redirect" value="{{ url()->current() }}">
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                @empty
                    <p class="text-muted mb-0">No products found.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@include('admin.categories.partials.edit-modal', ['category' => $category])
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if($errors->any() && old('_method') === 'PUT')
            var modalEl = document.getElementById('editCategoryModal{{ $category->id }}');
            if (modalEl && window.bootstrap) {
                bootstrap.Modal.getOrCreateInstance(modalEl).show();
            }
        @endif
    });
</script>
@endpush

