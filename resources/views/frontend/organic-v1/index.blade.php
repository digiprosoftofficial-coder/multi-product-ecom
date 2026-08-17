@extends('layouts.app')

@section('title', site_name())

@section('content')
@php
    $heroTitle = \App\Support\Homepage::get('home_hero_title');
    $highlight = \App\Support\Homepage::get('home_hero_highlight');
    $heroHtml = e($heroTitle);
    if ($highlight !== '' && str_contains($heroTitle, $highlight)) {
        $heroHtml = str_replace(e($highlight), '<span class="fw-bold text-primary">'.e($highlight).'</span>', $heroHtml);
    }
@endphp

<section style="background-image: url('{{ \App\Support\Homepage::heroImageUrl() }}');background-repeat: no-repeat;background-size: cover;">
  <div class="container-lg">
    <div class="row">
      <div class="col-lg-6 pt-5 mt-5">
        <h2 class="display-1 ls-1">{!! $heroHtml !!}</h2>
        @if(\App\Support\Homepage::get('home_hero_subtitle'))
          <p class="fs-4">{{ \App\Support\Homepage::get('home_hero_subtitle') }}</p>
        @endif
        <div class="d-flex gap-3">
          @if(\App\Support\Homepage::get('home_hero_btn1_text'))
            <a href="{{ \App\Support\Homepage::get('home_hero_btn1_url') ?: route('products.index') }}" class="btn btn-primary text-uppercase fs-6 rounded-pill px-4 py-3 mt-3">{{ \App\Support\Homepage::get('home_hero_btn1_text') }}</a>
          @endif
          @if(\App\Support\Homepage::get('home_hero_btn2_text'))
            <a href="{{ \App\Support\Homepage::get('home_hero_btn2_url') ?: route('register') }}" class="btn btn-dark text-uppercase fs-6 rounded-pill px-4 py-3 mt-3">{{ \App\Support\Homepage::get('home_hero_btn2_text') }}</a>
          @endif
        </div>
        <div class="row my-5">
          @foreach([1,2,3] as $i)
            <div class="col">
              <div class="row text-dark">
                <div class="col-auto"><p class="fs-1 fw-bold lh-sm mb-0">{{ \App\Support\Homepage::get('home_stat'.$i.'_value') }}</p></div>
                <div class="col"><p class="text-uppercase lh-sm mb-0">{{ \App\Support\Homepage::get('home_stat'.$i.'_label') }}</p></div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>

@if(\App\Support\Homepage::enabled('home_show_categories'))
    <section class="py-5 overflow-hidden">
      <div class="container-lg">
        <div class="section-header d-flex flex-wrap justify-content-between mb-5">
          <h2 class="section-title">{{ \App\Support\Homepage::get('home_categories_title') }}</h2>
          <a href="{{ route('products.index') }}" class="btn btn-primary me-2">View All</a>
        </div>
        <div class="category-list d-flex flex-wrap justify-content-center">
          @foreach($categories as $category)
            <a href="{{ route('products.category', $category->slug) }}" class="category-list-item text-decoration-none text-dark">
              <img src="{{ $category->image_url ?? asset('organic-v1/images/category-thumb-1.jpg') }}" class="rounded-circle" alt="{{ $category->name }}">
              <h4 class="fs-6 mt-3 mb-0 fw-normal category-title">{{ $category->name }}</h4>
            </a>
          @endforeach
        </div>
      </div>
    </section>
@endif

@if(\App\Support\Homepage::enabled('home_show_best_selling') && $bestSellingProducts->isNotEmpty())
    <section class="pb-5" id="best-selling-products">
      <div class="container-lg">
        <div class="section-header d-flex flex-wrap justify-content-between my-4">
          <h2 class="section-title">{{ \App\Support\Homepage::get('home_best_selling_title') }}</h2>
          <a href="{{ route('products.index') }}" class="btn btn-primary rounded-1">View All</a>
        </div>
        <div class="product-grid row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 row-cols-xl-4 row-cols-xxl-5 g-4">
          @foreach($bestSellingProducts as $product)
            <div class="col">
              @include('frontend.components.product-card', ['product' => $product])
            </div>
          @endforeach
        </div>
      </div>
    </section>
@endif

