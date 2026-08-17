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

                    @include('admin.products.partials.category-picker')

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
                               <small class="form-text text-muted">Original price before discount</small>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if(compare_price_enabled())
                    <div class="mb-3">
                        <label for="compare_price" class="form-label">Compare Price (MRP)</label>
                        <input type="number" step="0.01" class="form-control @error('compare_price') is-invalid @enderror" 
                               id="compare_price" name="compare_price" value="{{ old('compare_price') }}">
                        <small class="form-text text-muted">Input this price always higher than the price</small>
                        @error('compare_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @endif

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
                               id="discount_price_display" value="{{ old('discount_price') }}" readonly>
                        <input type="hidden" id="discount_price" name="discount_price" value="{{ old('discount_price') }}">
                        <small class="form-text text-muted">Auto-calculated from discount percentage</small>
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
    const priceInput = document.getElementById('price');
    const comparePriceInput = document.getElementById('compare_price');
    const discountPercentageInput = document.getElementById('discount_percentage');

    function calculateDiscountPrice() {
        const priceVal = parseFloat(priceInput.value) || 0;
        const discountPercentage = parseFloat(discountPercentageInput.value) || 0;
        const discountPriceDisplay = document.getElementById('discount_price_display');
        const discountPriceHidden = document.getElementById('discount_price');

        if (priceVal > 0 && discountPercentage > 0 && discountPercentage <= 100) {
            const discountAmount = (priceVal * discountPercentage) / 100;
            const finalPriceValue = (priceVal - discountAmount).toFixed(2);
            if (discountPriceDisplay) discountPriceDisplay.value = finalPriceValue;
            if (discountPriceHidden) discountPriceHidden.value = finalPriceValue;
        } else {
            if (discountPriceDisplay) discountPriceDisplay.value = '';
            if (discountPriceHidden) discountPriceHidden.value = '';
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

