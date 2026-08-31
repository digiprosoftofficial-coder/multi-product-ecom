(function($) {

  "use strict";

  var initPreloader = function() {
    $(document).ready(function($) {
    var Body = $('body');
        Body.addClass('preloader-site');
    });
    $(window).on('load', function() {
        $('.preloader-wrapper').fadeOut();
        $('body').removeClass('preloader-site');
    });
    setTimeout(function() {
        $('.preloader-wrapper').fadeOut();
        $('body').removeClass('preloader-site');
    }, 1500);
  }

  // init Chocolat light box
	var initChocolat = function() {
		Chocolat(document.querySelectorAll('.image-link'), {
		  imageSize: 'contain',
		  loop: true,
		})
	}

  var initSwiper = function() {

    if (document.querySelector(".main-swiper")) {
      new Swiper(".main-swiper", {
        speed: 500,
        pagination: {
          el: ".swiper-pagination",
          clickable: true,
        },
      });
    }

    var categoryCarousel = document.querySelector(".category-carousel");
    if (categoryCarousel) {
      new Swiper(".category-carousel", {
        slidesPerView: 8,
        spaceBetween: 30,
        speed: 500,
        navigation: {
          nextEl: ".category-carousel-next",
          prevEl: ".category-carousel-prev",
        },
        breakpoints: {
          0: {
            slidesPerView: 2,
          },
          768: {
            slidesPerView: 3,
          },
          991: {
            slidesPerView: 5,
          },
          1500: {
            slidesPerView: 8,
          },
        }
      });
    }

    $(".products-carousel").each(function(){
      var $el_id = $(this).attr('id');
      if (!$el_id) return;

      new Swiper("#"+$el_id+" .swiper", {
        slidesPerView: 2,
        slidesPerGroup: 1,
        spaceBetween: 12,
        speed: 500,
        watchOverflow: true,
        resistanceRatio: 0.65,
        grabCursor: true,
        navigation: {
          nextEl: "#"+$el_id+" .products-carousel-next",
          prevEl: "#"+$el_id+" .products-carousel-prev",
        },
        breakpoints: {
          0: {
            slidesPerView: 2,
            slidesPerGroup: 1,
            spaceBetween: 12,
          },
          576: {
            slidesPerView: 2,
            slidesPerGroup: 1,
            spaceBetween: 14,
          },
          768: {
            slidesPerView: 2,
            slidesPerGroup: 1,
            spaceBetween: 18,
          },
          992: {
            slidesPerView: 3,
            slidesPerGroup: 1,
            spaceBetween: 22,
          },
          1200: {
            slidesPerView: 4,
            slidesPerGroup: 1,
            spaceBetween: 24,
          },
          1500: {
            slidesPerView: 5,
            slidesPerGroup: 1,
            spaceBetween: 28,
          },
        }
      });

    });


    // product single page
    if (document.querySelector(".product-thumbnail-slider") && document.querySelector(".product-large-slider")) {
      var thumb_slider = new Swiper(".product-thumbnail-slider", {
        slidesPerView: 5,
        spaceBetween: 20,
        direction: "vertical",
        breakpoints: {
          0: {
            direction: "horizontal"
          },
          992: {
            direction: "vertical"
          },
        },
      });

      new Swiper(".product-large-slider", {
        slidesPerView: 1,
        spaceBetween: 0,
        effect: 'fade',
        thumbs: {
          swiper: thumb_slider,
        },
        pagination: {
          el: ".swiper-pagination",
          clickable: true,
        },
      });
    }
  }

  // input spinner
  var initProductQty = function(){

    $('.product-qty').each(function(){
      
      var $el_product = $(this);
      var quantity = 0;
      
      $el_product.find('.quantity-right-plus').click(function(e){
        e.preventDefault();
        quantity = parseInt($el_product.find('#quantity').val());
        $el_product.find('#quantity').val(quantity + 1);
      });

      $el_product.find('.quantity-left-minus').click(function(e){
        e.preventDefault();
        quantity = parseInt($el_product.find('#quantity').val());
        if(quantity>0){
          $el_product.find('#quantity').val(quantity - 1);
        }
      });

    });

  }

  // init jarallax parallax
  var initJarallax = function() {
    jarallax(document.querySelectorAll(".jarallax"));

    jarallax(document.querySelectorAll(".jarallax-keep-img"), {
      keepImg: true,
    });
  }

  // document ready
  $(document).ready(function() {
    
    initPreloader();
    initSwiper();
    initProductQty();
    initJarallax();
    initChocolat();

  }); // End of a document

})(jQuery);