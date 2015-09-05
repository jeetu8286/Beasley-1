<?php

namespace GreaterMedia\HomepageCuration;

use \WP_Query;

add_action( 'init', __NAMESPACE__ . '\register_homepage_cpt' );

/**
* Registers Homepage post type
*
* @param string  Post type key, must not exceed 20 characters
* @param array|string  See optional args description above.
* @return object|WP_Error the registered post type object, or an error object
*/
function register_homepage_cpt() {

	$labels = array(
		'name'                => __( 'Homepages', 'greatermedia' ),
		'singular_name'       => __( 'Homepage', 'greatermedia' ),
		'add_new'             => _x( 'Add New Homepage', 'greatermedia', 'greatermedia' ),
		'add_new_item'        => __( 'Add New Homepage', 'greatermedia' ),
		'edit_item'           => __( 'Edit Homepage', 'greatermedia' ),
		'new_item'            => __( 'New Homepage', 'greatermedia' ),
		'view_item'           => __( 'View Homepage', 'greatermedia' ),
		'search_items'        => __( 'Search Homepages', 'greatermedia' ),
		'not_found'           => __( 'No Homepages found', 'greatermedia' ),
		'not_found_in_trash'  => __( 'No Homepages found in Trash', 'greatermedia' ),
		'parent_item_colon'   => __( 'Parent Homepage:', 'greatermedia' ),
		'menu_name'           => __( 'Homepages', 'greatermedia' ),
	);

	$args = array(
		'labels'                   => $labels,
		'hierarchical'        => false,
		'description'         => 'description',
		'taxonomies'          => array(),
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-admin-home',
		'show_in_nav_menus'   => true,
		'publicly_queryable'  => true,
		'exclude_from_search' => false,
		'has_archive'         => true,
		'query_var'           => true,
		'can_export'          => true,
		'rewrite'             => true,
		'capability_type'     => 'post',
		'supports'            => array( 'title' ),
		'register_meta_box_cb' => __NAMESPACE__ . '\register_meta_boxes',
	);

	register_post_type( gmr_homepages_slug(), $args );
}

function gmr_homepages_slug() {
	return 'gmr_homepage';
}


function register_meta_boxes( $homepage ) {
	add_meta_box( 'featured-meta-box', 'Featured', __NAMESPACE__ . '\render_source_meta_box', $homepage->post_type, 'normal', 'high' );
	add_meta_box( 'dont-miss-meta-box', 'Don\'t miss', __NAMESPACE__ . '\render_source_meta_box', $homepage->post_type, 'normal', 'high' );
	add_meta_box( 'events-meta-box', 'Events', __NAMESPACE__ . '\render_source_meta_box', $homepage->post_type, 'normal', 'high' );
}

function render_source_meta_box( $homepage ) {
	$post_ids = get_post_meta( $homepage->ID, 'homepage_featured_post_ids', true );

	render_post_picker( 'featured-post-ids', $post_ids, array(
		'limit'                   => 5,
		'show_numbers'            => true,
		'show_icons'              => true,
		'show_recent_select_list' => true,
		'args'                    => array( 'post_type' => __NAMESPACE__ . '\get_supported_post_types' ),
	) );
}

function render_post_picker( $name, $value, $options = array() ) {
	if ( class_exists( 'NS_Post_Finder' ) ) {
		\NS_Post_Finder::render( $name, $value, $options );
	} else {
		?><p>The Post Finder plugin was not found.</p><?php
	}
}

function get_supported_post_types() {
	return (array) apply_filters( 'gmr_homepage_curation_supported_post_types', array( 'post' ), __NAMESPACE__ . '\gmr_homepages_slug' );
}