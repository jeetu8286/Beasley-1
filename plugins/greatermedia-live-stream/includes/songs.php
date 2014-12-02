<?php

// action hooks
add_action( 'init', 'gmr_songs_register_post_type' );
add_action( 'admin_menu', 'gmr_songs_register_admin_menu' );
add_action( 'dbx_post_advanced', 'gmr_songs_adjust_current_admin_menu' );
add_action( 'save_post', 'gmr_songs_save_meta_box_data' );

// filter hooks
add_filter( 'gmr_live_link_add_copy_action', 'gmr_songs_remove_copy_to_live_link_action', 10, 2 );
add_filter( 'gmr_show_widget_item_post_types', 'gmr_songs_add_songs_shows_widget' );
add_filter( 'gmr_show_widget_item', 'gmr_songs_shows_widget_item' );

/**
 * Registers Song post type.
 *
 * @action init
 */
function gmr_songs_register_post_type() {
	$labels = array(
		'name'               => 'Songs',
		'singular_name'      => 'Song',
		'menu_name'          => 'Songs',
		'parent_item_colon'  => 'Parent Song:',
		'all_items'          => 'All Songs',
		'view_item'          => 'View Song',
		'add_new_item'       => 'Add New Song',
		'add_new'            => 'Add New',
		'edit_item'          => 'Edit Song',
		'update_item'        => 'Update Song',
		'search_items'       => 'Search Songs',
		'not_found'          => 'Not found',
		'not_found_in_trash' => 'Not found in Trash',
	);

	$args = array(
		'label'                => 'Songs',
		'labels'               => $labels,
		'public'               => false,
		'show_ui'              => true,
		'show_in_menu'         => false,
		'can_export'           => false,
		'has_archive'          => false,
		'rewrite'              => false,
		'register_meta_box_cb' => 'gmr_songs_register_meta_boxes',
		'supports'             => array( 'title' ),
	);

	register_post_type( GMR_SONG_CPT, $args );
}

/**
 * Registers "Songs" submenu in the "Live Streams" menu group.
 *
 * @action admin_menu
 */
function gmr_songs_register_admin_menu() {
	$pt = get_post_type_object( GMR_SONG_CPT );

	$parent_slug = 'edit.php?post_type=' . GMR_LIVE_STREAM_CPT;
	$submenu_slug = 'edit.php?post_type=' . $pt->name;
	
	add_submenu_page( $parent_slug, $pt->labels->all_items, $pt->labels->name, $pt->cap->create_posts, $submenu_slug );
}

/**
 * Adds the songs post types to the available post types to be queried by the shows widget
 *
 * @filter gmr_show_widget_item_post_types
 * @param array $post_types The post types array.
 * @return array The post types array.
 */
function gmr_songs_add_songs_shows_widget( $post_types ) {
	if ( ! in_array( GMR_SONG_CPT, $post_types ) ) {
		$post_types[] = GMR_SONG_CPT;
	}
	return $post_types;
}

/**
 * Selects proper admin menu items for songs pages.
 *
 * @action dbx_post_advanced
 * @global string $parent_file The current parent menu page.
 * @global string $submenu_file The current submenu page.
 * @global string $typenow The current post type.
 * @global string $pagenow The current admin page.
 */
function gmr_songs_adjust_current_admin_menu() {
	global $parent_file, $submenu_file, $typenow, $pagenow;

	if ( in_array( $pagenow, array( 'post-new.php', 'post.php' ) ) && in_array( $typenow, array( GMR_SONG_CPT, GMR_LIVE_STREAM_CPT ) ) ) {
		$parent_file = 'edit.php?post_type=' . GMR_LIVE_STREAM_CPT;
		$submenu_file = 'edit.php?post_type=' . $typenow;
	}
}

/**
 * Checks whether or not to add "Copy Live Link" action to the song posts.
 *
 * @filter gmr_live_link_add_copy_action
 * @param boolean $add_copy_action Determines whether or not to add the action.
 * @param WP_Post $post The current post object.
 * @return boolean Initial flag if a post type is not a songs pt, otherwise FALSE.
 */
function gmr_songs_remove_copy_to_live_link_action( $add_copy_action, WP_Post $post ) {
	return GMR_SONG_CPT != $post->post_type ? $add_copy_action : false;
}

/**
 * Returns show widget item content.
 *
 * @filter gmr_show_widget_item
 * @param string $item The initial item HTML.
 * @return string The song item HTML if it has song post type or initial HTML if it doesn't.
 */
function gmr_songs_shows_widget_item( $item ) {
	if ( get_post_type() != GMR_SONG_CPT ) {
		return $item;
	}

	$item = '<div class="live-link__song"><div class="live-link__song--artist">' . get_the_content() . '</div><div class="live-link__song--title">' . get_the_title() . '</div></div>';

	return $item;
}

/**
 * Saves song meta data.
 *
 * @action save_post
 * @param int $post_id The song post id.
 */
function gmr_songs_save_meta_box_data( $post_id ) {
	// validate nonce and user permissions
	$doing_autosave = defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE;
	$valid_nonce = wp_verify_nonce( filter_input( INPUT_POST, '_gmr_songs_nonce' ), 'gmr_songs_meta_boxes' );
	$can_edit = current_user_can( 'edit_post', $post_id );
	if ( $doing_autosave || ! $valid_nonce || ! $can_edit ) {
		return;
	}

	// save artist
	$artist = sanitize_text_field( filter_input( INPUT_POST, 'song_artist' ) );
	update_post_meta( $post_id, 'artist', $artist );

	// save purchase link
	$purchase_link = filter_input( INPUT_POST, 'song_purchase_link', FILTER_VALIDATE_URL );
	update_post_meta( $post_id, 'purchase_link', $purchase_link );
}

/**
 * Registers songs meta boxes.
 */
function gmr_songs_register_meta_boxes() {
	add_meta_box( 'artist', 'Artist', 'gmr_songs_render_artist_meta_box', GMR_SONG_CPT, 'normal' );
	add_meta_box( 'purchase-link', 'Purchase Link', 'gmr_songs_render_purchase_link_meta_box', GMR_SONG_CPT, 'normal' );
}

/**
 * Renders song artist meta box.
 *
 * @param WP_Post $post The song object.
 */
function gmr_songs_render_artist_meta_box( WP_Post $post ) {
	wp_nonce_field( 'gmr_songs_meta_boxes', '_gmr_songs_nonce', false );
	
	?><input type="text" name="song_artist" class="widefat" value="<?php echo esc_attr( get_post_meta( $post->ID, 'artist', true ) ) ?>">
	<p class="description">Enter artist of this song.</p><?php
}

/**
 * Renders song purchase link meta box.
 *
 * @param WP_Post $post The song object.
 */
function gmr_songs_render_purchase_link_meta_box( WP_Post $post ) {
	?><input type="text" name="song_purchase_link" class="widefat" value="<?php echo esc_attr( get_post_meta( $post->ID, 'purchase_link', true ) ) ?>">
	<p class="description">Enter iTunes link to this song.</p><?php
}