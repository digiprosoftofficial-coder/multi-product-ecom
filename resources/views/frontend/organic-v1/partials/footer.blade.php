@unless(request()->routeIs('login', 'register'))
<footer class="py-5">
  <div class="container-lg">
    <div class="row">
      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="footer-menu">
          @if(footer_logo_url())
            <a href="{{ route('home') }}">
              <img src="{{ footer_logo_url() }}" alt="{{ site_name() }}" style="max-width: 240px; max-height: 70px; width: auto; height: auto;">
            </a>
          @else
            <a href="{{ route('home') }}" class="text-decoration-none">
              <h4 class="mb-0">{{ site_name() }}</h4>
            </a>
          @endif
          @if(setting('footer_text'))
            <p class="text-muted small mt-3 mb-0">{{ setting('footer_text') }}</p>
          @endif
        </div>
      </div>

      <div class="col-md-2 col-sm-6">
        <div class="footer-menu">
          <h5 class="widget-title">{{ site_name() }}</h5>
          <ul class="menu-list list-unstyled">
            <li class="menu-item"><a href="{{ route('about') }}" class="nav-link">About</a></li>
          </ul>
        </div>
      </div>
      <div class="col-md-2 col-sm-6">
        <div class="footer-menu">
          <h5 class="widget-title">Quick Links</h5>
          <ul class="menu-list list-unstyled">
            <li class="menu-item"><a href="{{ route('products.index') }}" class="nav-link">Shop</a></li>
            <li class="menu-item"><a href="{{ route('cart.index') }}" class="nav-link">Cart</a></li>
            <li class="menu-item"><a href="{{ route('checkout.index') }}" class="nav-link">Checkout</a></li>
            @auth
              <li class="menu-item"><a href="{{ route('orders.index') }}" class="nav-link">My Orders</a></li>
            @endauth
          </ul>
        </div>
      </div>
      <div class="col-md-2 col-sm-6">
        <div class="footer-menu">
          <h5 class="widget-title">Customer Service</h5>
          <ul class="menu-list list-unstyled">
            <li class="menu-item"><a href="{{ route('contact') }}" class="nav-link">Contact</a></li>
            <li class="menu-item"><a href="{{ route('delivery') }}" class="nav-link">Delivery Information</a></li>
            <li class="menu-item"><a href="{{ route('returns') }}" class="nav-link">Product Returns</a></li>
            <li class="menu-item"><a href="{{ route('privacy') }}" class="nav-link">Privacy Policy</a></li>
            <li class="menu-item"><a href="{{ route('terms') }}" class="nav-link">Terms &amp; Conditions</a></li>
          </ul>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="footer-menu">
          @if(setting('contact_phone') || setting('contact_email') || setting('contact_address'))
            <h5 class="widget-title">Get in touch</h5>
            @if(setting('contact_phone'))
              <p class="mb-1">{{ setting('contact_phone') }}</p>
            @endif
            @if(setting('contact_email'))
              <p class="mb-1">{{ setting('contact_email') }}</p>
            @endif
            @if(setting('contact_address'))
              <p class="mb-0">{!! nl2br(e(setting('contact_address'))) !!}</p>
            @endif
          @endif
        </div>
      </div>
    </div>
  </div>
</footer>
@endunless
<div id="footer-bottom">
  <div class="container-lg">
    <p class="mb-0">&copy; {{ date('Y') }} {{ site_name() }}. All rights reserved.</p>
  </div>
</div>
