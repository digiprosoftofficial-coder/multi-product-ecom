<style>
    :root {
        --header-bg: {{ theme_color('header_bg_color', '#1f3b2c') }};
        --header-text: {{ theme_color('header_text_color', '#ffffff') }};
        --footer-bg: {{ theme_color('footer_bg_color', '#1f3b2c') }};
        --footer-text: {{ theme_color('footer_text_color', '#ffffff') }};
        --footer-bottom-bg: {{ theme_color('footer_bottom_bg_color', '#6bb252') }};
        --footer-bottom-text: {{ theme_color('footer_bottom_text_color', '#ffffff') }};
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
</style>
