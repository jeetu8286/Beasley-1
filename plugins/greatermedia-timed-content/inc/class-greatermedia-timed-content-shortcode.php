<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

/**
 * Class GreaterMediaTimedContentShortcode
 * Implements a shortcode for showing/hiding content based on time
 */
class GreaterMediaTimedContentShortcode {

	function __construct() {
		add_action( 'init', array( $this, 'init' ) );
	}

	public function init() {
		add_shortcode( 'time-restricted', array( $this, 'process_shortcode' ) );
	}

	/**
	 * Process the time-restricted shortcode
	 *
	 * @param      array  $atts
	 * @param string|null $content optional content to display
	 *
	 * @return null|string output to display
	 */
	function process_shortcode( $atts, $content = null ) {

		$local_to_gmt_time_offset = get_option( 'gmt_offset' ) * - 1 * 3600;

		if ( isset( $atts['show'] ) ) {
			$show     = strtotime( $atts['show'] );
			$show_gmt = $show + $local_to_gmt_time_offset;
		} else {
			$show_gmt = 0;
		}

		if ( isset( $atts['hide'] ) ) {
			$hide     = strtotime( $atts['hide'] );
			$hide_gmt = $hide + $local_to_gmt_time_offset;
		} else {
			$hide_gmt = PHP_INT_MAX;
		}

		$now_gmt = intval( gmdate( 'U' ) );

		if ( $now_gmt > $show_gmt && $hide_gmt > $now_gmt ) {
			return $content;
		} else {
			return '';
		}

	}

}

$GreaterMediaTimedContentShortcode = new GreaterMediaTimedContentShortcode();

