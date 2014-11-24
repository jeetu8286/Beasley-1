<?php

// action hooks
add_action( 'gmr_quickpost_add_metaboxes', 'gmi_personalities_add_quickpost_meta_box' );
// filter hooks
add_filter( 'gmr_quickpost_post_data', 'gmi_personalities_filter_quickpost_data' );

/**
 * Adds the meta box container for personality info.
 *
 * @action gmr_quickpost_add_metaboxes
 * @param string $screen_id The quickpost screen id.
 */
function gmi_personalities_add_quickpost_meta_box( $screen_id ) {
	add_meta_box( 'personalities_meta_box', __( 'Personalities', GMI_Personality::CPT_SLUG ), 'gmi_personalities_render_quickpost_meta_box', $screen_id, 'side', 'high' );
}

/**
 * Renders personalities meta box for quickpost popup.
 *
 * @param array $args The meta box arguments.
 */
function gmi_personalities_render_quickpost_meta_box( $args ) {
	require_once ABSPATH . 'wp-admin/includes/meta-boxes.php';

	// set global post object 
	$GLOBALS['post'] = get_post( $args['post_id'] );

	// activate default personalities filter
	add_filter( 'wp_get_object_terms', 'gmi_personalities_set_quickpost_defaults' );
	// render personalities metabox
	post_categories_meta_box( get_post( $args['post_id'] ), array( 'args' => array( 'taxonomy' => GMI_Personality::SHADOW_TAX_SLUG ) ) );
	// deactivate default personalities filter
	remove_filter( 'wp_get_object_terms', 'gmi_personalities_set_quickpost_defaults' );
}

/**
 * Filters quickpost post data before saving into database. Casts personality term ids to int.
 *
 * @filter gmr_quickpost_post_data
 * @param array $post The post data.
 * @return array The post data.
 */
function gmi_personalities_filter_quickpost_data( $post ) {
	if ( ! empty( $post['tax_input'][GMI_Personality::SHADOW_TAX_SLUG] ) ) {
		$post['tax_input'][GMI_Personality::SHADOW_TAX_SLUG] = array_map( 'intval', $post['tax_input'][GMI_Personality::SHADOW_TAX_SLUG] );
	}
	
	return $post;
}

/**
 * Sets default personalities for the quickpost form.
 *
 * @filter wp_get_object_terms
 * @param array $terms The initial array of personalities.
 * @return array The extended arra of peronalities.
 */
function gmi_personalities_set_quickpost_defaults( $terms ) {
	$user_personality = get_user_option( 'personality_taxonomy_id' );
	if ( $user_personality ) {
		$term = get_term_by( 'id', $user_personality, GMI_Personality::SHADOW_TAX_SLUG );
		if ( $term ) {
			$terms[] = $term->term_id;
		}
	}

	return $terms;
}