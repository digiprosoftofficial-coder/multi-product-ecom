@extends('admin.layouts.master')

@section('title', 'Themes')
@section('page-title', 'Themes')

@section('content')
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
