<style>
    :root {
        --form-border: #cfd5cc;
        --form-border-focus: #6BB252;
        --form-bg: #ffffff;
        --form-bg-focus: #fafff8;
    }

    main .form-control:not(.border-0),
    main .form-select,
    main textarea.form-control,
    .offcanvas .form-control:not(.border-0),
    .offcanvas .form-select,
    body.auth-page .form-control,
    body.auth-page .form-select,
    body.auth-page textarea.form-control {
        background-color: var(--form-bg);
        border: 1.5px solid var(--form-border) !important;
        border-radius: 10px;
        min-height: 46px;
        padding: 0.55rem 0.9rem;
        color: #1f2937;
        box-shadow: none;
        transition: border-color .15s ease, box-shadow .15s ease, background-color .15s ease;
    }

    main textarea.form-control,
    .offcanvas textarea.form-control,
    body.auth-page textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    main .form-control:not(.border-0):focus,
    main .form-select:focus,
    main textarea.form-control:focus,
    .offcanvas .form-control:not(.border-0):focus,
    .offcanvas .form-select:focus,
    body.auth-page .form-control:focus,
    body.auth-page .form-select:focus,
    body.auth-page textarea.form-control:focus {
        background-color: var(--form-bg-focus);
        border-color: var(--form-border-focus) !important;
        box-shadow: 0 0 0 0.22rem rgba(107, 178, 82, 0.22) !important;
        outline: none;
        color: #1f2937;
    }

    main .form-control::placeholder,
    .offcanvas .form-control::placeholder,
    body.auth-page .form-control::placeholder {
        color: #94a3b8;
        opacity: 1;
    }

    main .form-label,
    .offcanvas .form-label,
    body.auth-page .form-label {
        font-weight: 600;
        color: #334155;
        margin-bottom: 0.4rem;
    }

    main .form-control.is-invalid,
    main .form-select.is-invalid,
    body.auth-page .form-control.is-invalid {
        border-color: #dc3545 !important;
    }

    main .form-control.is-invalid:focus,
    body.auth-page .form-control.is-invalid:focus {
        box-shadow: 0 0 0 0.22rem rgba(220, 53, 69, 0.18) !important;
    }

    .site-header .search-bar .form-control,
    .site-header .search-bar .form-select {
        border: 0 !important;
        min-height: auto;
        box-shadow: none !important;
        background: transparent !important;
    }

    .input-group-sm .form-control:not(.border-0) {
        min-height: 34px;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
    }
</style>
