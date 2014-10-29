<?php

// action hooks
add_action( 'quickpost_add_metaboxes', 'qmi_add_quickpost_meta_box' );

/**
 * Adds the meta box container for personality info.
 *
 * @param string $screen_id The quickpost screen id.
 */
function qmi_add_quickpost_meta_box( $screen_id ) {
	add_meta_box( 'personalities_meta_box', __( 'Personalities', GMI_Personality::CPT_SLUG ), 'gmi_render_quickpost_meta_box', $screen_id, 'side', 'high' );
}

/**
 * Renders personalities meta box for quickpost popup.
 *
 * @param array $args The meta box arguments.
 */
function gmi_render_quickpost_meta_box( $args ) {
	require_once ABSPATH . 'wp-admin/includes/meta-boxes.php';
	post_categories_meta_box( get_post( $args['post_id'] ), array( 'args' => array( 'taxonomy' => GMI_Personality::SHADOW_TAX_SLUG ) ) );
}