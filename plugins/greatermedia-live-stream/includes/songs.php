<?php

// action hooks
add_action( 'init', 'gmr_songs_register_post_type' );
add_action( 'admin_menu', 'gmr_songs_register_admin_menu' );
add_action( 'dbx_post_advanced', 'gmr_songs_adjust_current_admin_menu' );

// filter hooks
add_filter( 'gmr_show_widget_item_post_types', 'gmr_songs_add_songs_shows_widget' );
add_filter( 'gmr_live_link_add_copy_action', 'gmr_songs_remove_copy_to_live_link_action', 10, 2 );

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
		'label'        => 'Songs',
		'labels'       => $labels,
		'public'       => false,
		'show_ui'      => true,
		'show_in_menu' => false,
		'can_export'   => false,
		'has_archive'  => false,
		'rewrite'      => false,
		'supports'     => array( 'title', 'editor', 'author', 'thumbnail' ),
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