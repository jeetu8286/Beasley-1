( function( $ ) {
	var $document = $( document );

	var __ready = function() {
		$( '.livestream-oembed.video-js[data-src]' ).each( function() {
			var player = videojs( this );

			player.src({
				src: $( this ).data( 'src' ),
				type: "application/x-mpegURL",
				withCredentials: true
			});
		} );
	};

	$document.ready( __ready );
	$document.bind( 'pjax:end', _.debounce( __ready, 300 ) );
} )( jQuery );
