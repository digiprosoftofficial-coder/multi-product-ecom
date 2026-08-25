@if(($products ?? collect())->isNotEmpty())
<section id="{{ $sectionId }}" class="products-carousel">
  <div class="container-lg py-4 py-md-5">
    <div class="section-header d-flex flex-wrap align-items-center justify-content-between mb-3 gap-2">
      <h2 class="section-title mb-0">{{ $title }}</h2>
      <div class="d-flex align-items-center gap-2 flex-shrink-0">
        <a href="{{ route('products.index') }}" class="btn btn-primary">View All</a>
        <div class="swiper-buttons">
          <button class="swiper-prev products-carousel-prev btn btn-primary" type="button" aria-label="Previous">❮</button>
          <button class="swiper-next products-carousel-next btn btn-primary" type="button" aria-label="Next">❯</button>
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
