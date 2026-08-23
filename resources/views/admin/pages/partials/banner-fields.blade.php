@php
    $pageKey = $pageKey ?? 'about';
    $label = $label ?? ucfirst($pageKey);
@endphp

<div class="card mb-4">
    <div class="card-header"><h5 class="mb-0">Top banner</h5></div>
    <div class="card-body">
        <p class="text-muted small mb-3">Banner height is 300px on desktop. Recommended upload: 1920 × 600 px.</p>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Banner title</label>
                <input type="text" name="{{ $pageKey }}_banner_title" class="form-control"
                       value="{{ old($pageKey.'_banner_title', $pages[$pageKey.'_banner_title'] ?? '') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Banner subtitle</label>
                <input type="text" name="{{ $pageKey }}_banner_subtitle" class="form-control"
                       value="{{ old($pageKey.'_banner_subtitle', $pages[$pageKey.'_banner_subtitle'] ?? '') }}">
            </div>
            <div class="col-12">
                <label class="form-label">Banner image</label>
                @include('admin.settings.partials.image-preview', [
                    'url' => setting_image_url($pages[$pageKey.'_banner_image'] ?? null),
                    'alt' => $label.' banner',
                    'removeName' => 'remove_'.$pageKey.'_banner_image',
                    'imgStyle' => 'max-height: 90px; max-width: 280px; object-fit: cover;',
                ])
                <input type="file" name="{{ $pageKey }}_banner_image" class="form-control" accept="image/*">
            </div>
        </div>
    </div>
</div>
