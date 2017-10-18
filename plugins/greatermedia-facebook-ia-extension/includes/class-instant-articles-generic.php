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
	}

	public static function transformer_loaded( $transformer ) {
		// Appends more rules to transformer
		$file_path     = GM_FBIA_URL . 'rules/generic-rules-configuration.json';
		$configuration = file_get_contents( $file_path );

		$transformer->loadRules( $configuration );

		return $transformer;
	}
}
