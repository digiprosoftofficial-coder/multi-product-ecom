@extends('admin.layouts.master')

@section('title', 'Subcategories')
@section('page-title', 'Subcategories')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5>All Subcategories <span class="badge bg-primary">Total: {{ $totalSubCategories }}</span></h5>
    <a href="{{ route('admin.subcategories.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Subcategory
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Subcategory Name</th>
                        <th>Parent Category</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subCategories as $subCategory)
                        <tr>
                            <td>{{ $subCategory->id }}</td>
                            <td>
                                @if($subCategory->image)
                                    <img src="{{ asset('uploads/categories/thumbnails/' . $subCategory->image) }}" 
                                         alt="{{ $subCategory->name }}" 
                                         class="me-2" 
                                         style="width: 40px; height: 40px; object-fit: cover;">
                                @endif
                                <strong>{{ $subCategory->name }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-info">
                                    <i class="fas fa-folder"></i> {{ $subCategory->category->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $subCategory->status ? 'success' : 'danger' }}">
                                    {{ $subCategory->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.subcategories.edit', $subCategory) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $subCategory->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="deleteModal{{ $subCategory->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Delete Subcategory</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete "{{ $subCategory->name }}"?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <form action="{{ route('admin.subcategories.destroy', ['subcategory' => $subCategory->id]) }}" method="POST" class="d-inline">
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
                            <td colspan="5" class="text-center">No subcategories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $subCategories->links() }}
        </div>
    </div>
</div>
@endsection

