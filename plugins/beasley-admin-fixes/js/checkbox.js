// Fixes to checkbox defaults on new post pages
jQuery( document ).ready( function( $ ) {
	if ( $( 'body' ).hasClass( 'post-new-php' ) ) {
		// Required until this bug is fixed https://github.com/Automattic/Edit-Flow/issues/397
		var $el = $( '#_ef_editorial_meta_checkbox_needs-photo' );
		if ( $el && $el.attr('checked') ) {
			$el.attr( 'checked', false );
		}

		var $el = $( '#_yst_is_cornerstone' );
		if ( $el && $el.attr('checked') ) {
			$el.attr( 'checked', false );
		}
	}

} );
