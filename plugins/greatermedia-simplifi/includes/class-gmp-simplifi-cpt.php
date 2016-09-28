<?php
/**
 * Class GMP_SIMPLIFI_CPT
 *
 * This class creates the required `Simplifi Pixels` Custom Post Types.
 *
 */
class GMP_SIMPLIFI_CPT {

	const SIMPLIFI_PIXEL_POST_TYPE = 'simplifi_pixel'; // todo fix all instances where this is hard coded to use this constant, then NAMESPACE

	/**
	 * Hook into the appropriate actions when the class is initiated.
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'simplifi_pixels_cpt' ), 100 );
		add_action( 'edit_form_after_title', array( __CLASS__, 'inline_instructions' ) );
		add_action( 'wp_footer', array( __CLASS__, 'render_simplifi_tags' ) );
	}

	/**
	 * Add the Podcast Custom Post Type
	 */
	public static function simplifi_pixels_cpt() {

		$labels = array(
			'name'                => _x( 'Simplifi Pixels', 'Post Type General Name', 'gmsimplifi_pixels' ),
			'singular_name'       => _x( 'Pixel', 'Post Type Singular Name', 'gmsimplifi_pixels' ),
			'menu_name'           => __( 'Simplifi Pixels', 'gmsimplifi_pixels' ),
			'parent_item_colon'   => __( 'Parent Item:', 'gmsimplifi_pixels' ),
			'all_items'           => __( 'Simplifi Pixels', 'gmsimplifi_pixels' ),
			'view_item'           => __( 'View Pixel', 'gmsimplifi_pixels' ),
			'add_new_item'        => __( 'Add New Pixel', 'gmsimplifi_pixels' ),
			'add_new'             => __( 'Add New', 'gmsimplifi_pixels' ),
			'edit_item'           => __( 'Edit Pixel', 'gmsimplifi_pixels' ),
			'update_item'         => __( 'Update Pixel', 'gmsimplifi_pixels' ),
			'search_items'        => __( 'Search Pixels', 'gmsimplifi_pixels' ),
			'not_found'           => __( 'Not found', 'gmsimplifi_pixels' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'gmsimplifi_pixels' ),
		);
		$args = array(
			'label'               => __( 'simplifi_pixels', 'gmsimplifi_pixels' ),
			'description'         => __( 'A post type for Simplifi Pixels', 'gmsimplifi_pixels' ),
			'labels'              => $labels,
			'supports'            => array( 'title' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => false,
			'menu_position'       => 100,
			'menu_icon'           => 'dashicons-tag',
			'can_export'          => false,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'rewrite'             => false,
			'capability_type'     => array( 'simplifi_pixel', 'simplifi_pixels' ),
			'map_meta_cap'        => true,
		);
		register_post_type( self::SIMPLIFI_PIXEL_POST_TYPE, $args );

	}

	/**
	 * Output instructions on creating a podcast episode.
	 */
	public static function inline_instructions( $post ) {

		// These instructions are about adding audio when the overwhelming purpose of the post is audio
		// therefore, it's only applicable to podcast episodes.
		if ( self::SIMPLIFI_PIXEL_POST_TYPE !== $post->post_type ) {
			return;
		}

		?>
		<h3>Simplifi Pixel Placement Instructions</h3>
		<ol>
			<li>Enter the pixel parameters as needed below.</li>
			<li>Leave any unused pixel parameters blank.</li>
			<li>Publishing the pixel will enable it on the public site.</li>
		</ol>

		<?php

	}

	/**
	 * Output instructions on creating a podcast episode.
	 */
	public static function render_simplifi_tags() {
		$cached_simplifi_tags = get_transient( 'simplifi_tags' );

		if ( false === $cached_simplifi_tags ){
			// need to generate the latest tags.

			$query_args = array(
				'post_type'      => array( self::SIMPLIFI_PIXEL_POST_TYPE ),
				'orderby'        => 'date',
				'order'          => 'DESC',
				'post_status'		 => 'publish',
				'no_found_rows'  => true,
				'fields' 				 => 'ID',
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false
			);

			$query = new WP_Query( $query_args );

			$pixel_tags = '';

			while( $query->have_posts() ) {
				$pixel = $query->next_post();

				$pixel_tags .= '<script async src="https://i.simpli.fi/dpx.js?';

				$cid = sanitize_text_field( get_post_meta( $pixel->ID, 'gmp_simplifi_pixels_cid', true ) );
				$action = sanitize_text_field( get_post_meta( $pixel->ID, 'gmp_simplifi_pixels_action', true ) );
				$segment = sanitize_text_field( get_post_meta( $pixel->ID, 'gmp_simplifi_pixels_segment', true ) );
				$m = sanitize_text_field( get_post_meta( $pixel->ID, 'gmp_simplifi_pixels_m', true ) );
				$conversion = sanitize_text_field( get_post_meta( $pixel->ID, 'gmp_simplifi_pixels_conversion', true ) );
				$tid = sanitize_text_field( get_post_meta( $pixel->ID, 'gmp_simplifi_pixels_tid', true ) );
				$c = sanitize_text_field( get_post_meta( $pixel->ID, 'gmp_simplifi_pixels_c', true ) );
				$campaign_id = sanitize_text_field( get_post_meta( $pixel->ID, 'gmp_simplifi_pixels_campaign_id', true ) );
				$sifi_tuid = sanitize_text_field( get_post_meta( $pixel->ID, 'gmp_simplifi_pixels_sifi_tuid', true ) );

				$data = array();

				if ( !empty( $cid ) ){
					$data['cid'] = $cid;
				}

				if ( !empty( $action ) ){
					$data['action'] = $action;
				}

				if ( !empty( $segment ) ){
					$data['segment'] = $segment;
				}

				if ( !empty( $m ) ){
					$data['m'] = $m;
				}

				if ( !empty( $conversion ) ){
					$data['conversion'] = $conversion;
				}

				if ( !empty( $tid ) ){
					$data['tid'] = $tid;
				}

				if ( !empty( $c ) ){
					$data['c'] = $c;
				}

				if ( !empty( $campaign_id ) ){
					$data['campaign_id'] = $campaign_id;
				}

				if ( !empty( $sifi_tuid ) ){
					$data['sifi_tuid'] = $sifi_tuid;
				}

				$pixel_tags .= http_build_query($data);

				$pixel_tags .= '"></script>';
			}

			$cached_simplifi_tags = $pixel_tags;
			set_transient( 'simplifi_tags', $cached_simplifi_tags, 1 * DAY_IN_SECONDS );
		}

		echo $cached_simplifi_tags;
	}

}

GMP_SIMPLIFI_CPT::init();
