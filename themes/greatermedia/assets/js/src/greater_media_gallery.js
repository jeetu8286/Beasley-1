(function ( $, window, undefined ) {
	"use strict";

	var document = window.document,
		$document = $( document ),
		$window = $( window ),
		slideshow = $( '.main.cycle-slideshow' ),
		gallery = document.querySelectorAll( '.gallery' ),
		main = document.querySelector( '.main' ),
		$gallery = $( gallery ),
		$main = $( main ),
		$main_wrapper = $( '.main-wrapper' ),
		$caption = $( '.caption' ),
		$sidebar = $( '.sidebar' ),
		$slide_paging = $( '.slide-paging' ),
		$slide_paging_previews = $( '.slide-paging-previews' ),
		$toolbar_thumbnails = $( '.toolbar-thumbnails' ),
		$single_thumbnail = $( '.slide-paging-previews div div' );

	/**
	 * Bind the gallery full screen toggle
	 */
	function bind_events() {
		var hashChange = false;

		/**
		 * Bind the fullscreen toggle.
		 */
		$gallery.on( 'click', '.fullscreen', function ( e ) {
			e.preventDefault();
			if ( isFullscreen() ) {
				collapse_full();
			} else if ( isWidescreen() ) {
				collapse_wide();
				expand_full();
			} else {
				expand_full();
			}
		} );

		$gallery.on( 'click', '.widescreen', function( e ) {
			e.preventDefault();
			if ( isWidescreen() ) {
				collapse_wide();
			} else if ( isFullscreen() ) {
				collapse_full();
				expand_wide();
			} else {
				expand_wide();
			}
		} );

		$gallery.on( 'click', '.fake-radio', function ( e ) {
			$( '.fake-radio' ).removeClass( 'fa fa-check' );
			$( '.sharing-option input' ).removeAttr( 'checked' );
			$( e.target )
				.addClass( 'fa fa-check' )
				.prev( 'input' ).attr( 'checked', 'checked' );

			update_share_urls();
		} );

		/**
		 * Make sure thumbnails are updated before the slideshow cycles.
		 */
		slideshow.on( 'cycle-before', function ( event, optionHash ) {
			update_thumbnails( optionHash.nextSlide );
			$caption.cycle( 'goto', optionHash.nextSlide );
		} );

		/**
		 * Wire up additional events after the slideshow has fully initialized.
		 */
		slideshow.on( 'cycle-update-view', function( event, optionHash ) {
			update_thumbnails( optionHash.currSlide );
		} );

		/**
		 * Update slide sharing URL and title (hidden values) when slide changes,
		 * then update sharing links
		 */
		slideshow.on( 'cycle-update-view', function( event, optionHash, slideOptionsHash, currentSlideEl ) {
			$( 'input.slide-url' ).val( slideOptionsHash.slide_shorturl );
			$( 'input.slide-title' ).val( slideOptionsHash.slide_title );
			update_share_urls( slideOptionsHash.slide_shorturl, slideOptionsHash.slide_title );
		} );

		/**
		 * On mobile, regroup thumbnails on page load
		 */
		slideshow.on( 'cycle-initialized', function( e, opts ) {
			responsive_thumbnails();
		} );

		/**
		 * Make sure the preview buttons transition the slideshow.
		 */
		$slide_paging_previews.on( 'click', '.cycle-slide div', function () {
			var $this = $( this ),
				index = $this.data( 'cycle-index' );

			slideshow.cycle( 'goto', index );
		} );

		// Make sure we disable other hashchange events that attempt to capture manual hash changes.
		$document.on( 'cycle-pre-initialize', function( e, opts ) {
			$( window ).off( 'hashchange', opts._onHashChange);
		});

		// Rebind Captions - Code taken directly from jquery2.cycle.caption.js (with certain code standard updates)
		$document.on( 'cycle-update-view', function ( e, opts, slideOpts, currSlide ) {
			if ( opts.captionModule !== 'caption' ) {
				return;
			}

			var el;
			$.each( ['caption', 'overlay'], function () {
				var name = this,
					template = slideOpts[name + 'Template'],
					el = opts.API.getComponent( name );

				if ( el.length && template ) {
					el.html( opts.API.tmpl( template, slideOpts, opts, currSlide ) );
					el.show();
				}
				else {
					el.hide();
				}
			} );
		} );

		$( '.toolbar-item.thumbnails' ).on( 'click', function( e ) {
			var $button_text = $( this ).children( '.toolbar-text' );
			if ( GMR_Button_Text.hide_thumbnails == $button_text.text() ) {
				$toolbar_thumbnails.hide();
				$caption.addClass( 'thumbnails-hidden' );
				$button_text.text( GMR_Button_Text.show_thumbnails );
			} else {
				$toolbar_thumbnails.show();
				$caption.removeClass( 'thumbnails-hidden' );
				$button_text.text( GMR_Button_Text.hide_thumbnails );
			}
		} );

		$( '.toolbar-item.info' ).on( 'click', function( e ) {
			var $button_text = $( this ).children( '.toolbar-text' );
			if ( GMR_Button_Text.hide_info == $button_text.text() ) {
				$caption.hide();
				$button_text.text( GMR_Button_Text.show_info );
			} else {
				$caption.show();
				$button_text.text( GMR_Button_Text.hide_info );
			}
		} );

		$window.resize( responsive_thumbnails );
	}

	/**
	 * Is the slideshow in full screen mode?
	 * @returns bool
	 */
	function isFullscreen() {
		return $gallery.hasClass( 'isfullscreen' );
	}

	/**
	 * Is the slideshow in widescreen mode?
	 * @returns bool
	 */
	function isWidescreen() {
		return $gallery.hasClass( 'iswidescreen' );
	}

	/**
	 * Is the slideshow a 16x9 ratio?
	 * @returns bool
	 */
	function is16x9() {
		return $gallery.hasClass( 'gallery-ratio-16-9' );
	}

	/**
	 * Are we on a mobile browser (or anything smaller than 768px)?
	 * @returns bool
	 */
	function isMobile() {
		return $gallery.hasClass( 'ismobile' );
	}

	/**
	 * Expand the gallery
	 */
	function expand_full() {
		$gallery.addClass( 'isfullscreen' );
		$( '.toolbar-item.fullscreen .toolbar-text' ).text( GMR_Button_Text.exit_fullscreen );
		$( '.gallery-toolbar' ).after( $caption );
		$caption.addClass( 'slide-overlay-control' );
		if ( 'Show Info' == $( '.toolbar-item.info .toolbar-text' ).text() ) {
			$caption.hide();
		}
		$toolbar_thumbnails
			.append( $slide_paging_previews )
			.append( $slide_paging );
		if ( is16x9() ) {
			regroup_thumbnails( 15 );
			update_thumbnails( $main.data("cycle.opts").currSlide );
		}
	}

	/**
	 * Collapse the gallery
	 */
	function collapse_full() {
		$gallery.removeClass( 'isfullscreen' );
		$( '.toolbar-item.fullscreen .toolbar-text' ).text( GMR_Button_Text.fullscreen );
		$caption.removeClass( 'slide-overlay-control' ).show();
		$sidebar
			.prepend( $caption )
			.prepend( $slide_paging )
			.prepend( $slide_paging_previews );
		if ( is16x9() ) {
			regroup_thumbnails( 10 );
			update_thumbnails( $main.data("cycle.opts").currSlide );
		}
	}

	/**
	 * Expand the gallery (widescreen)
	 */
	function expand_wide() {
		$gallery.addClass( 'iswidescreen' );
		$( '.toolbar-item.widescreen .toolbar-text' ).text( GMR_Button_Text.exit_widescreen );
		$sidebar.before( $slide_paging_previews );
		regroup_thumbnails( 8 );
		update_thumbnails( $main.data("cycle.opts").currSlide );
	}

	/**
	 * Collapse the gallery (widescreen)
	 */
	function collapse_wide() {
		$gallery.removeClass( 'iswidescreen' );
		$( '.toolbar-item.widescreen .toolbar-text' ).text( GMR_Button_Text.widescreen );
		$sidebar.prepend( $slide_paging_previews );
		if ( is16x9() ) {
			regroup_thumbnails( 10 );
		} else {
			regroup_thumbnails( 15 );
		}
		update_thumbnails( $main.data("cycle.opts").currSlide );
	}

	/**
	 * Regroup the thumbnails in mobile to fit the screen
	 */
	function responsive_thumbnails() {
		// If we're moving from mobile to normal, shift things around
		if ( $window.width() >= 768 && isMobile() ) {
			$gallery.removeClass( 'ismobile' );
			$sidebar.append( $caption );
			regroup_thumbnails( get_thumbs_per_page() );
			update_thumbnails( $main.data( "cycle.opts" ).currSlide );

			$main.css( 'height', '' );
			$main_wrapper.css( 'height', '' );
			$( '.slide-paging-previews, .slide-previews-group' ).css( 'height', '' );
			return;
		}

		// If we're on a small screen but ismobile is not set, shift things around
		if ( $window.width() < 768 && ! isMobile() ) {
			if ( isFullscreen() ) {
				collapse_full();
			} else if ( isWidescreen() ) {
				collapse_wide();
			}
			$gallery.addClass( 'ismobile' );
			$sidebar.prepend( $caption );
			regroup_thumbnails( get_thumbs_per_page() );
			update_thumbnails( $main.data( "cycle.opts" ).currSlide );
		}

		// If the window is being resized, adjust the thumbnail and main image height
		if ( isMobile() ) {
			var main_height, thumb_height;
			if ( is16x9() ) {
				main_height = Math.round( $main_wrapper.width() * 0.559 );
			} else {
				main_height = Math.round( $main_wrapper.width() * 0.729 );
			}
			$main.css( 'height', ( main_height - 20 ) + 'px' );
			$main_wrapper.css( 'height', main_height );

			thumb_height = $single_thumbnail.width();
			$( '.slide-paging-previews, .slide-previews-group' ).css( 'height', thumb_height + 'px' );
		}
	}

	/**
	 * Regroup the thumbnails in paged divs
	 *
	 * This is used when going between widescreen and other views, because the
	 * widescreen view can only hold 8 thumbnails per page while the other views
	 * can hold 15 per page.
	 *
	 * @param number_in_group
	 */
	function regroup_thumbnails( number_in_group ) {
		var $thumbnails_group = $( '.slide-previews-group' );

		$thumbnails_group.children( 'div' ).appendTo( '.slide-paging-previews' );
		$thumbnails_group.remove();
		$( '.slide-paging-previews div' ).each( function() {
			var $this = $( this );
			if ( undefined == $this.attr( 'id' ) ) {
				$this.remove();
			}
		} );
		$slide_paging_previews
			.each( function () {
				var divs = $( 'div', this );
				for ( var i = 0; i < divs.length; i += number_in_group ) {
					divs.slice( i, i + number_in_group ).wrapAll( '<div class="slide-previews-group"></div>' );
				}
			} )
			.cycle( 'reinit' );
	}

	/**
	 * Update the slide thumbnails to we're viewing the correct group.
	 *
	 * @param {Number} selected_slide
	 */
	function update_thumbnails( selected_slide ) {
		var slides_per_page = get_thumbs_per_page();

		$( '.pager-slide-active' ).removeClass( 'pager-slide-active' );
		$( '#preview-' + selected_slide ).addClass( 'pager-slide-active' );
		var selected_slide_group = Math.floor( selected_slide / slides_per_page );
		$slide_paging_previews.cycle( 'goto', selected_slide_group );
	}

	function get_thumbs_per_page() {
		var slides_per_page = 15;
		if ( is16x9() && ! isFullscreen() ) {
			slides_per_page = 10;
		}
		if ( isWidescreen() ) {
			slides_per_page = 8;
		}
		if ( isMobile() ) {
			slides_per_page = 4;
		}
		return slides_per_page;
	}

	/**
	 * Update sharing links and short URL in sharing overlay
	 */
	function update_share_urls() {
		var share_url, share_title;
		if ( $( '#share-image' ).is( ':checked' ) ) {
			share_url   = $( 'input.slide-url' ).val();
			share_title = $( 'input.slide-title' ).val();
		} else {
			share_url   = $( 'input.gallery-url' ).val();
			share_title = $( 'input.gallery-title' ).val();
		}
		var url_twitter  = 'http://twitter.com/home?status=' + share_url + '%20-%20' + share_title;
		var url_facebook = 'http://www.facebook.com/sharer.php?u=' + share_url + '&amp;t=' + share_title;
		var url_linkedin = 'http://www.linkedin.com/shareArticle?mini=true&url=' + share_url + '&title=' + share_title + '&source=World+Economic+Forum';

		$( '.gallery-toolbar .fa-twitter' ).attr( 'href', url_twitter );
		$( '.gallery-toolbar .fa-facebook' ).attr( 'href', url_facebook );
		$( '.gallery-toolbar .fa-linkedin' ).attr( 'href', url_linkedin );
		$( '.gallery-toolbar .short-url' ).html( '<a href="' + share_url + '">' + share_url + '</a>' );
	}

	bind_events();

	// Some galleries are set to be in widescreen by default
	if ( isWidescreen() ) {
		expand_wide();
	}

	window.WEF_Gallery = {
		isFullscreen: isFullscreen,
		expand      : expand_full,
		collapse    : collapse_full
	};

})( jQuery, window );
