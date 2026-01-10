@extends('admin.layouts.master')

@section('title', 'Edit Product')
@section('page-title', 'Edit Product')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-1">Edit Product: {{ $product->name }}</h5>
    </div>
    <div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.products.update', ['product' => $product->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $product->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="sku" class="form-label">SKU</label>
                        <input type="text" class="form-control @error('sku') is-invalid @enderror" 
                               id="sku" name="sku" value="{{ old('sku', $product->sku) }}">
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
                                        <option value="{{ $category->id }}" 
                                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                                    @foreach($subCategories as $subCategory)
                                        <option value="{{ $subCategory->id }}" 
                                            {{ old('sub_category_id', $product->sub_category_id) == $subCategory->id ? 'selected' : '' }}>
                                            {{ $subCategory->name }}
                                        </option>
                                    @endforeach
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
                                    @if($product->childCategory)
                                        <option value="{{ $product->childCategory->id }}" selected>{{ $product->childCategory->name }}</option>
                                    @endif
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
                                  id="description" name="description" rows="5">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                               id="price" name="price" value="{{ old('price', $product->price) }}" required>
                               <small class="form-text text-muted">Original price before discount</small>
                               @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="compare_price" class="form-label">Compare Price (MRP)</label>
                        <input type="number" step="0.01" class="form-control @error('compare_price') is-invalid @enderror" 
                               id="compare_price" name="compare_price" value="{{ old('compare_price', $product->compare_price) }}">
                        <small class="form-text text-muted">Input this price always higher than the price</small>
                        
                        @error('compare_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="discount_percentage" class="form-label">Discount Percentage (%)</label>
                        <input type="number" step="0.01" min="0" max="100" class="form-control @error('discount_percentage') is-invalid @enderror" 
                               id="discount_percentage" name="discount_percentage" value="{{ old('discount_percentage') }}" 
                               placeholder="Enter discount %">
                        <small class="form-text text-muted">Discount will be calculated from Price</small>
                        @error('discount_percentage')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="discount_price" class="form-label">Discount Price (Calculated)</label>
                        <input type="number" step="0.01" class="form-control @error('discount_price') is-invalid @enderror" 
                               id="discount_price_display" value="{{ old('discount_price', $product->discount_price) }}" readonly>
                        <input type="hidden" id="discount_price" name="discount_price" value="{{ old('discount_price', $product->discount_price) }}">
                        <small class="form-text text-muted">Auto-calculated from discount percentage</small>
                        @error('discount_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('stock') is-invalid @enderror" 
                               id="stock" name="stock" value="{{ old('stock', $product->stock) }}" required>
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" name="status" required>
                            <option value="1" {{ old('status', $product->status) == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status', $product->status) == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            @if($product->thumbnail)
                <div class="mb-3">
                    <label class="form-label">Current Thumbnail</label>
                    <div>
                        <img src="{{ asset('uploads/products/thumbnails/' . $product->thumbnail) }}" 
                             alt="{{ $product->name }}" 
                             style="max-width: 200px; height: auto;">
                    </div>
                </div>
            @endif

            <div class="mb-3">
                <label for="thumbnail" class="form-label">New Thumbnail Image</label>
                <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" 
                       id="thumbnail" name="thumbnail" accept="image/*">
                @error('thumbnail')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            @if($product->images->count() > 0)
                <div class="mb-3">
                    <label class="form-label">Current Product Images</label>
                    <div class="row">
                        @foreach($product->images as $image)
                            <div class="col-md-3 mb-2">
                                <img src="{{ asset('uploads/products/thumbnails/' . $image->filename) }}" 
                                     alt="Product Image" 
                                     class="img-thumbnail" 
                                     style="width: 100%; height: 150px; object-fit: cover;">
                                <button type="submit"
                                        form="delete-image-form-{{ $image->id }}"
                                        class="btn btn-sm btn-danger w-100 mt-2"
                                        onclick="return confirm('Are you sure?')">
                                    Delete
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="mb-3">
                <label for="images" class="form-label">Add More Product Images</label>
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
                       id="meta_title" name="meta_title" value="{{ old('meta_title', $product->meta_title) }}">
                @error('meta_title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="meta_description" class="form-label">Meta Description</label>
                <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                          id="meta_description" name="meta_description" rows="2">{{ old('meta_description', $product->meta_description) }}</textarea>
                @error('meta_description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Update Product</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@if($product->images->count() > 0)
    @foreach($product->images as $image)
        <form id="delete-image-form-{{ $image->id }}"
              action="{{ route('admin.products.images.destroy', $image) }}"
              method="POST"
              class="d-none">
            @csrf
            @method('DELETE')
        </form>
    @endforeach
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category_id');
    const subCategorySelect = document.getElementById('sub_category_id');
    const childCategorySelect = document.getElementById('child_category_id');
    const currentSubCategoryId = {{ $product->sub_category_id ?? 'null' }};
    const currentChildCategoryId = {{ $product->child_category_id ?? 'null' }};
    
    // Load subcategories when category changes
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
                                if (subCategory.id == currentSubCategoryId) {
                                    option.selected = true;
                                }
                                subCategorySelect.appendChild(option);
                            });
                            if (subCategorySelect.value) {
                                subCategorySelect.dispatchEvent(new Event('change'));
                            }
                        }
                    })
                    .catch(() => {
                        subCategorySelect.innerHTML = '<option value="">Error loading subcategories</option>';
                    });
            }
        });
        
        // Load subcategories on page load
        if (categorySelect.value) {
            categorySelect.dispatchEvent(new Event('change'));
        }
    }
    
    // Load child categories when subcategory changes
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
                                if (childCategory.id == currentChildCategoryId) {
                                    option.selected = true;
                                }
                                childCategorySelect.appendChild(option);
                            });
                        }
                    })
                    .catch(() => {
                        childCategorySelect.innerHTML = '<option value="">Error loading child categories</option>';
                    });
            }
        });
    }
    
    // Discount percentage calculation
    const priceInput = document.getElementById('price');
    const comparePriceInput = document.getElementById('compare_price');
    const discountPercentageInput = document.getElementById('discount_percentage');
    const discountPriceDisplay = document.getElementById('discount_price_display');
    const discountPriceHidden = document.getElementById('discount_price');
    
    // Calculate initial discount percentage from existing discount_price (base on price)
    @if($product->price && $product->discount_price)
        const basePriceInit = {{ $product->price }};
        const discountPriceInit = {{ $product->discount_price }};
        if (basePriceInit > 0 && discountPriceInit > 0 && basePriceInit > discountPriceInit) {
            const calculatedPercentage = ((basePriceInit - discountPriceInit) / basePriceInit) * 100;
            if (discountPercentageInput) {
                discountPercentageInput.value = calculatedPercentage.toFixed(2);
            }
        }
    @endif
    
    function calculateDiscountPrice() {
        if (!comparePriceInput || !priceInput || !discountPercentageInput || !discountPriceDisplay || !discountPriceHidden) {
            return;
        }
        
        const priceVal = parseFloat(priceInput.value) || 0; // base on price only
        const discountPercentageVal = parseFloat(discountPercentageInput.value) || 0;
        
        if (priceVal > 0 && discountPercentageVal > 0 && discountPercentageVal <= 100) {
            const discountAmount = (priceVal * discountPercentageVal) / 100;
            const finalPriceValue = (priceVal - discountAmount).toFixed(2);
            discountPriceDisplay.value = finalPriceValue;
            discountPriceHidden.value = finalPriceValue;
        } else {
            discountPriceDisplay.value = '';
            discountPriceHidden.value = '';
        }
    }
    
    if (comparePriceInput) {
        comparePriceInput.addEventListener('input', calculateDiscountPrice);
    }
    
    if (priceInput) {
        priceInput.addEventListener('input', calculateDiscountPrice);
    }
    
    if (discountPercentageInput) {
        discountPercentageInput.addEventListener('input', calculateDiscountPrice);
    }
});
</script>
@endpush
@endsection
