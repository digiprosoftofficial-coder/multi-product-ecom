@php
    $cartCount = session('cart') ? count(session('cart')) : 0;
    $isHome = request()->routeIs('home');
    $isShop = request()->routeIs('products.*');
    $isCart = request()->routeIs('cart.*', 'checkout.*');
    $isOrders = request()->routeIs('orders.*');
@endphp

<nav class="mobile-bottom-nav d-lg-none" aria-label="Mobile navigation">
  <a href="{{ route('home') }}" class="mobile-bottom-nav__item {{ $isHome ? 'is-active' : '' }}">
    <span class="mobile-bottom-nav__icon">
      <i class="fa-solid fa-house"></i>
    </span>
    <span class="mobile-bottom-nav__label">Home</span>
  </a>

  <a href="{{ route('products.index') }}" class="mobile-bottom-nav__item {{ $isShop ? 'is-active' : '' }}">
    <span class="mobile-bottom-nav__icon">
      <i class="fa-solid fa-store"></i>
    </span>
    <span class="mobile-bottom-nav__label">Shop</span>
  </a>

  <a href="#offcanvasCart"
     class="mobile-bottom-nav__item {{ $isCart ? 'is-active' : '' }}"
     id="mobileCartNav"
     data-bs-toggle="offcanvas"
     data-bs-target="#offcanvasCart"
     aria-controls="offcanvasCart">
    <span class="mobile-bottom-nav__icon">
      <i class="fa-solid fa-bag-shopping"></i>
      <span class="mobile-bottom-nav__badge js-cart-count" @if($cartCount < 1) style="display:none" @endif>{{ $cartCount }}</span>
    </span>
    <span class="mobile-bottom-nav__label">Cart</span>
  </a>

  <a href="{{ auth()->check() ? route('orders.index') : '#' }}"
     class="mobile-bottom-nav__item {{ $isOrders ? 'is-active' : '' }}"
     @guest
       id="mobileOrdersNav"
       data-bs-toggle="modal"
       data-bs-target="#loginRequiredModal"
     @endguest>
    <span class="mobile-bottom-nav__icon">
      <i class="fa-solid fa-clipboard-list"></i>
    </span>
    <span class="mobile-bottom-nav__label">Orders</span>
  </a>
</nav>

@guest
<div class="modal fade login-required-modal" id="loginRequiredModal" tabindex="-1" aria-labelledby="loginRequiredModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <button type="button" class="btn-close login-required-modal__close" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-body text-center">
        <div class="login-required-modal__icon" aria-hidden="true">
          <i class="fa-solid fa-clipboard-list"></i>
        </div>
        <h2 class="login-required-modal__title" id="loginRequiredModalLabel">Login required</h2>
        <p class="login-required-modal__text">Orders দেখতে আগে login করুন।</p>
        <div class="login-required-modal__actions">
          <button type="button" class="btn btn-outline-secondary login-required-modal__cancel" data-bs-dismiss="modal">Cancel</button>
          <a href="{{ route('login') }}" class="btn btn-primary login-required-modal__login">Login</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endguest
