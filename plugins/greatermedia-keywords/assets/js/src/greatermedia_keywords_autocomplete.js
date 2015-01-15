// Silence jslint warning about _ being undefined.
/*global _ */

(function ( window, undefined ) {
	'use strict';

	function Keyword_Search( keywords ) {
		this.keywords = keywords;
	}

	Keyword_Search.prototype.search = function ( search_term ) {

		search_term = this.normalize_search_term( search_term );

		if ( !search_term.length ) {
			return [];
		}

		var me = this; // This feels clunky, but it's what MDN recommends...
		var matches = _.filter( this.keywords, function ( item ) {
			return 0 === me.normalize_search_term( item.keyword ).indexOf( me.normalize_search_term( search_term ) );
		} );

		return matches;
	};

	Keyword_Search.prototype.normalize_search_term = function ( search_term ) {
		return search_term.trim().toLowerCase().replace( /[^a-z0-9]+/g, '' );
	};

	jQuery( function ( $ ) {
		// Wire things together.
		var max_results = 6;
		var search = new Keyword_Search( GMRKeywords );
		var $search_field = $( '#header-search' );
		var $overlay = $( '.overlay-mask' );

		var item_template = _.template( $( '#keyword-search-item-template' ).html() );
		var body_template = _.template( $( '#keyword-search-body-template' ).html() );

		// Add the body to the page, making sure the container is hidden.
		var $container = $( '#keyword-search-container' );
		$container.hide();
		$container.append( $( body_template() ) );

		// Reference header and item list for later.
		var $header = $container.find( '.keyword-search__header' );
		var $item_list = $container.find( '.keyword-search__items' );

		// Hook up the button.
		$container.find( '.keyword-search__btn' ).click( function() {
			$( '#header-search' ).parent( 'form' ).submit();
		} );

		// Hook up the search field.
		$search_field.on( 'keyup', function () {

			var items = search.search( $( this ).val() );

			// Bail if we don't have any items, and make sure the container is hidden.
			if ( ! items || ! items.length ) {
				$container.hide();
				$overlay.removeClass( 'is-visible' );
				return;
			}

			// Trim results
			items = items.slice( 0, max_results );

			// Update header.
			$header.html( items.length > 1 ? items.length + ' keyword matches found' : '1 keyword match found' );

			// Update the item list.
			$item_list.empty();
			_.each( items, function ( item ) {
				$item_list.append( $( item_template( item ) ) );
			} );

			// Display the container.
			$container.show();
			$overlay.addClass( 'is-visible' );
		} );
	} );

})( this );