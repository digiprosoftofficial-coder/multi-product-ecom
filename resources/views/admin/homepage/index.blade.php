@extends('admin.layouts.master')

@section('title', 'Homepage')
@section('page-title', 'Homepage')

@section('content')
<form action="{{ route('admin.homepage.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0">Hero carousel</h5>
            <button type="button" class="btn btn-sm btn-outline-primary" id="addHeroSlide" {{ count($heroSlides) >= 5 ? 'disabled' : '' }}>
                <i class="fas fa-plus me-1"></i> Add slide
            </button>
        </div>
        <div class="card-body">
            <p class="text-muted small">Upload a desktop and optional mobile background for each slide. Mobile image shows under 768px. Turn off "Show text & buttons" for image-only slides. Up to 5 slides.</p>
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <div class="form-check form-switch">
                        <input type="hidden" name="home_hero_autoplay" value="0">
                        <input class="form-check-input" type="checkbox" name="home_hero_autoplay" value="1" id="home_hero_autoplay" {{ old('home_hero_autoplay', $settings['home_hero_autoplay'] ?? '1') === '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="home_hero_autoplay">Auto-play carousel</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-check form-switch">
                        <input type="hidden" name="home_hero_show_dots" value="0">
                        <input class="form-check-input" type="checkbox" name="home_hero_show_dots" value="1" id="home_hero_show_dots" {{ old('home_hero_show_dots', $settings['home_hero_show_dots'] ?? '1') === '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="home_hero_show_dots">Show dots</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-check form-switch">
                        <input type="hidden" name="home_hero_show_arrows" value="0">
                        <input class="form-check-input" type="checkbox" name="home_hero_show_arrows" value="1" id="home_hero_show_arrows" {{ old('home_hero_show_arrows', $settings['home_hero_show_arrows'] ?? '1') === '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="home_hero_show_arrows">Show arrows</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="home_hero_interval">Slide interval (seconds)</label>
                    <input type="number" min="2" max="15" name="home_hero_interval" id="home_hero_interval" class="form-control" value="{{ old('home_hero_interval', $settings['home_hero_interval'] ?? '5') }}">
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <div class="form-check form-switch">
                        <input type="hidden" name="home_hero_show_overlay" value="0">
                        <input class="form-check-input" type="checkbox" name="home_hero_show_overlay" value="1" id="home_hero_show_overlay" {{ old('home_hero_show_overlay', $settings['home_hero_show_overlay'] ?? '1') === '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="home_hero_show_overlay">Show overlay color</label>
                    </div>
                </div>
                <div class="col-md-3 hero-overlay-fields {{ old('home_hero_show_overlay', $settings['home_hero_show_overlay'] ?? '1') === '1' ? '' : 'd-none' }}">
                    <label class="form-label" for="home_hero_overlay_color">Overlay color</label>
                    <input type="color" name="home_hero_overlay_color" id="home_hero_overlay_color" class="form-control form-control-color w-100" value="{{ old('home_hero_overlay_color', $settings['home_hero_overlay_color'] ?? '#ffffff') }}">
                </div>
                <div class="col-md-6 hero-overlay-fields {{ old('home_hero_show_overlay', $settings['home_hero_show_overlay'] ?? '1') === '1' ? '' : 'd-none' }}">
                    <label class="form-label" for="home_hero_overlay_opacity">Overlay opacity ({{ old('home_hero_overlay_opacity', $settings['home_hero_overlay_opacity'] ?? '45') }}%)</label>
                    <input type="range" name="home_hero_overlay_opacity" id="home_hero_overlay_opacity" class="form-range js-hero-overlay-opacity" min="0" max="100" value="{{ old('home_hero_overlay_opacity', $settings['home_hero_overlay_opacity'] ?? '45') }}">
                </div>
            </div>
            <div id="heroSlidesWrap">
                @foreach($heroSlides as $index => $slide)
                    @include('admin.homepage.partials.slide-fields', ['index' => $index, 'slide' => $slide])
                @endforeach
            </div>
            <hr>
            <div class="row g-3">
                @foreach([1,2,3] as $i)
                    <div class="col-md-4">
                        <label class="form-label">Stat {{ $i }} value</label>
                        <input type="text" name="home_stat{{ $i }}_value" class="form-control" value="{{ old('home_stat'.$i.'_value', $settings['home_stat'.$i.'_value']) }}">
                        <input type="text" name="home_stat{{ $i }}_label" class="form-control mt-2" value="{{ old('home_stat'.$i.'_label', $settings['home_stat'.$i.'_label']) }}" placeholder="Label">
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0">Sections</h5></div>
        <div class="card-body">
            <p class="text-muted small">Turn sections on/off and set titles. Assign products from Products → Featured / Popular / Just arrived / Best selling.</p>
            @php
                $sections = [
                    'home_show_categories' => ['Categories', 'home_categories_title', null],
                    'home_show_best_selling' => ['Best selling', 'home_best_selling_title', 'home_best_selling_limit'],
                    'home_show_banners' => ['Promo banners', null, null],
                    'home_show_featured' => ['Featured products', 'home_featured_title', 'home_featured_limit'],
                    'home_show_popular' => ['Most popular', 'home_popular_title', 'home_popular_limit'],
                    'home_show_newsletter' => ['Newsletter banner', null, null],
                    'home_show_new' => ['Just arrived', 'home_new_title', 'home_new_limit'],
                    'home_show_features' => ['Feature cards', null, null],
                ];
            @endphp
            @foreach($sections as $toggle => $meta)
                <div class="row g-2 align-items-end mb-3 pb-3 border-bottom">
                    <div class="col-md-3">
                        <div class="form-check form-switch">
                            <input type="hidden" name="{{ $toggle }}" value="0">
                            <input class="form-check-input" type="checkbox" name="{{ $toggle }}" value="1" id="{{ $toggle }}" {{ old($toggle, $settings[$toggle]) === '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="{{ $toggle }}">{{ $meta[0] }}</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        @if($meta[1])
                            <input type="text" name="{{ $meta[1] }}" class="form-control" value="{{ old($meta[1], $settings[$meta[1]]) }}" placeholder="Section title">
                        @endif
                    </div>
                    <div class="col-md-3">
                        @if($meta[2])
                            <input type="number" min="1" max="24" name="{{ $meta[2] }}" class="form-control" value="{{ old($meta[2], $settings[$meta[2]]) }}">
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0">Promo banners</h5></div>
        <div class="card-body">
            <div class="row g-3">
                @foreach([1,2,3] as $i)
                    <div class="col-md-4">
                        <label class="form-label">Banner {{ $i }} title</label>
                        <input type="text" name="home_banner{{ $i }}_title" class="form-control" value="{{ old('home_banner'.$i.'_title', $settings['home_banner'.$i.'_title']) }}">
                        <input type="text" name="home_banner{{ $i }}_text" class="form-control mt-2" value="{{ old('home_banner'.$i.'_text', $settings['home_banner'.$i.'_text']) }}" placeholder="Subtitle">
                        <input type="text" name="home_banner{{ $i }}_url" class="form-control mt-2" value="{{ old('home_banner'.$i.'_url', $settings['home_banner'.$i.'_url']) }}" placeholder="Link">
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0">Newsletter</h5></div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="home_newsletter_title" class="form-control" value="{{ old('home_newsletter_title', $settings['home_newsletter_title']) }}">
            </div>
            <div>
                <label class="form-label">Text</label>
                <input type="text" name="home_newsletter_text" class="form-control" value="{{ old('home_newsletter_text', $settings['home_newsletter_text']) }}">
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0">Feature cards</h5></div>
        <div class="card-body">
            <div class="row g-3">
                @foreach([1,2,3,4,5] as $i)
                    <div class="col-md">
                        <label class="form-label">Card {{ $i }} title</label>
                        <input type="text" name="home_feature_{{ $i }}_title" class="form-control" value="{{ old('home_feature_'.$i.'_title', $settings['home_feature_'.$i.'_title']) }}">
                        <textarea name="home_feature_{{ $i }}_text" class="form-control mt-2" rows="3">{{ old('home_feature_'.$i.'_text', $settings['home_feature_'.$i.'_text']) }}</textarea>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 sticky-bottom bg-body py-3 border-top">
        <button type="submit" class="btn btn-primary">Save homepage</button>
    </div>
</form>
@endsection

@push('styles')
<style>
    .brand-preview {
        position: relative;
        display: inline-block;
        padding: .5rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: .5rem;
    }
    .brand-preview img { display: block; }
    .brand-preview-remove {
        position: absolute;
        top: -8px;
        right: -8px;
        width: 22px;
        height: 22px;
        padding: 0;
        border: 0;
        border-radius: 50%;
        background: #dc3545;
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        line-height: 1;
        cursor: pointer;
    }
</style>
@endpush

@push('scripts')
<script type="text/template" id="heroSlideTemplate">
@include('admin.homepage.partials.slide-fields', ['index' => '__INDEX__', 'slide' => \App\Support\Homepage::normalizeSlide([])])
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.js-remove-brand-image').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var wrap = btn.closest('.brand-preview');
            if (!wrap) return;
            var input = wrap.querySelector('input[type="hidden"]');
            if (input) input.value = '1';
            wrap.classList.add('d-none');
        });
    });

    function bindSlideCard(card) {
        var contentToggle = card.querySelector('.js-slide-show-content');
        var contentFields = card.querySelector('.slide-content-fields');
        if (contentToggle && contentFields) {
            contentToggle.addEventListener('change', function () {
                contentFields.classList.toggle('d-none', !contentToggle.checked);
            });
        }

        var removeBtn = card.querySelector('.js-remove-slide');
        if (removeBtn) {
            removeBtn.addEventListener('click', function () {
                card.remove();
                renumberSlides();
            });
        }
    }

    function renumberSlides() {
        var wrap = document.getElementById('heroSlidesWrap');
        if (!wrap) return;

        wrap.querySelectorAll('.hero-slide-card').forEach(function (card, index) {
            card.dataset.slideIndex = index;
            var heading = card.querySelector('.card-header h6');
            if (heading) heading.textContent = 'Slide ' + (index + 1);

            card.querySelectorAll('[name]').forEach(function (input) {
                input.name = input.name.replace(/slides\[\d+\]/, 'slides[' + index + ']');
            });

            card.querySelectorAll('[id]').forEach(function (input) {
                input.id = input.id.replace(/_\d+$/, '_' + index);
            });

            card.querySelectorAll('label[for]').forEach(function (label) {
                label.htmlFor = label.htmlFor.replace(/_\d+$/, '_' + index);
            });

            if (index === 0) {
                var removeBtn = card.querySelector('.js-remove-slide');
                if (removeBtn) removeBtn.remove();
            } else if (!card.querySelector('.js-remove-slide')) {
                var actions = card.querySelector('.card-header .d-flex.gap-3');
                if (actions) {
                    var btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'btn btn-sm btn-outline-danger js-remove-slide';
                    btn.textContent = 'Remove';
                    btn.addEventListener('click', function () {
                        card.remove();
                        renumberSlides();
                    });
                    actions.appendChild(btn);
                }
            }
        });

        var addBtn = document.getElementById('addHeroSlide');
        if (addBtn) {
            addBtn.disabled = wrap.querySelectorAll('.hero-slide-card').length >= 5;
        }
    }

    document.querySelectorAll('.hero-slide-card').forEach(bindSlideCard);

    var overlayToggle = document.getElementById('home_hero_show_overlay');
    if (overlayToggle) {
        overlayToggle.addEventListener('change', function () {
            document.querySelectorAll('.hero-overlay-fields').forEach(function (el) {
                el.classList.toggle('d-none', !overlayToggle.checked);
            });
        });
    }

    var overlayOpacity = document.getElementById('home_hero_overlay_opacity');
    if (overlayOpacity) {
        overlayOpacity.addEventListener('input', function () {
            var label = document.querySelector('label[for="home_hero_overlay_opacity"]');
            if (label) {
                label.textContent = 'Overlay opacity (' + overlayOpacity.value + '%)';
            }
        });
    }

    var addBtn = document.getElementById('addHeroSlide');
    var template = document.getElementById('heroSlideTemplate');
    if (addBtn && template) {
        addBtn.addEventListener('click', function () {
            var wrap = document.getElementById('heroSlidesWrap');
            if (!wrap || wrap.querySelectorAll('.hero-slide-card').length >= 5) return;

            var index = wrap.querySelectorAll('.hero-slide-card').length;
            var html = template.innerHTML.replace(/__INDEX__/g, index);
            var temp = document.createElement('div');
            temp.innerHTML = html.trim();
            var card = temp.firstElementChild;
            wrap.appendChild(card);
            bindSlideCard(card);
            renumberSlides();
        });
    }
});
</script>
@endpush
