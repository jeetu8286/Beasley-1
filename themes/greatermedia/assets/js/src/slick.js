(function ($, window, document, undefined) {
  if( $('.featured-post-slider').length ) {
    $('.featured-post-slider').slick({
      dots: true,
      infinite: true,
      speed: 300,
      slidesToShow: 1,
      adaptiveHeight: true
    });
  }
})(jQuery, window, document);