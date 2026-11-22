document.addEventListener('DOMContentLoaded', function () {
  if (typeof Swiper === 'undefined') return;

  document.querySelectorAll('.products-carousel').forEach(function (root) {
    if (!root.id) return;
    var el = root.querySelector('.swiper');
    if (!el) return;

    new Swiper(el, {
      slidesPerView: 2,
      slidesPerGroup: 1,
      spaceBetween: 12,
      speed: 500,
      watchOverflow: true,
      resistanceRatio: 0.65,
      grabCursor: true,
      navigation: {
        nextEl: root.querySelector('.products-carousel-next'),
        prevEl: root.querySelector('.products-carousel-prev'),
      },
      breakpoints: {
        0: { slidesPerView: 2, slidesPerGroup: 1, spaceBetween: 12 },
        576: { slidesPerView: 2, slidesPerGroup: 1, spaceBetween: 14 },
        768: { slidesPerView: 2, slidesPerGroup: 1, spaceBetween: 18 },
        992: { slidesPerView: 3, slidesPerGroup: 1, spaceBetween: 22 },
        1200: { slidesPerView: 4, slidesPerGroup: 1, spaceBetween: 24 },
        1500: { slidesPerView: 5, slidesPerGroup: 1, spaceBetween: 28 },
      },
    });
  });
});
