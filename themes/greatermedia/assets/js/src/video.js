( function( $, config ) {
	var $document = $( document );

	var startEvent = 'click';
	if ( navigator.userAgent.match( /iPhone/i ) || navigator.userAgent.match( /iPad/i ) || navigator.userAgent.match( /Android/i ) ) {
		startEvent = 'touchend';
	}

	var __ready = function() {
		$( '.livestream-oembed .video-js[data-src]' ).each( function() {
			var id = this.id;
			var player = videojs( this );

			player.src( {
				src: $( this ).data( 'src' ),
				type: 'application/x-mpegURL',
				withCredentials: true
			} );

			if ( config.adTagUrl ) {
				player.ima( { id: id, adTagUrl: config.adTagUrl } );

				var wrapper = document.getElementById( id );
				if ( wrapper ) {
					// Initialize the ad container when the video player is clicked, but only the
					// first time it's clicked.
					var initAdDisplayContainer = function() {
						player.ima.initializeAdDisplayContainer();
						wrapper.removeEventListener( startEvent, initAdDisplayContainer );
					};

					wrapper.addEventListener( startEvent, initAdDisplayContainer );
				}
			}
		} );
	};

	$document.ready( __ready );
	$document.bind( 'pjax:end', _.debounce( __ready, 300 ) );
} )( jQuery, platformConfig.videojs || {} );
