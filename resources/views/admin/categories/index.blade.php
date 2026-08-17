@extends('admin.layouts.master')

@section('title', 'Categories')
@section('page-title', 'Categories')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5>All Categories <span class="badge bg-primary">Total: {{ $totalCategories }}</span></h5>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
        <i class="fas fa-plus"></i> Add Main Category
    </button>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Category Name</th>
                        <th>Subcategories</th>
                        <th>Child Categories</th>
                        <th>Products <span class="badge bg-primary">{{ $totalProducts }}</span></th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if($category->image)
                                    <img src="{{ asset('uploads/categories/thumbnails/' . $category->image) }}"
                                         alt="{{ $category->name }}"
                                         class="me-2"
                                         style="width: 40px; height: 40px; object-fit: cover;">
                                @endif
                                <strong>{{ $category->name }}</strong>
                            </td>
                            <td>
                                <div class="dropdown d-inline-block">
                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ $category->subCategories->count() }} Subcategories
                                    </button>
                                    <ul class="dropdown-menu">
                                        @forelse($category->subCategories as $sub)
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.subcategories.show', $sub->id) }}">
                                                    {{ $sub->name }}
                                                </a>
                                            </li>
                                        @empty
                                            <li><span class="dropdown-item text-muted">No subcategories</span></li>
                                        @endforelse
                                    </ul>
                                </div>
                            </td>
                            <td>
                                @php
                                    $allChildCategories = $category->subCategories->flatMap->childCategories;
                                    $totalChildCategories = $allChildCategories->count();
                                @endphp
                                @if($totalChildCategories > 0)
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-sm btn-outline-success dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            {{ $totalChildCategories }} Child Categories
                                        </button>
                                        <ul class="dropdown-menu">
                                            @foreach($category->subCategories as $sub)
                                                @foreach($sub->childCategories as $childCategory)
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.childcategories.show', ['childcategory' => $childCategory->id]) }}">
                                                            <span class="badge bg-info me-2">{{ $sub->name }}</span>
                                                            {{ $childCategory->name }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            @endforeach
                                        </ul>
                                    </div>
                                @else
                                    <span class="text-muted">No child categories</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $category->products_count }} Products</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $category->status ? 'success' : 'danger' }}">
                                    {{ $category->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-sm btn-info text-white">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $category->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->count() == 0)
            <div class="text-center py-4">
                <p class="text-muted mb-0">
                    No categories found.
                    <button type="button" class="btn btn-link p-0 align-baseline" data-bs-toggle="modal" data-bs-target="#createCategoryModal">Create your first category</button>
                </p>
            </div>
        @endif
    </div>
</div>

{{-- Create Modal --}}
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createCategoryModalLabel">Add Main Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('admin.categories.partials.form-fields', ['category' => null])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($categories as $category)
    @include('admin.categories.partials.edit-modal', ['category' => $category])

    {{-- Delete Modal --}}
    <div class="modal fade" id="deleteModal{{ $category->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">Are you sure you want to delete "{{ $category->name }}"?</p>
                    <div class="alert alert-warning mb-0">
                        Please delete all the products under this category first.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if($errors->any())
            @if(old('_method') === 'PUT' && old('edit_category_id'))
                var modalEl = document.getElementById('editCategoryModal{{ (int) old('edit_category_id') }}');
            @else
                var modalEl = document.getElementById('createCategoryModal');
            @endif
            if (modalEl && window.bootstrap) {
                bootstrap.Modal.getOrCreateInstance(modalEl).show();
            }
        @endif
    });
</script>
@endpush
