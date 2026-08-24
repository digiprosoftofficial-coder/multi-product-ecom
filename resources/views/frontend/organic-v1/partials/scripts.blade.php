<script src="{{ asset('organic-v1/js/jquery-1.11.0.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="{{ asset('organic-v1/js/plugins.js') }}"></script>
<script src="{{ asset('organic-v1/js/script.js') }}"></script>
<script src="{{ asset('js/bangladesh-phone.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
  const cartCountEl = document.getElementById('cart-count');

  function updateCartCount(count) {
    if (!cartCountEl) return;
    const value = Number(count) || 0;
    cartCountEl.textContent = value;
    cartCountEl.style.display = value > 0 ? 'inline-block' : 'none';
  }

  if (cartCountEl) {
    updateCartCount(cartCountEl.textContent);
  }

  document.querySelectorAll('form.js-add-to-cart').forEach(function (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
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
        .then(async function (res) {
          if (!res.ok) {
            const data = await res.json().catch(function () { return {}; });
            throw new Error(data.message || 'Unable to add to cart.');
          }
          return res.json();
        })
        .then(function (data) {
          if (data.tracking && window.StorefrontTracking) {
            window.StorefrontTracking.addToCart(data.tracking);
          }
          if (data.redirect) {
            window.location.href = data.redirect;
            return;
          }
          if (typeof data.cartCount !== 'undefined') {
            updateCartCount(data.cartCount);
            refreshCartSidebar();
          }
        })
        .catch(function () {
          form.submit();
        });
    });
  });

  const offcanvasCart = document.getElementById('offcanvasCart');
  if (offcanvasCart) {
    offcanvasCart.addEventListener('submit', function (e) {
      if (!e.target.matches('form.js-remove-from-cart') && !e.target.matches('form.js-update-cart-qty')) return;
      e.preventDefault();
      const form = e.target;
      const formData = new FormData(form);

      fetch(form.action, {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
          ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
        },
        body: formData,
      })
        .then(function (res) {
          if (!res.ok) throw new Error('Cart request failed');
          return res.json();
        })
        .then(function (data) {
          if (typeof data.cartCount !== 'undefined') {
            updateCartCount(data.cartCount);
          }
          refreshCartSidebar();
        })
        .catch(function () {
          form.submit();
        });
    });

    offcanvasCart.addEventListener('click', function (e) {
      const minus = e.target.closest('.js-qty-minus');
      const plus = e.target.closest('.js-qty-plus');
      if (!minus && !plus) return;

      const form = e.target.closest('form.js-update-cart-qty');
      if (!form) return;
      const input = form.querySelector('.js-qty-input');
      if (!input) return;

      const min = Number(input.min || 1);
      const max = Number(input.max || 99);
      let value = Number(input.value || 1);

      if (minus) value = Math.max(min, value - 1);
      if (plus) value = Math.min(max, value + 1);

      if (value === Number(input.value)) return;
      input.value = value;
      form.requestSubmit();
    });

    offcanvasCart.addEventListener('change', function (e) {
      if (!e.target.matches('.js-qty-input')) return;
      const form = e.target.closest('form.js-update-cart-qty');
      if (!form) return;
      const min = Number(e.target.min || 1);
      const max = Number(e.target.max || 99);
      let value = Number(e.target.value || 1);
      if (value < min) value = min;
      if (value > max) value = max;
      e.target.value = value;
      form.requestSubmit();
    });
  }

  function refreshCartSidebar() {
    fetch('{{ route("cart.sidebar") }}', {
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
    })
      .then(function (res) {
        if (!res.ok) return;
        return res.text();
      })
      .then(function (html) {
        if (typeof html === 'string') {
          const wrap = document.getElementById('cart-sidebar-content');
          if (wrap) wrap.innerHTML = html;
        }
      })
      .catch(function () {});
  }
});
</script>
