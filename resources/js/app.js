import './bootstrap';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

document.addEventListener('DOMContentLoaded', () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    const cartCountEl = document.getElementById('cart-count');
    const cartIconEl = document.getElementById('cartIcon');

    function updateCartCount(count) {
        if (!cartCountEl) return;
        const value = Number(count) || 0;
        cartCountEl.textContent = value;
        cartCountEl.style.display = value > 0 ? 'inline-block' : 'none';
    }

    // Initialize badge visibility
    updateCartCount(cartCountEl?.textContent);

    function flyToCart(imageUrl, startEl) {
        if (!cartIconEl) return;
        const startRect = startEl.getBoundingClientRect();
        const targetRect = cartIconEl.getBoundingClientRect();

        const img = document.createElement('img');
        img.src = imageUrl || 'https://via.placeholder.com/40?text=%20';
        img.style.position = 'fixed';
        img.style.width = '50px';
        img.style.height = '50px';
        img.style.objectFit = 'cover';
        img.style.top = `${startRect.top}px`;
        img.style.left = `${startRect.left}px`;
        img.style.zIndex = '2000';
        img.style.borderRadius = '50%';
        img.style.boxShadow = '0 4px 12px rgba(0,0,0,0.2)';
        img.style.transition = 'transform 0.7s ease, opacity 0.7s ease';
        document.body.appendChild(img);

        const targetX = targetRect.left + targetRect.width / 2 - startRect.left;
        const targetY = targetRect.top + targetRect.height / 2 - startRect.top;

        requestAnimationFrame(() => {
            img.style.transform = `translate(${targetX}px, ${targetY}px) scale(0.2)`;
            img.style.opacity = '0';
        });

        setTimeout(() => {
            img.remove();
        }, 800);
    }

    document.querySelectorAll('form.js-add-to-cart').forEach((form) => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const submitBtn = e.submitter || form.querySelector('button[type="submit"]') || form;

            const formData = new FormData(form);
            if (e.submitter && e.submitter.name) {
                formData.set(e.submitter.name, e.submitter.value);
            }

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
                },
                body: formData,
            })
                .then(async (res) => {
                    if (!res.ok) {
                        const data = await res.json().catch(() => ({}));
                        const msg = data.message || 'Unable to add to cart.';
                        throw new Error(msg);
                    }
                    return res.json();
                })
                .then((data) => {
                    if (data?.redirect) {
                        window.location.href = data.redirect;
                        return;
                    }
                    if (data?.cartCount !== undefined) {
                        updateCartCount(data.cartCount);
                    }
                    const imgUrl = form.dataset.productImage || '';
                    flyToCart(imgUrl, submitBtn);
                })
                .catch((err) => {
                    console.error(err);
                    form.submit();
                });
        });
    });
});
