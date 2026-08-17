@extends('admin.layouts.master')

@section('title', 'Category Details')
@section('page-title', 'Category Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-1">{{ $category->name }}</h5>
        <span class="badge bg-secondary">{{ $category->pathName() }}</span>
        <span class="badge bg-{{ $category->status ? 'success' : 'danger' }}">{{ $category->status ? 'Active' : 'Inactive' }}</span>
        @if($category->isLeaf())
            <span class="badge bg-info">Leaf — products go here</span>
        @endif
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category->id }}">
            <i class="fas fa-edit"></i> Edit
        </button>
        @if($category->canAddChild($parentMap, $category->products()->count()))
            <button type="button" class="btn btn-success btn-sm add-child-btn" data-bs-toggle="modal" data-bs-target="#createCategoryModal" data-parent-id="{{ $category->id }}">
                <i class="fas fa-plus"></i> Add child
            </button>
        @endif
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary btn-sm">Back</a>
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
                             style="max-height: 240px; object-fit: cover;">
                    </div>
                @endif
                @if($category->parent)
                    <p class="mb-1"><strong>Parent:</strong> {{ $category->parent->name }}</p>
                @endif
                <p class="mb-1"><strong>Children:</strong> {{ $category->children->count() }}</p>
                <p class="mb-1"><strong>Products in this branch:</strong> {{ $products->total() }}</p>
                @if($category->description)
                    <p class="mt-3 text-muted">{{ $category->description }}</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Child categories</h6>
                @forelse($category->children as $child)
                    <div class="d-flex justify-content-between align-items-center border rounded p-2 mb-2">
                        <div class="d-flex align-items-center gap-2">
                            @if($child->image)
                                <img src="{{ asset('uploads/categories/thumbnails/' . $child->image) }}" alt="" style="width:32px;height:32px;object-fit:cover;" class="rounded">
                            @endif
                            <div>
                                <div class="fw-semibold">{{ $child->name }}</div>
                                <small class="text-muted">{{ $child->children_count }} children · {{ $child->products_count }} products</small>
                            </div>
                        </div>
                        <a href="{{ route('admin.categories.show', $child) }}" class="btn btn-sm btn-info text-white"><i class="fas fa-eye"></i></a>
                    </div>
                @empty
                    <p class="text-muted mb-0">No child categories. {{ $category->isLeaf() ? 'This is a leaf — assign products here.' : '' }}</p>
                @endforelse
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Products</h6>
                @forelse($products as $product)
                    <div class="d-flex justify-content-between align-items-center border rounded p-2 mb-2">
                        <div>
                            <div class="fw-semibold">{{ $product->name }}</div>
                            <small class="text-muted">SKU: {{ $product->sku }}</small>
                        </div>
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                    </div>
                @empty
                    <p class="text-muted mb-0">No products in this branch.</p>
                @endforelse
                <div class="mt-3">{{ $products->links() }}</div>
            </div>
        </div>
    </div>
</div>

@include('admin.categories.partials.create-modal', ['eligibleParents' => $eligibleParents, 'defaultParentId' => $category->id])
@include('admin.categories.partials.edit-modal', ['category' => $category, 'eligibleParents' => $eligibleParents])
@endsection

@push('scripts')
    @include('admin.partials.open-form-modal')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var createModal = document.getElementById('createCategoryModal');
            if (!createModal) return;
            createModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var parentSelect = createModal.querySelector('[name="parent_id"]');
                if (!parentSelect) return;
                var parentId = button && button.getAttribute('data-parent-id') ? button.getAttribute('data-parent-id') : '{{ $category->id }}';
                parentSelect.value = parentId;
            });
        });
    </script>
@endpush
