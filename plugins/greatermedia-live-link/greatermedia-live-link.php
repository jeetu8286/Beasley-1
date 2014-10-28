<?php
/*
 * Plugin Name: Greater Media Live Link
 * Description: Adds Live Link functionality.
 * Author:      10up
 * Author URI:  http://10up.com/
 */

define( 'GMR_LIVE_LINK_CPT', 'gmr-live-link' );

add_action( 'init', 'gmr_ll_register_post_type' );
add_action( 'save_post', 'gmr_ll_save_redirect_meta_box_data' );

/**
 * Registers Live Link post type.
 *
 * @action init
 * @uses 'gmr_live_link_taxonomies' filter to filter supported taxonomies.
 */
function gmr_ll_register_post_type() {
	register_post_type( GMR_LIVE_LINK_CPT, array(
		'public'               => false,
		'show_ui'              => true,
		'rewrite'              => false,
		'query_var'            => false,
		'can_export'           => false,
		'menu_position'        => 5,
		'menu_icon'            => 'dashicons-admin-links',
		'supports'             => array( 'title', 'post-formats' ),
		'taxonomies'           => apply_filters( 'gmr_live_link_taxonomies', array() ),
		'register_meta_box_cb' => 'gmr_ll_register_meta_boxes',
		'label'                => 'Live Links',
		'labels'               => array(
			'name'               => 'Live Links',
			'singular_name'      => 'Live Link',
			'menu_name'          => 'Live Links',
			'name_admin_bar'     => 'Live Link',
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add New Link',
			'new_item'           => 'New Link',
			'edit_item'          => 'Edit Link',
			'view_item'          => 'View Link',
			'all_items'          => 'All Links',
			'search_items'       => 'Search Links',
			'parent_item_colon'  => 'Parent Links:',
			'not_found'          => 'No links found.',
			'not_found_in_trash' => 'No links found in Trash.',
		),
	) );
}

/**
 * Registers meta boxes for the Live Link post type.
 *
 * @param WP_Post $post The current post instance.
 */
function gmr_ll_register_meta_boxes( WP_Post $post ) {
	add_meta_box( 'gmr-ll-redirect', 'Redirect To', 'gmr_ll_render_redirect_meta_box', null, 'normal', 'high' );
}

/**
 * Rendres "Redirect To" meta box.
 *
 * @param WP_Post $post The current post instance.
 */
function gmr_ll_render_redirect_meta_box( WP_Post $post ) {
	wp_nonce_field( 'gmr-ll-redirect', 'gmr_ll_redirect_nonce', false );

	echo '<input type="text" class="widefat" name="gmr_ll_redirect" value="', esc_attr( get_post_meta( $post->ID, 'redirect', true ) ), '">';
	echo '<p class="description">Enter link or post id to redirect to.</p>';
}

/**
 * Saves redirection link.
 *
 * @action save_post
 * @param int $post_id The post id.
 */
function gmr_ll_save_redirect_meta_box_data( $post_id ) {
	// validate nonce
	$doing_autosave = defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE;
	$valid_nonce = wp_verify_nonce( filter_input( INPUT_POST, 'gmr_ll_redirect_nonce' ), 'gmr-ll-redirect' );
	if ( $doing_autosave || ! $valid_nonce ) {
		return;
	}

	// check the user's permissions.
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// sanitize user input and update the meta field
	$redirect = sanitize_text_field( filter_input( INPUT_POST, 'gmr_ll_redirect' ) );
	update_post_meta( $post_id, 'redirect', $redirect );
}