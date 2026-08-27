@php
    $pageKey = $pageKey ?? 'privacy';
    $label = $label ?? ucfirst($pageKey);
    $previewUrl = $previewUrl ?? '#';
    $content = trim(strip_tags($pages[$pageKey.'_content'] ?? ''));
    $hasContent = $content !== '';
    $bannerImage = setting_image_url($pages[$pageKey.'_banner_image'] ?? null);
    $enabledKey = $pageKey.'_banner_enabled';
    $bannerEnabled = old($enabledKey, $pages[$enabledKey] ?? '1') === '1';
@endphp

<div class="info-page-section">
    <div class="info-page-section-head">
        <div>
            <h5 class="info-page-section-title mb-1">{{ $label }}</h5>
            <p class="text-muted small mb-0">Edit the top banner and page body content.</p>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="badge rounded-pill {{ $hasContent ? 'text-bg-success' : 'text-bg-secondary' }}">
                {{ $hasContent ? 'Published' : 'Empty' }}
            </span>
            <a href="{{ $previewUrl }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-external-link-alt me-1"></i> View live
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="info-banner-card h-100">
                <div class="info-banner-preview @if($bannerImage) has-image @endif"
                     @if($bannerImage) style="background-image: url('{{ $bannerImage }}');" @endif>
                    <div class="info-banner-preview-overlay"></div>
                    <div class="info-banner-preview-text">
                        <span class="info-banner-preview-label">Banner preview</span>
                        <strong>{{ old($pageKey.'_banner_title', $pages[$pageKey.'_banner_title'] ?? $label) }}</strong>
                        <small>{{ old($pageKey.'_banner_subtitle', $pages[$pageKey.'_banner_subtitle'] ?? '') }}</small>
                    </div>
                </div>

                <div class="info-banner-fields">
                    <div class="d-flex align-items-center justify-content-between mb-3 gap-2">
                        <h6 class="mb-0">Top banner</h6>
                        <div class="form-check form-switch mb-0">
                            <input type="hidden" name="{{ $enabledKey }}" value="0">
                            <input class="form-check-input" type="checkbox" name="{{ $enabledKey }}" value="1" id="{{ $enabledKey }}" {{ $bannerEnabled ? 'checked' : '' }}>
                            <label class="form-check-label small" for="{{ $enabledKey }}">Show</label>
                        </div>
                    </div>
                    <p class="text-muted small mb-3">220px height · Recommended: 1920 × 440 px</p>
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="{{ $pageKey }}_banner_title" class="form-control js-banner-preview-input"
                               data-preview-title="{{ $pageKey }}"
                               value="{{ old($pageKey.'_banner_title', $pages[$pageKey.'_banner_title'] ?? '') }}"
                               placeholder="{{ $label }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subtitle</label>
                        <input type="text" name="{{ $pageKey }}_banner_subtitle" class="form-control js-banner-preview-input"
                               data-preview-subtitle="{{ $pageKey }}"
                               value="{{ old($pageKey.'_banner_subtitle', $pages[$pageKey.'_banner_subtitle'] ?? '') }}"
                               placeholder="Short description for visitors">
                    </div>
                    <div>
                        <label class="form-label">Background image</label>
                        @include('admin.settings.partials.image-preview', [
                            'url' => $bannerImage,
                            'alt' => $label.' banner',
                            'removeName' => 'remove_'.$pageKey.'_banner_image',
                            'imgStyle' => 'max-height: 72px; max-width: 100%; object-fit: cover; border-radius: 8px;',
                        ])
                        <input type="file" name="{{ $pageKey }}_banner_image" class="form-control mt-2" accept="image/*">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="info-content-card h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="mb-0">Page content</h6>
                    <span class="text-muted small">Rich text editor</span>
                </div>
                <textarea class="form-control js-page-editor" name="{{ $pageKey }}_content" id="{{ $pageKey }}_content" rows="12">{{ old($pageKey.'_content', $pages[$pageKey.'_content'] ?? '') }}</textarea>
            </div>
        </div>
    </div>
</div>
