<style>
    body.auth-screen {
        margin: 0;
        min-height: 100vh;
        background: var(--auth-backdrop);
        color: #1f2937;
        font-family: "Segoe UI", system-ui, sans-serif;
    }
    .auth-stage {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
    }
    .auth-frame {
        width: min(1040px, 100%);
        min-height: 620px;
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1.05fr);
        background: #f7f7f8;
        border-radius: 28px;
        overflow: hidden;
        box-shadow: 0 24px 60px rgba(80, 48, 42, .12);
    }
    .auth-panel {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2.5rem 2.25rem;
    }
    .auth-form {
        width: min(100%, 360px);
    }
    .auth-form h1 {
        margin: 0 0 .45rem;
        font-size: 2rem;
        font-weight: 750;
        letter-spacing: -0.03em;
        text-align: center;
    }
    .auth-form .auth-lead {
        margin: 0 0 1.35rem;
        text-align: center;
        color: #6b7280;
        font-size: .95rem;
    }
    .auth-form .form-control {
        min-height: 48px;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        background: #fff;
        box-shadow: none;
        padding: .7rem .9rem;
    }
    .auth-form .form-control:focus {
        border-color: var(--auth-color);
        box-shadow: 0 0 0 .2rem color-mix(in srgb, var(--auth-color) 22%, transparent);
    }
    .auth-password {
        position: relative;
    }
    .auth-password .form-control {
        padding-right: 2.6rem;
    }
    .auth-password-toggle {
        position: absolute;
        top: 50%;
        right: .7rem;
        transform: translateY(-50%);
        border: 0;
        background: transparent;
        color: #9ca3af;
        padding: 0;
        line-height: 1;
    }
    .auth-submit {
        width: 100%;
        min-height: 48px;
        border: 0;
        border-radius: 10px;
        background: var(--auth-color);
        color: #fff;
        font-weight: 600;
    }
    .auth-submit:hover,
    .auth-submit:focus {
        background: color-mix(in srgb, var(--auth-color) 86%, #000);
        color: #fff;
    }
    .auth-switch {
        margin: 1rem 0 0;
        text-align: center;
        color: #6b7280;
        font-size: .92rem;
    }
    .auth-switch a {
        color: var(--auth-color);
        font-weight: 600;
        text-decoration: none;
    }
    .auth-switch a:hover {
        text-decoration: underline;
    }
    .auth-visual {
        margin: 1rem 1rem 1rem 0;
        border-radius: 22px;
        overflow: hidden;
        background: color-mix(in srgb, var(--auth-color) 28%, #f3e7ea);
        min-height: 420px;
    }
    .auth-visual img,
    .auth-visual-fallback {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .auth-visual-fallback {
        background:
            radial-gradient(circle at 50% 32%, #fff6c8 0 9%, transparent 10%),
            linear-gradient(180deg, #f0b48a 0%, #d9a0c4 42%, #8d7ec8 100%);
    }
    @media (max-width: 860px) {
        .auth-frame {
            grid-template-columns: 1fr;
            min-height: 0;
        }
        .auth-visual {
            order: -1;
            margin: 1rem 1rem 0;
            min-height: 220px;
            height: 220px;
        }
        .auth-panel {
            padding: 1.5rem 1.25rem 1.75rem;
        }
    }
</style>
