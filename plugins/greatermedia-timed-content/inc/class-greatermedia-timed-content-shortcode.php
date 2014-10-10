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
		add_action( 'admin_head', array( $this, 'admin_head' ) );

	}

	public function init() {
		add_shortcode( 'time-restricted', array( $this, 'process_shortcode' ) );
	}

	public function admin_head() {
		// check user permissions
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			return;
		}

		// check if WYSIWYG is enabled
		if ( 'true' == get_user_option( 'rich_editing' ) ) {
			add_filter( 'mce_external_plugins', array( $this, 'gm_timed_content_tinymce_plugin' ) );
			add_filter( 'mce_buttons', array( $this, 'gm_timed_content_tinymce_button' ) );
		}

	}

	/**
	 * Declare script for new button
	 * mce_external_plugins Implementation.
	 *
	 * @param array $plugin_array
	 *
	 * @return array
	 */
	function gm_timed_content_tinymce_plugin( array $plugin_array ) {

		$plugin_array['gm_timed_content_mce_button'] = trailingslashit( GREATER_MEDIA_TIMED_CONTENT_URL ) . 'js/gm-timed-content-tinymce.js';

		return $plugin_array;

	}

	/**
	 * Register new button in the editor
	 * mce_buttons Implementation.
	 *
	 * @param array $buttons
	 *
	 * @return array
	 */
	function gm_timed_content_tinymce_button( array $buttons ) {

		array_push( $buttons, 'gm_timed_content_mce_button' );

		return $buttons;

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

		if ( isset( $atts['show'] ) ) {
			$show = strtotime( $atts['show'] );
		} else {
			$show = 0;
		}

		if ( isset( $atts['hide'] ) ) {
			$hide = strtotime( $atts['hide'] );
		} else {
			$hide = PHP_INT_MAX;
		}

		$now_gmt = intval( gmdate( 'U' ) );
		if ( ( $now_gmt > $show ) && ( $hide > $now_gmt ) ) {

			// Render the template which wraps $content in a span so JavaScript can hide/show cached content
			ob_start();
			include trailingslashit( GREATER_MEDIA_TIMED_CONTENT_PATH ) . 'tpl/timed-content-render.tpl.php';

			return ob_get_clean();

		} else {
			return '';
		}

	}

}

$GreaterMediaTimedContentShortcode = new GreaterMediaTimedContentShortcode();

