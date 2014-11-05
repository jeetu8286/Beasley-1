<?php

// action hooks
add_action( 'gmr_quickpost_add_metaboxes', 'qmi_shows_add_quickpost_meta_box' );
// filter hooks
add_filter( 'gmr_quickpost_post_data', 'gmi_shows_filter_quickpost_data' );

/**
 * Adds the meta box container for shows info.
 *
 * @action gmr_quickpost_add_metaboxes
 * @param string $screen_id The quickpost screen id.
 */
function qmi_shows_add_quickpost_meta_box( $screen_id ) {
	add_meta_box( 'shows_meta_box', __( 'Shows', 'greatermedia' ), 'gmi_shows_render_quickpost_meta_box', $screen_id, 'side', 'high' );
}

/**
 * Renders shows meta box for quickpost popup.
 *
 * @param array $args The meta box arguments.
 */
function gmi_shows_render_quickpost_meta_box( $args ) {
	require_once ABSPATH . 'wp-admin/includes/meta-boxes.php';
	post_categories_meta_box( get_post( $args['post_id'] ), array( 'args' => array( 'taxonomy' => ShowsCPT::SHOW_TAXONOMY ) ) );
}

/**
 * Filters quickpost post data before saving into database. Casts show term ids to int.
 *
 * @filter gmr_quickpost_post_data
 * @param array $post The post data.
 * @return array The post data.
 */
function gmi_shows_filter_quickpost_data( $post ) {
	if ( ! empty( $post['tax_input'][ShowsCPT::SHOW_TAXONOMY] ) ) {
		$post['tax_input'][ShowsCPT::SHOW_TAXONOMY] = array_map( 'intval', $post['tax_input'][ShowsCPT::SHOW_TAXONOMY] );
	}

	return $post;
}