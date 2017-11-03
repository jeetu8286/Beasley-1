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

		add_action( 'fp_created_post', array( $this, 'invalidate_post_transformation_info_cache' ), 20, 1 );
		add_action( 'fp_updated_post', array( $this, 'invalidate_post_transformation_info_cache' ), 20, 1 );

		add_action( 'instant_articles_before_transform_post', array( $this, 'start' ) );
		add_action( 'instant_articles_after_transform_post', array( $this, 'end' ) );
	}

	public static function transformer_loaded( $transformer ) {
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
		delete_transient( 'instantarticles_mod_' . $post_id );
	}

	function start() {
		add_filter( 'the_content', array( $this, 'the_content' ), 10, 1 );
	}

	function end() {
		remove_filter( 'the_content', array( $this, 'the_content' ), 10, 1 );
	}

	function the_content( $content ) {
		// width have %% to skip facebook-instant-article replacement of %
		$listen_now = sprintf( '<iframe class="no-margin">
    							<a href="%1$s" rel="noopener"><img  id="listenNow" src="%2$s" /></a>
    							<script>
    							var listenNowImage = document.getElementById("listenNow");
								if(listenNowImage && listenNowImage.style) {
								    listenNowImage.style.width = \'100%%\';
								}
								</script>
    						</iframe>', esc_url( home_url() . '#listen-live' ), esc_url( GM_FBIA_URL . 'images/listen-live-btn.png' ) );

		return $content . $listen_now;
	}
}
