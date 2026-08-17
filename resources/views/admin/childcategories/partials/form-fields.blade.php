@php
    $isEdit = !empty($childcategory);
    $submitted = $isEdit
        ? old('form_type') === 'edit_childcategory' && (string) old('edit_childcategory_id') === (string) $childcategory->id
        : old('form_type') === 'create_childcategory';
    $name = $submitted ? old('name', $isEdit ? $childcategory->name : '') : ($isEdit ? $childcategory->name : '');
    $description = $submitted ? old('description', $isEdit ? $childcategory->description : '') : ($isEdit ? $childcategory->description : '');
    $status = (string) ($submitted ? old('status', $isEdit ? $childcategory->status : '1') : ($isEdit ? $childcategory->status : '1'));
    $subCategoryId = (string) ($submitted
        ? old('sub_category_id', $isEdit ? $childcategory->sub_category_id : ($defaultSubCategoryId ?? ''))
        : ($isEdit ? $childcategory->sub_category_id : ($defaultSubCategoryId ?? '')));
    $fieldId = $isEdit ? 'child-'.$childcategory->id : 'child-create';
@endphp

<div class="mb-3">
    <label for="sub_category_id-{{ $fieldId }}" class="form-label">Parent Subcategory <span class="text-danger">*</span></label>
    <select class="form-select @error('sub_category_id') {{ $submitted ? 'is-invalid' : '' }} @enderror"
            id="sub_category_id-{{ $fieldId }}" name="sub_category_id" required>
        <option value="">Select Parent Subcategory</option>
        @foreach($subCategories as $parentSubCategory)
            <option value="{{ $parentSubCategory->id }}" {{ (string) $parentSubCategory->id === $subCategoryId ? 'selected' : '' }}>
                {{ $parentSubCategory->category->name ?? 'N/A' }} &gt; {{ $parentSubCategory->name }}
            </option>
        @endforeach
    </select>
    @if($submitted)
        @error('sub_category_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    @endif
</div>

<div class="mb-3">
    <label for="name-{{ $fieldId }}" class="form-label">Child Category Name <span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('name') {{ $submitted ? 'is-invalid' : '' }} @enderror"
           id="name-{{ $fieldId }}" name="name" value="{{ $name }}" required>
    @if($submitted)
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    @endif
</div>

<div class="mb-3">
    <label for="description-{{ $fieldId }}" class="form-label">Description</label>
    <textarea class="form-control @error('description') {{ $submitted ? 'is-invalid' : '' }} @enderror"
              id="description-{{ $fieldId }}" name="description" rows="3">{{ $description }}</textarea>
    @if($submitted)
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    @endif
</div>

@if($isEdit && $childcategory->image)
    <div class="mb-3">
        <label class="form-label">Current Image</label>
        <div>
            <img src="{{ asset('uploads/categories/thumbnails/' . $childcategory->image) }}"
                 alt="{{ $childcategory->name }}"
                 style="max-width: 160px; height: auto;">
        </div>
    </div>
@endif

<div class="mb-3">
    <label for="image-{{ $fieldId }}" class="form-label">{{ $isEdit ? 'New Image' : 'Image' }}</label>
    <input type="file" class="form-control @error('image') {{ $submitted ? 'is-invalid' : '' }} @enderror"
           id="image-{{ $fieldId }}" name="image" accept=".jpg,.jpeg,.png,.gif,.webp,image/webp">
    <div class="form-text">JPG, PNG, GIF or WebP. Max 2MB.</div>
    @if($submitted)
        @error('image')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    @endif
</div>

<div class="mb-0">
    <label for="status-{{ $fieldId }}" class="form-label">Status <span class="text-danger">*</span></label>
    <select class="form-select @error('status') {{ $submitted ? 'is-invalid' : '' }} @enderror"
            id="status-{{ $fieldId }}" name="status" required>
        <option value="1" {{ $status === '1' ? 'selected' : '' }}>Active</option>
        <option value="0" {{ $status === '0' ? 'selected' : '' }}>Inactive</option>
    </select>
    @if($submitted)
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    @endif
</div>
