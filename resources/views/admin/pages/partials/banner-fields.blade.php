@php
    $pageKey = $pageKey ?? 'about';
    $label = $label ?? ucfirst($pageKey);
    $enabledKey = $pageKey.'_banner_enabled';
    $enabled = old($enabledKey, $pages[$enabledKey] ?? '1') === '1';
@endphp

<div class="card mb-4">
    <div class="card-header d-flex align-items-center justify-content-between gap-2">
        <h5 class="mb-0">Top banner</h5>
        <div class="form-check form-switch mb-0">
            <input type="hidden" name="{{ $enabledKey }}" value="0">
            <input class="form-check-input" type="checkbox" name="{{ $enabledKey }}" value="1" id="{{ $enabledKey }}" {{ $enabled ? 'checked' : '' }}>
            <label class="form-check-label" for="{{ $enabledKey }}">Show banner</label>
        </div>
    </div>
    <div class="card-body">
        <p class="text-muted small mb-3">Banner height is 220px on desktop. Recommended upload: 1920 × 440 px.</p>
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
