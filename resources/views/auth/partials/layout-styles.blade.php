<style>
    body.auth-page main {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f6f7f2;
        padding: 1rem 0 1.25rem;
    }

    body.auth-page .auth-header {
        box-shadow: 0 1px 8px rgba(0, 0, 0, 0.05);
    }

    body.auth-page .auth-shell {
        width: 100%;
        max-width: 440px;
        padding: 0 1rem;
        margin: 0 auto;
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
</style>
