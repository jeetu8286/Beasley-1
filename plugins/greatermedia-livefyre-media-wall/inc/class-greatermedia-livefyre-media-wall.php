<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

class GreaterMediaLiveFyreMediaWall {

	function __construct() {

		add_action( 'init', array( $this, 'init' ) );

	}

	public function init() {

		// Generated using http://generatewp.com/post-type/
		$labels = array(
			'name'                => _x( 'LiveFyre Media Walls', 'Post Type General Name', 'greatermedia-livefyre-media-wall' ),
			'singular_name'       => _x( 'LiveFyre Media Wall', 'Post Type Singular Name', 'greatermedia-livefyre-media-wall' ),
			'menu_name'           => __( 'Media Wall', 'greatermedia-livefyre-media-wall' ),
			'parent_item_colon'   => __( 'Parent Wall:', 'greatermedia-livefyre-media-wall' ),
			'all_items'           => __( 'All Walls', 'greatermedia-livefyre-media-wall' ),
			'view_item'           => __( 'View Wall', 'greatermedia-livefyre-media-wall' ),
			'add_new_item'        => __( 'Add New Wall', 'greatermedia-livefyre-media-wall' ),
			'add_new'             => __( 'Add a Wall', 'greatermedia-livefyre-media-wall' ),
			'edit_item'           => __( 'Edit Wall', 'greatermedia-livefyre-media-wall' ),
			'update_item'         => __( 'Update Wall', 'greatermedia-livefyre-media-wall' ),
			'search_items'        => __( 'Search Wall', 'greatermedia-livefyre-media-wall' ),
			'not_found'           => __( 'Not found', 'greatermedia-livefyre-media-wall' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'greatermedia-livefyre-media-wall' ),
		);
		$args = array(
			'label'               => __( 'livefyre_media_wall', 'greatermedia-livefyre-media-wall' ),
			'description'         => __( 'LiveFyre Media Wall', 'greatermedia-livefyre-media-wall' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'author', 'custom-fields', ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
		);
		register_post_type( 'livefyre_media_wall', $args );

	}

}

$GreaterMediaLiveFyreMediaWall = new GreaterMediaLiveFyreMediaWall();