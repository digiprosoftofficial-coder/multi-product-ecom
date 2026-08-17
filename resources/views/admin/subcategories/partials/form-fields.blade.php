@php
    $isEdit = !empty($subcategory);
    $submitted = $isEdit
        ? old('form_type') === 'edit_subcategory' && (string) old('edit_subcategory_id') === (string) $subcategory->id
        : old('form_type') === 'create_subcategory';
    $name = $submitted ? old('name', $isEdit ? $subcategory->name : '') : ($isEdit ? $subcategory->name : '');
    $description = $submitted ? old('description', $isEdit ? $subcategory->description : '') : ($isEdit ? $subcategory->description : '');
    $status = (string) ($submitted ? old('status', $isEdit ? $subcategory->status : '1') : ($isEdit ? $subcategory->status : '1'));
    $categoryId = (string) ($submitted
        ? old('category_id', $isEdit ? $subcategory->category_id : ($defaultCategoryId ?? ''))
        : ($isEdit ? $subcategory->category_id : ($defaultCategoryId ?? '')));
    $fieldId = $isEdit ? 'sub-'.$subcategory->id : 'sub-create';
@endphp

<div class="mb-3">
    <label for="category_id-{{ $fieldId }}" class="form-label">Parent Category <span class="text-danger">*</span></label>
    <select class="form-select @error('category_id') {{ $submitted ? 'is-invalid' : '' }} @enderror"
            id="category_id-{{ $fieldId }}" name="category_id" required>
        <option value="">Select Parent Category</option>
        @foreach($categories as $parentCategory)
            <option value="{{ $parentCategory->id }}" {{ (string) $parentCategory->id === $categoryId ? 'selected' : '' }}>
                {{ $parentCategory->name }}
            </option>
        @endforeach
    </select>
    @if($submitted)
        @error('category_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    @endif
</div>

<div class="mb-3">
    <label for="name-{{ $fieldId }}" class="form-label">Subcategory Name <span class="text-danger">*</span></label>
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

@if($isEdit && $subcategory->image)
    <div class="mb-3">
        <label class="form-label">Current Image</label>
        <div>
            <img src="{{ asset('uploads/categories/thumbnails/' . $subcategory->image) }}"
                 alt="{{ $subcategory->name }}"
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
