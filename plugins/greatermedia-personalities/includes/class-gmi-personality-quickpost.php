<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

if ( ! class_exists( "GMI_Personality_QuickPost" ) ) :
	class GMI_Personality_QuickPost {

		/**
		 * Constructor.
		 */
		protected function __construct() {
			add_action( 'quickpost_add_metaboxes', array( $this, 'add_quickpost_meta_box' ) );
		}
		
		/**
		 * Adds the meta box container for personality info.
		 *
		 * @param string $screen_id The quickpost screen id.
		 */
		public function add_quickpost_meta_box( $screen_id ) {
			add_meta_box( 'personalities_meta_box', __( 'Personalities', GMI_Personality::CPT_SLUG ), array( $this, 'render_quickpost_meta_box' ), $screen_id, 'side', 'high' );
		}

		/**
		 * Renders personalities meta box for quickpost popup.
		 */
		public function render_quickpost_meta_box( $args ) {
			require_once ABSPATH . 'wp-admin/includes/meta-boxes.php';
			
			post_categories_meta_box( get_post( $args['post_id'] ), array(
				'args' => array(
					'taxonomy' => GMI_Personality::SHADOW_TAX_SLUG,
				),
			) );
		}

		/**
		 * Class initialization function.
		 *
		 * @return GMI_Personality|mixed
		 */
		public static function init() {
			return new static();
		}
		
	}

	$gmi_personalities_quickpost = GMI_Personality_QuickPost::init();
endif;