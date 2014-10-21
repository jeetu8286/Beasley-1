<?php
/**
 * Created by Eduard
 * Date: 15.10.2014
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

class ShowsCPT {

	public static function init() {
		add_action('init', array( __CLASS__,  'register_post_type') );
		add_action('init', array( __CLASS__,  'register_shadow_taxonomy') );
	}

	/**
	 * Registers shows post types
	 */
	public static function register_post_type() {

		$labels = array(
			'name'                => __( 'Shows', 'text-domain' ),
			'singular_name'       => __( 'Show', 'text-domain' ),
			'add_new'             => _x( 'Add New Show', 'text-domain', 'text-domain' ),
			'add_new_item'        => __( 'Add New Show', 'text-domain' ),
			'edit_item'           => __( 'Edit Show', 'text-domain' ),
			'new_item'            => __( 'New Show', 'text-domain' ),
			'view_item'           => __( 'View Show', 'text-domain' ),
			'search_items'        => __( 'Search Shows', 'text-domain' ),
			'not_found'           => __( 'No Shows found', 'text-domain' ),
			'not_found_in_trash'  => __( 'No Shows found in Trash', 'text-domain' ),
			'parent_item_colon'   => __( 'Parent Show:', 'text-domain' ),
			'menu_name'           => __( 'Shows', 'text-domain' ),
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
			'menu_position'       => null,
			'menu_icon'           => null,
			'show_in_nav_menus'   => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => false,
			'has_archive'         => true,
			'query_var'           => true,
			'can_export'          => true,
			'rewrite'             => true,
			'capability_type'     => 'post',
			'supports'            => array(
				'title', 'editor', 'author', 'thumbnail',
				'comments', 'revisions'
			)
		);

		register_post_type( 'show', $args );
	}

	/**
	 * Regsiter shadow taxonomy for shows
	 */
	public static function register_shadow_taxonomy() {

		$args = array(
			'labels'            => __( 'Shows' ),
			'public'            => false,
			'rewrite'           => false,
			'hierarchical'      => true
		);

		register_taxonomy( 'shows_shadow_taxonomy', 'show', $args );
	}

	public static function createShadowTerm( $term_title ) {
		$sanitize_title = sanitize_title($term_title);
		echo $new_url;
		wp_insert_term(
			$term_title,
			'shows_shadow_taxonomy',
			array(
				'description'=> 'Shadow term of shows shadow taxonomy.',
				'slug' => $sanitize_title,
			)
		);
	}
}

ShowsCPT::init();