<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Organic - Grocery Store HTML Website Template</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="">
    <meta name="keywords" content="">
    <meta name="description" content="">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="{{ asset('organic-v1/css/vendor.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('organic-v1/style.css') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&family=Open+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

  </head>
  <body>

    

    <div class="preloader-wrapper">
      <div class="preloader">
      </div>
    </div>

    <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasCart">
      <div class="offcanvas-header justify-content-center">
        <h4 class="fw-normal text-uppercase fs-6">Your Cart</h4>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <div class="order-md-last" id="cart-sidebar-content">
          @include('frontend.organic-v1.partials.cart-sidebar-content', ['cartItems' => $cartItems, 'cartTotal' => $cartTotal])
        </div>
      </div>
    </div>
    
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar">

      <div class="offcanvas-header justify-content-between">
        <h4 class="fw-normal text-uppercase fs-6">Menu</h4>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>

      <div class="offcanvas-body">
    
        <ul class="navbar-nav justify-content-end menu-list list-unstyled d-flex flex-column gap-2 mb-0">
          @isset($categories)
              @foreach($categories as $category)
                  @if($category->subCategories->count())
                      <li class="nav-item border-dashed">
                        <button class="btn btn-toggle dropdown-toggle position-relative w-100 d-flex justify-content-between align-items-center text-dark p-2" data-bs-toggle="collapse" data-bs-target="#cat-{{ $category->id }}-collapse" aria-expanded="true">
                          <div class="d-flex gap-3 align-items-center">
                            @if($category->image_url)
                                <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover;">
                            @else
                                <span class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                    <svg width="18" height="18" viewBox="0 0 24 24"><use xlink:href="#category"></use></svg>
                                </span>
                            @endif
                            <span>{{ $category->name }}</span>
                          </div>
                        </button>
                        <div class="collapse show" id="cat-{{ $category->id }}-collapse">
                          <ul class="btn-toggle-nav list-unstyled fw-normal ps-4 pb-2">
                            @foreach($category->subCategories as $subCategory)
                                <li class="border-bottom py-1">
                                  <a href="{{ route('products.subcategory', $subCategory->slug) }}" class="dropdown-item d-flex align-items-center gap-2">
                                    @if($subCategory->image_url ?? false)
                                        <img src="{{ $subCategory->image_url }}" alt="{{ $subCategory->name }}" class="rounded-circle" style="width: 26px; height: 26px; object-fit: cover;">
                                    @else
                                        <span class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center" style="width: 26px; height: 26px;">
                                            <svg width="14" height="14" viewBox="0 0 24 24"><use xlink:href="#category"></use></svg>
                                        </span>
                                    @endif
                                    <span>{{ $subCategory->name }}</span>
                                  </a>
                                </li>
                            @endforeach
                          </ul>
                        </div>
                      </li>
                  @else
                      <li class="nav-item border-dashed">
                        <a href="{{ route('products.category', $category->slug) }}" class="nav-link d-flex align-items-center gap-3 text-dark p-2">
                          @if($category->image_url)
                              <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover;">
                          @else
                              <span class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                  <svg width="18" height="18" viewBox="0 0 24 24"><use xlink:href="#category"></use></svg>
                              </span>
                          @endif
                          <span>{{ $category->name }}</span>
                        </a>
                      </li>
                  @endif
              @endforeach
          @endisset
        </ul>
      
      </div>

    </div>

    <header>
      <div class="container-fluid">
        <div class="row py-3 border-bottom">
          
          <div class="col-sm-4 col-lg-2 text-center text-sm-start d-flex gap-3 justify-content-center justify-content-md-start">
            <div class="d-flex align-items-center my-3 my-sm-0">
              <a href="{{ route('home') }}">
                <img src="{{ asset('organic-v1/images/logo.svg') }}" alt="logo" class="img-fluid">
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
                <select class="form-select border-0 bg-transparent">
                  <option value="">
                    All Categories
                  </option>
                  @isset($categories)
                      @foreach($categories as $category)
                              <option value="{{ route('products.category', $category->slug) }}">
                                  {{ $category->name }}
                              </option>
                      @endforeach
                  @endisset
                </select>
              </div>
              <div class="col-11 col-md-6">
                <form id="search-form" class="text-center" action="index.html" method="post">
                  <input type="text" class="form-control border-0 bg-transparent" placeholder="Search for more than 20,000 products">
                </form>
              </div>
              <div class="col-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M21.71 20.29L18 16.61A9 9 0 1 0 16.61 18l3.68 3.68a1 1 0 0 0 1.42 0a1 1 0 0 0 0-1.39ZM11 18a7 7 0 1 1 7-7a7 7 0 0 1-7 7Z"/></svg>
              </div>
            </div>
          </div>

          <div class="col-lg-4">
            <ul class="navbar-nav list-unstyled d-flex flex-row gap-3 gap-lg-5 justify-content-center flex-wrap align-items-center mb-0 fw-bold text-uppercase text-dark">
              <li class="nav-item active">
                <a href="index.html" class="nav-link">Home</a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle pe-3" role="button" id="pages" data-bs-toggle="dropdown" aria-expanded="false">Pages</a>
                <ul class="dropdown-menu border-0 p-3 rounded-0 shadow" aria-labelledby="pages">
                  <li><a href="index.html" class="dropdown-item">About Us </a></li>
                  <li><a href="index.html" class="dropdown-item">Shop </a></li>
                  <li><a href="index.html" class="dropdown-item">Single Product </a></li>
                  <li><a href="index.html" class="dropdown-item">Cart </a></li>
                  <li><a href="index.html" class="dropdown-item">Checkout </a></li>
                  <li><a href="index.html" class="dropdown-item">Blog </a></li>
                  <li><a href="index.html" class="dropdown-item">Single Post </a></li>
                  <li><a href="index.html" class="dropdown-item">Styles </a></li>
                  <li><a href="index.html" class="dropdown-item">Contact </a></li>
                  <li><a href="index.html" class="dropdown-item">Thank You </a></li>
                  <li><a href="index.html" class="dropdown-item">My Account </a></li>
                  <li><a href="index.html" class="dropdown-item">404 Error </a></li>
                </ul>
              </li>
            </ul>
          </div>
          
          <div class="col-sm-8 col-lg-2 d-flex gap-5 align-items-center justify-content-center justify-content-sm-end">
            <ul class="d-flex justify-content-end list-unstyled m-0 align-items-center">
              <li>
                <a href="#" class="p-2 mx-1">
                  <i class="fa-regular fa-user fa-lg"></i>
                </a>
              </li>
              <li>
                <a href="#" class="p-2 mx-1">
                  <i class="fa-regular fa-heart fa-lg"></i>
                </a>
              </li>
              <li>
                <a href="#" class="p-2 mx-1 position-relative" id="cartIcon" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart" aria-controls="offcanvasCart">
                  <i class="fa-solid fa-bag-shopping fa-lg"></i>
                  @php $cartCount = session('cart') ? count(session('cart')) : 0; @endphp
                  <span id="cart-count"
                        class="badge bg-danger position-absolute top-0 start-100 translate-middle"
                        style="{{ $cartCount > 0 ? '' : 'display:none;' }}">
                    {{ $cartCount }}
                  </span>
                </a>
              </li>
            </ul>
          </div>

        </div>
      </div>
    </header>
    
    <section style="background-image: url('{{ asset('organic-v1/images/banner-1.jpg') }}');background-repeat: no-repeat;background-size: cover;">
      <div class="container-lg">
        <div class="row">
          <div class="col-lg-6 pt-5 mt-5">
            <h2 class="display-1 ls-1"><span class="fw-bold text-primary">Organic</span> Foods at your <span class="fw-bold">Doorsteps</span></h2>
            <p class="fs-4">Dignissim massa diam elementum.</p>
            <div class="d-flex gap-3">
              <a href="#" class="btn btn-primary text-uppercase fs-6 rounded-pill px-4 py-3 mt-3">Start Shopping</a>
              <a href="#" class="btn btn-dark text-uppercase fs-6 rounded-pill px-4 py-3 mt-3">Join Now</a>
            </div>
            <div class="row my-5">
              <div class="col">
                <div class="row text-dark">
                  <div class="col-auto"><p class="fs-1 fw-bold lh-sm mb-0">14k+</p></div>
                  <div class="col"><p class="text-uppercase lh-sm mb-0">Product Varieties</p></div>
                </div>
              </div>
              <div class="col">
                <div class="row text-dark">
                  <div class="col-auto"><p class="fs-1 fw-bold lh-sm mb-0">50k+</p></div>
                  <div class="col"><p class="text-uppercase lh-sm mb-0">Happy Customers</p></div>
                </div>
              </div>
              <div class="col">
                <div class="row text-dark">
                  <div class="col-auto"><p class="fs-1 fw-bold lh-sm mb-0">10+</p></div>
                  <div class="col"><p class="text-uppercase lh-sm mb-0">Store Locations</p></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="row row-cols-1 row-cols-sm-3 row-cols-lg-3 g-0 justify-content-center">
          <div class="col">
            <div class="card border-0 bg-primary rounded-0 p-4 text-light">
              <div class="row">
                <div class="col-md-3 text-center">
                  <svg width="60" height="60"><use xlink:href="#fresh"></use></svg>
                </div>
                <div class="col-md-9">
                  <div class="card-body p-0">
                    <h5 class="text-light">Fresh from farm</h5>
                    <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipi elit.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card border-0 bg-secondary rounded-0 p-4 text-light">
              <div class="row">
                <div class="col-md-3 text-center">
                  <svg width="60" height="60"><use xlink:href="#organic"></use></svg>
                </div>
                <div class="col-md-9">
                  <div class="card-body p-0">
                    <h5 class="text-light">100% Organic</h5>
                    <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipi elit.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card border-0 bg-danger rounded-0 p-4 text-light">
              <div class="row">
                <div class="col-md-3 text-center">
                  <svg width="60" height="60"><use xlink:href="#delivery"></use></svg>
                </div>
                <div class="col-md-9">
                  <div class="card-body p-0">
                    <h5 class="text-light">Free delivery</h5>
                    <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipi elit.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      
      </div>
    </section>

    <section class="py-5 overflow-hidden">
      <div class="container-lg">
        <div class="row">
          <div class="col-md-12">

            <div class="section-header d-flex flex-wrap justify-content-between mb-5">
              <h2 class="section-title">Category</h2>

              <div class="d-flex align-items-center">
                <a href="#" class="btn btn-primary me-2">View All</a>
                <div class="swiper-buttons">
                  <button class="swiper-prev category-carousel-prev btn btn-yellow">❮</button>
                  <button class="swiper-next category-carousel-next btn btn-yellow">❯</button>
                </div>
              </div>
            </div>
            
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">

            <div class="category-carousel swiper">
              <div class="swiper-wrapper">
                @isset($categories)
                    @foreach($categories as $category)
                        <a href="{{ route('products.category', $category->slug) }}" class="nav-link swiper-slide text-center">
                          <img
                              src="{{ $category->image_url ?? asset('organic-v1/images/category-thumb-1.jpg') }}"
                              class="rounded-circle"
                              alt="{{ $category->name }}"
                              style="width: 120px; height: 120px; object-fit: cover;"
                          >
                          <h4 class="fs-6 mt-3 fw-normal category-title">{{ $category->name }}</h4>
                        </a>
                    @endforeach
                @endisset
              </div>
            </div>

          </div>
        </div>
      </div>
    </section>

    <section class="pb-5" id="best-selling-products">
      <div class="container-lg">

        <div class="row">
          <div class="col-md-12">

            <div class="section-header d-flex flex-wrap justify-content-between my-4">
              
              <h2 class="section-title">Best selling products</h2>

              <div class="d-flex align-items-center">
                <a href="#" class="btn btn-primary rounded-1">View All</a>
              </div>
            </div>
            
          </div>
        </div>
        
        <div class="row">
          <div class="col-md-12">

          <div class="product-grid row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 row-cols-xl-4 row-cols-xxl-5">
              @isset($bestSellingProducts)
                  @foreach($bestSellingProducts as $product)
                      <div class="col">
                          <div class="product-item">
                              <figure>
                                  <a href="{{ route('products.show', $product->slug) }}" title="{{ $product->name }}">
                                      <img
                                          src="{{ $product->thumbnail_url ?? asset('organic-v1/images/product-thumb-1.png') }}"
                                          alt="{{ $product->name }}"
                                          class="tab-image"
                                      >
                                  </a>
                              </figure>
                              <div class="d-flex flex-column text-center">
                                  <h3 class="fs-6 fw-normal">{{ $product->name }}</h3>

                                  <div>
                                      
                                      <span>({{ $product->orderItems->count() }})</span>
                                  </div>

                                  @php
                                      $compare = $product->compare_price;
                                      $final = $product->final_price;
                                      $hasDiscount = $compare && $compare > $final;
                                      $discountPercent = $hasDiscount ? round((($compare - $final) / $compare) * 100) : null;
                                  @endphp
                                  <div class="d-flex justify-content-center align-items-center gap-2">
                                      @if($hasDiscount)
                                          <del>${{ number_format($compare, 2) }}</del>
                                      @endif
                                      <span class="text-dark fw-semibold">${{ number_format($final, 2) }}</span>
                                      @if($hasDiscount && $discountPercent > 0)
                                          <span class="badge border border-dark-subtle rounded-0 fw-normal px-1 fs-7 lh-1 text-body-tertiary">
                                              {{ $discountPercent }}% OFF
                                          </span>
                                      @endif
                                  </div>

                                  <div class="button-area p-3 pt-0">
                                      <form action="{{ route('cart.add', $product) }}" method="POST"
                                            class="w-100 js-add-to-cart"
                                            data-product-id="{{ $product->id }}"
                                            data-product-name="{{ $product->name }}"
                                            data-product-image="{{ $product->thumbnail_url }}">
                                          @csrf
                                          <div class="row g-1 mt-2">
                                              <div class="col-3">
                                                  <input type="number" name="quantity"
                                                         class="form-control border-dark-subtle input-number quantity"
                                                         value="1" min="1">
                                              </div>
                                              <div class="col-7">
                                                  <button type="submit" class="btn btn-primary rounded-1 p-2 fs-7 btn-cart w-100">
                                                      <i class="fa-solid fa-cart-shopping me-1"></i>
                                                      Add to Cart
                                                  </button>
                                              </div>
                                              <div class="col-2">
                                                  <button type="button" class="btn btn-outline-dark rounded-1 p-2 fs-6">
                                                      <i class="fa-regular fa-heart"></i>
                                                  </button>
                                              </div>
                                          </div>
                                      </form>
                                  </div>
                              </div>
                          </div>
                      </div>
                  @endforeach
              @endisset
          </div>


          </div>
        </div>
      </div>
    </section>

    <section class="py-3">
      <div class="container-lg">
        <div class="row g-3 banner-blocks">

          <!-- Large left banner -->
          <div class="col-lg-8">
            <div class="banner-ad d-flex align-items-center rounded-4 shadow-sm w-100 h-100"
                 style="background: url('{{ asset('organic-v1/images/banner-ad-1.jpg') }}') center/cover no-repeat;">
              <div class="banner-content p-4 p-md-5 w-100">
                <div class="content-wrapper text-light">
                  <h3 class="banner-title text-light mb-2">Items on SALE</h3>
                  <p class="mb-3">Discounts up to 30%</p>
                  <a href="#" class="btn btn-light btn-sm rounded-5 px-3">Shop Now</a>
                </div>
              </div>
            </div>
          </div>

          <!-- Right stacked banners -->
          <div class="col-lg-4 d-flex flex-column gap-3">
            <div class="banner-ad rounded-4 shadow-sm"
                 style="background:url('{{ asset('organic-v1/images/banner-ad-2.jpg') }}') center/cover no-repeat;">
              <div class="banner-content p-4">
                <div class="content-wrapper text-light">
                  <h3 class="banner-title text-light mb-1 fs-5">Combo offers</h3>
                  <p class="mb-2 small">Discounts up to 50%</p>
                  <a href="#" class="btn btn-outline-light btn-sm rounded-5 px-3">Shop Now</a>
                </div>
              </div>
            </div>

            <div class="banner-ad rounded-4 shadow-sm"
                 style="background:url('{{ asset('organic-v1/images/banner-ad-3.jpg') }}') center/cover no-repeat;">
              <div class="banner-content p-4">
                <div class="content-wrapper text-light">
                  <h3 class="banner-title text-light mb-1 fs-5">Discount Coupons</h3>
                  <p class="mb-2 small">Discounts up to 40%</p>
                  <a href="#" class="btn btn-outline-light btn-sm rounded-5 px-3">Shop Now</a>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>

    <section id="featured-products" class="products-carousel">
      <div class="container-lg overflow-hidden py-5">
        <div class="row">
          <div class="col-md-12">

            <div class="section-header d-flex flex-wrap justify-content-between my-4">
              
              <h2 class="section-title">Featured products</h2>

              <div class="d-flex align-items-center">
                <a href="#" class="btn btn-primary me-2">View All</a>
                <div class="swiper-buttons">
                  <button class="swiper-prev products-carousel-prev btn btn-primary">❮</button>
                  <button class="swiper-next products-carousel-next btn btn-primary">❯</button>
                </div>  
              </div>
            </div>
            
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">

            <div class="swiper">
              <div class="swiper-wrapper">
                                
                <div class="product-item swiper-slide">
                  <figure>
                    <a href="index.html" title="Product Title">
                      <img src="{{ asset('organic-v1/images/product-thumb-10.png') }}" alt="Product Thumbnail" class="tab-image">
                    </a>
                  </figure>
                  <div class="d-flex flex-column text-center">
                    <h3 class="fs-6 fw-normal">Greek Style Plain Yogurt</h3>
                    <div>
                      <span class="rating">
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-half"></use></svg>
                      </span>
                      <span>(222)</span>
                    </div>
                    <div class="d-flex justify-content-center align-items-center gap-2">
                      <del>$24.00</del>
                      <span class="text-dark fw-semibold">$18.00</span>
                      <span class="badge border border-dark-subtle rounded-0 fw-normal px-1 fs-7 lh-1 text-body-tertiary">10% OFF</span>
                    </div>
                    <div class="button-area p-3 pt-0">
                      <div class="row g-1 mt-2">
                        <div class="col-3"><input type="number" name="quantity" class="form-control border-dark-subtle input-number quantity" value="1"></div>
                        <div class="col-7"><a href="#" class="btn btn-primary rounded-1 p-2 fs-7 btn-cart"><svg width="18" height="18"><use xlink:href="#cart"></use></svg> Add to Cart</a></div>
                        <div class="col-2"><a href="#" class="btn btn-outline-dark rounded-1 p-2 fs-6"><svg width="18" height="18"><use xlink:href="#heart"></use></svg></a></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="product-item swiper-slide">
                  <figure>
                    <a href="index.html" title="Product Title">
                      <img src="{{ asset('organic-v1/images/product-thumb-11.png') }}" alt="Product Thumbnail" class="tab-image">
                    </a>
                  </figure>
                  <div class="d-flex flex-column text-center">
                    <h3 class="fs-6 fw-normal">Pure Squeezed No Pulp Orange Juice</h3>
                    <div>
                      <span class="rating">
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-half"></use></svg>
                      </span>
                      <span>(222)</span>
                    </div>
                    <div class="d-flex justify-content-center align-items-center gap-2">
                      <del>$24.00</del>
                      <span class="text-dark fw-semibold">$18.00</span>
                      <span class="badge border border-dark-subtle rounded-0 fw-normal px-1 fs-7 lh-1 text-body-tertiary">10% OFF</span>
                    </div>
                    <div class="button-area p-3 pt-0">
                      <div class="row g-1 mt-2">
                        <div class="col-3"><input type="number" name="quantity" class="form-control border-dark-subtle input-number quantity" value="1"></div>
                        <div class="col-7"><a href="#" class="btn btn-primary rounded-1 p-2 fs-7 btn-cart"><svg width="18" height="18"><use xlink:href="#cart"></use></svg> Add to Cart</a></div>
                        <div class="col-2"><a href="#" class="btn btn-outline-dark rounded-1 p-2 fs-6"><svg width="18" height="18"><use xlink:href="#heart"></use></svg></a></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="product-item swiper-slide">
                  <figure>
                    <a href="index.html" title="Product Title">
                      <img src="{{ asset('organic-v1/images/product-thumb-12.png') }}" alt="Product Thumbnail" class="tab-image">
                    </a>
                  </figure>
                  <div class="d-flex flex-column text-center">
                    <h3 class="fs-6 fw-normal">Fresh Oranges</h3>
                    <div>
                      <span class="rating">
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-half"></use></svg>
                      </span>
                      <span>(222)</span>
                    </div>
                    <div class="d-flex justify-content-center align-items-center gap-2">
                      <del>$24.00</del>
                      <span class="text-dark fw-semibold">$18.00</span>
                      <span class="badge border border-dark-subtle rounded-0 fw-normal px-1 fs-7 lh-1 text-body-tertiary">10% OFF</span>
                    </div>
                    <div class="button-area p-3 pt-0">
                      <div class="row g-1 mt-2">
                        <div class="col-3"><input type="number" name="quantity" class="form-control border-dark-subtle input-number quantity" value="1"></div>
                        <div class="col-7"><a href="#" class="btn btn-primary rounded-1 p-2 fs-7 btn-cart"><svg width="18" height="18"><use xlink:href="#cart"></use></svg> Add to Cart</a></div>
                        <div class="col-2"><a href="#" class="btn btn-outline-dark rounded-1 p-2 fs-6"><svg width="18" height="18"><use xlink:href="#heart"></use></svg></a></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="product-item swiper-slide">
                  <figure>
                    <a href="index.html" title="Product Title">
                      <img src="{{ asset('organic-v1/images/product-thumb-13.png') }}" alt="Product Thumbnail" class="tab-image">
                    </a>
                  </figure>
                  <div class="d-flex flex-column text-center">
                    <h3 class="fs-6 fw-normal">Gourmet Dark Chocolate Bars</h3>
                    <div>
                      <span class="rating">
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-half"></use></svg>
                      </span>
                      <span>(222)</span>
                    </div>
                    <div class="d-flex justify-content-center align-items-center gap-2">
                      <del>$24.00</del>
                      <span class="text-dark fw-semibold">$18.00</span>
                      <span class="badge border border-dark-subtle rounded-0 fw-normal px-1 fs-7 lh-1 text-body-tertiary">10% OFF</span>
                    </div>
                    <div class="button-area p-3 pt-0">
                      <div class="row g-1 mt-2">
                        <div class="col-3"><input type="number" name="quantity" class="form-control border-dark-subtle input-number quantity" value="1"></div>
                        <div class="col-7"><a href="#" class="btn btn-primary rounded-1 p-2 fs-7 btn-cart"><svg width="18" height="18"><use xlink:href="#cart"></use></svg> Add to Cart</a></div>
                        <div class="col-2"><a href="#" class="btn btn-outline-dark rounded-1 p-2 fs-6"><svg width="18" height="18"><use xlink:href="#heart"></use></svg></a></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="product-item swiper-slide">
                  <figure>
                    <a href="index.html" title="Product Title">
                      <img src="{{ asset('organic-v1/images/product-thumb-14.png') }}" alt="Product Thumbnail" class="tab-image">
                    </a>
                  </figure>
                  <div class="d-flex flex-column text-center">
                    <h3 class="fs-6 fw-normal">Fresh Green Celery</h3>
                    <div>
                      <span class="rating">
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-half"></use></svg>
                      </span>
                      <span>(222)</span>
                    </div>
                    <div class="d-flex justify-content-center align-items-center gap-2">
                      <del>$24.00</del>
                      <span class="text-dark fw-semibold">$18.00</span>
                      <span class="badge border border-dark-subtle rounded-0 fw-normal px-1 fs-7 lh-1 text-body-tertiary">10% OFF</span>
                    </div>
                    <div class="button-area p-3 pt-0">
                      <div class="row g-1 mt-2">
                        <div class="col-3"><input type="number" name="quantity" class="form-control border-dark-subtle input-number quantity" value="1"></div>
                        <div class="col-7"><a href="#" class="btn btn-primary rounded-1 p-2 fs-7 btn-cart"><svg width="18" height="18"><use xlink:href="#cart"></use></svg> Add to Cart</a></div>
                        <div class="col-2"><a href="#" class="btn btn-outline-dark rounded-1 p-2 fs-6"><svg width="18" height="18"><use xlink:href="#heart"></use></svg></a></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="product-item swiper-slide">
                  <figure>
                    <a href="index.html" title="Product Title">
                      <img src="{{ asset('organic-v1/images/product-thumb-15.png') }}" alt="Product Thumbnail" class="tab-image">
                    </a>
                  </figure>
                  <div class="d-flex flex-column text-center">
                    <h3 class="fs-6 fw-normal">Sandwich Bread</h3>
                    <div>
                      <span class="rating">
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-half"></use></svg>
                      </span>
                      <span>(222)</span>
                    </div>
                    <div class="d-flex justify-content-center align-items-center gap-2">
                      <del>$24.00</del>
                      <span class="text-dark fw-semibold">$18.00</span>
                      <span class="badge border border-dark-subtle rounded-0 fw-normal px-1 fs-7 lh-1 text-body-tertiary">10% OFF</span>
                    </div>
                    <div class="button-area p-3 pt-0">
                      <div class="row g-1 mt-2">
                        <div class="col-3"><input type="number" name="quantity" class="form-control border-dark-subtle input-number quantity" value="1"></div>
                        <div class="col-7"><a href="#" class="btn btn-primary rounded-1 p-2 fs-7 btn-cart"><svg width="18" height="18"><use xlink:href="#cart"></use></svg> Add to Cart</a></div>
                        <div class="col-2"><a href="#" class="btn btn-outline-dark rounded-1 p-2 fs-6"><svg width="18" height="18"><use xlink:href="#heart"></use></svg></a></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="product-item swiper-slide">
                  <figure>
                    <a href="index.html" title="Product Title">
                      <img src="{{ asset('organic-v1/images/product-thumb-16.png') }}" alt="Product Thumbnail" class="tab-image">
                    </a>
                  </figure>
                  <div class="d-flex flex-column text-center">
                    <h3 class="fs-6 fw-normal">Honeycrisp Apples</h3>
                    <div>
                      <span class="rating">
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-half"></use></svg>
                      </span>
                      <span>(222)</span>
                    </div>
                    <div class="d-flex justify-content-center align-items-center gap-2">
                      <del>$24.00</del>
                      <span class="text-dark fw-semibold">$18.00</span>
                      <span class="badge border border-dark-subtle rounded-0 fw-normal px-1 fs-7 lh-1 text-body-tertiary">10% OFF</span>
                    </div>
                    <div class="button-area p-3 pt-0">
                      <div class="row g-1 mt-2">
                        <div class="col-3"><input type="number" name="quantity" class="form-control border-dark-subtle input-number quantity" value="1"></div>
                        <div class="col-7"><a href="#" class="btn btn-primary rounded-1 p-2 fs-7 btn-cart"><svg width="18" height="18"><use xlink:href="#cart"></use></svg> Add to Cart</a></div>
                        <div class="col-2"><a href="#" class="btn btn-outline-dark rounded-1 p-2 fs-6"><svg width="18" height="18"><use xlink:href="#heart"></use></svg></a></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="product-item swiper-slide">
                  <figure>
                    <a href="index.html" title="Product Title">
                      <img src="{{ asset('organic-v1/images/product-thumb-17.png') }}" alt="Product Thumbnail" class="tab-image">
                    </a>
                  </figure>
                  <div class="d-flex flex-column text-center">
                    <h3 class="fs-6 fw-normal">Whole Wheat Sandwich Bread</h3>
                    <div>
                      <span class="rating">
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-half"></use></svg>
                      </span>
                      <span>(222)</span>
                    </div>
                    <div class="d-flex justify-content-center align-items-center gap-2">
                      <del>$24.00</del>
                      <span class="text-dark fw-semibold">$18.00</span>
                      <span class="badge border border-dark-subtle rounded-0 fw-normal px-1 fs-7 lh-1 text-body-tertiary">10% OFF</span>
                    </div>
                    <div class="button-area p-3 pt-0">
                      <div class="row g-1 mt-2">
                        <div class="col-3"><input type="number" name="quantity" class="form-control border-dark-subtle input-number quantity" value="1"></div>
                        <div class="col-7"><a href="#" class="btn btn-primary rounded-1 p-2 fs-7 btn-cart"><svg width="18" height="18"><use xlink:href="#cart"></use></svg> Add to Cart</a></div>
                        <div class="col-2"><a href="#" class="btn btn-outline-dark rounded-1 p-2 fs-6"><svg width="18" height="18"><use xlink:href="#heart"></use></svg></a></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="product-item swiper-slide">
                  <figure>
                    <a href="index.html" title="Product Title">
                      <img src="{{ asset('organic-v1/images/product-thumb-18.png') }}" alt="Product Thumbnail" class="tab-image">
                    </a>
                  </figure>
                  <div class="d-flex flex-column text-center">
                    <h3 class="fs-6 fw-normal">Honeycrisp Apples</h3>
                    <div>
                      <span class="rating">
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-half"></use></svg>
                      </span>
                      <span>(222)</span>
                    </div>
                    <div class="d-flex justify-content-center align-items-center gap-2">
                      <del>$24.00</del>
                      <span class="text-dark fw-semibold">$18.00</span>
                      <span class="badge border border-dark-subtle rounded-0 fw-normal px-1 fs-7 lh-1 text-body-tertiary">10% OFF</span>
                    </div>
                    <div class="button-area p-3 pt-0">
                      <div class="row g-1 mt-2">
                        <div class="col-3"><input type="number" name="quantity" class="form-control border-dark-subtle input-number quantity" value="1"></div>
                        <div class="col-7"><a href="#" class="btn btn-primary rounded-1 p-2 fs-7 btn-cart"><svg width="18" height="18"><use xlink:href="#cart"></use></svg> Add to Cart</a></div>
                        <div class="col-2"><a href="#" class="btn btn-outline-dark rounded-1 p-2 fs-6"><svg width="18" height="18"><use xlink:href="#heart"></use></svg></a></div>
                      </div>
                    </div>
                  </div>
                </div>

                  
              </div>
            </div>
            <!-- / products-carousel -->

          </div>
        </div>
      </div>
    </section>

    <section>
      <div class="container-lg">

        <div class="bg-secondary text-light py-5 my-5" style="background: url('{{ asset('organic-v1/images/banner-newsletter.jpg') }}') no-repeat; background-size: cover;">
          <div class="container">
            <div class="row justify-content-center">
              <div class="col-md-5 p-3">
                <div class="section-header">
                  <h2 class="section-title display-5 text-light">Get 25% Discount on your first purchase</h2>
                </div>
                <p>Just Sign Up & Register it now to become member.</p>
              </div>
              <div class="col-md-5 p-3">
                <form>
                  <div class="mb-3">
                    <label for="name" class="form-label d-none">Name</label>
                    <input type="text"
                      class="form-control form-control-md rounded-0" name="name" id="name" placeholder="Name">
                  </div>
                  <div class="mb-3">
                    <label for="email" class="form-label d-none">Email</label>
                    <input type="email" class="form-control form-control-md rounded-0" name="email" id="email" placeholder="Email Address">
                  </div>
                  <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-dark btn-md rounded-0">Submit</button>
                  </div>
                </form>
                
              </div>
              
            </div>
            
          </div>
        </div>
        
      </div>
    </section>

    <section id="popular-products" class="products-carousel">
      <div class="container-lg overflow-hidden py-5">
        <div class="row">
          <div class="col-md-12">

            <div class="section-header d-flex justify-content-between my-4">
              
              <h2 class="section-title">Most popular products</h2>

              <div class="d-flex align-items-center">
                <a href="#" class="btn btn-primary me-2">View All</a>
                <div class="swiper-buttons">
                  <button class="swiper-prev products-carousel-prev btn btn-primary">❮</button>
                  <button class="swiper-next products-carousel-next btn btn-primary">❯</button>
                </div>
              </div>
            </div>
            
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">

            <div class="swiper">
              <div class="swiper-wrapper">
                                
                <div class="product-item swiper-slide">
                  <figure>
                    <a href="index.html" title="Product Title">
                      <img src="{{ asset('organic-v1/images/product-thumb-15.png') }}" alt="Product Thumbnail" class="tab-image">
                    </a>
                  </figure>
                  <div class="d-flex flex-column text-center">
                    <h3 class="fs-6 fw-normal">Sandwich Bread</h3>
                    <div>
                      <span class="rating">
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-half"></use></svg>
                      </span>
                      <span>(222)</span>
                    </div>
                    <div class="d-flex justify-content-center align-items-center gap-2">
                      <del>$24.00</del>
                      <span class="text-dark fw-semibold">$18.00</span>
                      <span class="badge border border-dark-subtle rounded-0 fw-normal px-1 fs-7 lh-1 text-body-tertiary">10% OFF</span>
                    </div>
                    <div class="button-area p-3 pt-0">
                      <div class="row g-1 mt-2">
                        <div class="col-3"><input type="number" name="quantity" class="form-control border-dark-subtle input-number quantity" value="1"></div>
                        <div class="col-7"><a href="#" class="btn btn-primary rounded-1 p-2 fs-7 btn-cart"><svg width="18" height="18"><use xlink:href="#cart"></use></svg> Add to Cart</a></div>
                        <div class="col-2"><a href="#" class="btn btn-outline-dark rounded-1 p-2 fs-6"><svg width="18" height="18"><use xlink:href="#heart"></use></svg></a></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="product-item swiper-slide">
                  <figure>
                    <a href="index.html" title="Product Title">
                      <img src="{{ asset('organic-v1/images/product-thumb-16.png') }}" alt="Product Thumbnail" class="tab-image">
                    </a>
                  </figure>
                  <div class="d-flex flex-column text-center">
                    <h3 class="fs-6 fw-normal">Honeycrisp Apples</h3>
                    <div>
                      <span class="rating">
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-half"></use></svg>
                      </span>
                      <span>(222)</span>
                    </div>
                    <div class="d-flex justify-content-center align-items-center gap-2">
                      <del>$24.00</del>
                      <span class="text-dark fw-semibold">$18.00</span>
                      <span class="badge border border-dark-subtle rounded-0 fw-normal px-1 fs-7 lh-1 text-body-tertiary">10% OFF</span>
                    </div>
                    <div class="button-area p-3 pt-0">
                      <div class="row g-1 mt-2">
                        <div class="col-3"><input type="number" name="quantity" class="form-control border-dark-subtle input-number quantity" value="1"></div>
                        <div class="col-7"><a href="#" class="btn btn-primary rounded-1 p-2 fs-7 btn-cart"><svg width="18" height="18"><use xlink:href="#cart"></use></svg> Add to Cart</a></div>
                        <div class="col-2"><a href="#" class="btn btn-outline-dark rounded-1 p-2 fs-6"><svg width="18" height="18"><use xlink:href="#heart"></use></svg></a></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="product-item swiper-slide">
                  <figure>
                    <a href="index.html" title="Product Title">
                      <img src="{{ asset('organic-v1/images/product-thumb-17.png') }}" alt="Product Thumbnail" class="tab-image">
                    </a>
                  </figure>
                  <div class="d-flex flex-column text-center">
                    <h3 class="fs-6 fw-normal">Whole Wheat Sandwich Bread</h3>
                    <div>
                      <span class="rating">
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-half"></use></svg>
                      </span>
                      <span>(222)</span>
                    </div>
                    <div class="d-flex justify-content-center align-items-center gap-2">
                      <del>$24.00</del>
                      <span class="text-dark fw-semibold">$18.00</span>
                      <span class="badge border border-dark-subtle rounded-0 fw-normal px-1 fs-7 lh-1 text-body-tertiary">10% OFF</span>
                    </div>
                    <div class="button-area p-3 pt-0">
                      <div class="row g-1 mt-2">
                        <div class="col-3"><input type="number" name="quantity" class="form-control border-dark-subtle input-number quantity" value="1"></div>
                        <div class="col-7"><a href="#" class="btn btn-primary rounded-1 p-2 fs-7 btn-cart"><svg width="18" height="18"><use xlink:href="#cart"></use></svg> Add to Cart</a></div>
                        <div class="col-2"><a href="#" class="btn btn-outline-dark rounded-1 p-2 fs-6"><svg width="18" height="18"><use xlink:href="#heart"></use></svg></a></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="product-item swiper-slide">
                  <figure>
                    <a href="index.html" title="Product Title">
                      <img src="{{ asset('organic-v1/images/product-thumb-18.png') }}" alt="Product Thumbnail" class="tab-image">
                    </a>
                  </figure>
                  <div class="d-flex flex-column text-center">
                    <h3 class="fs-6 fw-normal">Honeycrisp Apples</h3>
                    <div>
                      <span class="rating">
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-half"></use></svg>
                      </span>
                      <span>(222)</span>
                    </div>
                    <div class="d-flex justify-content-center align-items-center gap-2">
                      <del>$24.00</del>
                      <span class="text-dark fw-semibold">$18.00</span>
                      <span class="badge border border-dark-subtle rounded-0 fw-normal px-1 fs-7 lh-1 text-body-tertiary">10% OFF</span>
                    </div>
                    <div class="button-area p-3 pt-0">
                      <div class="row g-1 mt-2">
                        <div class="col-3"><input type="number" name="quantity" class="form-control border-dark-subtle input-number quantity" value="1"></div>
                        <div class="col-7"><a href="#" class="btn btn-primary rounded-1 p-2 fs-7 btn-cart"><svg width="18" height="18"><use xlink:href="#cart"></use></svg> Add to Cart</a></div>
                        <div class="col-2"><a href="#" class="btn btn-outline-dark rounded-1 p-2 fs-6"><svg width="18" height="18"><use xlink:href="#heart"></use></svg></a></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="product-item swiper-slide">
                  <figure>
                    <a href="index.html" title="Product Title">
                      <img src="{{ asset('organic-v1/images/product-thumb-19.png') }}" alt="Product Thumbnail" class="tab-image">
                    </a>
                  </figure>
                  <div class="d-flex flex-column text-center">
                    <h3 class="fs-6 fw-normal">Sunstar Fresh Melon Juice</h3>
                    <div>
                      <span class="rating">
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-half"></use></svg>
                      </span>
                      <span>(222)</span>
                    </div>
                    <div class="d-flex justify-content-center align-items-center gap-2">
                      <del>$24.00</del>
                      <span class="text-dark fw-semibold">$18.00</span>
                      <span class="badge border border-dark-subtle rounded-0 fw-normal px-1 fs-7 lh-1 text-body-tertiary">10% OFF</span>
                    </div>
                    <div class="button-area p-3 pt-0">
                      <div class="row g-1 mt-2">
                        <div class="col-3"><input type="number" name="quantity" class="form-control border-dark-subtle input-number quantity" value="1"></div>
                        <div class="col-7"><a href="#" class="btn btn-primary rounded-1 p-2 fs-7 btn-cart"><svg width="18" height="18"><use xlink:href="#cart"></use></svg> Add to Cart</a></div>
                        <div class="col-2"><a href="#" class="btn btn-outline-dark rounded-1 p-2 fs-6"><svg width="18" height="18"><use xlink:href="#heart"></use></svg></a></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="product-item swiper-slide">
                  <figure>
                    <a href="index.html" title="Product Title">
                      <img src="{{ asset('organic-v1/images/product-thumb-10.png') }}" alt="Product Thumbnail" class="tab-image">
                    </a>
                  </figure>
                  <div class="d-flex flex-column text-center">
                    <h3 class="fs-6 fw-normal">Greek Style Plain Yogurt</h3>
                    <div>
                      <span class="rating">
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-half"></use></svg>
                      </span>
                      <span>(222)</span>
                    </div>
                    <div class="d-flex justify-content-center align-items-center gap-2">
                      <del>$24.00</del>
                      <span class="text-dark fw-semibold">$18.00</span>
                      <span class="badge border border-dark-subtle rounded-0 fw-normal px-1 fs-7 lh-1 text-body-tertiary">10% OFF</span>
                    </div>
                    <div class="button-area p-3 pt-0">
                      <div class="row g-1 mt-2">
                        <div class="col-3"><input type="number" name="quantity" class="form-control border-dark-subtle input-number quantity" value="1"></div>
                        <div class="col-7"><a href="#" class="btn btn-primary rounded-1 p-2 fs-7 btn-cart"><svg width="18" height="18"><use xlink:href="#cart"></use></svg> Add to Cart</a></div>
                        <div class="col-2"><a href="#" class="btn btn-outline-dark rounded-1 p-2 fs-6"><svg width="18" height="18"><use xlink:href="#heart"></use></svg></a></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="product-item swiper-slide">
                  <figure>
                    <a href="index.html" title="Product Title">
                      <img src="{{ asset('organic-v1/images/product-thumb-11.png') }}" alt="Product Thumbnail" class="tab-image">
                    </a>
                  </figure>
                  <div class="d-flex flex-column text-center">
                    <h3 class="fs-6 fw-normal">Pure Squeezed No Pulp Orange Juice</h3>
                    <div>
                      <span class="rating">
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-half"></use></svg>
                      </span>
                      <span>(222)</span>
                    </div>
                    <div class="d-flex justify-content-center align-items-center gap-2">
                      <del>$24.00</del>
                      <span class="text-dark fw-semibold">$18.00</span>
                      <span class="badge border border-dark-subtle rounded-0 fw-normal px-1 fs-7 lh-1 text-body-tertiary">10% OFF</span>
                    </div>
                    <div class="button-area p-3 pt-0">
                      <div class="row g-1 mt-2">
                        <div class="col-3"><input type="number" name="quantity" class="form-control border-dark-subtle input-number quantity" value="1"></div>
                        <div class="col-7"><a href="#" class="btn btn-primary rounded-1 p-2 fs-7 btn-cart"><svg width="18" height="18"><use xlink:href="#cart"></use></svg> Add to Cart</a></div>
                        <div class="col-2"><a href="#" class="btn btn-outline-dark rounded-1 p-2 fs-6"><svg width="18" height="18"><use xlink:href="#heart"></use></svg></a></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="product-item swiper-slide">
                  <figure>
                    <a href="index.html" title="Product Title">
                      <img src="{{ asset('organic-v1/images/product-thumb-12.png') }}" alt="Product Thumbnail" class="tab-image">
                    </a>
                  </figure>
                  <div class="d-flex flex-column text-center">
                    <h3 class="fs-6 fw-normal">Fresh Oranges</h3>
                    <div>
                      <span class="rating">
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-half"></use></svg>
                      </span>
                      <span>(222)</span>
                    </div>
                    <div class="d-flex justify-content-center align-items-center gap-2">
                      <del>$24.00</del>
                      <span class="text-dark fw-semibold">$18.00</span>
                      <span class="badge border border-dark-subtle rounded-0 fw-normal px-1 fs-7 lh-1 text-body-tertiary">10% OFF</span>
                    </div>
                    <div class="button-area p-3 pt-0">
                      <div class="row g-1 mt-2">
                        <div class="col-3"><input type="number" name="quantity" class="form-control border-dark-subtle input-number quantity" value="1"></div>
                        <div class="col-7"><a href="#" class="btn btn-primary rounded-1 p-2 fs-7 btn-cart"><svg width="18" height="18"><use xlink:href="#cart"></use></svg> Add to Cart</a></div>
                        <div class="col-2"><a href="#" class="btn btn-outline-dark rounded-1 p-2 fs-6"><svg width="18" height="18"><use xlink:href="#heart"></use></svg></a></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="product-item swiper-slide">
                  <figure>
                    <a href="index.html" title="Product Title">
                      <img src="{{ asset('organic-v1/images/product-thumb-13.png') }}" alt="Product Thumbnail" class="tab-image">
                    </a>
                  </figure>
                  <div class="d-flex flex-column text-center">
                    <h3 class="fs-6 fw-normal">Gourmet Dark Chocolate Bars</h3>
                    <div>
                      <span class="rating">
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-half"></use></svg>
                      </span>
                      <span>(222)</span>
                    </div>
                    <div class="d-flex justify-content-center align-items-center gap-2">
                      <del>$24.00</del>
                      <span class="text-dark fw-semibold">$18.00</span>
                      <span class="badge border border-dark-subtle rounded-0 fw-normal px-1 fs-7 lh-1 text-body-tertiary">10% OFF</span>
                    </div>
                    <div class="button-area p-3 pt-0">
                      <div class="row g-1 mt-2">
                        <div class="col-3"><input type="number" name="quantity" class="form-control border-dark-subtle input-number quantity" value="1"></div>
                        <div class="col-7"><a href="#" class="btn btn-primary rounded-1 p-2 fs-7 btn-cart"><svg width="18" height="18"><use xlink:href="#cart"></use></svg> Add to Cart</a></div>
                        <div class="col-2"><a href="#" class="btn btn-outline-dark rounded-1 p-2 fs-6"><svg width="18" height="18"><use xlink:href="#heart"></use></svg></a></div>
                      </div>
                    </div>
                  </div>
                </div>

                  
              </div>
            </div>
            <!-- / products-carousel -->

          </div>
        </div>
      </div>
    </section>

    <section id="latest-products" class="products-carousel">
      <div class="container-lg overflow-hidden pb-5">
        <div class="row">
          <div class="col-md-12">

            <div class="section-header d-flex justify-content-between my-4">
              
              <h2 class="section-title">Just arrived</h2>

              <div class="d-flex align-items-center">
                <a href="#" class="btn btn-primary me-2">View All</a>
                <div class="swiper-buttons">
                  <button class="swiper-prev products-carousel-prev btn btn-primary">❮</button>
                  <button class="swiper-next products-carousel-next btn btn-primary">❯</button>
                </div>  
              </div>
            </div>
            
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">

            <div class="swiper">
              <div class="swiper-wrapper">
                
                <div class="product-item swiper-slide">
                  <figure>
                    <a href="index.html" title="Product Title">
                      <img src="{{ asset('organic-v1/images/product-thumb-20.png') }}" alt="Product Thumbnail" class="tab-image">
                    </a>
                  </figure>
                  <div class="d-flex flex-column text-center">
                    <h3 class="fs-6 fw-normal">Sunstar Fresh Melon Juice</h3>
                    <div>
                      <span class="rating">
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-half"></use></svg>
                      </span>
                      <span>(222)</span>
                    </div>
                    <div class="d-flex justify-content-center align-items-center gap-2">
                      <del>$24.00</del>
                      <span class="text-dark fw-semibold">$18.00</span>
                      <span class="badge border border-dark-subtle rounded-0 fw-normal px-1 fs-7 lh-1 text-body-tertiary">10% OFF</span>
                    </div>
                    <div class="button-area p-3 pt-0">
                      <div class="row g-1 mt-2">
                        <div class="col-3"><input type="number" name="quantity" class="form-control border-dark-subtle input-number quantity" value="1"></div>
                        <div class="col-7"><a href="#" class="btn btn-primary rounded-1 p-2 fs-7 btn-cart"><svg width="18" height="18"><use xlink:href="#cart"></use></svg> Add to Cart</a></div>
                        <div class="col-2"><a href="#" class="btn btn-outline-dark rounded-1 p-2 fs-6"><svg width="18" height="18"><use xlink:href="#heart"></use></svg></a></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="product-item swiper-slide">
                  <figure>
                    <a href="index.html" title="Product Title">
                      <img src="{{ asset('organic-v1/images/product-thumb-1.png') }}" alt="Product Thumbnail" class="tab-image">
                    </a>
                  </figure>
                  <div class="d-flex flex-column text-center">
                    <h3 class="fs-6 fw-normal">Whole Wheat Sandwich Bread</h3>
                    <div>
                      <span class="rating">
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-half"></use></svg>
                      </span>
                      <span>(222)</span>
                    </div>
                    <div class="d-flex justify-content-center align-items-center gap-2">
                      <del>$24.00</del>
                      <span class="text-dark fw-semibold">$18.00</span>
                      <span class="badge border border-dark-subtle rounded-0 fw-normal px-1 fs-7 lh-1 text-body-tertiary">10% OFF</span>
                    </div>
                    <div class="button-area p-3 pt-0">
                      <div class="row g-1 mt-2">
                        <div class="col-3"><input type="number" name="quantity" class="form-control border-dark-subtle input-number quantity" value="1"></div>
                        <div class="col-7"><a href="#" class="btn btn-primary rounded-1 p-2 fs-7 btn-cart"><svg width="18" height="18"><use xlink:href="#cart"></use></svg> Add to Cart</a></div>
                        <div class="col-2"><a href="#" class="btn btn-outline-dark rounded-1 p-2 fs-6"><svg width="18" height="18"><use xlink:href="#heart"></use></svg></a></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="product-item swiper-slide">
                  <figure>
                    <a href="index.html" title="Product Title">
                      <img src="{{ asset('organic-v1/images/product-thumb-21.png') }}" alt="Product Thumbnail" class="tab-image">
                    </a>
                  </figure>
                  <div class="d-flex flex-column text-center">
                    <h3 class="fs-6 fw-normal">Sunstar Fresh Melon Juice</h3>
                    <div>
                      <span class="rating">
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-half"></use></svg>
                      </span>
                      <span>(222)</span>
                    </div>
                    <div class="d-flex justify-content-center align-items-center gap-2">
                      <del>$24.00</del>
                      <span class="text-dark fw-semibold">$18.00</span>
                      <span class="badge border border-dark-subtle rounded-0 fw-normal px-1 fs-7 lh-1 text-body-tertiary">10% OFF</span>
                    </div>
                    <div class="button-area p-3 pt-0">
                      <div class="row g-1 mt-2">
                        <div class="col-3"><input type="number" name="quantity" class="form-control border-dark-subtle input-number quantity" value="1"></div>
                        <div class="col-7"><a href="#" class="btn btn-primary rounded-1 p-2 fs-7 btn-cart"><svg width="18" height="18"><use xlink:href="#cart"></use></svg> Add to Cart</a></div>
                        <div class="col-2"><a href="#" class="btn btn-outline-dark rounded-1 p-2 fs-6"><svg width="18" height="18"><use xlink:href="#heart"></use></svg></a></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="product-item swiper-slide">
                  <figure>
                    <a href="index.html" title="Product Title">
                      <img src="{{ asset('organic-v1/images/product-thumb-22.png') }}" alt="Product Thumbnail" class="tab-image">
                    </a>
                  </figure>
                  <div class="d-flex flex-column text-center">
                    <h3 class="fs-6 fw-normal">Gourmet Dark Chocolate</h3>
                    <div>
                      <span class="rating">
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-half"></use></svg>
                      </span>
                      <span>(222)</span>
                    </div>
                    <div class="d-flex justify-content-center align-items-center gap-2">
                      <del>$24.00</del>
                      <span class="text-dark fw-semibold">$18.00</span>
                      <span class="badge border border-dark-subtle rounded-0 fw-normal px-1 fs-7 lh-1 text-body-tertiary">10% OFF</span>
                    </div>
                    <div class="button-area p-3 pt-0">
                      <div class="row g-1 mt-2">
                        <div class="col-3"><input type="number" name="quantity" class="form-control border-dark-subtle input-number quantity" value="1"></div>
                        <div class="col-7"><a href="#" class="btn btn-primary rounded-1 p-2 fs-7 btn-cart"><svg width="18" height="18"><use xlink:href="#cart"></use></svg> Add to Cart</a></div>
                        <div class="col-2"><a href="#" class="btn btn-outline-dark rounded-1 p-2 fs-6"><svg width="18" height="18"><use xlink:href="#heart"></use></svg></a></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="product-item swiper-slide">
                  <figure>
                    <a href="index.html" title="Product Title">
                      <img src="{{ asset('organic-v1/images/product-thumb-23.png') }}" alt="Product Thumbnail" class="tab-image">
                    </a>
                  </figure>
                  <div class="d-flex flex-column text-center">
                    <h3 class="fs-6 fw-normal">Sunstar Fresh Melon Juice</h3>
                    <div>
                      <span class="rating">
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-half"></use></svg>
                      </span>
                      <span>(222)</span>
                    </div>
                    <div class="d-flex justify-content-center align-items-center gap-2">
                      <del>$24.00</del>
                      <span class="text-dark fw-semibold">$18.00</span>
                      <span class="badge border border-dark-subtle rounded-0 fw-normal px-1 fs-7 lh-1 text-body-tertiary">10% OFF</span>
                    </div>
                    <div class="button-area p-3 pt-0">
                      <div class="row g-1 mt-2">
                        <div class="col-3"><input type="number" name="quantity" class="form-control border-dark-subtle input-number quantity" value="1"></div>
                        <div class="col-7"><a href="#" class="btn btn-primary rounded-1 p-2 fs-7 btn-cart"><svg width="18" height="18"><use xlink:href="#cart"></use></svg> Add to Cart</a></div>
                        <div class="col-2"><a href="#" class="btn btn-outline-dark rounded-1 p-2 fs-6"><svg width="18" height="18"><use xlink:href="#heart"></use></svg></a></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="product-item swiper-slide">
                  <figure>
                    <a href="index.html" title="Product Title">
                      <img src="{{ asset('organic-v1/images/product-thumb-10.png') }}" alt="Product Thumbnail" class="tab-image">
                    </a>
                  </figure>
                  <div class="d-flex flex-column text-center">
                    <h3 class="fs-6 fw-normal">Greek Style Plain Yogurt</h3>
                    <div>
                      <span class="rating">
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-half"></use></svg>
                      </span>
                      <span>(222)</span>
                    </div>
                    <div class="d-flex justify-content-center align-items-center gap-2">
                      <del>$24.00</del>
                      <span class="text-dark fw-semibold">$18.00</span>
                      <span class="badge border border-dark-subtle rounded-0 fw-normal px-1 fs-7 lh-1 text-body-tertiary">10% OFF</span>
                    </div>
                    <div class="button-area p-3 pt-0">
                      <div class="row g-1 mt-2">
                        <div class="col-3"><input type="number" name="quantity" class="form-control border-dark-subtle input-number quantity" value="1"></div>
                        <div class="col-7"><a href="#" class="btn btn-primary rounded-1 p-2 fs-7 btn-cart"><svg width="18" height="18"><use xlink:href="#cart"></use></svg> Add to Cart</a></div>
                        <div class="col-2"><a href="#" class="btn btn-outline-dark rounded-1 p-2 fs-6"><svg width="18" height="18"><use xlink:href="#heart"></use></svg></a></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="product-item swiper-slide">
                  <figure>
                    <a href="index.html" title="Product Title">
                      <img src="{{ asset('organic-v1/images/product-thumb-11.png') }}" alt="Product Thumbnail" class="tab-image">
                    </a>
                  </figure>
                  <div class="d-flex flex-column text-center">
                    <h3 class="fs-6 fw-normal">Pure Squeezed No Pulp Orange Juice</h3>
                    <div>
                      <span class="rating">
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-half"></use></svg>
                      </span>
                      <span>(222)</span>
                    </div>
                    <div class="d-flex justify-content-center align-items-center gap-2">
                      <del>$24.00</del>
                      <span class="text-dark fw-semibold">$18.00</span>
                      <span class="badge border border-dark-subtle rounded-0 fw-normal px-1 fs-7 lh-1 text-body-tertiary">10% OFF</span>
                    </div>
                    <div class="button-area p-3 pt-0">
                      <div class="row g-1 mt-2">
                        <div class="col-3"><input type="number" name="quantity" class="form-control border-dark-subtle input-number quantity" value="1"></div>
                        <div class="col-7"><a href="#" class="btn btn-primary rounded-1 p-2 fs-7 btn-cart"><svg width="18" height="18"><use xlink:href="#cart"></use></svg> Add to Cart</a></div>
                        <div class="col-2"><a href="#" class="btn btn-outline-dark rounded-1 p-2 fs-6"><svg width="18" height="18"><use xlink:href="#heart"></use></svg></a></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="product-item swiper-slide">
                  <figure>
                    <a href="index.html" title="Product Title">
                      <img src="{{ asset('organic-v1/images/product-thumb-12.png') }}" alt="Product Thumbnail" class="tab-image">
                    </a>
                  </figure>
                  <div class="d-flex flex-column text-center">
                    <h3 class="fs-6 fw-normal">Fresh Oranges</h3>
                    <div>
                      <span class="rating">
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-half"></use></svg>
                      </span>
                      <span>(222)</span>
                    </div>
                    <div class="d-flex justify-content-center align-items-center gap-2">
                      <del>$24.00</del>
                      <span class="text-dark fw-semibold">$18.00</span>
                      <span class="badge border border-dark-subtle rounded-0 fw-normal px-1 fs-7 lh-1 text-body-tertiary">10% OFF</span>
                    </div>
                    <div class="button-area p-3 pt-0">
                      <div class="row g-1 mt-2">
                        <div class="col-3"><input type="number" name="quantity" class="form-control border-dark-subtle input-number quantity" value="1"></div>
                        <div class="col-7"><a href="#" class="btn btn-primary rounded-1 p-2 fs-7 btn-cart"><svg width="18" height="18"><use xlink:href="#cart"></use></svg> Add to Cart</a></div>
                        <div class="col-2"><a href="#" class="btn btn-outline-dark rounded-1 p-2 fs-6"><svg width="18" height="18"><use xlink:href="#heart"></use></svg></a></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="product-item swiper-slide">
                  <figure>
                    <a href="index.html" title="Product Title">
                      <img src="{{ asset('organic-v1/images/product-thumb-13.png') }}" alt="Product Thumbnail" class="tab-image">
                    </a>
                  </figure>
                  <div class="d-flex flex-column text-center">
                    <h3 class="fs-6 fw-normal">Gourmet Dark Chocolate Bars</h3>
                    <div>
                      <span class="rating">
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                        <svg width="18" height="18" class="text-warning"><use xlink:href="#star-half"></use></svg>
                      </span>
                      <span>(222)</span>
                    </div>
                    <div class="d-flex justify-content-center align-items-center gap-2">
                      <del>$24.00</del>
                      <span class="text-dark fw-semibold">$18.00</span>
                      <span class="badge border border-dark-subtle rounded-0 fw-normal px-1 fs-7 lh-1 text-body-tertiary">10% OFF</span>
                    </div>
                    <div class="button-area p-3 pt-0">
                      <div class="row g-1 mt-2">
                        <div class="col-3"><input type="number" name="quantity" class="form-control border-dark-subtle input-number quantity" value="1"></div>
                        <div class="col-7"><a href="#" class="btn btn-primary rounded-1 p-2 fs-7 btn-cart"><svg width="18" height="18"><use xlink:href="#cart"></use></svg> Add to Cart</a></div>
                        <div class="col-2"><a href="#" class="btn btn-outline-dark rounded-1 p-2 fs-6"><svg width="18" height="18"><use xlink:href="#heart"></use></svg></a></div>
                      </div>
                    </div>
                  </div>
                </div>
                  
              </div>
            </div>
            <!-- / products-carousel -->

          </div>
        </div>
      </div>
    </section>

    <section id="latest-blog" class="pb-4">
      <div class="container-lg">
        <div class="row">
          <div class="section-header d-flex align-items-center justify-content-between my-4">
            <h2 class="section-title">Our Recent Blog</h2>
            <a href="#" class="btn btn-primary">View All</a>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <article class="post-item card border-0 shadow-sm p-3">
              <div class="image-holder zoom-effect">
                <a href="#">
                  <img src="{{ asset('organic-v1/images/post-thumbnail-1.jpg') }}" alt="post" class="card-img-top">
                </a>
              </div>
              <div class="card-body">
                <div class="post-meta d-flex text-uppercase gap-3 my-2 align-items-center">
                  <div class="meta-date"><svg width="16" height="16"><use xlink:href="#calendar"></use></svg>22 Aug 2021</div>
                  <div class="meta-categories"><svg width="16" height="16"><use xlink:href="#category"></use></svg>tips & tricks</div>
                </div>
                <div class="post-header">
                  <h3 class="post-title">
                    <a href="#" class="text-decoration-none">Top 10 casual look ideas to dress up your kids</a>
                  </h3>
                  <p>Lorem ipsum dolor sit amet, consectetur adipi elit. Aliquet eleifend viverra enim tincidunt donec quam. A in arcu, hendrerit neque dolor morbi...</p>
                </div>
              </div>
            </article>
          </div>
          <div class="col-md-4">
            <article class="post-item card border-0 shadow-sm p-3">
              <div class="image-holder zoom-effect">
                <a href="#">
                  <img src="{{ asset('organic-v1/images/post-thumbnail-2.jpg') }}" alt="post" class="card-img-top">
                </a>
              </div>
              <div class="card-body">
                <div class="post-meta d-flex text-uppercase gap-3 my-2 align-items-center">
                  <div class="meta-date"><svg width="16" height="16"><use xlink:href="#calendar"></use></svg>25 Aug 2021</div>
                  <div class="meta-categories"><svg width="16" height="16"><use xlink:href="#category"></use></svg>trending</div>
                </div>
                <div class="post-header">
                  <h3 class="post-title">
                    <a href="#" class="text-decoration-none">Latest trends of wearing street wears supremely</a>
                  </h3>
                  <p>Lorem ipsum dolor sit amet, consectetur adipi elit. Aliquet eleifend viverra enim tincidunt donec quam. A in arcu, hendrerit neque dolor morbi...</p>
                </div>
              </div>
            </article>
          </div>
          <div class="col-md-4">
            <article class="post-item card border-0 shadow-sm p-3">
              <div class="image-holder zoom-effect">
                <a href="#">
                  <img src="{{ asset('organic-v1/images/post-thumbnail-3.jpg') }}" alt="post" class="card-img-top">
                </a>
              </div>
              <div class="card-body">
                <div class="post-meta d-flex text-uppercase gap-3 my-2 align-items-center">
                  <div class="meta-date"><svg width="16" height="16"><use xlink:href="#calendar"></use></svg>28 Aug 2021</div>
                  <div class="meta-categories"><svg width="16" height="16"><use xlink:href="#category"></use></svg>inspiration</div>
                </div>
                <div class="post-header">
                  <h3 class="post-title">
                    <a href="#" class="text-decoration-none">10 Different Types of comfortable clothes ideas for women</a>
                  </h3>
                  <p>Lorem ipsum dolor sit amet, consectetur adipi elit. Aliquet eleifend viverra enim tincidunt donec quam. A in arcu, hendrerit neque dolor morbi...</p>
                </div>
              </div>
            </article>
          </div>
        </div>
      </div>
    </section>

    <section class="pb-4 my-4">
      <div class="container-lg">

        <div class="bg-warning pt-5 rounded-5">
          <div class="container">
            <div class="row justify-content-center align-items-center">
              <div class="col-md-4">
                <h2 class="mt-5">Download Organic App</h2>
                <p>Online Orders made easy, fast and reliable</p>
                <div class="d-flex gap-2 flex-wrap mb-5">
                  <a href="#" title="App store"><img src="{{ asset('organic-v1/images/img-app-store.png') }}" alt="app-store"></a>
                  <a href="#" title="Google Play"><img src="{{ asset('organic-v1/images/img-google-play.png') }}" alt="google-play"></a>
                </div>
              </div>
              <div class="col-md-5">
                <img src="{{ asset('organic-v1/images/banner-onlineapp.png') }}" alt="phone" class="img-fluid">
              </div>
            </div>
          </div>
        </div>
        
      </div>
    </section>

    <section class="py-4">
      <div class="container-lg">
        <h2 class="my-4">People are also looking for</h2>
        <a href="#" class="btn btn-warning me-2 mb-2">Blue diamon almonds</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Angie’s Boomchickapop Corn</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Salty kettle Corn</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Chobani Greek Yogurt</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Sweet Vanilla Yogurt</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Foster Farms Takeout Crispy wings</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Warrior Blend Organic</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Chao Cheese Creamy</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Chicken meatballs</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Blue diamon almonds</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Angie’s Boomchickapop Corn</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Salty kettle Corn</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Chobani Greek Yogurt</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Sweet Vanilla Yogurt</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Foster Farms Takeout Crispy wings</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Warrior Blend Organic</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Chao Cheese Creamy</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Chicken meatballs</a>
      </div>
    </section>

    <section class="py-5">
      <div class="container-lg">
        <div class="row row-cols-1 row-cols-sm-3 row-cols-lg-5">
          <div class="col">
            <div class="card mb-3 border border-dark-subtle p-3">
              <div class="text-dark mb-3">
                <svg width="32" height="32"><use xlink:href="#package"></use></svg>
              </div>
              <div class="card-body p-0">
                <h5>Free delivery</h5>
                <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipi elit.</p>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card mb-3 border border-dark-subtle p-3">
              <div class="text-dark mb-3">
                <svg width="32" height="32"><use xlink:href="#secure"></use></svg>
              </div>
              <div class="card-body p-0">
                <h5>100% secure payment</h5>
                <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipi elit.</p>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card mb-3 border border-dark-subtle p-3">
              <div class="text-dark mb-3">
                <svg width="32" height="32"><use xlink:href="#quality"></use></svg>
              </div>
              <div class="card-body p-0">
                <h5>Quality guarantee</h5>
                <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipi elit.</p>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card mb-3 border border-dark-subtle p-3">
              <div class="text-dark mb-3">
                <svg width="32" height="32"><use xlink:href="#savings"></use></svg>
              </div>
              <div class="card-body p-0">
                <h5>guaranteed savings</h5>
                <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipi elit.</p>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card mb-3 border border-dark-subtle p-3">
              <div class="text-dark mb-3">
                <svg width="32" height="32"><use xlink:href="#offers"></use></svg>
              </div>
              <div class="card-body p-0">
                <h5>Daily offers</h5>
                <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipi elit.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <footer class="py-5">
      <div class="container-lg">
        <div class="row">

          <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="footer-menu">
              <img src="{{ asset('organic-v1/images/logo.svg') }}" width="240" height="70" alt="logo">
              <div class="social-links mt-3">
                <ul class="d-flex list-unstyled gap-2">
                  <li>
                    <a href="#" class="btn btn-outline-light">
                      <svg width="16" height="16"><use xlink:href="#facebook"></use></svg>
                    </a>
                  </li>
                  <li>
                    <a href="#" class="btn btn-outline-light">
                      <svg width="16" height="16"><use xlink:href="#twitter"></use></svg>
                    </a>
                  </li>
                  <li>
                    <a href="#" class="btn btn-outline-light">
                      <svg width="16" height="16"><use xlink:href="#youtube"></use></svg>
                    </a>
                  </li>
                  <li>
                    <a href="#" class="btn btn-outline-light">
                      <svg width="16" height="16"><use xlink:href="#instagram"></use></svg>
                    </a>
                  </li>
                  <li>
                    <a href="#" class="btn btn-outline-light">
                      <svg width="16" height="16"><use xlink:href="#amazon"></use></svg>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <div class="col-md-2 col-sm-6">
            <div class="footer-menu">
              <h5 class="widget-title">Organic</h5>
              <ul class="menu-list list-unstyled">
                <li class="menu-item">
                  <a href="#" class="nav-link">About us</a>
                </li>
                <li class="menu-item">
                  <a href="#" class="nav-link">Conditions </a>
                </li>
                <li class="menu-item">
                  <a href="#" class="nav-link">Our Journals</a>
                </li>
                <li class="menu-item">
                  <a href="#" class="nav-link">Careers</a>
                </li>
                <li class="menu-item">
                  <a href="#" class="nav-link">Affiliate Programme</a>
                </li>
                <li class="menu-item">
                  <a href="#" class="nav-link">Ultras Press</a>
                </li>
              </ul>
            </div>
          </div>
          <div class="col-md-2 col-sm-6">
            <div class="footer-menu">
              <h5 class="widget-title">Quick Links</h5>
              <ul class="menu-list list-unstyled">
                <li class="menu-item">
                  <a href="#" class="nav-link">Offers</a>
                </li>
                <li class="menu-item">
                  <a href="#" class="nav-link">Discount Coupons</a>
                </li>
                <li class="menu-item">
                  <a href="#" class="nav-link">Stores</a>
                </li>
                <li class="menu-item">
                  <a href="#" class="nav-link">Track Order</a>
                </li>
                <li class="menu-item">
                  <a href="#" class="nav-link">Shop</a>
                </li>
                <li class="menu-item">
                  <a href="#" class="nav-link">Info</a>
                </li>
              </ul>
            </div>
          </div>
          <div class="col-md-2 col-sm-6">
            <div class="footer-menu">
              <h5 class="widget-title">Customer Service</h5>
              <ul class="menu-list list-unstyled">
                <li class="menu-item">
                  <a href="#" class="nav-link">FAQ</a>
                </li>
                <li class="menu-item">
                  <a href="#" class="nav-link">Contact</a>
                </li>
                <li class="menu-item">
                  <a href="#" class="nav-link">Privacy Policy</a>
                </li>
                <li class="menu-item">
                  <a href="#" class="nav-link">Returns & Refunds</a>
                </li>
                <li class="menu-item">
                  <a href="#" class="nav-link">Cookie Guidelines</a>
                </li>
                <li class="menu-item">
                  <a href="#" class="nav-link">Delivery Information</a>
                </li>
              </ul>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="footer-menu">
              <h5 class="widget-title">Subscribe Us</h5>
              <p>Subscribe to our newsletter to get updates about our grand offers.</p>
              <form class="d-flex mt-3 gap-0" action="index.html">
                <input class="form-control rounded-start rounded-0 bg-light" type="email" placeholder="Email Address" aria-label="Email Address">
                <button class="btn btn-dark rounded-end rounded-0" type="submit">Subscribe</button>
              </form>
            </div>
          </div>
          
        </div>
      </div>
    </footer>
    <div id="footer-bottom">
      <div class="container-lg">
        <div class="row">
          <div class="col-md-6 copyright">
            <p>© 2024 Organic. All rights reserved.</p>
          </div>
          <div class="col-md-6 credit-link text-start text-md-end">
            <p>HTML Template by <a href="https://templatesjungle.com/">TemplatesJungle</a> Distributed By <a href="https://themewagon.com">ThemeWagon</a> </p>
          </div>
        </div>
      </div>
    </div>
    <script src="{{ asset('organic-v1/js/jquery-1.11.0.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="{{ asset('organic-v1/js/plugins.js') }}"></script>
    <script src="{{ asset('organic-v1/js/script.js') }}"></script>

    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        const cartCountEl = document.getElementById('cart-count');
        const cartIconEl = document.getElementById('cartIcon');

        function updateCartCount(count) {
          if (!cartCountEl) return;
          const value = Number(count) || 0;
          cartCountEl.textContent = value;
          cartCountEl.style.display = value > 0 ? 'inline-block' : 'none';
        }

        // init from existing value
        if (cartCountEl) {
          updateCartCount(cartCountEl.textContent);
        }

        document.querySelectorAll('form.js-add-to-cart').forEach(function (form) {
          form.addEventListener('submit', function (e) {
            e.preventDefault();

            const submitBtn = form.querySelector('button[type="submit"]') || form;
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
              .then(async function (res) {
                if (!res.ok) {
                  const data = await res.json().catch(function () { return {}; });
                  const msg = data.message || 'Unable to add to cart.';
                  throw new Error(msg);
                }
                return res.json();
              })
              .then(function (data) {
                if (typeof data.cartCount !== 'undefined') {
                  updateCartCount(data.cartCount);
                  // Refresh sidebar cart content
                  refreshCartSidebar();
                }
              })
              .catch(function () {
                form.submit(); // graceful fallback
              });
          });
        });

        // Remove from cart handler
        // Event delegation: remove-from-cart works for static and dynamically injected forms
        const offcanvasCart = document.getElementById('offcanvasCart');
        if (offcanvasCart) {
          offcanvasCart.addEventListener('submit', function (e) {
            if (!e.target.matches('form.js-remove-from-cart')) return;
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
              .then(async function (res) {
                if (!res.ok) {
                  throw new Error('Failed to remove item');
                }
                return res.json();
              })
              .then(function (data) {
                if (typeof data.cartCount !== 'undefined') {
                  updateCartCount(data.cartCount);
                }
                refreshCartSidebar();
              })
              .catch(function () {
                form.submit(); // graceful fallback
              });
          });
        }

        // Refresh sidebar cart HTML without page reload
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
            .catch(function () {
              // Silent fail
            });
        }
      });
    </script>
  </body>
</html>