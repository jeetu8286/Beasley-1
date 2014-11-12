<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

/**
 * Class GreaterMediaSongs
 *
 * Creates a content type for Songs
 *
 * @since 0.1.0
 */
class GreaterMediaSongs {

	/**
	 * Initiate actions and filters
	 *
	 * @since 0.1.0
	 */
	public static function init() {

		add_action( 'init', array( __CLASS__, 'register_songs_post_type' ) );
		//add_filter( 'gmr_show_widget_item_post_types', array( __CLASS__, 'gmr_ll_add_show_widget_post_types' ) );

	}

	/**
	 * Register the Songs CPT
	 *
	 * @uses register_post_type
	 *
	 * @since 0.1.0
	 */
	public static function register_songs_post_type() {

		$labels = array(
			'name'                => _x( 'Songs', 'Post Type General Name', 'greatermedia_songs' ),
			'singular_name'       => _x( 'Song', 'Post Type Singular Name', 'greatermedia_songs' ),
			'menu_name'           => __( 'Songs', 'greatermedia_songs' ),
			'parent_item_colon'   => __( 'Parent Song:', 'greatermedia_songs' ),
			'all_items'           => __( 'All Songs', 'greatermedia_songs' ),
			'view_item'           => __( 'View Song', 'greatermedia_songs' ),
			'add_new_item'        => __( 'Add New Song', 'greatermedia_songs' ),
			'add_new'             => __( 'Add New', 'greatermedia_songs' ),
			'edit_item'           => __( 'Edit song', 'greatermedia_songs' ),
			'update_item'         => __( 'Update song', 'greatermedia_songs' ),
			'search_items'        => __( 'Search Songs', 'greatermedia_songs' ),
			'not_found'           => __( 'Not found', 'greatermedia_songs' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'greatermedia_songs' ),
		);

		$rewrite = array(
			'slug'                => 'song',
			'with_front'          => true,
			'pages'               => true,
			'feeds'               => true,
		);

		$args = array(
			'label'               => __( 'songs', 'greatermedia_songs' ),
			'description'         => __( 'Live Songs', 'greatermedia_songs' ),
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
		
		register_post_type( 'songs', $args );

	}

	/**
	 * Adds the songs post types to the available post types to be queried by the shows widget
	 *
	 * @filter gmr_show_widget_item_post_types
	 * @param array $post_types The post types array.
	 * @return array The post types array.
	 */
	public static function add_songs_shows_widget( $post_types ) {
		$post_types[] = 'songs';
		return $post_types;
	}
}

GreaterMediaSongs::init();
