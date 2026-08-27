@php
    $banner = $banner ?? \App\Support\PageBanner::for($page ?? 'shop');
    if (! ($banner['enabled'] ?? true)) {
        return;
    }
    $title = $banner['title'] ?: ($fallbackTitle ?? '');
    $subtitle = $banner['subtitle'] ?? '';
    $image = $banner['image'] ?? null;
    $height = $height ?? null;
@endphp

<section class="page-top-banner @if($image) has-image @endif @if($height) has-custom-height @endif"
         @if($image || $height) style="@if($image) background-image: url('{{ $image }}'); @endif @if($height) --page-banner-height: {{ $height }}px; @endif" @endif>
    <div class="page-top-banner-overlay"></div>
    <div class="container-lg page-top-banner-content">
        @if($title)
            <h1 class="page-top-banner-title">{{ $title }}</h1>
        @endif
        @if($subtitle)
            <p class="page-top-banner-subtitle">{{ $subtitle }}</p>
        @endif
    </div>
</section>

@once
@push('styles')
<style>
    .page-top-banner {
        position: relative;
        height: 220px;
        display: flex;
        align-items: center;
        background-color: #1f3b2c;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        overflow: hidden;
    }
    .page-top-banner.has-custom-height {
        height: var(--page-banner-height);
    }
    .page-top-banner:not(.has-image) {
        background-image: linear-gradient(135deg, #1f3b2c 0%, #2f6b45 55%, #6BB252 100%);
    }
    .page-top-banner-overlay {
        position: absolute;
        inset: 0;
        background: rgba(15, 23, 42, 0.45);
        pointer-events: none;
    }
    .page-top-banner:not(.has-image) .page-top-banner-overlay {
        background: rgba(15, 23, 42, 0.18);
    }
    .page-top-banner-content {
        position: relative;
        z-index: 1;
        color: #fff;
    }
    .page-top-banner-title {
        margin: 0 0 .4rem;
        font-size: clamp(1.65rem, 3vw, 2.35rem);
        font-weight: 700;
        line-height: 1.2;
        color: #fff;
    }
    .page-top-banner-subtitle {
        margin: 0;
        color: rgba(255, 255, 255, 0.9);
        font-size: 1.02rem;
        line-height: 1.55;
    }
    @media (max-width: 767px) {
        .page-top-banner:not(.has-custom-height) {
            height: 180px;
        }
        .page-top-banner.has-custom-height {
            height: var(--page-banner-height);
        }
        .page-top-banner-subtitle {
            font-size: .95rem;
        }
    }
</style>
@endpush
@endonce
