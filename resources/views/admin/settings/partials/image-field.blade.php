@php
    $inputName = $inputName ?? 'image';
    $inputId = $inputId ?? $inputName;
    $removeName = $removeName ?? 'remove_'.$inputName;
    $accept = $accept ?? 'image/*';
    $hint = $hint ?? '';
    $alt = $alt ?? 'Preview';
    $variant = $variant ?? 'logo';
    $hasImage = ! empty($url);
@endphp

<div class="brand-image-field mb-3" data-variant="{{ $variant }}">
    @if(! empty($label))
        <div class="form-label">{{ $label }}</div>
    @endif

    <div class="brand-image-card {{ $errors->has($inputName) ? 'is-invalid' : '' }}">
        <div class="brand-image-stage">
            <input type="hidden" name="{{ $removeName }}" value="0" class="js-brand-remove">
            <img class="brand-image-preview js-brand-preview {{ $hasImage ? '' : 'd-none' }}"
                 src="{{ $url ?: '' }}"
                 alt="{{ $alt }}">
            <div class="brand-image-empty js-brand-empty {{ $hasImage ? 'd-none' : '' }}">
                <i class="fas {{ $variant === 'icon' ? 'fa-image' : 'fa-images' }}"></i>
                <span>No image</span>
            </div>
            <button type="button" class="brand-image-remove js-brand-clear {{ $hasImage ? '' : 'd-none' }}" title="Remove image" aria-label="Remove image">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="brand-image-actions">
            <label class="btn btn-sm btn-outline-secondary mb-0" for="{{ $inputId }}">
                <i class="fas fa-upload me-1"></i>
                <span class="js-brand-action-label">{{ $hasImage ? 'Change' : 'Upload' }}</span>
            </label>
            <span class="brand-image-filename js-brand-filename text-muted">{{ $hasImage ? 'Current image' : 'No file chosen' }}</span>
            <input type="file"
                   class="d-none js-brand-file @error($inputName) is-invalid @enderror"
                   id="{{ $inputId }}"
                   name="{{ $inputName }}"
                   accept="{{ $accept }}">
        </div>
    </div>
    @if($hint !== '')
        <div class="form-text">{{ $hint }}</div>
    @endif
    @error($inputName)
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>
