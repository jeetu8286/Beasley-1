<?php
/**
 * Allow posts to be excluded from home page searches.
 *
 * @package GreaterMedia\HomepageCuration
 */

namespace GreaterMedia\HomepageCuration\KeepOffHomepage;

const NONCE_NAME = '_keep-off-homepage-nonce';
const NONCE_STRING = 'keep-off-homepage';
const META_KEY = 'keep-off-homepage';

function add_meta_boxes() {
	$screens = apply_filters( 'gmr-homepage-exclude-post-types', [ 'post' ] );
	add_meta_box( 'keep-off-homepage', 'Keep Off Homepage', __NAMESPACE__ . '\render_meta_box', $screens, 'side' );
}
add_action( 'add_meta_boxes', __NAMESPACE__ . '\add_meta_boxes' );

function render_meta_box() {
	\GreaterMedia\HomepageCuration\load_template( 'metabox-keep-off-homepage.php' );
}

function save_meta( $post_id ) {
	$allowed_types = apply_filters( 'gmr-homepage-exclude-post-types', [ 'post' ] );
	$post          = get_post( $post_id );
	if (
		! in_array( $post->post_type, $allowed_types ) ||
	  ! isset( $_POST[ NONCE_NAME ] ) || // PHPCS: input var ok.
	  ! wp_verify_nonce( $_POST[ NONCE_NAME ], NONCE_STRING ) || // PHPCS: input var ok | sanitization ok.
		wp_is_post_autosave( $post ) ||
	  wp_is_post_revision( $post )
	) {
		return;
	}

	if ( isset( $_POST[ META_KEY ] ) ) { // PHPCS: input var ok.
		add_post_meta( $post_id, META_KEY, true );
	} else {
		delete_post_meta( $post_id, META_KEY );
	}
}
add_action( 'save_post', __NAMESPACE__ . '\save_meta' );

function ignore_posts( $query ) {

	if ( ! $query->is_home() || ! $query->is_main_query() ) {
		return;
	}

	$meta_query   = $query->get( 'meta_query' );
	$meta_query[] = [
		'key'     => META_KEY,
		'compare' => 'NOT EXISTS',
	];
	$query->set( 'meta_query', $meta_query );
}
add_action( 'pre_get_posts', __NAMESPACE__ . '\ignore_posts' );
