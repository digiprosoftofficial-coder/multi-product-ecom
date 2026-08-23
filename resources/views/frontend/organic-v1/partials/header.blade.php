<header class="site-header">
  <div class="container-fluid">
    <div class="row py-3 border-bottom">
      <div class="col-sm-4 col-lg-2 text-center text-sm-start d-flex gap-3 justify-content-center justify-content-md-start">
        <div class="d-flex align-items-center my-3 my-sm-0">
          <a href="{{ route('home') }}" class="d-flex align-items-center gap-2 text-decoration-none">
            @if(site_logo_url())
              <img src="{{ site_logo_url() }}" alt="{{ site_name() }}" class="img-fluid" style="max-height: 48px; width: auto;">
            @else
              <span class="fw-bold fs-5 site-brand-text">{{ site_name() }}</span>
            @endif
          </a>
        </div>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
          aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
          <i class="fa-solid fa-bars fa-lg"></i>
        </button>
      </div>

      <div class="col-sm-6 offset-sm-2 offset-md-0 col-lg-4">
        <div class="search-bar row bg-light p-2 rounded-4">
          <div class="col-md-5 d-none d-md-block">
            <select class="form-select border-0 bg-transparent" onchange="if (this.value) window.location.href = this.value;">
              <option value="">All Categories</option>
              @foreach($navCategories ?? collect() as $navCategory)
                <option value="{{ route('products.category', $navCategory->slug) }}">{{ $navCategory->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-11 col-md-6">
            <form id="search-form" class="text-center" action="{{ route('products.index') }}" method="GET">
              <input type="text" name="search" class="form-control border-0 bg-transparent" placeholder="Search products" value="{{ request('search') }}">
            </form>
          </div>
          <div class="col-1">
            <button type="submit" form="search-form" class="btn p-0 border-0 bg-transparent" aria-label="Search">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M21.71 20.29L18 16.61A9 9 0 1 0 16.61 18l3.68 3.68a1 1 0 0 0 1.42 0a1 1 0 0 0 0-1.39ZM11 18a7 7 0 1 1 7-7a7 7 0 0 1-7 7Z"/></svg>
            </button>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <ul class="navbar-nav list-unstyled d-flex flex-row gap-3 gap-lg-4 justify-content-center flex-wrap align-items-center mb-0 fw-bold text-uppercase">
          <li class="nav-item">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
          </li>
          <li class="nav-item">
            <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">Shop</a>
          </li>
          <li class="nav-item">
            <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">About</a>
          </li>
          <li class="nav-item">
            <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
          </li>
        </ul>
      </div>

      <div class="col-sm-8 col-lg-2 d-flex gap-3 align-items-center justify-content-center justify-content-sm-end">
        <ul class="d-flex justify-content-end list-unstyled m-0 align-items-center">
          <li>
            @auth
              <a href="{{ route('dashboard') }}" class="p-2 mx-1" title="My account">
                <i class="fa-regular fa-user fa-lg"></i>
              </a>
            @else
              <a href="{{ route('login') }}" class="p-2 mx-1" title="Login">
                <i class="fa-regular fa-user fa-lg"></i>
              </a>
            @endauth
          </li>
          <li>
            <a href="#offcanvasCart" class="p-2 mx-1 position-relative" id="cartIcon" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart" aria-controls="offcanvasCart">
              <i class="fa-solid fa-bag-shopping fa-lg"></i>
              @php $cartCount = session('cart') ? count(session('cart')) : 0; @endphp
              <span id="cart-count"
                    class="badge bg-danger position-absolute top-0 start-100 translate-middle"
                    style="{{ $cartCount > 0 ? '' : 'display:none;' }}">
                {{ $cartCount }}
              </span>
            </a>
          </li>
          @auth
            <li>
              <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="p-2 mx-1 border-0 bg-transparent" title="Logout" aria-label="Logout">
                  <i class="fa-solid fa-right-from-bracket fa-lg"></i>
                </button>
              </form>
            </li>
          @endauth
        </ul>
      </div>
    </div>
  </div>
</header>
