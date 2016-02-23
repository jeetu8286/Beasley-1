<?php


/**
 * Class GM_FlexibleFeatureImages
 *
 * Adds meta data to a post or custom post type to allow flexible display of the feature image.
 *
 */
class GM_FlexibleFeatureImages {

	public function __construct(){

		add_action( 'post_submitbox_misc_actions', array( $this, 'post_submitbox_misc_actions' ), 1000 );
    //add_action( 'save_post', array( $this, 'save_feature_image_preference' ) );

	}

	/**
	 * Render feature image preference field in the post submitbox
	 */
	public function post_submitbox_misc_actions() {

		global $post;

		// if ( ! post_type_supports( $post->post_type, 'age-restricted-content' ) ) {
		// 	return;
		// }

		$feature_image_preference      = self::sanitize_feature_image_preference( get_post_meta( $post->ID, 'post_feature_image_preference', true ) );
		$feature_image_preference_desc = self::feature_image_preference_description( $feature_image_preference );

		include __DIR__ . '/post-submitbox-feature-image-preference.tpl.php';

	}

	/**
	 * Make sure an age restriction value is one of the accepted ones
	 *
	 * @param string $input value to sanitize
	 *
	 * @return string valid age restriction value or ''
	 */
	protected static function sanitize_feature_image_preference( $input ) {

		// Immediate check for something way wrong
		if ( ! is_string( $input ) ) {
			return '';
		}

		static $valid_values;
		if ( ! isset( $valid_values ) ) {
			$valid_values = array( 'poster', 'top', 'inline', 'none' );
		}

		// Sanitize
		if ( in_array( $input, $valid_values ) ) {
			return $input;
		} else {
			return '';
		}

	}

	/**
	 * Returns a translated description of a feature image preference
	 *
	 * @param string $feature_image_preference
	 *
	 * @return string description
	 */
	protected static function feature_image_preference_description( $feature_image_preference ) {

		if ( 'none' === $feature_image_preference ) {
			return __( 'None', 'greatermedia-feature-image-preference' );
		} else if ( 'top' === $feature_image_preference ) {
			return __( 'Top', 'greatermedia-feature-image-preference' );
		} else if ( 'inline' === $feature_image_preference ) {
			return __( 'Inline', 'greatermedia-feature-image-preference' );
		} else {
			return __( 'Poster', 'greatermedia-feature-image-preference' );
		}

	}

}

new GM_FlexibleFeatureImages();
