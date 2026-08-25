@php
    $slides = \App\Support\Homepage::slides();
    $multiple = count($slides) > 1;
    $showDots = \App\Support\Homepage::enabled('home_hero_show_dots');
    $showArrows = \App\Support\Homepage::enabled('home_hero_show_arrows');
    $showOverlay = \App\Support\Homepage::enabled('home_hero_show_overlay');
    $carouselId = 'homeHeroCarousel';
@endphp

<section class="hero-carousel-section">
    <div id="{{ $carouselId }}" class="carousel slide @if(\App\Support\Homepage::heroAutoplay() && $multiple) carousel-fade @endif" @if(\App\Support\Homepage::heroAutoplay() && $multiple) data-bs-ride="carousel" data-bs-interval="{{ \App\Support\Homepage::heroIntervalMs() }}" @endif>
        @if($multiple && $showDots)
            <div class="carousel-indicators hero-carousel-indicators">
                @foreach($slides as $index => $slide)
                    <button type="button" data-bs-target="#{{ $carouselId }}" data-bs-slide-to="{{ $index }}" @if($index === 0) class="active" aria-current="true" @endif aria-label="Slide {{ $index + 1 }}"></button>
                @endforeach
            </div>
        @endif

        <div class="carousel-inner">
            @foreach($slides as $index => $slide)
                @php
                    $hasContent = $slide['show_content'] && (
                        $slide['title'] || $slide['subtitle'] || $slide['btn1_text'] || $slide['btn2_text']
                    );
                    $desktopUrl = \App\Support\Homepage::slideImageUrl($slide['image'] ?? null);
                    $mobileUrl = ! empty($slide['image_mobile'])
                        ? \App\Support\Homepage::slideImageUrl($slide['image_mobile'])
                        : $desktopUrl;
                    $hasMobileImage = ! empty($slide['image_mobile']);
                @endphp
                <div class="carousel-item hero-carousel-item @if($hasMobileImage) has-mobile-image @endif @if($index === 0) active @endif"
                     style="--hero-bg-desktop: url('{{ $desktopUrl }}'); --hero-bg-mobile: url('{{ $mobileUrl }}'); background-image: var(--hero-bg-desktop);">
                    @if($showOverlay)
                        <div class="hero-carousel-overlay" style="background: {{ \App\Support\Homepage::heroOverlayBackground() }};"></div>
                    @endif
                    @if($hasContent)
                        <div class="hero-carousel-content">
                            <div class="container-lg">
                                <div class="row">
                                    <div class="col-lg-7 col-xl-6 hero-copy-col">
                                        @if($slide['title'])
                                            <h2 class="hero-title ls-1 mb-2 mb-md-3" style="color: {{ $slide['title_color'] }}">{!! \App\Support\Homepage::renderHeroTitle($slide) !!}</h2>
                                        @endif
                                        @if($slide['subtitle'])
                                            <p class="hero-subtitle mb-0" style="color: {{ $slide['subtitle_color'] }}">{{ $slide['subtitle'] }}</p>
                                        @endif
                                        <div class="d-flex flex-wrap gap-2 gap-md-3 hero-cta-row">
                                            @if($slide['btn1_text'])
                                                <a href="{{ \App\Support\Homepage::heroButtonUrl($slide['btn1_url'], route('products.index')) }}" class="btn btn-primary text-uppercase rounded-pill hero-cta-btn">{{ $slide['btn1_text'] }}</a>
                                            @endif
                                            @if($slide['btn2_text'])
                                                <a href="{{ \App\Support\Homepage::heroButtonUrl($slide['btn2_url'], route('register')) }}" class="btn btn-dark text-uppercase rounded-pill hero-cta-btn">{{ $slide['btn2_text'] }}</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        @if($multiple && $showArrows)
            <button class="carousel-control-prev hero-carousel-control hero-carousel-control-prev" type="button" data-bs-target="#{{ $carouselId }}" data-bs-slide="prev">
                <span class="hero-carousel-control-btn" aria-hidden="true">
                    <i class="fa-solid fa-chevron-left"></i>
                </span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next hero-carousel-control hero-carousel-control-next" type="button" data-bs-target="#{{ $carouselId }}" data-bs-slide="next">
                <span class="hero-carousel-control-btn" aria-hidden="true">
                    <i class="fa-solid fa-chevron-right"></i>
                </span>
                <span class="visually-hidden">Next</span>
            </button>
        @endif
    </div>

    <div class="container-lg">
        <div class="row my-3 my-md-4 my-lg-5 g-3 hero-stats-row">
            @foreach([1,2,3] as $i)
                <div class="col-4 col-md-4">
                    <div class="row text-dark g-1 g-md-2 align-items-center">
                        <div class="col-12 col-sm-auto"><p class="hero-stat-value fw-bold lh-sm mb-0">{{ \App\Support\Homepage::get('home_stat'.$i.'_value') }}</p></div>
                        <div class="col"><p class="text-uppercase lh-sm mb-0 hero-stat-label">{{ \App\Support\Homepage::get('home_stat'.$i.'_label') }}</p></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

