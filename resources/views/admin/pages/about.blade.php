@extends('admin.layouts.master')

@section('title', 'About')
@section('page-title', 'About')

@section('content')
<form action="{{ route('admin.about.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    @include('admin.pages.partials.banner-fields', ['pageKey' => 'about', 'label' => 'About', 'pages' => $pages])

    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0">About content</h5></div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Page title</label>
                <input type="text" name="about_title" class="form-control" value="{{ old('about_title', $pages['about_title']) }}">
                <div class="form-text">Used as fallback when banner title is empty.</div>
            </div>
            <label class="form-label">Content</label>
            <textarea class="form-control js-page-editor" name="about_content" id="about_content" rows="10">{{ old('about_content', $pages['about_content']) }}</textarea>
        </div>
    </div>

    <div class="d-flex gap-2 sticky-bottom bg-body py-3 border-top">
        <button type="submit" class="btn btn-primary">Save About page</button>
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
