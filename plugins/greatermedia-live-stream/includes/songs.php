<?php

// action hooks
add_action( 'init', 'gmr_songs_register_post_type' );

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
		'name'                => 'Songs',
		'singular_name'       => 'Song',
		'menu_name'           => 'Songs',
		'parent_item_colon'   => 'Parent Song:',
		'all_items'           => 'All Songs',
		'view_item'           => 'View Song',
		'add_new_item'        => 'Add New Song',
		'add_new'             => 'Add New',
		'edit_item'           => 'Edit Song',
		'update_item'         => 'Update Song',
		'search_items'        => 'Search Songs',
		'not_found'           => 'Not found',
		'not_found_in_trash'  => 'Not found in Trash',
	);

	$rewrite = array(
		'slug'                => 'song',
		'with_front'          => true,
		'pages'               => true,
		'feeds'               => true,
	);

	$args = array(
		'label'               => 'Songs',
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'author', 'thumbnail' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-format-audio',
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'rewrite'             => $rewrite,
		'capability_type'     => 'post',
	);

	register_post_type( GMR_SONG_CPT, $args );
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