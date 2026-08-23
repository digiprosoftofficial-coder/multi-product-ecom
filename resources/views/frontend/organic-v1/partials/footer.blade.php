@unless(request()->routeIs('login', 'register'))
<footer class="site-footer py-5">
  <div class="container-lg">
    <div class="row g-4 g-lg-5">
      <div class="col-lg-4 col-md-6">
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
            <p class="footer-brand-text mt-3 mb-0">{{ setting('footer_text') }}</p>
          @endif
        </div>
        @include('frontend.components.social-links', ['variant' => 'footer'])
      </div>

      <div class="col-lg-2 col-md-6">
        <div class="footer-menu">
          <h5 class="widget-title">Quick Links</h5>
          <ul class="menu-list list-unstyled">
            <li class="menu-item"><a href="{{ route('about') }}" class="nav-link">About</a></li>
            <li class="menu-item"><a href="{{ route('products.index') }}" class="nav-link">Shop</a></li>
            <li class="menu-item"><a href="{{ route('cart.index') }}" class="nav-link">Cart</a></li>
            <li class="menu-item"><a href="{{ route('checkout.index') }}" class="nav-link">Checkout</a></li>
            @auth
              <li class="menu-item"><a href="{{ route('orders.index') }}" class="nav-link">My Orders</a></li>
            @endauth
          </ul>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
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

      <div class="col-lg-3 col-md-6">
        <div class="footer-menu">
          @if(setting('contact_phone') || setting('contact_email') || setting('contact_address'))
            <h5 class="widget-title">Get in touch</h5>
            <ul class="footer-contact-list list-unstyled mb-0">
              @if(setting('contact_phone'))
                <li class="footer-contact-item">
                  <span class="footer-contact-icon footer-contact-icon--phone"><i class="fa-solid fa-phone"></i></span>
                  <a href="tel:{{ preg_replace('/\s+/', '', setting('contact_phone')) }}" class="footer-contact-value">{{ setting('contact_phone') }}</a>
                </li>
              @endif
              @if(setting('contact_email'))
                <li class="footer-contact-item">
                  <span class="footer-contact-icon footer-contact-icon--email"><i class="fa-solid fa-envelope"></i></span>
                  <a href="mailto:{{ setting('contact_email') }}" class="footer-contact-value">{{ setting('contact_email') }}</a>
                </li>
              @endif
              @if(setting('contact_address'))
                <li class="footer-contact-item">
                  <span class="footer-contact-icon footer-contact-icon--address"><i class="fa-solid fa-location-dot"></i></span>
                  <span class="footer-contact-value">{!! nl2br(e(setting('contact_address'))) !!}</span>
                </li>
              @endif
            </ul>
          @endif
        </div>
      </div>
    </div>
  </div>
</footer>
@endunless
<div id="footer-bottom">
  <div class="container-lg">
    <p class="mb-0">
      &copy; {{ date('Y') }} {{ site_name() }}. All rights reserved.
      <span class="footer-bottom-sep" aria-hidden="true">|</span>
      Developed by
      <a href="https://digiprosoft.com/" target="_blank" rel="noopener noreferrer" class="footer-credit-link">Digiprosoft</a>
    </p>
  </div>
</div>

@once
@push('styles')
<style>
    .footer-brand-text {
        font-size: .98rem;
        line-height: 1.65;
    }
    .footer-contact-list {
        display: flex;
        flex-direction: column;
        gap: 1.1rem;
    }
    .footer-contact-item {
        display: flex;
        align-items: center;
        gap: .85rem;
    }
    .footer-contact-icon {
        flex-shrink: 0;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1rem;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.1);
    }
    .footer-contact-icon--phone { background: #6BB252; }
    .footer-contact-icon--email { background: #2563eb; }
    .footer-contact-icon--address { background: #ea580c; }
    .footer-contact-value {
        flex: 1;
        min-width: 0;
        font-size: 1rem;
        font-weight: 600;
        line-height: 1.45;
        text-decoration: none;
        word-break: break-word;
    }
</style>
@endpush
@endonce
