<?php

/**
 * The function returns original content of a shortcode and used to replace deprecated shortcodes.
 */
function beasley_empty_shortcode( $atts, $content = null ) {
	return $content;
}

function besley_national_contest_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'contest' => 'national-contest',
		'brand'   => 'WMMR',
	), $atts, 'bbgi-contest' );

	$contest = urlencode( $atts['contest'] );
	$brand = urlencode( $atts['brand'] );

	$embed = <<<EOL
<style>#contestframe {width: 100%;}</style>
<iframe id="contestframe" src="https://contests.bbgi.com/landing?contest={$contest}&brand={$brand}" frameborder="0" scrolling="no" onload="iFrameResize({log:true, autoResize: true})"></iframe>
EOL;

	return $embed;
}

add_shortcode( 'age-restricted', 'beasley_empty_shortcode' );
add_shortcode( 'login-restricted', 'beasley_empty_shortcode' );
add_shortcode( 'livefyre-wall', 'beasley_empty_shortcode' );
add_shortcode( 'livefyre-poll', 'beasley_empty_shortcode' );
add_shortcode( 'livefyre-app', 'beasley_empty_shortcode' );

add_shortcode( 'bbgi-contest', 'besley_national_contest_shortcode' );
