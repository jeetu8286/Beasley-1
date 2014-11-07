<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

class GreaterMediaSongs {

	/**
	 * Initiate actions and filters
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_songs_post_type' ) );
	}

	/**
	 * Register the Songs CPT
	 *
	 * @uses register_post_type
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
			'supports'            => array( 'title', 'author', ),
			'taxonomies'          => array( 'category', 'post_tag' ),
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
}

GreaterMediaSongs::init();