@if(\App\Support\Homepage::enabled('home_show_banners'))
    <section class="py-3">
      <div class="container-lg">
        <div class="row g-3 banner-blocks">
          <div class="col-lg-8">
            <div class="banner-ad d-flex align-items-center rounded-4 shadow-sm w-100 h-100"
                 style="background: url('{{ asset('organic-v1/images/banner-ad-1.jpg') }}') center/cover no-repeat;">
              <div class="banner-content p-4 p-md-5 w-100">
                <div class="content-wrapper text-light">
                  <h3 class="banner-title text-light mb-2">{{ \App\Support\Homepage::get('home_banner1_title') }}</h3>
                  <p class="mb-3">{{ \App\Support\Homepage::get('home_banner1_text') }}</p>
                  <a href="{{ \App\Support\Homepage::get('home_banner1_url') ?: route('products.index') }}" class="btn btn-light btn-sm rounded-5 px-3">Shop Now</a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4 d-flex flex-column gap-3">
            <div class="banner-ad rounded-4 shadow-sm" style="background:url('{{ asset('organic-v1/images/banner-ad-2.jpg') }}') center/cover no-repeat;">
              <div class="banner-content p-4">
                <div class="content-wrapper text-light">
                  <h3 class="banner-title text-light mb-1 fs-5">{{ \App\Support\Homepage::get('home_banner2_title') }}</h3>
                  <p class="mb-2 small">{{ \App\Support\Homepage::get('home_banner2_text') }}</p>
                  <a href="{{ \App\Support\Homepage::get('home_banner2_url') ?: route('products.index') }}" class="btn btn-outline-light btn-sm rounded-5 px-3">Shop Now</a>
                </div>
              </div>
            </div>
            <div class="banner-ad rounded-4 shadow-sm" style="background:url('{{ asset('organic-v1/images/banner-ad-3.jpg') }}') center/cover no-repeat;">
              <div class="banner-content p-4">
                <div class="content-wrapper text-light">
                  <h3 class="banner-title text-light mb-1 fs-5">{{ \App\Support\Homepage::get('home_banner3_title') }}</h3>
                  <p class="mb-2 small">{{ \App\Support\Homepage::get('home_banner3_text') }}</p>
                  <a href="{{ \App\Support\Homepage::get('home_banner3_url') ?: route('products.index') }}" class="btn btn-outline-light btn-sm rounded-5 px-3">Shop Now</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
@endif

@if(\App\Support\Homepage::enabled('home_show_featured'))
    @include('frontend.organic-v1.partials.product-carousel', [
        'sectionId' => 'featured-products',
        'title' => \App\Support\Homepage::get('home_featured_title'),
        'products' => $featuredProducts,
    ])
@endif

@if(\App\Support\Homepage::enabled('home_show_newsletter'))
    <section>
      <div class="container-lg">
        <div class="bg-secondary text-light py-5 my-5" style="background: url('{{ asset('organic-v1/images/banner-newsletter.jpg') }}') no-repeat; background-size: cover;">
          <div class="container">
            <div class="row justify-content-center">
              <div class="col-md-5 p-3">
                <h2 class="section-title display-5 text-light">{{ \App\Support\Homepage::get('home_newsletter_title') }}</h2>
                <p>{{ \App\Support\Homepage::get('home_newsletter_text') }}</p>
              </div>
              <div class="col-md-5 p-3">
                <form action="{{ route('register') }}" method="GET">
                  <div class="d-grid gap-2">
                    <a href="{{ route('register') }}" class="btn btn-dark btn-md rounded-0">Register now</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
@endif

@if(\App\Support\Homepage::enabled('home_show_popular'))
    @include('frontend.organic-v1.partials.product-carousel', [
        'sectionId' => 'popular-products',
        'title' => \App\Support\Homepage::get('home_popular_title'),
        'products' => $popularProducts,
    ])
@endif

@if(\App\Support\Homepage::enabled('home_show_new'))
    @include('frontend.organic-v1.partials.product-carousel', [
        'sectionId' => 'latest-products',
        'title' => \App\Support\Homepage::get('home_new_title'),
        'products' => $newProducts,
    ])
@endif

@if(\App\Support\Homepage::enabled('home_show_features'))
    <section class="py-5 feature-highlights">
      <div class="container-lg">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-5 g-4">
          @foreach([1,2,3,4,5] as $i)
            <div class="col">
              <div class="feature-card h-100">
                <div class="feature-icon">
                  <i class="fa-solid {{ \App\Support\Homepage::get('home_feature_'.$i.'_icon') }}"></i>
                </div>
                <h5>{{ \App\Support\Homepage::get('home_feature_'.$i.'_title') }}</h5>
                <p>{{ \App\Support\Homepage::get('home_feature_'.$i.'_text') }}</p>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </section>
@endif
@endsection
