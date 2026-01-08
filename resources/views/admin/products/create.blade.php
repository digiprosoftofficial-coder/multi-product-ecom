@extends('admin.layouts.master')

@section('title', 'Create Product')
@section('page-title', 'Create Product')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="sku" class="form-label">SKU</label>
                        <input type="text" class="form-control @error('sku') is-invalid @enderror" 
                               id="sku" name="sku" value="{{ old('sku') }}" 
                               placeholder="Leave empty to auto-generate">
                        @error('sku')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror" 
                                        id="category_id" name="category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="sub_category_id" class="form-label">Sub Category</label>
                                <select class="form-select @error('sub_category_id') is-invalid @enderror" 
                                        id="sub_category_id" name="sub_category_id">
                                    <option value="">Select Sub Category</option>
                                </select>
                                @error('sub_category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="child_category_id" class="form-label">Child Category</label>
                                <select class="form-select @error('child_category_id') is-invalid @enderror" 
                                        id="child_category_id" name="child_category_id">
                                    <option value="">Select Child Category</option>
                                </select>
                                @error('child_category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="5">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                               id="price" name="price" value="{{ old('price') }}" required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="compare_price" class="form-label">Compare Price</label>
                        <input type="number" step="0.01" class="form-control @error('compare_price') is-invalid @enderror" 
                               id="compare_price" name="compare_price" value="{{ old('compare_price') }}">
                        @error('compare_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="discount_price" class="form-label">Discount Price</label>
                        <input type="number" step="0.01" class="form-control @error('discount_price') is-invalid @enderror" 
                               id="discount_price" name="discount_price" value="{{ old('discount_price') }}">
                        @error('discount_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('stock') is-invalid @enderror" 
                               id="stock" name="stock" value="{{ old('stock', 0) }}" required>
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" name="status" required>
                            <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="thumbnail" class="form-label">Thumbnail Image</label>
                <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" 
                       id="thumbnail" name="thumbnail" accept="image/*">
                @error('thumbnail')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="images" class="form-label">Product Images</label>
                <input type="file" class="form-control @error('images.*') is-invalid @enderror" 
                       id="images" name="images[]" accept="image/*" multiple>
                @error('images.*')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">You can select multiple images</small>
            </div>

            <div class="mb-3">
                <label for="meta_title" class="form-label">Meta Title</label>
                <input type="text" class="form-control @error('meta_title') is-invalid @enderror" 
                       id="meta_title" name="meta_title" value="{{ old('meta_title') }}">
                @error('meta_title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="meta_description" class="form-label">Meta Description</label>
                <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                          id="meta_description" name="meta_description" rows="2">{{ old('meta_description') }}</textarea>
                @error('meta_description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Create Product</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category_id');
    const subCategorySelect = document.getElementById('sub_category_id');
    const childCategorySelect = document.getElementById('child_category_id');
    
    if (categorySelect) {
        categorySelect.addEventListener('change', function() {
            const categoryId = this.value;
            
            subCategorySelect.innerHTML = '<option value="">Select Sub Category</option>';
            childCategorySelect.innerHTML = '<option value="">Select Child Category</option>';
            subCategorySelect.disabled = !categoryId;
            childCategorySelect.disabled = true;
            
            if (categoryId) {
                fetch(`/admin/categories/${categoryId}/subcategories`)
                    .then(response => response.json())
                    .then(data => {
                        subCategorySelect.innerHTML = '<option value="">Select Sub Category</option>';
                        if (data.length > 0) {
                            data.forEach(subCategory => {
                                const option = document.createElement('option');
                                option.value = subCategory.id;
                                option.textContent = subCategory.name;
                                subCategorySelect.appendChild(option);
                            });
                        } else {
                            const option = document.createElement('option');
                            option.value = '';
                            option.textContent = 'No subcategories available';
                            option.disabled = true;
                            subCategorySelect.appendChild(option);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        subCategorySelect.innerHTML = '<option value="">Error loading subcategories</option>';
                    });
            } else {
                subCategorySelect.innerHTML = '<option value="">Select Sub Category</option>';
            }
        });
        
        // Trigger change on page load if category is pre-selected
        if (categorySelect.value) {
            categorySelect.dispatchEvent(new Event('change'));
        }
    }
    
    if (subCategorySelect) {
        subCategorySelect.addEventListener('change', function() {
            const subCategoryId = this.value;
            
            childCategorySelect.innerHTML = '<option value="">Select Child Category</option>';
            childCategorySelect.disabled = !subCategoryId;
            
            if (subCategoryId) {
                fetch(`/admin/subcategories/${subCategoryId}/childcategories`)
                    .then(response => response.json())
                    .then(data => {
                        childCategorySelect.innerHTML = '<option value="">Select Child Category</option>';
                        if (data.length > 0) {
                            data.forEach(childCategory => {
                                const option = document.createElement('option');
                                option.value = childCategory.id;
                                option.textContent = childCategory.name;
                                childCategorySelect.appendChild(option);
                            });
                        } else {
                            const option = document.createElement('option');
                            option.value = '';
                            option.textContent = 'No child categories available';
                            option.disabled = true;
                            childCategorySelect.appendChild(option);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        childCategorySelect.innerHTML = '<option value="">Error loading child categories</option>';
                    });
            } else {
                childCategorySelect.innerHTML = '<option value="">Select Child Category</option>';
            }
        });
    }
});
</script>
@endpush
@endsection

