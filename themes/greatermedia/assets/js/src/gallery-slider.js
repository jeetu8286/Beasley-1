( function ( $, window, document ) {
	var $window = $( window );
	var $document = $( document );

	var sidebar = document.querySelector( '.swiper-sidebar' );
	var swiperContainer = document.querySelector( '.gallery-top' );
	var sidebarExpand = document.getElementById( 'js-expand' );

	var $galleryTopSlider = $( '.gallery-top .swiper-wrapper' );
	var $galleryThumbsSlider = $( '.gallery-thumbs' );

	var updateHistory = true;

	if ( ! $galleryTopSlider.length ) {
		return;
	}

	function positionSidebar() {
		var swiperWrapper = document.querySelector( '.gallery-top .slick-track' );
		var newActiveSlide = document.querySelector( '.gallery-top .slick-current' );
		var newActiveSlidePos = newActiveSlide.getBoundingClientRect();
		var paddingRight = parseInt( ( $window.width() - newActiveSlidePos.width )/2 - 385, 10 );

		// We want to move the sidebar and add enough padding so that it's always at least 300px wide (exluding padding).
		// In some cases where the image is too wide, we want to apply a negative margin left to pull the image to the left
		// so that the sidebar has enough room.

		if ( ! swiperContainer.classList.contains( 'show-ad' ) && ! newActiveSlide.classList.contains( 'last-slide' ) ) {
			sidebar.classList.remove( 'hidden' );

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

	function updateURL( slide ) {
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

	function resetSidebarMargin() {
		var swiperWrapper = document.querySelector( '.gallery-top .slick-track' );

		swiperWrapper.style.marginLeft = '0px';
	};

	function updateSidebarInfo( slide ) {
		var newActiveSlide = document.querySelector( '.gallery-top .slick-slide:nth-child(' + parseInt( slide+1, 10 ) + ')' );
		var sidebarTitle = document.getElementById( 'js-swiper-sidebar-title' );
		var sidebarCaption = document.getElementById( 'js-swiper-sidebar-caption' );

		var facebookButton = sidebar.querySelector( '.social__link.icon-facebook' );
		var twitterButton = sidebar.querySelector( '.social__link.icon-twitter' );
		var googleButton = sidebar.querySelector( '.social__link.icon-google-plus' );

		// If we're not on an ad slide, update the sidebar information with new title, caption and social sharing
		if ( ! newActiveSlide.classList.contains( 'meta-spacer' ) ) {
			var title = newActiveSlide.getAttribute( 'data-title' );
			var caption = newActiveSlide.getAttribute( 'data-caption' );
			var url = window.location.href;
			var facebookURL = 'http://www.facebook.com/sharer/sharer.php?u=' + encodeURI( url ) + '&title=' + encodeURIComponent( title );
			var twitterURL = 'http://twitter.com/home?status=' + encodeURIComponent( title ) + '+' + encodeURI( url );
			var googleURL = 'https://plus.google.com/share?url=' + encodeURI( url );

			sidebarTitle.innerText = title;
			sidebarCaption.innerText = caption;
			if ( facebookButton ) {
				facebookButton.href = facebookURL;
			}
			if ( twitterButton ) {
				twitterButton.href = twitterURL;
			}
			if ( googleButton ) {
				googleButton.href = googleURL;
			}
		}
	};

	function refreshAds( ads ) {
		var sidebarSlots = [];

		ads.each( function() {
			sidebarSlots.push( $( this ).data( 'slot' ) );
		} );

		if ( sidebarSlots.length && googletag ) {
			setTimeout( function() {
				googletag.pubads().refresh( sidebarSlots );
			}, 500 );
		}
	}

	function maybeShowCenteredAd( newSlide ) {
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

	function maybeHideSidebar( newSlide ) {
		var newActiveSlide = document.querySelector( '.gallery-top .slick-slide:nth-child(' + parseInt( newSlide+1, 10 ) + ')' );

		if ( newActiveSlide.classList.contains( 'last-slide' ) ) {
			sidebar.classList.add( 'hidden' );
			$galleryThumbsSlider.addClass( 'hidden' );
		} else {
			sidebar.classList.remove( 'hidden' );
			$galleryThumbsSlider.removeClass( 'hidden' );
		}
	};

	function reposition() {
		resetSidebarMargin();
		positionSidebar();
	};

	function updateCurrentSlide() {
		var slide = document.querySelector( '.gallery-top .swiper-slide[data-slug="' + window.location.href + '"]' );
		if ( slide ) {
			$galleryTopSlider.slick( 'slickGoTo', parseInt( slide.getAttribute( 'data-index' ), 10 ), true );
		}
	}

	// Expand sidebar on mobile
	sidebarExpand.addEventListener( 'click', function( e ) {
		e.preventDefault();
		if ( sidebar.classList.contains( 'expand' ) ) {
			sidebar.classList.remove( 'expand' );
		} else {
			sidebar.classList.add( 'expand' );
		}
	} );

	// Go to the correct slide if users press back button
	$window.on( 'popstate', function( event ) {
		updateHistory = false;
		updateCurrentSlide();
		updateHistory = true;
	} );

	$window.load( function() {
		var cleanIndex = [];
		var sidebarAdRefreshInterval = parseInt( document.querySelector( '.gallery-top' ).getAttribute( 'data-refresh-interval' ), 10 );

		var initialSlide = document.querySelector( '.gallery-top .swiper-slide[data-slug="' + window.location.href + '"]' );
		if ( initialSlide ) {
			var galleryInitialIndex = parseInt( initialSlide.getAttribute( 'data-index' ), 10 );
		}

		$galleryTopSlider.on( 'init', function( event, slick ) {

			// Extract slides that are not ad spacers
			slick.$slides.each( function() {
				if ( ! $( this ).find( '.meta-spacer' ).length ) {
					cleanIndex.push( $( this ) );
				}
			} );

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

			$galleryThumbsSlider.removeClass( 'loading' )

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
			initialSlide: galleryInitialIndex
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

	} );

	$window.on( 'resize', _.debounce( reposition, 300 ) );

} )( jQuery, window, document );
