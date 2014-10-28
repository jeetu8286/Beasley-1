<?php
/**
 * Created by Eduard
 * Date: 15.10.2014
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

class ShowsCPT {

	const CPT_SLUG = 'show';
	const SHADOW_TAXONOMY = 'shows_shadow_taxonomy';

	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_post_type' ) );
		add_action( 'init', array( __CLASS__, 'register_shadow_taxonomy' ) );
	}

	/**
	 * Registers shows post types
	 */
	public static function register_post_type() {
		$labels = array(
			'name'               => __( 'Shows', 'greatermedia' ),
			'singular_name'      => __( 'Show', 'greatermedia' ),
			'add_new'            => _x( 'Add New Show', 'greatermedia', 'greatermedia' ),
			'add_new_item'       => __( 'Add New Show', 'greatermedia' ),
			'edit_item'          => __( 'Edit Show', 'greatermedia' ),
			'new_item'           => __( 'New Show', 'greatermedia' ),
			'view_item'          => __( 'View Show', 'greatermedia' ),
			'search_items'       => __( 'Search Shows', 'greatermedia' ),
			'not_found'          => __( 'No Shows found', 'greatermedia' ),
			'not_found_in_trash' => __( 'No Shows found in Trash', 'greatermedia' ),
			'parent_item_colon'  => __( 'Parent Show:', 'greatermedia' ),
			'menu_name'          => __( 'Shows', 'greatermedia' ),
		);

		$args = array(
			'labels'              => $labels,
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
			'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'comments', 'revisions' ),
		);

		register_post_type( self::CPT_SLUG, $args );
	}

	/**
	 * Regsiter shadow taxonomy for shows
	 */
	public static function register_shadow_taxonomy() {

		$labels = array(
			'name' => _x( 'Show terms', 'Taxonomy Show terms', 'greatermedia' ),
			'singular_name'         => _x( 'Show term', 'Taxonomy Show term', 'greatermedia' ),
			'search_items'          => __( 'Search Show terms', 'greatermedia' ),
			'popular_items'         => __( 'Popular Show terms', 'greatermedia' ),
			'all_items'             => __( 'All Show terms', 'greatermedia' ),
			'parent_item'           => __( 'Parent Show term', 'greatermedia' ),
			'parent_item_colon'     => __( 'Parent Show term', 'greatermedia' ),
			'edit_item'             => __( 'Edit Show term', 'greatermedia' ),
			'update_item'           => __( 'Update Show term', 'greatermedia' ),
			'add_new_item'          => __( 'Add New Show term', 'greatermedia' ),
			'new_item_name'         => __( 'New Show term Name', 'greatermedia' ),
			'add_or_remove_items'   => __( 'Add or remove Show terms', 'greatermedia' ),
			'choose_from_most_used' => __( 'Choose from most used greatermedia', 'greatermedia' ),
			'menu_name'             => __( 'Show term', 'greatermedia' ),
		);

		$args = array(
			'labels'            => $labels,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_admin_column' => false,
			'hierarchical'      => false,
			'show_tagcloud'     => true,
			'show_ui'           => true,
			'query_var'         => true,
			'rewrite'           => true,
			'query_var'         => true,
			'capabilities'      => array(),
		);

		$supported_posttypes = array(
			'show',
			'post',
			'albums',
			'contest',
			'podcast',
			'personality',
			'tribe_events'
		);

		register_taxonomy( self::SHADOW_TAXONOMY, $supported_posttypes, $args );

		if ( function_exists( 'TDS\add_relationship' ) ) {
			TDS\add_relationship( 'show', 'shows_shadow_taxonomy' );
		}
	}

}

ShowsCPT::init();