@once
@push('styles')
<style>
    .hero-carousel-section {
        background: #f8faf9;
    }
    .hero-carousel-item {
        position: relative;
        min-height: 720px;
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
        touch-action: pan-y;
    }
    @media (max-width: 767.98px) {
        .hero-carousel-item.has-mobile-image {
            background-image: var(--hero-bg-mobile) !important;
            background-position: center top;
        }
    }
    .hero-carousel-overlay {
        position: absolute;
        inset: 0;
        pointer-events: none;
    }
    .hero-carousel-content {
        position: relative;
        z-index: 1;
        min-height: 720px;
        display: flex;
        align-items: center;
    }
    .hero-copy-col {
        padding-top: 3rem;
        padding-bottom: 2rem;
        margin-top: 1.5rem;
    }
    .hero-title {
        font-size: clamp(1.75rem, 5vw, 4.5rem);
        font-weight: 700;
        line-height: 1.15;
    }
    .hero-subtitle {
        font-size: clamp(0.95rem, 2.2vw, 1.35rem);
        line-height: 1.45;
        max-width: 36rem;
    }
    .hero-cta-btn {
        font-size: 0.85rem;
        padding: 0.7rem 1.35rem;
        margin-top: 0.85rem;
    }
    .hero-stat-value {
        font-size: clamp(1.35rem, 3vw, 2.5rem);
    }
    .hero-stat-label {
        font-size: clamp(0.7rem, 1.5vw, 0.95rem);
    }
    .hero-carousel-indicators [data-bs-target] {
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }
    .hero-carousel-control {
        width: auto;
        opacity: 1;
        z-index: 2;
    }
    .hero-carousel-control-prev {
        left: 24px;
    }
    .hero-carousel-control-next {
        right: 24px;
    }
    .hero-carousel-control-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: #6BB252;
        color: #fff;
        font-size: 1.1rem;
        line-height: 1;
        box-shadow: 0 4px 14px rgba(21, 128, 61, 0.35);
        transition: background-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hero-carousel-control:hover .hero-carousel-control-btn,
    .hero-carousel-control:focus .hero-carousel-control-btn {
        background: #5a9a45;
        color: #fff;
        transform: scale(1.04);
        box-shadow: 0 6px 18px rgba(21, 128, 61, 0.42);
    }
    .hero-carousel-control:focus {
        box-shadow: none;
    }
    @media (max-width: 991.98px) {
        .hero-carousel-item,
        .hero-carousel-content {
            min-height: min(520px, 70vh);
        }
        .hero-copy-col {
            padding-top: 2rem;
            padding-bottom: 1.5rem;
            margin-top: 0.5rem;
        }
    }
    @media (max-width: 767.98px) {
        .hero-carousel-item,
        .hero-carousel-content {
            min-height: min(400px, 62vh);
        }
        .hero-copy-col {
            padding-top: 1.25rem;
            padding-bottom: 1.25rem;
            margin-top: 0;
        }
        .hero-cta-btn {
            font-size: 0.78rem;
            padding: 0.55rem 1.1rem;
            margin-top: 0.65rem;
        }
        .hero-carousel-control-prev {
            left: 10px;
        }
        .hero-carousel-control-next {
            right: 10px;
        }
        .hero-carousel-control-btn {
            width: 40px;
            height: 40px;
            font-size: 0.9rem;
        }
        .hero-stats-row .col-4 {
            text-align: center;
        }
    }
</style>
@endpush

@if($multiple)
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var carouselEl = document.getElementById(@json($carouselId));
    if (!carouselEl || carouselEl.querySelectorAll('.carousel-item').length <= 1) return;
    if (!window.bootstrap || typeof bootstrap.Carousel !== 'function') return;

    var carousel = bootstrap.Carousel.getOrCreateInstance(carouselEl);
    var startX = 0;
    var startY = 0;
    var tracking = false;

    carouselEl.addEventListener('touchstart', function (e) {
        if (!e.changedTouches || !e.changedTouches.length) return;
        startX = e.changedTouches[0].clientX;
        startY = e.changedTouches[0].clientY;
        tracking = true;
    }, { passive: true });

    carouselEl.addEventListener('touchend', function (e) {
        if (!tracking || !e.changedTouches || !e.changedTouches.length) return;
        tracking = false;

        var diffX = e.changedTouches[0].clientX - startX;
        var diffY = e.changedTouches[0].clientY - startY;

        if (Math.abs(diffX) < 45 || Math.abs(diffX) <= Math.abs(diffY)) return;

        if (diffX > 0) {
            carousel.prev();
        } else {
            carousel.next();
        }
    }, { passive: true });
});
</script>
@endpush
@endif

@endonce
