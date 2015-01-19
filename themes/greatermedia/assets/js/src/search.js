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
				.toggleClass('header__search--open')
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
		}
	}
	
	/**
	 * Event listeners to run on click to show and close the search.
	 */
	if (searchBtn !== null) {
		searchBtn.addEventListener('click', showSearch, false);
		/**
		 * An event listener is also in place for the header search form so that when a user clicks inside of it, it will
		 * not hide. This is key because the header search for sits within the element that the click event that closes the
		 * search. If this is event listener is not in place and a user clicks within the search area, it will close.
		 */
		searchForm.addEventListener('click', function(e) {
			e.stopPropagation();
		});
	}
	
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
	
})();