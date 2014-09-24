<?php

class GM_Podcasts_CPT {

	public static function init() {

		add_action( 'init', array( __CLASS__, 'podcasts_cpt' ), 0 );
		add_action( 'init', array( __CLASS__, 'episodes_cpt' ), 0 );

	}

	public static function podcasts_cpt() {

		$labels = array(
			'name'                => _x( 'Podcasts', 'Post Type General Name', 'gmpodcasts' ),
			'singular_name'       => _x( 'Podcast', 'Post Type Singular Name', 'gmpodcasts' ),
			'menu_name'           => __( 'Podcasts', 'gmpodcasts' ),
			'parent_item_colon'   => __( 'Parent Item:', 'gmpodcasts' ),
			'all_items'           => __( 'All Podcasts', 'gmpodcasts' ),
			'view_item'           => __( 'View Podcast', 'gmpodcasts' ),
			'add_new_item'        => __( 'Add New Podcast', 'gmpodcasts' ),
			'add_new'             => __( 'Add New', 'gmpodcasts' ),
			'edit_item'           => __( 'Edit Podcast', 'gmpodcasts' ),
			'update_item'         => __( 'Update Podcast', 'gmpodcasts' ),
			'search_items'        => __( 'Search Podcasts', 'gmpodcasts' ),
			'not_found'           => __( 'Not found', 'gmpodcasts' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'gmpodcasts' ),
		);
		$rewrite = array(
			'slug'                => 'podcasts',
			'with_front'          => true,
			'pages'               => true,
			'feeds'               => true,
		);
		$args = array(
			'label'               => __( 'podcasts', 'gmpodcasts' ),
			'description'         => __( 'A post type for Podcasts', 'gmpodcasts' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', ),
			'taxonomies'          => array( 'post_tag' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-microphone',
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'rewrite'             => $rewrite,
			'capability_type'     => 'page',
		);
		register_post_type( 'podcasts', $args );

	}

	public static function episodes_cpt() {

		$labels = array(
			'name'                => _x( 'Episodes', 'Post Type General Name', 'gmpodcasts' ),
			'singular_name'       => _x( 'Episode', 'Post Type Singular Name', 'gmpodcasts' ),
			'menu_name'           => __( 'Episodes', 'gmpodcasts' ),
			'parent_item_colon'   => __( 'Parent Item:', 'gmpodcasts' ),
			'all_items'           => __( 'Episodes', 'gmpodcasts' ),
			'view_item'           => __( 'View Episode', 'gmpodcasts' ),
			'add_new_item'        => __( 'Add New Episode', 'gmpodcasts' ),
			'add_new'             => __( 'Add New', 'gmpodcasts' ),
			'edit_item'           => __( 'Edit Episode', 'gmpodcasts' ),
			'update_item'         => __( 'Update Episode', 'gmpodcasts' ),
			'search_items'        => __( 'Search Episodes', 'gmpodcasts' ),
			'not_found'           => __( 'Not found', 'gmpodcasts' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'gmpodcasts' ),
		);
		$rewrite = array(
			'slug'                => 'episode',
			'with_front'          => true,
			'pages'               => true,
			'feeds'               => true,
		);
		$args = array(
			'label'               => __( 'episodes', 'gmpodcasts' ),
			'description'         => __( 'Episodes CPT', 'gmpodcasts' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', ),
			'taxonomies'          => array( 'post_tag' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => 'edit.php?post_type=podcasts',
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-media-text',
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'rewrite'             => $rewrite,
			'capability_type'     => 'page',
		);
		register_post_type( 'episodes', $args );

	}

}

GM_Podcasts_CPT::init();