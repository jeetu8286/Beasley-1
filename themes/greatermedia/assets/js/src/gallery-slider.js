( function ( $, window, document ) {
	var $window = $( window );
	var $document = $( document );

	var __ready = function() {
		var $galleryTopSlider = $( '.gallery-top .swiper-wrapper' );
		if ( ! $galleryTopSlider.length ) {
			return;
		}

		var sidebar = document.querySelector( '.swiper-sidebar' );
		var swiperContainer = document.querySelector( '.gallery-top' );
		var sidebarExpand = document.getElementById( 'js-expand' );
		var sidebarFullscreen = document.getElementById( 'js-fullscreen' );
		var $galleryThumbsSlider = $( '.gallery-thumbs' );
		var updateHistory = true;

		var getCurrentLocation = function() {
			return window.location.href.split('#')[0].split('?')[0];
		};

		var positionSidebar = function() {
			var swiperWrapper = document.querySelector( '.gallery-top .slick-track' );
			var newActiveSlide = document.querySelector( '.gallery-top .slick-current' );
			var newActiveSlidePos = newActiveSlide.getBoundingClientRect();
			var paddingRight = parseInt( ( $window.width() - newActiveSlidePos.width )/2 - 385, 10 );

			// We want to move the sidebar and add enough padding so that it's always at least 300px wide (exluding padding).
			// In some cases where the image is too wide, we want to apply a negative margin left to pull the image to the left
			// so that the sidebar has enough room.

			if ( ! swiperContainer.classList.contains( 'show-ad' ) && ! newActiveSlide.classList.contains( 'last-slide' ) ) {
				sidebar.classList.remove( 'hidden' );
				if ( sidebar.classList.contains( 'expand' ) ) {
					sidebar.classList.remove( 'expand' );
				}

				if (window.matchMedia("(min-width: 768px)").matches) {
					if ( paddingRight < 0 ) {
						swiperWrapper.style.marginLeft = paddingRight + 'px';
						sidebar.setAttribute( 'style', 'left:' + parseInt( ($window.width() - newActiveSlidePos.width)/2 + newActiveSlidePos.width + paddingRight, 10 ) + 'px' );
					} else {
						swiperWrapper.style.marginLeft = '0px';
						sidebar.setAttribute( 'style', 'left:' + parseInt( ($window.width() - newActiveSlidePos.width)/2 + newActiveSlidePos.width, 10 ) + 'px;padding-right:' + paddingRight + 'px' );
					}
				}
			} else {
				// If a centered ad is active, hide the sidebar
				sidebar.setAttribute( 'style', '' );
				sidebar.classList.add( 'hidden' );
			}
		};

		var updateURL = function( slide ) {
			var newActiveSlide = document.querySelector( '.gallery-top .slick-slide:nth-child(' + parseInt( slide+1, 10 ) + ')' );

			// If we're not on an ad slide, update the URL
			if ( ! newActiveSlide.classList.contains( 'meta-spacer' ) ) {
				var slug = newActiveSlide.getAttribute( 'data-slug' );
				var title = newActiveSlide.getAttribute( 'data-title' );
				// Save index in state object to navigate back to slide
				var stateObject = { index: slide };

				if ( window.history ) {
					history.pushState( stateObject, title, slug );
				}
			}
		};

		var resetSidebarMargin = function() {
			var swiperWrapper = document.querySelector( '.gallery-top .slick-track' );

			swiperWrapper.style.marginLeft = '0px';
		};

		var updateSidebarInfo = function( slide ) {
			var newActiveSlide = document.querySelector( '.gallery-top .slick-slide:nth-child(' + parseInt( slide + 1, 10 ) + ')' );
			if ( newActiveSlide.classList.contains( 'meta-spacer' ) ) {
				return;
			}

			var title = newActiveSlide.getAttribute( 'data-title' );
			var caption = newActiveSlide.getAttribute( 'data-caption' );

			var sidebarTitle = document.getElementById( 'js-swiper-sidebar-title' );
			if ( sidebarTitle ) {
				sidebarTitle.innerText = title;
			}

			var sidebarCaption = document.getElementById( 'js-swiper-sidebar-caption' );
			if ( sidebarCaption ) {
				sidebarCaption.innerText = caption;
			}

			var sidebarDownload = document.getElementById( 'js-swiper-sidebar-download' );
			if ( sidebarDownload ) {
				sidebarDownload.href = newActiveSlide.getAttribute( 'data-source' );
			}

			var shareIndividualPhotos = parseInt( document.querySelector( '.gallery-top' ).getAttribute( 'data-share-photos' ), 10 );
			if ( isNaN( shareIndividualPhotos ) || ! shareIndividualPhotos ) {
				// if we don't need to share individual photos, then we don't need to change share buttons
				return;
			}

			var url = encodeURIComponent( newActiveSlide.getAttribute( 'data-share' ) );

			var facebookButton = sidebar.querySelector( '.social__link.icon-facebook' );
			if ( facebookButton ) {
				facebookButton.href = 'https://www.facebook.com/sharer/sharer.php?u=' + url + '&title=' + encodeURIComponent( title );
			}

			var twitterButton = sidebar.querySelector( '.social__link.icon-twitter' );
			if ( twitterButton ) {
				twitterButton.href = 'https://twitter.com/home?status=' + encodeURIComponent( title ) + '+' + url;
			}

			var googleButton = sidebar.querySelector( '.social__link.icon-google-plus' );
			if ( googleButton ) {
				googleButton.href = 'https://plus.google.com/share?url=' + url;
			}
		};

		var refreshAds = function( ads ) {
			var sidebarSlots = [];

			ads.each( function() {
				sidebarSlots.push( $( this ).data( 'slot' ) );
			} );

			if ( sidebarSlots.length && googletag ) {
				setTimeout( function() {
					googletag.pubads().refresh( sidebarSlots );
				}, 500 );
			}
		};

		var maybeShowCenteredAd = function( newSlide ) {
			var newActiveSlide = document.querySelector( '.gallery-top .slick-slide:nth-child(' + parseInt( newSlide+1, 10 ) + ')' );

			if ( newActiveSlide.classList.contains( 'meta-spacer' ) ) {
				sidebar.classList.add( 'hidden' );
				swiperContainer.classList.add( 'show-ad' );
				refreshAds( $( '.swiper-sidebar-meta .gmr-ad' ) );
			} else {
				if ( swiperContainer.classList.contains( 'show-ad' ) ) {
					refreshAds( $( '.swiper-meta-inner .gmr-ad' ) );
				}

				sidebar.classList.remove( 'hidden' );
				swiperContainer.classList.remove( 'show-ad' );
			}
		};

		var maybeHideSidebar = function( newSlide ) {
			var newActiveSlide = document.querySelector( '.gallery-top .slick-slide:nth-child(' + parseInt( newSlide+1, 10 ) + ')' );

			if ( newActiveSlide.classList.contains( 'last-slide' ) ) {
				sidebar.classList.add( 'hidden' );
				$galleryThumbsSlider.addClass( 'hidden' );
			} else {
				sidebar.classList.remove( 'hidden' );
				$galleryThumbsSlider.removeClass( 'hidden' );
			}
		};

		var reposition = function() {
			resetSidebarMargin();
			positionSidebar();
		};

		var updateCurrentSlide = function() {
			var slide = document.querySelector( '.gallery-top .swiper-slide[data-slug="' + getCurrentLocation() + '"]' );
			if ( slide ) {
				$galleryTopSlider.slick( 'slickGoTo', parseInt( slide.getAttribute( 'data-index' ), 10 ), true );
			}
		};

		// Expand sidebar on mobile
		sidebarExpand.addEventListener( 'click', function( e ) {
			e.preventDefault();
			if ( sidebar.classList.contains( 'expand' ) ) {
				sidebar.classList.remove( 'expand' );
			} else {
				sidebar.classList.add( 'expand' );
			}
		} );

		// Fullscreen
		sidebarFullscreen.addEventListener( 'click', function( e ) {
			e.preventDefault();
			if ( swiperContainer.classList.contains( 'fullscreen' ) ) {
				swiperContainer.classList.remove( 'fullscreen' );
			} else {
				swiperContainer.classList.add( 'fullscreen' );
			}

			setTimeout( function() {
				$galleryTopSlider.slick( 'setPosition' );
				reposition();
			}, 400 );
		} );

		// Go to the correct slide if users press back button
		$window.on( 'popstate', function( event ) {
			updateHistory = false;
			updateCurrentSlide();
			updateHistory = true;
		} );

		var initialSlide = document.querySelector( '.gallery-top .swiper-slide[data-slug="' + getCurrentLocation() + '"]' );
		if ( initialSlide ) {
			var galleryInitialIndex = parseInt( initialSlide.getAttribute( 'data-index' ), 10 );
		}

		$galleryTopSlider.on( 'init', function( event, slick ) {
			// 300ms is the global animation speed
			positionSidebar();
			updateSidebarInfo( galleryInitialIndex );
			swiperContainer.classList.remove( 'loading' );
		} );

		$galleryThumbsSlider.on( 'init', function( event, slick ) {
			// We want to know which thumb is an ad spacer.
			slick.$slides.each( function() {
				if ( $( this ).find( '.meta-spacer' ).length ) {
					$( this ).addClass( 'is-meta' );
				}
			} );

			$galleryThumbsSlider.removeClass( 'loading' );

		} );

		$galleryTopSlider.slick( {
			infinite: false,
			speed: 300,
			slidesToShow: 1,
			centerMode: true,
			variableWidth: true,
			adaptiveHeight: true,
			asNavFor: '.gallery-thumbs',
			arrows: true,
			prevArrow: '<button type="button" class="slick-prev"><span class="icon-arrow-prev"></span></button>',
			nextArrow: '<button type="button" class="slick-next"><span class="icon-arrow-next"></span></button>',
			initialSlide: galleryInitialIndex,
			responsive: [
				{
					breakpoint: 767,
					settings: {
						variableWidth: false,
						centerMode: false,
					}
				}
			]
		} );

		$galleryThumbsSlider.slick( {
			infinite: false,
			speed: 300,
			slidesToShow: 10,
			centerMode: true,
			focusOnSelect: true,
			variableWidth: true,
			asNavFor: '.gallery-top .swiper-wrapper',
			arrows: false,
			initialSlide: galleryInitialIndex
		} );

		$galleryTopSlider.on( 'beforeChange', function( event, slick, currentSlide, nextSlide ) {
			resetSidebarMargin();
			maybeShowCenteredAd( nextSlide );
			maybeHideSidebar( nextSlide );
		} );

		$galleryTopSlider.on( 'afterChange', function( event, slick, currentSlide ) {
			// 300ms is the global animation speed
			positionSidebar();

			if ( updateHistory ) {
				updateURL( currentSlide );
			}

			updateSidebarInfo( currentSlide );
		} );

		$window.on( 'resize', _.debounce( reposition, 300 ) );

	};

	$window.load( __ready );
	$document.bind( 'pjax:end', _.debounce( __ready, 300 ) );
} )( jQuery, window, document );
