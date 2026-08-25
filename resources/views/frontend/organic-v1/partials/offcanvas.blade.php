<div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasCart">
  <div class="offcanvas-header">
    <button type="button" class="btn-close ms-0 me-2" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    <h4 class="offcanvas-title flex-grow-1 text-center text-uppercase fs-6 mb-0">Your Cart</h4>
  </div>
  <div class="offcanvas-body">
    <div class="order-md-last" id="cart-sidebar-content">
      @include('frontend.organic-v1.partials.cart-sidebar-content', [
        'cartItems' => $cartItems ?? [],
        'cartTotal' => $cartTotal ?? 0,
      ])
    </div>
  </div>
</div>

<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar">
  <div class="offcanvas-header justify-content-between">
    <h4 class="fw-normal text-uppercase fs-6">{{ site_name() }}</h4>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <ul class="navbar-nav list-unstyled d-flex flex-column gap-1 mb-3 pb-3 border-bottom d-lg-none">
      <li class="nav-item">
        <a href="{{ route('home') }}" class="nav-link text-dark p-2 fw-semibold {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
      </li>
      <li class="nav-item">
        <a href="{{ route('products.index') }}" class="nav-link text-dark p-2 fw-semibold {{ request()->routeIs('products.*') ? 'active' : '' }}">Shop</a>
      </li>
      <li class="nav-item">
        <a href="{{ route('about') }}" class="nav-link text-dark p-2 fw-semibold {{ request()->routeIs('about') ? 'active' : '' }}">About</a>
      </li>
      <li class="nav-item">
        <a href="{{ route('contact') }}" class="nav-link text-dark p-2 fw-semibold {{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
      </li>
    </ul>
    <p class="text-uppercase text-muted small mb-2 px-2 d-lg-none">Categories</p>
    <ul class="navbar-nav justify-content-end menu-list list-unstyled d-flex flex-column gap-2 mb-0">
      @foreach($navCategories ?? collect() as $navCategory)
        @if($navCategory->children->count())
          <li class="nav-item border-dashed">
            <button class="btn btn-toggle dropdown-toggle position-relative w-100 d-flex justify-content-between align-items-center text-dark p-2" data-bs-toggle="collapse" data-bs-target="#cat-{{ $navCategory->id }}-collapse" aria-expanded="true">
              <div class="d-flex gap-3 align-items-center">
                @if($navCategory->image_url)
                  <img src="{{ $navCategory->image_url }}" alt="{{ $navCategory->name }}" class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover;">
                @else
                  <span class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                    <i class="fa-solid fa-folder"></i>
                  </span>
                @endif
                <span>{{ $navCategory->name }}</span>
              </div>
            </button>
            <div class="collapse show" id="cat-{{ $navCategory->id }}-collapse">
              <ul class="btn-toggle-nav list-unstyled fw-normal ps-4 pb-2">
                @foreach($navCategory->children as $child)
                  <li class="border-bottom py-1">
                    <a href="{{ route('products.category', $child->slug) }}" class="dropdown-item d-flex align-items-center gap-2">
                      @if($child->image_url)
                        <img src="{{ $child->image_url }}" alt="{{ $child->name }}" class="rounded-circle" style="width: 26px; height: 26px; object-fit: cover;">
                      @else
                        <span class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center" style="width: 26px; height: 26px;">
                          <i class="fa-solid fa-tag"></i>
                        </span>
                      @endif
                      <span>{{ $child->name }}</span>
                    </a>
                  </li>
                @endforeach
              </ul>
            </div>
          </li>
        @else
          <li class="nav-item border-dashed">
            <a href="{{ route('products.category', $navCategory->slug) }}" class="nav-link d-flex align-items-center gap-3 text-dark p-2">
              @if($navCategory->image_url)
                <img src="{{ $navCategory->image_url }}" alt="{{ $navCategory->name }}" class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover;">
              @else
                <span class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                  <i class="fa-solid fa-folder"></i>
                </span>
              @endif
              <span>{{ $navCategory->name }}</span>
            </a>
          </li>
        @endif
      @endforeach
    </ul>
    <ul class="navbar-nav list-unstyled d-flex flex-column gap-2 mt-4 pt-3 border-top mb-0">
      @auth
        <li class="nav-item">
          <a href="{{ route('dashboard') }}" class="nav-link text-dark p-2">My account</a>
        </li>
        <li class="nav-item">
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link border-0 bg-transparent text-dark p-2 w-100 text-start">Logout</button>
          </form>
        </li>
      @else
        <li class="nav-item">
          <a href="{{ route('login') }}" class="nav-link text-dark p-2">Login</a>
        </li>
        <li class="nav-item">
          <a href="{{ route('register') }}" class="nav-link text-dark p-2">Register</a>
        </li>
      @endauth
    </ul>
  </div>
</div>
