@extends('admin.layouts.master')

@section('title', 'Categories')
@section('page-title', 'Categories')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5>All Categories <span class="badge bg-primary">Total: {{ $totalCategories }}</span></h5>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Main Category
    </a>
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
                        <th>Products <span class="badge bg-primary">{{ $totalProducts }}</span></th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
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
                                                <a class="dropdown-item" href="{{ route('admin.subcategories.edit', $sub->id) }}">
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
                                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $category->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Delete Modal -->
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
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->count() == 0)
            <div class="text-center py-4">
                <p class="text-muted">No categories found. <a href="{{ route('admin.categories.create') }}">Create your first category</a></p>
            </div>
        @endif
    </div>
</div>
@endsection

