<script src="{{ asset('organic-v1/js/jquery-1.11.0.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="{{ asset('organic-v1/js/plugins.js') }}"></script>
<script src="{{ asset('organic-v1/js/script.js') }}"></script>
<script src="{{ asset('js/bangladesh-phone.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
  function updateCartCount(count) {
    const value = Number(count) || 0;
    document.querySelectorAll('.js-cart-count').forEach(function (el) {
      el.textContent = value;
      el.style.display = value > 0 ? '' : 'none';
    });
  }

  const cartCountEl = document.getElementById('cart-count');
  if (cartCountEl) {
    updateCartCount(cartCountEl.textContent);
  }

  var searchToggle = document.getElementById('headerSearchToggle');
  var mobileSearchPanel = document.getElementById('headerSearchPanel');

  if (searchToggle) {
    searchToggle.addEventListener('click', function () {
      if (mobileSearchPanel && window.bootstrap?.Collapse) {
        bootstrap.Collapse.getOrCreateInstance(mobileSearchPanel).toggle();
      }
    });
  }

  if (mobileSearchPanel) {
    mobileSearchPanel.addEventListener('shown.bs.collapse', function () {
      document.getElementById('mobile-search-input')?.focus();
    });
    mobileSearchPanel.addEventListener('show.bs.collapse', function () {
      searchToggle?.setAttribute('aria-expanded', 'true');
    });
    mobileSearchPanel.addEventListener('hide.bs.collapse', function () {
      searchToggle?.setAttribute('aria-expanded', 'false');
    });
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
          if (window.showStoreToast) {
            window.showStoreToast(form.dataset.productName || data.message || '');
          }
        })
        .catch(function (err) {
          if (window.showStoreToast) {
            window.showStoreToast(err && err.message ? err.message : 'Unable to add to cart.', 'error');
            return;
          }
          form.submit();
        });
    });
  });

  const offcanvasCart = document.getElementById('offcanvasCart');
  if (offcanvasCart) {
    offcanvasCart.addEventListener('submit', function (e) {
      if (!e.target.matches('form.js-remove-from-cart') && !e.target.matches('form.js-update-cart-qty') && !e.target.matches('form.js-change-cart-variant')) return;
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

  function syncCartVariantForm(form) {
    var variants = [];
    try { variants = JSON.parse(form.getAttribute('data-variants') || '[]'); } catch (e) { variants = []; }
    var o1 = form.querySelector('[data-cart-option="1"]');
    var o2 = form.querySelector('[data-cart-option="2"]');
    var hidden = form.querySelector('.js-variant-id');
    var selected1 = o1 ? o1.value : '';
    var selected2 = o2 ? o2.value : '';

    if (o2) {
      var firstAvailable = '';
      Array.prototype.forEach.call(o2.options, function (opt) {
        if (!opt.value) return;
        var row = variants.find(function (v) { return v.option1 === selected1 && v.option2 === opt.value; });
        opt.disabled = !row || row.stock < 1;
        if (!opt.disabled && !firstAvailable) firstAvailable = opt.value;
      });
      var current = variants.find(function (v) { return v.option1 === selected1 && v.option2 === selected2; });
      if ((!current || current.stock < 1) && firstAvailable) {
        o2.value = firstAvailable;
        selected2 = firstAvailable;
      }
    }

    var match = variants.find(function (row) {
      if (row.option1 !== selected1) return false;
      if (o2 && row.option2 !== selected2) return false;
      return true;
    });
    if (hidden && match) hidden.value = String(match.id);
    return match;
  }

  document.addEventListener('change', function (e) {
    if (!e.target.matches('[data-cart-option]')) return;
    var form = e.target.closest('form.js-change-cart-variant');
    if (!form) return;
    var from = form.querySelector('[name="from_variant_id"]');
    var match = syncCartVariantForm(form);
    if (!match || match.stock < 1) return;
    if (from && String(match.id) === String(from.value)) return;
    form.requestSubmit();
  });
});
</script>
