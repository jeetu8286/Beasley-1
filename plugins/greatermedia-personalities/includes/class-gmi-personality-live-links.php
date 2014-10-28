<?php

// action hooks
add_action( 'gmr_live_link_copy_post', 'gmrp_copy_personalities_to_live_link', 10, 2 );
// filter hooks
add_filter( 'gmr_live_link_taxonomies', 'gmrp_add_live_links_taxonomy_support' );

/**
 * Adds support of personalities taxonomy to live links post type.
 *
 * @filter gmr_live_link_taxonomies
 * @param array $taxonomies The array of already supported taxonomies.
 * @return array The extended array of supported taxonomies.
 */
function gmrp_add_live_links_taxonomy_support( $taxonomies ) {
	$taxonomies[] = GMI_Personality::SHADOW_TAX_SLUG;
	return $taxonomies;
}

/**
 * Copies personality terms when a post is copied to live links.
 *
 * @action gmr_live_link_copy_post
 * @param int $ll_id The live link post id.
 * @param int $post_id The copied post id.
 */
function gmrp_copy_personalities_to_live_link( $ll_id, $post_id ) {
	$terms = wp_get_post_terms( $post_id, GMI_Personality::SHADOW_TAX_SLUG );
	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
		$terms = array_filter( (array) wp_list_pluck( $terms, 'term_id' ) );
		wp_set_post_terms( $ll_id, $terms, GMI_Personality::SHADOW_TAX_SLUG );
	}
}