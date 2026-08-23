@if(has_social_links())
@php
    $variant = $variant ?? 'default';
@endphp
<div class="site-social-block site-social-block--{{ $variant }} {{ $class ?? '' }}">
    <div class="site-social-links">
        @foreach(social_links() as $platform => $link)
            <a href="{{ $link['url'] }}"
               class="site-social-link site-social-link--{{ $platform }}"
               target="_blank"
               rel="noopener noreferrer"
               aria-label="{{ $link['label'] }}"
               title="{{ $link['label'] }}">
                <i class="{{ $link['icon'] }}" aria-hidden="true"></i>
            </a>
        @endforeach
    </div>
</div>
@endif

@once
@push('styles')
<style>
    .site-social-block--footer,
    .site-social-block--dark {
        margin-top: 1.25rem;
        padding: 0;
        background: none;
        border: 0;
        box-shadow: none;
    }
    .site-social-links {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 1.15rem;
    }
    .site-social-block--footer .site-social-link,
    .site-social-block--dark .site-social-link {
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        width: auto;
        height: auto;
        padding: 0;
        border: 0 !important;
        background: none !important;
        box-shadow: none !important;
        text-decoration: none !important;
        text-decoration-line: none !important;
        outline: none;
        color: #fff !important;
        line-height: 1;
        transition: opacity .15s ease, transform .15s ease;
    }
    .site-social-block--footer .site-social-link::before,
    .site-social-block--footer .site-social-link::after,
    .site-social-block--dark .site-social-link::before,
    .site-social-block--dark .site-social-link::after {
        content: none !important;
        display: none !important;
    }
    .site-social-block--footer .site-social-link i,
    .site-social-block--dark .site-social-link i {
        font-size: 1.85rem;
        line-height: 1;
        pointer-events: none;
    }
    .site-social-block--footer .site-social-link:hover,
    .site-social-block--dark .site-social-link:hover {
        opacity: .82;
        transform: translateY(-1px);
        color: #fff !important;
        background: none;
        box-shadow: none;
    }
    .site-social-block--default .site-social-link {
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        color: #fff !important;
        text-decoration: none !important;
        background: none;
        border: 0;
        box-shadow: none;
    }
    .site-social-block--default .site-social-link i {
        font-size: 1.85rem;
    }
</style>
@endpush
@endonce
