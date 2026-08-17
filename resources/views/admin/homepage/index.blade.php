@extends('admin.layouts.master')

@section('title', 'Homepage')
@section('page-title', 'Homepage')

@section('content')
<form action="{{ route('admin.homepage.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0">Hero banner</h5></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Title</label>
                    <input type="text" name="home_hero_title" class="form-control" value="{{ old('home_hero_title', $settings['home_hero_title']) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Highlight word</label>
                    <input type="text" name="home_hero_highlight" class="form-control" value="{{ old('home_hero_highlight', $settings['home_hero_highlight']) }}">
                    <div class="form-text">This word is shown in green inside the title.</div>
                </div>
                <div class="col-12">
                    <label class="form-label">Subtitle</label>
                    <input type="text" name="home_hero_subtitle" class="form-control" value="{{ old('home_hero_subtitle', $settings['home_hero_subtitle']) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Primary button text</label>
                    <input type="text" name="home_hero_btn1_text" class="form-control" value="{{ old('home_hero_btn1_text', $settings['home_hero_btn1_text']) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Primary button URL</label>
                    <input type="text" name="home_hero_btn1_url" class="form-control" value="{{ old('home_hero_btn1_url', $settings['home_hero_btn1_url']) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Secondary button text</label>
                    <input type="text" name="home_hero_btn2_text" class="form-control" value="{{ old('home_hero_btn2_text', $settings['home_hero_btn2_text']) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Secondary button URL</label>
                    <input type="text" name="home_hero_btn2_url" class="form-control" value="{{ old('home_hero_btn2_url', $settings['home_hero_btn2_url']) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Background image</label>
                    @include('admin.settings.partials.image-preview', [
                        'url' => setting_image_url($settings['home_hero_image'] ?? null),
                        'alt' => 'Hero',
                        'removeName' => 'remove_home_hero_image',
                        'imgStyle' => 'max-height: 90px; max-width: 220px;',
                    ])
                    <input type="file" name="home_hero_image" class="form-control" accept="image/*">
                </div>
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
});
</script>
@endpush
