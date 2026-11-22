@php
    $buttonBg = \App\Support\Homepage::normalizeColor(
        \App\Models\Setting::query()->where('key', 'button_bg_color')->value('value'),
        ''
    );
    $buttonText = \App\Support\Homepage::normalizeColor(
        \App\Models\Setting::query()->where('key', 'button_text_color')->value('value'),
        '#ffffff'
    );
    $buttonHover = \App\Support\Homepage::normalizeColor(
        \App\Models\Setting::query()->where('key', 'button_hover_color')->value('value'),
        $buttonBg !== '' ? $buttonBg : '#f7a422'
    );
    $menuActive = \App\Support\Homepage::normalizeColor(
        \App\Models\Setting::query()->where('key', 'menu_active_color')->value('value'),
        ''
    );
    $menuHover = \App\Support\Homepage::normalizeColor(
        \App\Models\Setting::query()->where('key', 'menu_hover_color')->value('value'),
        ''
    );
    $storeAccent = $buttonBg !== '' ? $buttonBg : '#6bb252';
    $storeAccentHex = ltrim($storeAccent, '#');
    $storeAccentRgb = hexdec(substr($storeAccentHex, 0, 2)).', '.hexdec(substr($storeAccentHex, 2, 2)).', '.hexdec(substr($storeAccentHex, 4, 2));
