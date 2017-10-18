<?php

/**
 * Generic Support class
 *
 * @since 0.1.0
 *
 */
class Instant_Articles_Generic {

	/**
	 * Init the compat layer
	 *
	 */
	function setup() {
		add_filter( 'instant_articles_transformer_rules_loaded', array( $this, 'transformer_loaded' ) );
		add_action( 'greatermedia-post-update', array( $this, 'invalidate_post_transformation_info_cache' ), 10, 1 );

	}

	public static function _loaded( $transformer ) {
		// Appends more rules to transformer
		$file_path     = GM_FBIA_URL . 'rules/generic-rules-configuration.json';
		$configuration = file_get_contents( $file_path );

		$transformer->loadRules( $configuration );

		return $transformer;
	}

	function invalidate_post_transformation_info_cache( $post_id ) {
		// These post metas are caches on the calculations made to decide if
		// a post is in good state to be converted to an Instant Article or not
		delete_post_meta( $post_id, '_has_warnings_after_transformation' );
		delete_post_meta( $post_id, '_is_empty_after_transformation' );
	}

}
