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
			'name'                => _x( 'Songs', 'Post Type General Name', 'greater_media_songs' ),
			'singular_name'       => _x( 'Song', 'Post Type Singular Name', 'greater_media_songs' ),
			'menu_name'           => __( 'Songs', 'greater_media_songs' ),
			'parent_item_colon'   => __( 'Parent Song:', 'greater_media_songs' ),
			'all_items'           => __( 'All Songs', 'greater_media_songs' ),
			'view_item'           => __( 'View Song', 'greater_media_songs' ),
			'add_new_item'        => __( 'Add New Song', 'greater_media_songs' ),
			'add_new'             => __( 'Add New', 'greater_media_songs' ),
			'edit_item'           => __( 'Edit song', 'greater_media_songs' ),
			'update_item'         => __( 'Update song', 'greater_media_songs' ),
			'search_items'        => __( 'Search Songs', 'greater_media_songs' ),
			'not_found'           => __( 'Not found', 'greater_media_songs' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'greater_media_songs' ),
		);

		$rewrite = array(
			'slug'                => 'song',
			'with_front'          => true,
			'pages'               => true,
			'feeds'               => true,
		);

		$args = array(
			'label'               => __( 'songs', 'greater_media_songs' ),
			'description'         => __( 'Live Songs', 'greater_media_songs' ),
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
