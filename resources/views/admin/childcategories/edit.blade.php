@extends('admin.layouts.master')

@section('title', 'Edit Child Category')
@section('page-title', 'Edit Child Category')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.childcategories.update', ['childcategory' => $childcategory->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="sub_category_id" class="form-label">Parent Subcategory <span class="text-danger">*</span></label>
                <select class="form-select @error('sub_category_id') is-invalid @enderror" 
                        id="sub_category_id" name="sub_category_id" required>
                    <option value="">Select Parent Subcategory</option>
                    @foreach($subcategories as $subCategory)
                        <option value="{{ $subCategory->id }}" 
                                {{ old('sub_category_id', $childcategory->sub_category_id) == $subCategory->id ? 'selected' : '' }}>
                            {{ $subCategory->category->name ?? 'N/A' }} > {{ $subCategory->name }}
                        </option>
                    @endforeach
                </select>
                <small class="form-text text-muted">
                    <i class="fas fa-info-circle"></i> 
                    Currently under: <strong>{{ $childcategory->subCategory->category->name ?? 'N/A' }} > {{ $childcategory->subCategory->name ?? 'N/A' }}</strong>
                </small>
                @error('sub_category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Child Category Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                       id="name" name="name" value="{{ old('name', $childcategory->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="3">{{ old('description', $childcategory->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            @if($childcategory->image)
                <div class="mb-3">
                    <label class="form-label">Current Image</label>
                    <div>
                        <img src="{{ asset('uploads/categories/thumbnails/' . $childcategory->image) }}" 
                             alt="{{ $childcategory->name }}" 
                             style="max-width: 200px; height: auto;">
                    </div>
                </div>
            @endif

            <div class="mb-3">
                <label for="image" class="form-label">New Image</label>
                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                       id="image" name="image" accept="image/*">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                <select class="form-select @error('status') is-invalid @enderror" 
                        id="status" name="status" required>
                    <option value="1" {{ old('status', $childcategory->status) == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('status', $childcategory->status) == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Update Child Category</button>
                <a href="{{ route('admin.childcategories.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

