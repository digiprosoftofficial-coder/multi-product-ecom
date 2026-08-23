@php
    $banner = $banner ?? \App\Support\PageBanner::for($page ?? 'shop');
    $title = $banner['title'] ?: ($fallbackTitle ?? '');
    $subtitle = $banner['subtitle'] ?? '';
    $image = $banner['image'] ?? null;
@endphp

<section class="page-top-banner @if($image) has-image @endif"
         @if($image) style="background-image: url('{{ $image }}');" @endif>
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
        height: 300px;
        display: flex;
        align-items: center;
        background-color: #1f3b2c;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        overflow: hidden;
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
        font-size: clamp(1.8rem, 3.4vw, 2.6rem);
        font-weight: 700;
        line-height: 1.2;
        color: #fff;
    }
    .page-top-banner-subtitle {
        margin: 0;
        color: rgba(255, 255, 255, 0.9);
        font-size: 1.05rem;
        line-height: 1.55;
    }
    @media (max-width: 767px) {
        .page-top-banner {
            height: 240px;
        }
        .page-top-banner-subtitle {
            font-size: .98rem;
        }
    }
</style>
@endpush
@endonce
