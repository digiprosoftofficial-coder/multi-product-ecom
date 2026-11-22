@extends('admin.layouts.master')

@section('title', 'Themes')
@section('page-title', 'Themes')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <h5 class="mb-1">Admin theme color</h5>
        <p class="text-muted small mb-3">Changes the admin panel buttons, links, and sidebar accent. The storefront is not affected.</p>
        <form action="{{ route('admin.themes.admin-color') }}" method="POST" class="d-flex flex-wrap align-items-end gap-3">
            @csrf
            @method('PUT')
            <div>
                <label for="admin_theme_color" class="form-label">Admin theme color</label>
                <input type="color" class="form-control form-control-color @error('admin_theme_color') is-invalid @enderror"
                       id="admin_theme_color" name="admin_theme_color"
                       value="{{ old('admin_theme_color', $adminThemeColor ?? '#16a34a') }}">
                @error('admin_theme_color')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-primary">Save color</button>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <h5 class="mb-1">Login page</h5>
        <p class="text-muted small mb-3">Color and side image for the login and registration pages. Those pages have no store header or footer.</p>
        <form action="{{ route('admin.themes.auth-design') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="auth_page_color" class="form-label">Design color</label>
                    <input type="color" class="form-control form-control-color w-100 @error('auth_page_color') is-invalid @enderror"
                           id="auth_page_color" name="auth_page_color"
                           value="{{ old('auth_page_color', $authPageColor ?? '#8d6b78') }}">
                    @error('auth_page_color')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-8">
                    <label for="auth_page_image" class="form-label">Side image</label>
                    @if(!empty($authPageImage))
                        <div class="mb-2">
                            <img src="{{ $authPageImage }}" alt="Login page image" style="height: 72px; width: 120px; object-fit: cover; border-radius: 8px;">
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="remove_auth_page_image" id="remove_auth_page_image" value="1">
                            <label class="form-check-label" for="remove_auth_page_image">Remove current image</label>
                        </div>
                    @endif
                    <input type="file" class="form-control @error('auth_page_image') is-invalid @enderror"
                           id="auth_page_image" name="auth_page_image" accept="image/jpeg,image/png,image/webp,image/gif">
                    <div class="form-text">Shown on the right of login and registration. JPG, PNG, or WEBP.</div>
                    @error('auth_page_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Save login design</button>
        </form>
    </div>
</div>

<div class="mb-4">
    <p class="text-muted mb-0">Frontend themes are scanned from <code>resources/views/frontend</code>. A folder is a valid theme only if it contains <code>index.blade.php</code> and <code>theme.json</code>.</p>
</div>

@if(count($themes) === 0)
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5 text-muted">
            <i class="fas fa-palette fa-3x mb-3 opacity-50"></i>
            <p class="mb-0">No themes found. Add a theme folder with <code>index.blade.php</code> and <code>theme.json</code>.</p>
        </div>
    </div>
@else
    <div class="row g-4">
        @foreach($themes as $slug => $meta)
            @php
                $isActive = $slug === $activeTheme;
                $canDelete = ($meta['deletable'] ?? true) && !$isActive;
            @endphp
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border shadow-sm {{ $isActive ? 'border-primary border-2' : '' }}">
                    {{-- Preview image (top) --}}
                    <div class="card-img-top position-relative bg-dark overflow-hidden" style="height: 180px;">
                        @php
                            $previewUrl = route('admin.themes.preview', ['slug' => $slug]);
                        @endphp
                        <img src="{{ $previewUrl }}" alt="{{ $meta['name'] ?? $slug }}" class="img-fluid w-100 h-100" style="object-fit: cover;" onerror="this.style.display='none'; var f=this.nextElementSibling; if(f) f.classList.remove('d-none');">
                        <div class="d-none position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center flex-column text-white-50 bg-dark">
                            <i class="fas fa-image fa-3x mb-2"></i>
                            <small>No preview</small>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                            <h5 class="card-title mb-0">{{ $meta['name'] ?? $slug }}</h5>
                            @if($isActive)
                                <span class="badge bg-success flex-shrink-0">Active</span>
                            @else
                                <span class="badge bg-secondary flex-shrink-0">Inactive</span>
                            @endif
                        </div>
                        @if(!empty($meta['description']))
                            <p class="card-text text-muted small mb-2">{{ Str::limit($meta['description'], 80) }}</p>
                        @endif
                        <div class="small text-muted mb-3">
                            <span>v{{ $meta['version'] ?? '1.0.0' }}</span>
                            @if(!empty($meta['author']))
                                <span class="mx-1">·</span>
                                <span>{{ $meta['author'] }}</span>
                            @endif
                        </div>
                        @if(!empty($meta['supports']) && is_array($meta['supports']))
                            <div class="mb-3">
                                @foreach($meta['supports'] as $mode)
                                    <span class="badge bg-light text-dark border me-1">{{ $mode }}</span>
                                @endforeach
                            </div>
                        @endif
                        <div class="mt-auto d-flex flex-wrap gap-2">
                            @if(!$isActive)
                                <form action="{{ route('admin.themes.activate') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="theme" value="{{ $slug }}">
                                    <button type="submit" class="btn btn-primary btn-sm">Activate</button>
                                </form>
                            @endif
                            @if($canDelete)
                                <form action="{{ route('admin.themes.destroy') }}" method="POST" class="d-inline" data-confirm="Delete theme &quot;{{ $meta['name'] ?? $slug }}&quot;? This cannot be undone.">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="theme" value="{{ $slug }}">
                                    <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            if (!confirm(form.getAttribute('data-confirm'))) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endpush
@endsection
