<?php

// action hooks
add_action( 'init', 'gmr_streams_register_post_type' );
add_action( 'admin_menu', 'gmr_streams_update_admin_menu' );
add_action( 'save_post', 'gmr_streams_save_meta_box_data' );

/**
 * Registers Live Stream post type.
 *
 * @action init
 */
function gmr_streams_register_post_type() {
	register_post_type( GMR_LIVE_STREAM_CPT, array(
		'public'               => false,
		'show_ui'              => true,
		'rewrite'              => false,
		'query_var'            => false,
		'can_export'           => false,
		'menu_position'        => 5,
		'menu_icon'            => 'dashicons-format-audio',
		'supports'             => array( 'title' ),
		'register_meta_box_cb' => 'gmr_streams_register_meta_boxes',
		'label'                => 'Live Streams',
		'labels'               => array(
			'name'               => 'Live Streams',
			'singular_name'      => 'Live Stream',
			'menu_name'          => 'Live Streams',
			'name_admin_bar'     => 'Live Stream',
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add New Stream',
			'new_item'           => 'New Stream',
			'edit_item'          => 'Edit Stream',
			'view_item'          => 'View Stream',
			'all_items'          => 'Streams',
			'search_items'       => 'Search Streams',
			'parent_item_colon'  => 'Parent Streams:',
			'not_found'          => 'No links found.',
			'not_found_in_trash' => 'No links found in Trash.',
		),
	) );
}

/**
 * Registers meta boxes for Live Stream post type.
 */
function gmr_streams_register_meta_boxes() {
	add_meta_box( 'call-sign', 'Call Sign', 'gmr_streams_render_call_sign_meta_box', GMR_LIVE_STREAM_CPT, 'normal', 'high' );
	add_meta_box( 'description', 'Description', 'gmr_streams_render_description_meta_box', GMR_LIVE_STREAM_CPT, 'normal' );
}

/**
 * Renders Call Sign meta box.
 *
 * @param WP_Post $post The stream post object.
 */
function gmr_streams_render_call_sign_meta_box( WP_Post $post ) {
	wp_nonce_field( 'gmr_stream_meta_boxes', '_gmr_stream_nonce', false );

	echo '<input type="text" name="stream_call_sign" class="widefat" value="', esc_attr( get_post_meta( $post->ID, 'call_sign', true ) ), '">';
	echo '<p class="description">Enter stream call sign, for instance WRIF-FM.</p>';
}

/**
 * Renders Description meta box.
 *
 * @param WP_Post $post The stream post object.
 */
function gmr_streams_render_description_meta_box( WP_Post $post ) {
	echo '<input type="text" name="stream_description" class="widefat" value="', esc_attr( get_post_meta( $post->ID, 'description', true ) ), '">';
	echo '<p class="description">Enter short description of the stream, for instance ', esc_html( '"Detroit\'s best rock all day and night, plus Dave and Chuck the Freak in the morning"' ), '.</p>';
}

/**
 * Saves Live Stream meta box data.
 *
 * @action save_post
 * @param int $post_id The post id.
 */
function gmr_streams_save_meta_box_data( $post_id ) {
	// validate nonce
	$doing_autosave = defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE;
	$valid_nonce = wp_verify_nonce( filter_input( INPUT_POST, '_gmr_stream_nonce' ), 'gmr_stream_meta_boxes' );
	if ( $doing_autosave || ! $valid_nonce ) {
		return;
	}

	// check the user's permissions.
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// save call sign
	$call_sign = sanitize_text_field( filter_input( INPUT_POST, 'stream_call_sign' ) );
	update_post_meta( $post_id, 'call_sign', $call_sign );

	// save description
	$description = sanitize_text_field( filter_input( INPUT_POST, 'stream_description' ) );
	update_post_meta( $post_id, 'description', $description );
}

/**
 * Removes "Add New" sub menu item from "Live Streams" group.
 *
 * @action admin_menu
 */
function gmr_streams_update_admin_menu() {
	remove_submenu_page( 'edit.php?post_type=' . GMR_LIVE_STREAM_CPT, 'post-new.php?post_type=' . GMR_LIVE_STREAM_CPT );
}