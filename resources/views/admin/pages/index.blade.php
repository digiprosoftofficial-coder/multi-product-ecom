@extends('admin.layouts.master')

@section('title', 'Info pages')
@section('page-title', 'Info pages')

@section('content')
@php
    $infoPages = [
        ['key' => 'privacy', 'label' => 'Privacy Policy', 'icon' => 'fa-shield-halved', 'route' => route('privacy')],
        ['key' => 'terms', 'label' => 'Terms & Conditions', 'icon' => 'fa-file-contract', 'route' => route('terms')],
        ['key' => 'delivery', 'label' => 'Delivery Information', 'icon' => 'fa-truck', 'route' => route('delivery')],
        ['key' => 'returns', 'label' => 'Product Returns', 'icon' => 'fa-rotate-left', 'route' => route('returns')],
    ];
    $activeTab = old('active_tab', request('tab', 'privacy'));
@endphp

<div class="info-pages-toolbar">
    <div class="info-pages-intro">
        <p class="text-muted mb-0">Manage legal and customer information pages shown in the footer. Switch tabs to edit each page, then save all changes at once.</p>
    </div>
</div>

<form action="{{ route('admin.pages.update') }}" method="POST" enctype="multipart/form-data" id="infoPagesForm">
    @csrf
    @method('PUT')
    <input type="hidden" name="active_tab" id="activeTabInput" value="{{ $activeTab }}">

    <ul class="nav info-pages-tabs mb-4" role="tablist">
        @foreach($infoPages as $page)
            @php
                $hasContent = filled(trim(strip_tags($pages[$page['key'].'_content'] ?? '')));
            @endphp
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab === $page['key'] ? 'active' : '' }}"
                        id="tab-btn-{{ $page['key'] }}"
                        data-bs-toggle="tab"
                        data-bs-target="#tab-{{ $page['key'] }}"
                        type="button"
                        role="tab"
                        aria-controls="tab-{{ $page['key'] }}"
                        aria-selected="{{ $activeTab === $page['key'] ? 'true' : 'false' }}">
                    <span class="tab-status {{ $hasContent ? 'is-published' : '' }}"></span>
                    <i class="fas {{ $page['icon'] }}"></i>
                    {{ $page['label'] }}
                </button>
            </li>
        @endforeach
    </ul>

    <div class="tab-content">
        @foreach($infoPages as $page)
            <div class="tab-pane fade {{ $activeTab === $page['key'] ? 'show active' : '' }}"
                 id="tab-{{ $page['key'] }}"
                 role="tabpanel"
                 aria-labelledby="tab-btn-{{ $page['key'] }}">
                @include('admin.pages.partials.info-page-section', [
                    'pageKey' => $page['key'],
                    'label' => $page['label'],
                    'previewUrl' => $page['route'],
                    'pages' => $pages,
                ])
            </div>
        @endforeach
    </div>

    <div class="info-pages-savebar">
        <div class="savebar-meta">
            <i class="fas fa-circle-info me-1"></i>
            Changes apply to all four pages when you save.
        </div>
        <button type="submit" class="btn btn-primary px-4">
            <i class="fas fa-save me-1"></i> Save all pages
        </button>
    </div>
</form>
@endsection

@include('admin.pages.partials.banner-assets')
@include('admin.pages.partials.info-pages-assets')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tinymce@7.6.0/tinymce.min.js" referrerpolicy="origin"></script>
@endpush
