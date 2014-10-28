<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

/**
 * Class GreaterMediaTaxonomies
 * @see http://generatewp.com/taxonomy/
 */
class GreaterMediaTaxonomies {

	function __construct() {

		add_action( 'init', array( $this, 'register_taxonomy_market' ) );
		add_action( 'init', array( $this, 'register_taxonomy_format' ) );
		add_action( 'init', array( $this, 'register_taxonomy_blog' ) );
		//add_action( 'init', array( $this, 'hide_categories_taxonomy' ) );

	}

	/**
	 * Register the "Market" taxonomy
	 */
	function register_taxonomy_market() {

		$labels = array(
			'name'                       => _x( 'Markets', 'Taxonomy General Name', 'greatermedia-taxonomies' ),
			'singular_name'              => _x( 'Market', 'Taxonomy Singular Name', 'greatermedia-taxonomies' ),
			'menu_name'                  => __( 'Markets', 'greatermedia-taxonomies' ),
			'all_items'                  => __( 'All Items', 'greatermedia-taxonomies' ),
			'parent_item'                => __( 'Parent Item', 'greatermedia-taxonomies' ),
			'parent_item_colon'          => __( 'Parent Item:', 'greatermedia-taxonomies' ),
			'new_item_name'              => __( 'New Item Name', 'greatermedia-taxonomies' ),
			'add_new_item'               => __( 'Add New Item', 'greatermedia-taxonomies' ),
			'edit_item'                  => __( 'Edit Item', 'greatermedia-taxonomies' ),
			'update_item'                => __( 'Update Item', 'greatermedia-taxonomies' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'greatermedia-taxonomies' ),
			'search_items'               => __( 'Search Items', 'greatermedia-taxonomies' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'greatermedia-taxonomies' ),
			'choose_from_most_used'      => __( 'Choose from the most used items', 'greatermedia-taxonomies' ),
			'not_found'                  => __( 'Not Found', 'greatermedia-taxonomies' ),
		);
		$args   = array(
			'labels'            => $labels,
			'hierarchical'      => false,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
		);
		register_taxonomy( 'market', array( 'post' ), $args );

	}

	/**
	 * Register the "Format" taxonomy
	 */
	function register_taxonomy_format() {

		$labels = array(
			'name'                       => _x( 'Formats', 'Taxonomy General Name', 'greatermedia-taxonomies' ),
			'singular_name'              => _x( 'Format', 'Taxonomy Singular Name', 'greatermedia-taxonomies' ),
			'menu_name'                  => __( 'Formats', 'greatermedia-taxonomies' ),
			'all_items'                  => __( 'All Items', 'greatermedia-taxonomies' ),
			'parent_item'                => __( 'Parent Item', 'greatermedia-taxonomies' ),
			'parent_item_colon'          => __( 'Parent Item:', 'greatermedia-taxonomies' ),
			'new_item_name'              => __( 'New Item Name', 'greatermedia-taxonomies' ),
			'add_new_item'               => __( 'Add New Item', 'greatermedia-taxonomies' ),
			'edit_item'                  => __( 'Edit Item', 'greatermedia-taxonomies' ),
			'update_item'                => __( 'Update Item', 'greatermedia-taxonomies' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'greatermedia-taxonomies' ),
			'search_items'               => __( 'Search Items', 'greatermedia-taxonomies' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'greatermedia-taxonomies' ),
			'choose_from_most_used'      => __( 'Choose from the most used items', 'greatermedia-taxonomies' ),
			'not_found'                  => __( 'Not Found', 'greatermedia-taxonomies' ),
		);
		$args   = array(
			'labels'            => $labels,
			'hierarchical'      => false,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
		);
		register_taxonomy( 'format', array( 'post' ), $args );

	}

	/**
	 * Register the "Format" taxonomy
	 */
	function register_taxonomy_blog() {

		$labels = array(
			'name'                       => _x( 'Blogs', 'Taxonomy General Name', 'greatermedia-taxonomies' ),
			'singular_name'              => _x( 'Blog', 'Taxonomy Singular Name', 'greatermedia-taxonomies' ),
			'menu_name'                  => __( 'Blogs', 'greatermedia-taxonomies' ),
			'all_items'                  => __( 'All Items', 'greatermedia-taxonomies' ),
			'parent_item'                => __( 'Parent Item', 'greatermedia-taxonomies' ),
			'parent_item_colon'          => __( 'Parent Item:', 'greatermedia-taxonomies' ),
			'new_item_name'              => __( 'New Item Name', 'greatermedia-taxonomies' ),
			'add_new_item'               => __( 'Add New Item', 'greatermedia-taxonomies' ),
			'edit_item'                  => __( 'Edit Item', 'greatermedia-taxonomies' ),
			'update_item'                => __( 'Update Item', 'greatermedia-taxonomies' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'greatermedia-taxonomies' ),
			'search_items'               => __( 'Search Items', 'greatermedia-taxonomies' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'greatermedia-taxonomies' ),
			'choose_from_most_used'      => __( 'Choose from the most used items', 'greatermedia-taxonomies' ),
			'not_found'                  => __( 'Not Found', 'greatermedia-taxonomies' ),
		);
		$args   = array(
			'labels'            => $labels,
			'hierarchical'      => false,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
		);
		register_taxonomy( 'blog', array( 'post' ), $args );

	}

	function hide_categories_taxonomy() {

		register_taxonomy('category', array());

	}

}

$GreaterMediaTaxonomies = new GreaterMediaTaxonomies();