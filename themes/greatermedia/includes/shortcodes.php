<?php

/**
 * The function returns original content of a shortcode and used to replace deprecated shortcodes.
 */
function greatermedia_empty_shortcode( $atts, $content = null ) {
	return $content;
}

add_shortcode( 'age-restricted', 'greatermedia_empty_shortcode' );
add_shortcode( 'login-restricted', 'greatermedia_empty_shortcode' );
add_shortcode( 'livefyre-wall', 'greatermedia_empty_shortcode' );
add_shortcode( 'livefyre-poll', 'greatermedia_empty_shortcode' );
add_shortcode( 'livefyre-app', 'greatermedia_empty_shortcode' );
