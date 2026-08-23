<footer class="footer-gadget py-5 mt-5">
  <div class="container">
    <div class="row g-4">
      <div class="col-md-4">
        <h6 class="text-accent mb-3">GadgetStore</h6>
        <p class="text-muted small mb-0">Accessories & gadgets for the modern life. Tech-focused, quality-driven.</p>
      </div>
      <div class="col-md-2">
        <h6 class="text-white mb-3">Quick links</h6>
        <ul class="list-unstyled small">
          <li><a href="{{ route('home') }}" class="link-accent text-decoration-none">Home</a></li>
          <li><a href="{{ route('products.index') }}" class="link-accent text-decoration-none">Shop</a></li>
          <li><a href="{{ route('about') }}" class="link-accent text-decoration-none">About</a></li>
          <li><a href="{{ route('contact') }}" class="link-accent text-decoration-none">Contact</a></li>
        </ul>
      </div>
      <div class="col-md-2">
        <h6 class="text-white mb-3">Support</h6>
        <ul class="list-unstyled small">
          <li><a href="{{ route('contact') }}" class="link-accent text-decoration-none">Contact us</a></li>
          <li><a href="{{ route('delivery') }}" class="link-accent text-decoration-none">Delivery</a></li>
          <li><a href="{{ route('returns') }}" class="link-accent text-decoration-none">Returns</a></li>
          <li><a href="{{ route('cart.index') }}" class="link-accent text-decoration-none">Cart</a></li>
        </ul>
      </div>
      <div class="col-md-4 text-md-end">
        <h6 class="text-white mb-3">Newsletter</h6>
        <p class="text-muted small">Stay updated with new products and offers.</p>
      </div>
    </div>
    <hr class="border-secondary my-4">
    <div class="text-center text-muted small">&copy; {{ date('Y') }} GadgetStore. All rights reserved.</div>
  </div>
</footer>
