<script>
(function () {
  var key = 'gadget-v1-theme';
  var stored = localStorage.getItem(key);
  var mode = stored === 'light' ? 'light' : 'dark';
  document.documentElement.classList.add('gadget-theme-loaded');
  document.body.classList.add('theme-' + mode);
})();
</script>
<header class="gadget-header navbar navbar-expand-lg navbar-dark sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('home') }}">
      <span class="text-accent">Gadget</span>Store
    </a>
    <button class="navbar-toggler border-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#gadgetNav" aria-controls="gadgetNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="gadgetNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}">Shop</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">About</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact</a></li>
      </ul>
      <ul class="navbar-nav align-items-center">
        <li class="nav-item me-2">
          <button type="button" class="btn btn-sm btn-outline-secondary border-0 p-2 gadget-theme-toggle" aria-label="Toggle dark/light mode" title="Toggle theme">
            <i class="fas fa-moon theme-icon-dark d-none" aria-hidden="true"></i>
            <i class="fas fa-sun theme-icon-light" aria-hidden="true"></i>
          </button>
        </li>
        <li class="nav-item">
          <a class="nav-link position-relative" href="{{ route('cart.index') }}" data-bs-toggle="offcanvas" data-bs-target="#gadgetCartOffcanvas" aria-controls="gadgetCartOffcanvas">
            <i class="fas fa-shopping-cart"></i>
            @if(isset($cartCount) && $cartCount > 0)
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary">{{ $cartCount }}</span>
            @endif
          </a>
        </li>
      </ul>
    </div>
  </div>
</header>
<script>
(function () {
  var key = 'gadget-v1-theme';
  function applyMode(mode) {
    document.body.classList.remove('theme-dark', 'theme-light');
    document.body.classList.add('theme-' + mode);
    localStorage.setItem(key, mode);
    var darkIcon = document.querySelector('.gadget-theme-toggle .theme-icon-dark');
    var lightIcon = document.querySelector('.gadget-theme-toggle .theme-icon-light');
    if (darkIcon && lightIcon) {
      darkIcon.classList.toggle('d-none', mode !== 'light');
      lightIcon.classList.toggle('d-none', mode !== 'dark');
    }
  }
  document.addEventListener('DOMContentLoaded', function () {
    var stored = localStorage.getItem(key);
    var mode = stored === 'light' ? 'light' : 'dark';
    applyMode(mode);
    var btn = document.querySelector('.gadget-theme-toggle');
    if (btn) btn.addEventListener('click', function () { applyMode(mode === 'dark' ? 'light' : 'dark'); mode = mode === 'dark' ? 'light' : 'dark'; });
  });
})();
</script>

<div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="gadgetCartOffcanvas" aria-labelledby="gadgetCartOffcanvasLabel">
  <div class="offcanvas-header border-secondary">
    <h5 class="offcanvas-title" id="gadgetCartOffcanvasLabel">Your Cart</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body" id="gadget-cart-sidebar-content">
    @if(isset($cartItems) && isset($cartTotal))
      @include('frontend.gadget-v1.partials.cart-sidebar-content', ['cartItems' => $cartItems, 'cartTotal' => $cartTotal])
    @else
      <p class="text-muted mb-0">Cart is empty.</p>
      <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">Shop</a>
    @endif
  </div>
</div>
