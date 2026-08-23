@extends('admin.layouts.master')

@section('title', 'Info pages')
@section('page-title', 'Info pages')

@section('content')
<form action="{{ route('admin.pages.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    @include('admin.pages.partials.banner-fields', ['pageKey' => 'privacy', 'label' => 'Privacy policy', 'pages' => $pages])

    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0">Privacy policy content</h5></div>
        <div class="card-body">
            <textarea class="form-control js-page-editor" name="privacy_content" id="privacy_content" rows="10">{{ old('privacy_content', $pages['privacy_content']) }}</textarea>
        </div>
    </div>

    @include('admin.pages.partials.banner-fields', ['pageKey' => 'terms', 'label' => 'Terms & conditions', 'pages' => $pages])

    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0">Terms &amp; conditions content</h5></div>
        <div class="card-body">
            <textarea class="form-control js-page-editor" name="terms_content" id="terms_content" rows="10">{{ old('terms_content', $pages['terms_content']) }}</textarea>
        </div>
    </div>

    @include('admin.pages.partials.banner-fields', ['pageKey' => 'delivery', 'label' => 'Delivery information', 'pages' => $pages])

    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0">Delivery information content</h5></div>
        <div class="card-body">
            <textarea class="form-control js-page-editor" name="delivery_content" id="delivery_content" rows="10">{{ old('delivery_content', $pages['delivery_content']) }}</textarea>
        </div>
    </div>

    @include('admin.pages.partials.banner-fields', ['pageKey' => 'returns', 'label' => 'Product returns', 'pages' => $pages])

    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0">Product returns content</h5></div>
        <div class="card-body">
            <textarea class="form-control js-page-editor" name="returns_content" id="returns_content" rows="10">{{ old('returns_content', $pages['returns_content']) }}</textarea>
        </div>
    </div>

    <div class="d-flex gap-2 sticky-bottom bg-body py-3 border-top">
        <button type="submit" class="btn btn-primary">Save pages</button>
    </div>
</form>
@endsection

@include('admin.pages.partials.banner-assets')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tinymce@7.6.0/tinymce.min.js" referrerpolicy="origin"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof tinymce === 'undefined') return;
    tinymce.init({
        selector: 'textarea.js-page-editor',
        license_key: 'gpl',
        menubar: false,
        branding: false,
        promotion: false,
        height: 320,
        plugins: 'lists link',
        toolbar: 'undo redo | blocks | bold italic underline | bullist numlist | link | removeformat',
        setup: function (editor) {
            var form = editor.getElement().closest('form');
            if (!form) return;
            form.addEventListener('submit', function () {
                editor.save();
            });
        }
    });
});
</script>
@endpush
