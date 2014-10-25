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
		add_action('before_delete_post', array( __CLASS__, 'remove_show_term_old' ) );
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

		$labels = array(
			'name'					=> _x( 'Show terms', 'Taxonomy Show terms', 'text-domain' ),
			'singular_name'			=> _x( 'Show term', 'Taxonomy Show term', 'text-domain' ),
			'search_items'			=> __( 'Search Show terms', 'text-domain' ),
			'popular_items'			=> __( 'Popular Show terms', 'text-domain' ),
			'all_items'				=> __( 'All Show terms', 'text-domain' ),
			'parent_item'			=> __( 'Parent Show term', 'text-domain' ),
			'parent_item_colon'		=> __( 'Parent Show term', 'text-domain' ),
			'edit_item'				=> __( 'Edit Show term', 'text-domain' ),
			'update_item'			=> __( 'Update Show term', 'text-domain' ),
			'add_new_item'			=> __( 'Add New Show term', 'text-domain' ),
			'new_item_name'			=> __( 'New Show term Name', 'text-domain' ),
			'add_or_remove_items'	=> __( 'Add or remove Show terms', 'text-domain' ),
			'choose_from_most_used'	=> __( 'Choose from most used text-domain', 'text-domain' ),
			'menu_name'				=> __( 'Show term', 'text-domain' ),
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

		register_taxonomy( 'shows_shadow_taxonomy', $supported_posttypes, $args );

		if( function_exists( 'TDS\add_relationship' ) ) {
			TDS\add_relationship( 'show', 'shows_shadow_taxonomy' );
		}
	}

	/*
	 * Remove term taxonomy if the show is permanently deleted
	 */
	public static function remove_show_term( $post_id ) {
		if( function_exists( 'TDS\delete_related_term' ) ) {
			TDS\delete_related_term( $post_id, 'shows_shadow_taxonomy' );
		}
	}

	public static function remove_show_term_old( $post_id ) {
		if( function_exists( 'TDS\get_related_term' ) ) {
			$term = TDS\get_related_term( $post_id );
			
			if( $term->term_id ) {
				wp_delete_term( $term->term_id, 'shows_shadow_taxonomy' );
			}
		}
	}
}

ShowsCPT::init();