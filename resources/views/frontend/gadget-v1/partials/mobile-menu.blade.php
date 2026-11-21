<nav class="d-lg-none py-2 border-secondary border-bottom">
  <div class="container">
    <ul class="nav flex-column">
      <li class="nav-item"><a class="nav-link link-accent" href="{{ route('home') }}">{{ __('storefront.home') }}</a></li>
      <li class="nav-item"><a class="nav-link link-accent" href="{{ route('products.index') }}">{{ __('storefront.shop') }}</a></li>
      <li class="nav-item"><a class="nav-link link-accent" href="{{ route('cart.index') }}">{{ __('storefront.cart') }}</a></li>
      <li class="nav-item"><a class="nav-link link-accent" href="{{ route('about') }}">{{ __('storefront.about') }}</a></li>
      <li class="nav-item"><a class="nav-link link-accent" href="{{ route('contact') }}">{{ __('storefront.contact') }}</a></li>
      @auth
        <li class="nav-item"><a class="nav-link link-accent" href="{{ route('dashboard') }}">{{ __('storefront.account') }}</a></li>
        <li class="nav-item">
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link link-accent border-0 bg-transparent">{{ __('storefront.logout') }}</button>
          </form>
        </li>
      @else
        <li class="nav-item"><a class="nav-link link-accent" href="{{ route('login') }}">{{ __('storefront.login') }}</a></li>
      @endauth
    </ul>
  </div>
</nav>
