@extends('admin.layouts.master')

@section('title', 'Settings')
@section('page-title', 'Settings')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="site_name" class="form-label">Site Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('site_name') is-invalid @enderror" 
                       id="site_name" name="site_name" value="{{ old('site_name', $settings['site_name']) }}" required>
                @error('site_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            @if($settings['site_logo'])
                <div class="mb-3">
                    <label class="form-label">Current Logo</label>
                    <div>
                        <img src="{{ asset('uploads/settings/' . $settings['site_logo']) }}" 
                             alt="Site Logo" 
                             style="max-width: 200px; height: auto;">
                    </div>
                </div>
            @endif

            <div class="mb-3">
                <label for="site_logo" class="form-label">Site Logo</label>
                <input type="file" class="form-control @error('site_logo') is-invalid @enderror" 
                       id="site_logo" name="site_logo" accept="image/*">
                @error('site_logo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="footer_text" class="form-label">Footer Text</label>
                <textarea class="form-control @error('footer_text') is-invalid @enderror" 
                          id="footer_text" name="footer_text" rows="3">{{ old('footer_text', $settings['footer_text']) }}</textarea>
                @error('footer_text')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="tax_rate" class="form-label">Tax Rate (%)</label>
                        <input type="number" step="0.01" class="form-control @error('tax_rate') is-invalid @enderror" 
                               id="tax_rate" name="tax_rate" value="{{ old('tax_rate', $settings['tax_rate']) }}" 
                               min="0" max="100">
                        @error('tax_rate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="vat_rate" class="form-label">VAT Rate (%)</label>
                        <input type="number" step="0.01" class="form-control @error('vat_rate') is-invalid @enderror" 
                               id="vat_rate" name="vat_rate" value="{{ old('vat_rate', $settings['vat_rate']) }}" 
                               min="0" max="100">
                        @error('vat_rate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
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
                <div class="form-text">How deep the category tree can go (child of child, and so on). Products can only be placed on the last level. Every level can have an image.</div>
                @error('category_max_depth')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
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
                <div class="form-text">When off, the Compare Price field is hidden on add/edit product forms and is not shown on the storefront.</div>
                @error('enable_compare_price')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Update Settings</button>
            </div>
        </form>
    </div>
</div>
@endsection

