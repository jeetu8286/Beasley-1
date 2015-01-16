/**
 * GreaterMedia Keywords
 * http://wordpress.org/plugins
 *
 * Copyright (c) 2014 10up
 * Licensed under the GPLv2+ license.
 */

// Silence jslint warning about _ being undefined.
/*global _, ajaxurl */

( function( window, undefined ) {
	'use strict';

	jQuery( function( $ ) {

		var $search_item_container = $( '#linked_content_item_container' );
		var search_item_template = _.template( $( '#linked_content_item_template' ).html() );

		function do_search( term ) {
			$search_item_container.addClass( 'is-loading' );
			
			$.getJSON( ajaxurl, {
				action: 'greater_media/keywords/get_posts',
				s: term
			} )
				.done( function ( res ) {
					$search_item_container.empty();
					
					_.each( res.data, function ( item ) {
						$search_item_container.append( $( search_item_template( item ) ) );
					} );
					
					$search_item_container.removeClass( 'is-loading' );
				} )
			;
		}

		$( '#linked_content_search' ).keyup( _.debounce( function () {
			search( $( this ).val() );
		}, 500 ) );
		

		$('.submitdelete').on( 'click', function( event ) {
			event.preventDefault();
			var el = $(this);
			var post_id = el.data( 'postid');
			jQuery.ajax({
				type : "post",
				url : ajax_data.ajaxurl,
				data : {
					action: "delete_keyword" ,
					post_id : post_id ,
					delete_key_nonce: ajax_data.delete_key_nonce
				},
				success: function( success ) {
					if( success ) {
						el.closest('tr').addClass('delete').hide( 'drop' );
					} else {
						$('.form-table').first().prepend(
							'<div id="message" class="error"><p>Something gone wrong! Keyword is not deleted!</p></div>'
						);
					}
				}
			});
		});
	});

} )( this );