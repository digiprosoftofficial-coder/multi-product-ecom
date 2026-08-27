@extends('admin.layouts.master')

@section('title', 'Product page')
@section('page-title', 'Product page')

@section('content')
<form action="{{ route('admin.product-page.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="alert alert-light border mb-4">
        <i class="fas fa-info-circle text-success me-1"></i>
        On the product details page, the banner <strong>title</strong> is always the product name and the background uses the product image when available.
        Use the fields below for the default subtitle and fallback banner image.
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between gap-2">
            <h5 class="mb-0">Product details banner</h5>
            <div class="form-check form-switch mb-0">
                <input type="hidden" name="product_banner_enabled" value="0">
                <input class="form-check-input" type="checkbox" name="product_banner_enabled" value="1" id="product_banner_enabled" {{ old('product_banner_enabled', $pages['product_banner_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
                <label class="form-check-label" for="product_banner_enabled">Show banner</label>
            </div>
        </div>
        <div class="card-body">
            <p class="text-muted small mb-3">Banner height is 220px on desktop. Recommended upload: 1920 × 440 px.</p>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Default subtitle</label>
                    <input type="text" name="product_banner_subtitle" class="form-control"
                           value="{{ old('product_banner_subtitle', $pages['product_banner_subtitle'] ?? '') }}"
                           placeholder="Shown when the product has no category">
                    <div class="form-text">If the product belongs to a category, the category name is used instead.</div>
                </div>
                <div class="col-12">
                    <label class="form-label">Fallback banner image</label>
                    @include('admin.settings.partials.image-preview', [
                        'url' => setting_image_url($pages['product_banner_image'] ?? null),
                        'alt' => 'Product page banner',
                        'removeName' => 'remove_product_banner_image',
                        'imgStyle' => 'max-height: 90px; max-width: 280px; object-fit: cover;',
                    ])
                    <input type="file" name="product_banner_image" class="form-control" accept="image/*">
                    <div class="form-text">Used only when the product has no image.</div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 sticky-bottom bg-body py-3 border-top">
        <button type="submit" class="btn btn-primary">Save Product page</button>
    </div>
</form>
@endsection

@include('admin.pages.partials.banner-assets')
