@extends('admin.layouts.master')

@section('title', 'Settings')
@section('page-title', 'Site settings')

@section('content')
<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row g-3 g-xl-4 align-items-stretch mb-4">
        <div class="col-12 col-lg-6 col-xxl-4">
    <div class="card h-100">
        <div class="card-header">
            <h5 class="mb-0">Store identity</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="site_name" class="form-label">Site name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('site_name') is-invalid @enderror"
                       id="site_name" name="site_name" value="{{ old('site_name', $settings['site_name']) }}" required>
                @error('site_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            @php
                $logoUrl = site_logo_url();
                $footerLogoUrl = setting_image_url($settings['footer_logo'] ?? null);
                $faviconPreview = setting_image_url($settings['favicon'] ?? null);
            @endphp

            <div class="mb-3">
                <label class="form-label">Header logo</label>
                @include('admin.settings.partials.image-preview', [
                    'url' => $logoUrl,
                    'alt' => 'Header logo',
                    'removeName' => 'remove_site_logo',
                    'imgStyle' => 'max-height: 48px; max-width: 180px;',
                ])
                <input type="file" class="form-control @error('site_logo') is-invalid @enderror"
                       id="site_logo" name="site_logo" accept="image/*">
                <div class="form-text">PNG or JPG. Shown in the top menu, invoices, and emails.</div>
                @error('site_logo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Footer logo</label>
                @include('admin.settings.partials.image-preview', [
                    'url' => $footerLogoUrl,
                    'alt' => 'Footer logo',
                    'removeName' => 'remove_footer_logo',
                    'imgStyle' => 'max-height: 48px; max-width: 180px;',
                ])
                <input type="file" class="form-control @error('footer_logo') is-invalid @enderror"
                       id="footer_logo" name="footer_logo" accept="image/*">
                <div class="form-text">Optional. If empty, the header logo is used in the footer.</div>
                @error('footer_logo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Favicon</label>
                @include('admin.settings.partials.image-preview', [
                    'url' => $faviconPreview,
                    'alt' => 'Favicon',
                    'removeName' => 'remove_favicon',
                    'imgStyle' => 'height: 32px; width: 32px; object-fit: contain;',
                ])
                <input type="file" class="form-control @error('favicon') is-invalid @enderror"
                       id="favicon" name="favicon" accept="image/png,image/jpeg,image/webp,image/gif,image/x-icon,.ico">
                <div class="form-text">PNG or ICO, square. Browser tab icon. If empty, the header logo is used.</div>
                @error('favicon')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-0">
                <label for="footer_text" class="form-label">Footer tagline</label>
                <textarea class="form-control @error('footer_text') is-invalid @enderror"
                          id="footer_text" name="footer_text" rows="2">{{ old('footer_text', $settings['footer_text']) }}</textarea>
                @error('footer_text')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
        </div>

        <div class="col-12 col-lg-6 col-xxl-4">
    <div class="card h-100">
        <div class="card-header">
            <h5 class="mb-0">Contact information</h5>
        </div>
        <div class="card-body">
            <p class="text-muted small">Shown on the Contact page, footer, and invoices. The contact form can be added later.</p>
            <div class="mb-3">
                <label for="contact_phone" class="form-label">Phone</label>
                <input type="text" class="form-control @error('contact_phone') is-invalid @enderror"
                       id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone']) }}">
                @error('contact_phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="contact_email" class="form-label">Email</label>
                <input type="email" class="form-control @error('contact_email') is-invalid @enderror"
                       id="contact_email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}">
                @error('contact_email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="contact_address" class="form-label">Address</label>
                <textarea class="form-control @error('contact_address') is-invalid @enderror"
                          id="contact_address" name="contact_address" rows="3">{{ old('contact_address', $settings['contact_address']) }}</textarea>
                @error('contact_address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="contact_hours" class="form-label">Business hours</label>
                <input type="text" class="form-control @error('contact_hours') is-invalid @enderror"
                       id="contact_hours" name="contact_hours" value="{{ old('contact_hours', $settings['contact_hours']) }}"
                       placeholder="Sat–Thu, 10:00–20:00">
                @error('contact_hours')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-0">
                <label for="contact_intro" class="form-label">Contact page intro</label>
                <textarea class="form-control @error('contact_intro') is-invalid @enderror"
                          id="contact_intro" name="contact_intro" rows="2">{{ old('contact_intro', $settings['contact_intro']) }}</textarea>
                @error('contact_intro')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
        </div>

        <div class="col-12 col-lg-6 col-xxl-4">
    <div class="card h-100">
        <div class="card-header">
            <h5 class="mb-0">Store &amp; catalog</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="currency_symbol" class="form-label">Currency symbol <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('currency_symbol') is-invalid @enderror"
                       id="currency_symbol" name="currency_symbol" value="{{ old('currency_symbol', $settings['currency_symbol']) }}" required>
                <div class="form-text">Example: $ or ৳</div>
                @error('currency_symbol')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="currency_code" class="form-label">Currency code</label>
                <input type="text" class="form-control @error('currency_code') is-invalid @enderror"
                       id="currency_code" name="currency_code" value="{{ old('currency_code', $settings['currency_code']) }}"
                       maxlength="8" placeholder="USD">
                @error('currency_code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="tax_rate" class="form-label">Tax rate (%)</label>
                <input type="number" step="0.01" class="form-control @error('tax_rate') is-invalid @enderror"
                       id="tax_rate" name="tax_rate" value="{{ old('tax_rate', $settings['tax_rate']) }}"
                       min="0" max="100">
                @error('tax_rate')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="vat_rate" class="form-label">VAT rate (%)</label>
                <input type="number" step="0.01" class="form-control @error('vat_rate') is-invalid @enderror"
                       id="vat_rate" name="vat_rate" value="{{ old('vat_rate', $settings['vat_rate']) }}"
                       min="0" max="100">
                @error('vat_rate')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="category_max_depth" class="form-label">Category max depth</label>
                <select class="form-select @error('category_max_depth') is-invalid @enderror" id="category_max_depth" name="category_max_depth">
                    @php $depth = (string) old('category_max_depth', $settings['category_max_depth']); @endphp
                    <option value="2" {{ $depth === '2' ? 'selected' : '' }}>2 levels</option>
                    <option value="3" {{ $depth === '3' ? 'selected' : '' }}>3 levels</option>
                    <option value="4" {{ $depth === '4' ? 'selected' : '' }}>4 levels</option>
                    <option value="5" {{ $depth === '5' ? 'selected' : '' }}>5 levels</option>
                    <option value="6" {{ $depth === '6' ? 'selected' : '' }}>6 levels</option>
                    <option value="0" {{ $depth === '0' ? 'selected' : '' }}>Unlimited</option>
                </select>
                <div class="form-text">How deep the category tree can go. Products can only be placed on the last level.</div>
                @error('category_max_depth')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-0">
                @php $compareOn = (string) old('enable_compare_price', $settings['enable_compare_price']) === '1'; @endphp
                <div class="form-check form-switch">
                    <input type="hidden" name="enable_compare_price" value="0">
                    <input class="form-check-input @error('enable_compare_price') is-invalid @enderror"
                           type="checkbox"
                           role="switch"
                           id="enable_compare_price"
                           name="enable_compare_price"
                           value="1"
                           {{ $compareOn ? 'checked' : '' }}>
                    <label class="form-check-label" for="enable_compare_price">Enable compare price (MRP)</label>
                </div>
                <div class="form-text">When off, the Compare Price field is hidden on product forms and the storefront.</div>
                @error('enable_compare_price')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Pages</h5>
        </div>
        <div class="card-body">
            <p class="text-muted small mb-4">Add your own copy. Privacy and Terms should be reviewed for your business — these boxes start empty on purpose.</p>

            <div class="mb-4">
                <label for="about_content" class="form-label">About page</label>
                <textarea class="form-control js-page-editor @error('about_content') is-invalid @enderror"
                          id="about_content" name="about_content" rows="8">{{ old('about_content', $settings['about_content']) }}</textarea>
                @error('about_content')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="privacy_content" class="form-label">Privacy policy</label>
                <textarea class="form-control js-page-editor @error('privacy_content') is-invalid @enderror"
                          id="privacy_content" name="privacy_content" rows="8">{{ old('privacy_content', $settings['privacy_content']) }}</textarea>
                @error('privacy_content')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-0">
                <label for="terms_content" class="form-label">Terms &amp; conditions</label>
                <textarea class="form-control js-page-editor @error('terms_content') is-invalid @enderror"
                          id="terms_content" name="terms_content" rows="8">{{ old('terms_content', $settings['terms_content']) }}</textarea>
                @error('terms_content')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 sticky-bottom bg-body py-3 border-top" style="z-index: 10;">
        <button type="submit" class="btn btn-primary">Save settings</button>
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
    .brand-preview img {
        display: block;
    }
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
        box-shadow: 0 1px 3px rgba(0,0,0,.2);
    }
    .brand-preview-remove:hover {
        background: #bb2d3b;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tinymce@7.6.0/tinymce.min.js" referrerpolicy="origin"></script>
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

    if (typeof tinymce === 'undefined') return;

    tinymce.init({
        selector: 'textarea.js-page-editor',
        license_key: 'gpl',
        menubar: false,
        branding: false,
        promotion: false,
        height: 280,
        plugins: 'lists link table autoresize',
        toolbar: 'blocks | bold italic underline | bullist numlist | link table | undo redo',
        block_formats: 'Paragraph=p; Heading 2=h2; Heading 3=h3',
        convert_urls: false,
        default_link_target: '_blank',
        link_default_target: '_blank',
        content_style: 'body { font-family: Figtree, Segoe UI, sans-serif; font-size: 14px; line-height: 1.6; }',
        setup: function (editor) {
            var form = editor.getElement().closest('form');
            if (!form) return;
            form.addEventListener('submit', function () {
                editor.save();
            });
        }
    });
});
</script>
@endpush
