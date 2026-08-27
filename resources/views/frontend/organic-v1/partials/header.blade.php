<header class="site-header">
  <div class="container-fluid">
    <div class="row py-2 py-lg-3 border-bottom align-items-center g-2 flex-nowrap header-main-row">
      {{-- Left: menu (mobile) + logo --}}
      <div class="col-auto col-lg-3 d-flex align-items-center gap-2 header-left">
        <button class="navbar-toggler border-0 d-lg-none p-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
          aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
          <i class="fa-solid fa-bars fa-lg"></i>
        </button>
        <a href="{{ route('home') }}" class="d-none d-lg-flex align-items-center gap-2 text-decoration-none site-logo-link">
          @if(site_logo_url())
            <img src="{{ site_logo_url() }}" alt="{{ site_name() }}" class="img-fluid site-logo-img">
          @else
            <span class="fw-bold fs-6 site-brand-text">{{ site_name() }}</span>
          @endif
        </a>
      </div>

      {{-- Mobile centered logo --}}
      <div class="header-logo-center d-lg-none text-center">
        <a href="{{ route('home') }}" class="d-inline-flex align-items-center justify-content-center text-decoration-none site-logo-link">
          @if(site_logo_url())
            <img src="{{ site_logo_url() }}" alt="{{ site_name() }}" class="img-fluid site-logo-img">
          @else
            <span class="fw-bold fs-6 site-brand-text">{{ site_name() }}</span>
          @endif
        </a>
      </div>

      {{-- Desktop centered menu --}}
      <div class="col-lg-6 d-none d-lg-flex justify-content-center header-nav-col">
        <ul class="navbar-nav list-unstyled d-flex flex-row gap-3 gap-xl-4 justify-content-center flex-wrap align-items-center mb-0 fw-bold text-uppercase">
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

      <div class="col-auto col-lg-3 ms-auto d-flex gap-1 gap-sm-2 align-items-center justify-content-end header-right">
        <ul class="d-flex justify-content-end list-unstyled m-0 align-items-center header-icon-list">
          <li class="header-search-item d-flex align-items-center">
            {{-- Desktop: inline expand beside icon --}}
            <form action="{{ route('products.index') }}" method="GET"
                  class="header-inline-search d-none d-lg-flex align-items-center {{ request('search') ? 'is-open' : '' }}"
                  id="desktop-search-form"
                  role="search">
              <input type="text"
                     name="search"
                     class="form-control header-inline-search-input"
                     placeholder="Search products"
                     value="{{ request('search') }}"
                     id="desktop-search-input"
                     autocomplete="off">
              <button type="submit" class="btn header-inline-search-submit p-0 border-0 bg-transparent" aria-label="Search">
                <i class="fa-solid fa-arrow-right"></i>
              </button>
            </form>
            <button type="button"
                    class="p-2 mx-1 border-0 bg-transparent header-search-toggle"
                    id="headerSearchToggle"
                    title="Search"
                    aria-label="Search"
                    aria-expanded="{{ request('search') ? 'true' : 'false' }}"
                    aria-controls="desktop-search-form">
              <i class="fa-solid fa-magnifying-glass fa-lg"></i>
            </button>
          </li>
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
          <li class="d-none d-lg-inline-flex">
            <a href="#offcanvasCart" class="p-2 mx-1 position-relative" id="cartIcon" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart" aria-controls="offcanvasCart">
              <i class="fa-solid fa-bag-shopping fa-lg"></i>
              @php $cartCount = session('cart') ? count(session('cart')) : 0; @endphp
              <span id="cart-count"
                    class="badge bg-danger position-absolute top-0 start-100 translate-middle js-cart-count"
                    style="{{ $cartCount > 0 ? '' : 'display:none;' }}">
                {{ $cartCount }}
              </span>
            </a>
          </li>
          @auth
            <li class="d-none d-lg-inline-flex">
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

    {{-- Mobile expandable search only --}}
    <div class="collapse d-lg-none" id="headerSearchPanel">
      <div class="header-search-panel py-2">
        <form action="{{ route('products.index') }}" method="GET" class="search-bar d-flex align-items-center bg-light p-2 rounded-4 gap-2" id="mobile-search-form">
          <input type="text" name="search" class="form-control border-0 bg-transparent" placeholder="Search products" value="{{ request('search') }}" id="mobile-search-input" autocomplete="off">
          <button type="submit" class="btn p-1 border-0 bg-transparent" aria-label="Search">
            <i class="fa-solid fa-magnifying-glass"></i>
          </button>
        </form>
      </div>
    </div>
  </div>
</header>
