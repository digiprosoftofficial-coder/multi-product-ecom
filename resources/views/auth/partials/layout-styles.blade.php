<style>
    body.auth-page main {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f6f7f2;
        padding: 1rem 0 1.25rem;
    }

    body.auth-page .auth-shell {
        width: 100%;
        max-width: 440px;
        padding: 0 1rem;
        margin: 0 auto;
    }

    body.auth-page .auth-title-wrap {
        padding-top: 0.25rem;
    }

    body.auth-page .auth-title {
        margin: 0 0 0.45rem;
        font-size: clamp(1.55rem, 4.5vw, 1.85rem);
        font-weight: 700;
        letter-spacing: -0.03em;
        line-height: 1.2;
        color: #1f2937;
    }

    body.auth-page .auth-title::after {
        content: "";
        display: block;
        width: 40px;
        height: 3px;
        margin: 0.7rem auto 0;
        border-radius: 999px;
        background: linear-gradient(90deg, #6BB252, #9ed086);
    }

    body.auth-page .auth-subtitle {
        font-size: 0.9rem;
        color: #64748b;
        line-height: 1.45;
    }

    body.auth-page .login-card {
        background: #fff;
    }

    body.auth-page .login-card .card-body {
        padding: 1.5rem !important;
    }

    body.auth-page .login-card .form-control {
        background: #fff;
        border: 1px solid #cfd5cc;
        box-shadow: none;
        min-height: 46px;
        padding: 0.5rem 0.875rem;
        font-size: 1rem;
    }

    body.auth-page .login-card .form-control:focus {
        border-color: #6BB252;
        box-shadow: 0 0 0 0.2rem rgba(107, 178, 82, 0.2);
    }

    body.auth-page .login-card .form-check-input {
        border: 1px solid #cfd5cc;
        background-color: #fff;
    }

    body.auth-page .login-card .form-check-input:checked {
        background-color: #6BB252;
        border-color: #6BB252;
    }

    body.auth-page .login-card .btn-primary {
        min-height: 46px;
    }

    @media (min-width: 768px) {
        body.auth-page .login-card .card-body {
            padding: 2rem !important;
        }
    }

    @media (max-width: 991.98px) {
        body.auth-page main {
            padding-bottom: calc(1rem + 68px + env(safe-area-inset-bottom, 0px));
        }
    }
</style>
