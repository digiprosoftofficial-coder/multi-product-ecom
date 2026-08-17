@extends('admin.layouts.master')

@section('title', 'Child Categories')
@section('page-title', 'Child Categories')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5>All Child Categories <span class="badge bg-primary">Total: {{ $totalChildCategories }}</span></h5>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createChildCategoryModal">
        <i class="fas fa-plus"></i> Add Child Category
    </button>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Child Category Name</th>
                        <th>Subcategory</th>
                        <th>Category</th>
                        <th>Products</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($childCategories as $childCategory)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if($childCategory->image)
                                    <img src="{{ asset('uploads/categories/thumbnails/' . $childCategory->image) }}"
                                         alt="{{ $childCategory->name }}"
                                         class="me-2"
                                         style="width: 40px; height: 40px; object-fit: cover;">
                                @endif
                                <strong>{{ $childCategory->name }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-info">
                                    <i class="fas fa-tag"></i> {{ $childCategory->subCategory->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-secondary">
                                    <i class="fas fa-folder"></i> {{ $childCategory->subCategory->category->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $childCategory->products_count ?? 0 }} Products</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $childCategory->status ? 'success' : 'danger' }}">
                                    {{ $childCategory->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.childcategories.show', ['childcategory' => $childCategory->id]) }}" class="btn btn-sm btn-info text-white">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editChildCategoryModal{{ $childCategory->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $childCategory->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No child categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $childCategories->links() }}
        </div>
    </div>
</div>

@include('admin.childcategories.partials.create-modal', ['subCategories' => $subCategories])

@foreach($childCategories as $childCategory)
    @include('admin.childcategories.partials.edit-modal', ['childcategory' => $childCategory, 'subCategories' => $subCategories])

    <div class="modal fade" id="deleteModal{{ $childCategory->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Child Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete "{{ $childCategory->name }}"?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('admin.childcategories.destroy', ['childcategory' => $childCategory->id]) }}" method="POST" class="d-inline">
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
    @include('admin.partials.open-form-modal')
@endpush
