@php
    $slide = \App\Support\Homepage::normalizeSlide($slide ?? []);
@endphp
<div class="hero-slide-card card mb-3" data-slide-index="{{ $index }}">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h6 class="mb-0">Slide {{ (int) $index + 1 }}</h6>
        <div class="d-flex flex-wrap gap-3 align-items-center">
            <div class="form-check form-switch mb-0">
                <input type="hidden" name="slides[{{ $index }}][enabled]" value="0">
                <input class="form-check-input" type="checkbox" name="slides[{{ $index }}][enabled]" value="1" id="slide_enabled_{{ $index }}" {{ old("slides.{$index}.enabled", $slide['enabled']) ? 'checked' : '' }}>
                <label class="form-check-label" for="slide_enabled_{{ $index }}">Enabled</label>
            </div>
            <div class="form-check form-switch mb-0">
                <input type="hidden" name="slides[{{ $index }}][show_content]" value="0">
                <input class="form-check-input js-slide-show-content" type="checkbox" name="slides[{{ $index }}][show_content]" value="1" id="slide_content_{{ $index }}" {{ old("slides.{$index}.show_content", $slide['show_content']) ? 'checked' : '' }}>
                <label class="form-check-label" for="slide_content_{{ $index }}">Show text & buttons</label>
            </div>
            @if($index > 0)
                <button type="button" class="btn btn-sm btn-outline-danger js-remove-slide">Remove</button>
            @endif
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Background image</label>
                @include('admin.settings.partials.image-preview', [
                    'url' => setting_image_url($slide['image'] ?? null),
                    'alt' => 'Slide '.((int) $index + 1),
                    'removeName' => 'slides['.$index.'][remove_image]',
                    'imgStyle' => 'max-height: 90px; max-width: 220px;',
                ])
                <input type="file" name="slides[{{ $index }}][image]" class="form-control" accept="image/*">
            </div>
        </div>

        <div class="slide-content-fields mt-3 {{ old("slides.{$index}.show_content", $slide['show_content']) ? '' : 'd-none' }}">
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Title</label>
                    <input type="text" name="slides[{{ $index }}][title]" class="form-control" value="{{ old("slides.{$index}.title", $slide['title']) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Highlight word</label>
                    <input type="text" name="slides[{{ $index }}][highlight]" class="form-control" value="{{ old("slides.{$index}.highlight", $slide['highlight']) }}">
                    <div class="form-text">Colored word inside the title.</div>
                </div>
                <div class="col-12">
                    <label class="form-label">Subtitle</label>
                    <input type="text" name="slides[{{ $index }}][subtitle]" class="form-control" value="{{ old("slides.{$index}.subtitle", $slide['subtitle']) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Title color</label>
                    <input type="color" name="slides[{{ $index }}][title_color]" class="form-control form-control-color w-100" value="{{ old("slides.{$index}.title_color", $slide['title_color']) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Subtitle color</label>
                    <input type="color" name="slides[{{ $index }}][subtitle_color]" class="form-control form-control-color w-100" value="{{ old("slides.{$index}.subtitle_color", $slide['subtitle_color']) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Highlight color</label>
                    <input type="color" name="slides[{{ $index }}][highlight_color]" class="form-control form-control-color w-100" value="{{ old("slides.{$index}.highlight_color", $slide['highlight_color']) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Primary button text</label>
                    <input type="text" name="slides[{{ $index }}][btn1_text]" class="form-control" value="{{ old("slides.{$index}.btn1_text", $slide['btn1_text']) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Primary button URL</label>
                    <input type="text" name="slides[{{ $index }}][btn1_url]" class="form-control" value="{{ old("slides.{$index}.btn1_url", $slide['btn1_url']) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Secondary button text</label>
                    <input type="text" name="slides[{{ $index }}][btn2_text]" class="form-control" value="{{ old("slides.{$index}.btn2_text", $slide['btn2_text']) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Secondary button URL</label>
                    <input type="text" name="slides[{{ $index }}][btn2_url]" class="form-control" value="{{ old("slides.{$index}.btn2_url", $slide['btn2_url']) }}">
                </div>
            </div>
        </div>
    </div>
</div>
