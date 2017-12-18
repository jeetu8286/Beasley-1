/*global $:false, jQuery:false */
(function($){
	var $resetPostButton = $(document.getElementById( 'js-syndication-reset' ) );

	if ( $resetPostButton.length !== 0 ) {

		$resetPostButton.on( 'click', function( e ) {
			if ( ! window.confirm( "Are you sure you want to reset this post to the source site? You will lose any changes you may have made.") ) {
				return false;
			}
		});
	}
})(jQuery);
