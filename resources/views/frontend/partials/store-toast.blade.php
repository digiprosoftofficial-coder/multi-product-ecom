<div id="store-toast" class="store-toast" hidden role="status" aria-live="polite">
    <span class="store-toast__icon" aria-hidden="true">
        <i class="fa-solid fa-circle-check"></i>
    </span>
    <span class="store-toast__body">
        <strong class="store-toast__title">Added to cart</strong>
        <span class="store-toast__text"></span>
    </span>
    <button type="button" class="store-toast__cart" data-store-toast-cart>View</button>
</div>

<style>
    .store-toast {
        position: fixed;
        left: 50%;
        bottom: calc(84px + env(safe-area-inset-bottom, 0px));
        z-index: 1080;
        display: flex;
        align-items: center;
        gap: 0.7rem;
        width: min(420px, calc(100vw - 1.5rem));
        padding: 0.8rem 0.9rem;
        border-radius: 16px;
        background: linear-gradient(135deg, #5cb85c 0%, #6BB252 42%, #f7a422 100%);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.35);
        box-shadow: 0 10px 28px rgba(107, 178, 82, 0.45), 0 6px 18px rgba(247, 164, 34, 0.28);
        transform: translate(-50%, 12px);
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.2s ease, transform 0.2s ease;
        overflow: hidden;
    }
    .store-toast::before {
        content: "";
        position: absolute;
        inset: 0 auto 0 -40%;
        width: 40%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.28), transparent);
        transform: skewX(-18deg);
        pointer-events: none;
    }
    .store-toast.is-visible {
        opacity: 1;
        transform: translate(-50%, 0);
        pointer-events: auto;
    }
    .store-toast.is-visible::before {
        animation: store-toast-shine 0.9s ease-out;
    }
    .store-toast.is-error {
        background: linear-gradient(135deg, #ef4444 0%, #f95f09 100%);
        box-shadow: 0 10px 28px rgba(239, 68, 68, 0.4);
    }
    .store-toast__icon {
        position: relative;
        flex: 0 0 auto;
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        background: #fff;
        color: #5cb85c;
        font-size: 1.05rem;
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.28);
    }
    .store-toast.is-error .store-toast__icon {
        background: #fff;
        color: #ef4444;
    }
    .store-toast__body {
        position: relative;
        min-width: 0;
        flex: 1 1 auto;
        display: flex;
        flex-direction: column;
        gap: 0.12rem;
        line-height: 1.25;
    }
    .store-toast__title {
        font-size: 0.95rem;
        font-weight: 800;
        letter-spacing: 0.01em;
        text-shadow: 0 1px 0 rgba(0, 0, 0, 0.12);
    }
    .store-toast__text {
        font-size: 0.78rem;
        color: #fff8e7;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .store-toast__cart {
        position: relative;
        flex: 0 0 auto;
        border: 0;
        background: #fff;
        color: #f95f09;
        font-size: 0.78rem;
        font-weight: 800;
        border-radius: 999px;
        padding: 0.4rem 0.85rem;
        line-height: 1.2;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
    }
    .store-toast.is-error .store-toast__cart {
        display: none;
    }
    @keyframes store-toast-shine {
        from { left: -40%; }
        to { left: 120%; }
    }
    @media (min-width: 992px) {
        .store-toast {
            bottom: 24px;
        }
    }
</style>

<script>
(function () {
    var toast = document.getElementById('store-toast');
    if (!toast) return;
    var titleEl = toast.querySelector('.store-toast__title');
    var textEl = toast.querySelector('.store-toast__text');
    var iconEl = toast.querySelector('.store-toast__icon i');
    var hideTimer;

    window.showStoreToast = function (message, type, title) {
        type = type || 'success';
        clearTimeout(hideTimer);
        toast.classList.toggle('is-error', type === 'error');
        if (iconEl) {
            iconEl.className = type === 'error' ? 'fa-solid fa-circle-exclamation' : 'fa-solid fa-circle-check';
        }
        if (titleEl) titleEl.textContent = title || (type === 'error' ? 'Couldn’t add to cart' : 'Added to cart');
        if (textEl) {
            textEl.textContent = message || '';
            textEl.hidden = !message;
        }
        toast.hidden = false;
        requestAnimationFrame(function () {
            toast.classList.add('is-visible');
        });
        hideTimer = setTimeout(function () {
            toast.classList.remove('is-visible');
            setTimeout(function () { toast.hidden = true; }, 220);
        }, 2800);
    };

    toast.querySelector('[data-store-toast-cart]')?.addEventListener('click', function () {
        var cartEl = document.getElementById('offcanvasCart') || document.getElementById('gadgetCartOffcanvas');
        if (cartEl && window.bootstrap && window.bootstrap.Offcanvas) {
            window.bootstrap.Offcanvas.getOrCreateInstance(cartEl).show();
            return;
        }
        window.location.href = @json(route('cart.index'));
    });
})();
</script>
