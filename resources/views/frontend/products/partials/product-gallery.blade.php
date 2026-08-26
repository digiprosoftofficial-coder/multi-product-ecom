@php
    $images = $product->images;
    $hasMultiple = $images->count() > 1;
@endphp

<div class="product-detail-gallery">
    @if($images->count() > 0)
        <div class="product-detail-gallery-main-wrap">
            <div class="swiper product-detail-gallery-main border rounded-3 overflow-hidden">
                <div class="swiper-wrapper">
                    @foreach($images as $image)
                        <div class="swiper-slide">
                            <div class="product-zoom-wrap">
                                <img src="{{ $image->image_url }}"
                                     alt="{{ $product->name }}">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            @if($hasMultiple)
                <button type="button" class="product-detail-gallery-prev" aria-label="Previous image">
                    <span><i class="fa-solid fa-chevron-left"></i></span>
                </button>
                <button type="button" class="product-detail-gallery-next" aria-label="Next image">
                    <span><i class="fa-solid fa-chevron-right"></i></span>
                </button>
            @endif
        </div>

        @if($hasMultiple)
            <div class="swiper product-detail-gallery-thumbs mt-3">
                <div class="swiper-wrapper">
                    @foreach($images as $image)
                        <div class="swiper-slide">
                            <button type="button" class="product-detail-thumb-btn">
                                <img src="{{ $image->image_url }}"
                                     alt="{{ $product->name }} thumbnail">
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @elseif($product->thumbnail)
        <div class="product-detail-gallery-main-wrap border rounded-3 overflow-hidden">
            <div class="product-zoom-wrap">
                <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}">
            </div>
        </div>
    @else
        <div class="bg-light border rounded-3 d-flex align-items-center justify-content-center product-detail-gallery-empty">
            <span class="text-muted">No Image Available</span>
        </div>
    @endif
</div>

@once
@push('styles')
<style>
    .product-detail-gallery-main-wrap {
        position: relative;
    }
    .product-detail-gallery-main {
        background: #f8f9fa;
    }
    .product-zoom-wrap {
        height: 480px;
        overflow: hidden;
        background: #f8f9fa;
        cursor: zoom-in;
    }
    .product-zoom-wrap img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: transform .25s ease;
        will-change: transform;
    }
    .product-detail-gallery-prev,
    .product-detail-gallery-next {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 3;
        border: 0;
        background: transparent;
        padding: 0;
        opacity: 1;
    }
    .product-detail-gallery-prev {
        left: 14px;
    }
    .product-detail-gallery-next {
        right: 14px;
    }
    .product-detail-gallery-prev span,
    .product-detail-gallery-next span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #6BB252;
        color: #fff;
        font-size: 1rem;
        line-height: 1;
        box-shadow: 0 4px 14px rgba(21, 128, 61, 0.35);
        transition: background-color .2s ease, transform .2s ease, box-shadow .2s ease;
    }
    .product-detail-gallery-prev:hover span,
    .product-detail-gallery-next:hover span,
    .product-detail-gallery-prev:focus span,
    .product-detail-gallery-next:focus span {
        background: #5a9a45;
        transform: scale(1.05);
        box-shadow: 0 6px 18px rgba(21, 128, 61, 0.42);
    }
    .product-detail-gallery-thumbs .swiper-slide {
        width: auto;
    }
    .product-detail-thumb-btn {
        display: block;
        width: 100%;
        padding: 0;
        border: 2px solid #e2ebe5;
        border-radius: 10px;
        overflow: hidden;
        background: #fff;
        cursor: pointer;
        transition: border-color .15s ease, opacity .15s ease;
        opacity: .78;
    }
    .product-detail-gallery-thumbs .swiper-slide-thumb-active .product-detail-thumb-btn {
        border-color: #6BB252;
        opacity: 1;
    }
    .product-detail-thumb-btn img {
        display: block;
        width: 100%;
        height: 84px;
        object-fit: cover;
    }
    .product-detail-gallery-empty {
        height: 420px;
    }
    @media (max-width: 767px) {
        .product-zoom-wrap {
            height: 360px;
        }
        .product-detail-gallery-prev {
            left: 8px;
        }
        .product-detail-gallery-next {
            right: 8px;
        }
        .product-detail-gallery-prev span,
        .product-detail-gallery-next span {
            width: 42px;
            height: 42px;
        }
        .product-detail-thumb-btn img {
            height: 72px;
        }
    }
</style>
@endpush
@endonce

@if($images->count() > 0)
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var mainEl = document.querySelector('.product-detail-gallery-main');
    var thumbsEl = document.querySelector('.product-detail-gallery-thumbs');

    if (mainEl && typeof Swiper !== 'undefined') {
        var thumbSwiper = null;
        if (thumbsEl) {
            thumbSwiper = new Swiper(thumbsEl, {
                spaceBetween: 12,
                slidesPerView: 4,
                freeMode: true,
                watchSlidesProgress: true,
                breakpoints: {
                    576: { slidesPerView: 5 },
                    768: { slidesPerView: 6 },
                },
            });
        }

        new Swiper(mainEl, {
            loop: true,
            spaceBetween: 0,
            effect: 'fade',
            fadeEffect: { crossFade: true },
            thumbs: thumbSwiper ? { swiper: thumbSwiper } : undefined,
            navigation: {
                nextEl: '.product-detail-gallery-next',
                prevEl: '.product-detail-gallery-prev',
            },
        });
    }

    document.querySelectorAll('.product-zoom-wrap').forEach(function (wrap) {
        var img = wrap.querySelector('img');
        if (!img) return;

        wrap.addEventListener('mousemove', function (e) {
            var rect = wrap.getBoundingClientRect();
            var x = ((e.clientX - rect.left) / rect.width) * 100;
            var y = ((e.clientY - rect.top) / rect.height) * 100;
            img.style.transformOrigin = x + '% ' + y + '%';
            img.style.transform = 'scale(2)';
        });

        wrap.addEventListener('mouseleave', function () {
            img.style.transform = 'scale(1)';
        });
    });
});
</script>
@endpush
@elseif($product->thumbnail)
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.product-zoom-wrap').forEach(function (wrap) {
        var img = wrap.querySelector('img');
        if (!img) return;

        wrap.addEventListener('mousemove', function (e) {
            var rect = wrap.getBoundingClientRect();
            var x = ((e.clientX - rect.left) / rect.width) * 100;
            var y = ((e.clientY - rect.top) / rect.height) * 100;
            img.style.transformOrigin = x + '% ' + y + '%';
            img.style.transform = 'scale(2)';
        });

        wrap.addEventListener('mouseleave', function () {
            img.style.transform = 'scale(1)';
        });
    });
});
</script>
@endpush
@endif