@endphp
<style>
    :root {
        --store-accent: {{ $storeAccent }};
        --store-accent-rgb: {{ $storeAccentRgb }};
        --header-bg: {{ theme_color('header_bg_color', '#1f3b2c') }};
        --header-text: {{ theme_color('header_text_color', '#ffffff') }};
        --footer-bg: {{ theme_color('footer_bg_color', '#1f3b2c') }};
        --footer-text: {{ theme_color('footer_text_color', '#ffffff') }};
        --footer-bottom-bg: {{ theme_color('footer_bottom_bg_color', '#6bb252') }};
        --footer-bottom-text: {{ theme_color('footer_bottom_text_color', '#ffffff') }};
        --logo-height-desktop: {{ max(24, min(96, (int) setting('logo_height_desktop', 48))) }}px;
        --logo-height-mobile: {{ max(24, min(80, (int) setting('logo_height_mobile', 50))) }}px;
    }
    .site-header {
        background: var(--header-bg) !important;
        color: var(--header-text);
        box-shadow: 0 2px 16px rgba(0, 0, 0, 0.12);
    }
    .site-header .border-bottom {
        border-color: rgba(255, 255, 255, 0.12) !important;
    }
    .site-header .navbar-toggler,
    .site-header a,
    .site-header button,
    .site-header .nav-link,
    .site-header .site-brand-text {
        color: var(--header-text);
    }
    .site-header a:hover,
    .site-header .nav-link:hover,
    .site-header .nav-link.active {
        color: var(--header-text);
        opacity: 0.82;
    }
    @if($menuActive !== '')
    .site-header .header-nav-col .nav-link.active,
    #offcanvasNavbar .nav-link.active {
        color: {{ $menuActive }} !important;
        opacity: 1;
    }
    @endif
    @if($menuHover !== '')
    .site-header .header-nav-col .nav-link:hover,
    .site-header .header-nav-col .nav-link:focus,
    .site-header .header-nav-col .nav-link:focus-visible,
    #offcanvasNavbar .nav-link:hover,
    #offcanvasNavbar .nav-link:focus,
    #offcanvasNavbar .nav-link:focus-visible {
        color: {{ $menuHover }} !important;
        opacity: 1;
    }
    @endif
    .site-header .search-bar {
        background: rgba(255, 255, 255, 0.96) !important;
    }
    .site-header .search-bar .form-control,
    .site-header .search-bar .form-select,
    .site-header .search-bar button,
    .site-header .search-bar svg {
        color: #1f2937;
    }
    .site-footer {
        background: var(--footer-bg);
        color: var(--footer-text);
    }
    .site-footer .widget-title,
    .site-footer h4,
    .site-footer .footer-contact-value {
        color: var(--footer-text);
    }
    .site-footer .footer-brand-text,
    .site-footer .nav-link,
    .site-footer .menu-list a {
        color: color-mix(in srgb, var(--footer-text) 78%, transparent);
    }
    .site-footer .nav-link:hover,
    .site-footer .menu-list a:hover,
    a.footer-contact-value:hover {
        color: var(--footer-text);
    }
    .site-footer .site-social-link {
        color: var(--footer-text) !important;
        text-decoration: none !important;
        text-decoration-line: none !important;
        border: 0 !important;
        box-shadow: none !important;
        background: none !important;
    }
    .site-footer .site-social-link:hover {
        color: var(--footer-text) !important;
        text-decoration: none !important;
    }
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
    .site-footer .site-social-link,
    .site-social-block--footer .site-social-link,
    .site-social-block--dark .site-social-link {
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        width: auto;
        height: auto;
        padding: 0;
        margin: 0;
        border: 0 !important;
        background: none !important;
        box-shadow: none !important;
        text-decoration: none !important;
        text-decoration-line: none !important;
        outline: none;
        line-height: 1;
        transition: opacity .15s ease, transform .15s ease;
    }
    .site-footer .site-social-link::before,
    .site-footer .site-social-link::after,
    .site-social-block--footer .site-social-link::before,
    .site-social-block--footer .site-social-link::after,
    .site-social-block--dark .site-social-link::before,
    .site-social-block--dark .site-social-link::after {
        content: none !important;
        display: none !important;
    }
    .site-footer .site-social-link i,
    .site-social-block--footer .site-social-link i,
    .site-social-block--dark .site-social-link i {
        font-size: 1.85rem;
        line-height: 1;
        pointer-events: none;
        text-decoration: none !important;
        border: 0 !important;
    }
    .site-footer .site-social-link:hover,
    .site-social-block--footer .site-social-link:hover,
    .site-social-block--dark .site-social-link:hover {
        opacity: .82;
        transform: translateY(-1px);
        background: none !important;
        box-shadow: none !important;
        text-decoration: none !important;
    }
    #footer-bottom {
        background: var(--footer-bottom-bg) !important;
        color: var(--footer-bottom-text);
    }
    #footer-bottom p {
        color: var(--footer-bottom-text) !important;
    }
    #footer-bottom .footer-bottom-sep {
        margin: 0 .65rem;
        opacity: .65;
    }
    #footer-bottom .footer-credit-link {
        color: var(--footer-bottom-text) !important;
        text-decoration: none;
        font-weight: 600;
        margin-left: .25rem;
    }
    #footer-bottom .footer-credit-link:hover {
        opacity: .85;
        text-decoration: underline;
    }
    @if($buttonBg !== '')
    .btn-primary {
        --bs-btn-color: {{ $buttonText }};
        --bs-btn-bg: {{ $buttonBg }};
        --bs-btn-border-color: {{ $buttonBg }};
        --bs-btn-hover-color: {{ $buttonText }};
        --bs-btn-hover-bg: {{ $buttonHover }};
        --bs-btn-hover-border-color: {{ $buttonHover }};
        --bs-btn-active-color: {{ $buttonText }};
        --bs-btn-active-bg: {{ $buttonHover }};
        --bs-btn-active-border-color: {{ $buttonHover }};
        --bs-btn-disabled-color: {{ $buttonText }};
        background-color: {{ $buttonBg }};
        border-color: {{ $buttonBg }};
        color: {{ $buttonText }};
    }
    .btn-primary:hover,
    .btn-primary:focus,
    .btn-primary:active {
        background-color: {{ $buttonHover }};
        border-color: {{ $buttonHover }};
        color: {{ $buttonText }};
    }
    .gadget-v1 .btn-primary,
    .gadget-v1 .btn-accent {
        background: {{ $buttonBg }};
        border-color: {{ $buttonBg }};
        color: {{ $buttonText }};
    }
    .gadget-v1 .btn-primary:hover,
    .gadget-v1 .btn-primary:focus,
    .gadget-v1 .btn-primary:active,
    .gadget-v1 .btn-accent:hover,
    .gadget-v1 .btn-accent:focus,
    .gadget-v1 .btn-accent:active {
        background: {{ $buttonHover }};
        border-color: {{ $buttonHover }};
        color: {{ $buttonText }};
        box-shadow: none;
    }
    @endif
    .btn-outline-primary {
        --bs-btn-color: var(--store-accent);
        --bs-btn-border-color: var(--store-accent);
        --bs-btn-hover-color: #fff;
        --bs-btn-hover-bg: var(--store-accent);
        --bs-btn-hover-border-color: var(--store-accent);
        --bs-btn-active-color: #fff;
        --bs-btn-active-bg: var(--store-accent);
        --bs-btn-active-border-color: var(--store-accent);
        color: var(--store-accent);
        border-color: var(--store-accent);
    }
    .cart-qty-btn:hover,
    .checkout-qty-btn:hover {
        color: var(--store-accent);
    }
    .cart-sidebar-item .product-title a:hover {
        color: var(--store-accent) !important;
    }
    .store-accent {
        color: var(--store-accent) !important;
    }
    #offcanvasCart {
        --bs-primary: var(--store-accent);
        --bs-primary-rgb: var(--store-accent-rgb);
    }
</style>
