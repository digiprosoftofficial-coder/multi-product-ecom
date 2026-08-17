@if(($products ?? collect())->isNotEmpty())
<section id="{{ $sectionId }}" class="products-carousel">
  <div class="container-lg overflow-hidden py-5">
    <div class="section-header d-flex flex-wrap justify-content-between my-4">
      <h2 class="section-title">{{ $title }}</h2>
      <div class="d-flex align-items-center">
        <a href="{{ route('products.index') }}" class="btn btn-primary me-2">View All</a>
        <div class="swiper-buttons">
          <button class="swiper-prev products-carousel-prev btn btn-primary">❮</button>
          <button class="swiper-next products-carousel-next btn btn-primary">❯</button>
        </div>
      </div>
    </div>
    <div class="swiper">
      <div class="swiper-wrapper">
        @foreach($products as $product)
          <div class="swiper-slide">
            @include('frontend.components.product-card', ['product' => $product])
          </div>
        @endforeach
      </div>
    </div>
  </div>
</section>
@endif
