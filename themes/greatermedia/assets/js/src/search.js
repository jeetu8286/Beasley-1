(function() {
	var $ = jQuery,
		searchForm = document.getElementById( 'header__search--form'),
		searchBtn = document.getElementById( 'header__search'),
		searchInput = document.getElementById( 'header-search' ),
		$overlay = $('.overlay-mask' );
	
	/**
	 * A function to show the header search when an event is targeted.
	 *
	 * @param e
	 */
	function showSearch(e) {
		if (searchForm !== null) {
			e.preventDefault();
			$overlay.addClass( 'is-visible' )
			
			// Now, show the search form, but don't set focus until the transition
			// animation is complete. This is because Webkit browsers scroll to 
			// the element when it gets focus, and they scroll to it where it was
			// before the transition started. 
			$( searchForm )
				.addClass('header__search--open')
				.on('transitionend webkitTransitionEnd oTransitionEnd otransitionend MSTransitionEnd', function () {
					searchInput.focus();
					$(searchInput).select();
				} );
		}
	}
	
	/**
	 * A function to hide the header search when an event is targeted.
	 *
	 * @param e
	 */
	function closeSearch(e) {
		if (searchForm !== null && searchForm.classList.contains('header__search--open')) {
			e.preventDefault();
			searchForm.classList.remove('header__search--open');
			$overlay.removeClass('is-visible');
			$( searchForm ).parent().focus();
		}
	}
	
	/**
	 * Event listeners to run on click to show and close the search.
	 */
	$( searchBtn ).click( showSearch ); 
	
	// Show search if the field has focus.
	$( searchInput ).focus( showSearch ); 
	
	function checkSearchField () {
		var $search_body = $( searchForm ).find( '.header-search-body' );
		
		// Show the body only if there's text in the search field.
		if ( $( searchInput ).val().length ) {
			$search_body.show();
		} else {
			$search_body.hide();
		}
	}
	
	$( searchInput ).keyup( checkSearchField );
	
	checkSearchField(); 
	
	/**
	 * Close the search box when user presses escape.
	 */
	$(window).keydown(function (e) {
		if (e.keyCode === 27){
			closeSearch(e);
		}
	});
	
	/**
	 * Close the search box (if open) if the user clicks on the overlay.
	 */
	$overlay.click(function (e) {
		closeSearch(e);
	});
	
	/**
	 * Close the search box (if open) if the user clicks the close button.
	 */
	$( searchForm ).find( '.header__search--cancel' ).click( function ( e ) {
		e.preventDefault();
		closeSearch( e );
	} );
	
	/**
	 * Make "Search All Content" button trigger form submit.
	 */
	$( searchForm ).find( '.header-search__search-all-btn' ).click( function () {
		$( searchForm ).find( 'form' ).submit(); 	
	} );
	
	/**
	 * PJAX workaround. PJAX is set to only handle links when they're clicked,
	 * so to get the form to work over PJAX we need to create a fake link and 
	 * then click it. Clunky but it is the quick fix for now. 
	 */
	$( searchForm ).find( 'form' ).submit( function ( e ) {
		e.preventDefault();		
		
		$( '<a></a>' )
			.attr( 'href', $( this ).attr( 'action' ) + '?s=' + $( this ).find( 'input[name=s]' ).val() )
			.appendTo( $( this ) )
			.click()
		;
		
		closeSearch( e );
	} );
	
})();