<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

/**
 * Class GreaterMediaContests
 * @see  https://core.trac.wordpress.org/ticket/12668#comment:27
 * @TODO abstract GreaterMediaContestEntry into its own class?
 */
class GreaterMediaContests {

	const COMMENT_TYPE = 'contest_entry';

	function __construct() {

		add_action( 'init', array( $this, 'register_contest_post_type' ) );
		add_action( 'init', array( $this, 'register_contest_type_taxonomy' ) );

		// Hide the custom comment type from queries
		add_filter( 'comments_clauses', array( $this, 'comments_clauses' ), 10, 2 );
		add_filter( 'comment_feed_where', array( $this, 'comment_feed_where' ), 10, 2 );
		add_filter( 'wp_count_comments', array( $this, 'wp_count_comments' ), 10, 2 );

	}

	/**
	 * Register a Custom Post Type representing a contest
	 * @uses register_post_type
	 */
	public function register_contest_post_type() {

		$labels = array(
			'name'               => _x( 'Contests', 'Post Type General Name', 'greatermedia_contests' ),
			'singular_name'      => _x( 'Contest', 'Post Type Singular Name', 'greatermedia_contests' ),
			'menu_name'          => __( 'Contests', 'greatermedia_contests' ),
			'parent_item_colon'  => __( 'Parent Contest:', 'greatermedia_contests' ),
			'all_items'          => __( 'All Contests', 'greatermedia_contests' ),
			'view_item'          => __( 'View Contest', 'greatermedia_contests' ),
			'add_new_item'       => __( 'Add New Contest', 'greatermedia_contests' ),
			'add_new'            => __( 'Add New', 'greatermedia_contests' ),
			'edit_item'          => __( 'Edit Contest', 'greatermedia_contests' ),
			'update_item'        => __( 'Update Contest', 'greatermedia_contests' ),
			'search_items'       => __( 'Search Contests', 'greatermedia_contests' ),
			'not_found'          => __( 'Not found', 'greatermedia_contests' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'greatermedia_contests' ),
		);
		$args   = array(
			'label'               => __( 'contest', 'greatermedia_contests' ),
			'description'         => __( 'Contest', 'greatermedia_contests' ),
			'labels'              => $labels,
			'supports'            => array( 'title', ),
			'taxonomies'          => array( 'contest_type' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
		);

		register_post_type( 'contest', $args );

	}

	/**
	 * Register a custom taxonomy representing Contest Types
	 * @uses register_taxonomy
	 */
	public function register_contest_type_taxonomy() {

		$labels = array(
			'name'                       => _x( 'Contest Types', 'Taxonomy General Name', 'greatermedia_contests' ),
			'singular_name'              => _x( 'Contest Type', 'Taxonomy Singular Name', 'greatermedia_contests' ),
			'menu_name'                  => __( 'Contest Type', 'greatermedia_contests' ),
			'all_items'                  => __( 'All Contest Types', 'greatermedia_contests' ),
			'parent_item'                => __( 'Parent Contest Type', 'greatermedia_contests' ),
			'parent_item_colon'          => __( 'Parent Contest Type:', 'greatermedia_contests' ),
			'new_item_name'              => __( 'New Contest Type Name', 'greatermedia_contests' ),
			'add_new_item'               => __( 'Add New Contest Type', 'greatermedia_contests' ),
			'edit_item'                  => __( 'Edit Contest Type', 'greatermedia_contests' ),
			'update_item'                => __( 'Update Contest Type', 'greatermedia_contests' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'greatermedia_contests' ),
			'search_items'               => __( 'Search Contest Types', 'greatermedia_contests' ),
			'add_or_remove_items'        => __( 'Add or remove contest types', 'greatermedia_contests' ),
			'choose_from_most_used'      => __( 'Choose from the most used contest types', 'greatermedia_contests' ),
			'not_found'                  => __( 'Not Found', 'greatermedia_contests' ),
		);

		$args = array(
			'labels'            => $labels,
			// The data isn't hierarchical. This is just to make WP display checkboxes instead of free-form text entry
			'hierarchical'      => true,
			'public'            => false,
			'show_ui'           => true,
			'show_admin_column' => false,
			'show_in_nav_menus' => false,
			'show_tagcloud'     => false,
		);

		register_taxonomy( 'contest_type', array( 'contest' ), $args );

		$this->maybe_seed_contest_type_taxonomy();

	}

	/**
	 * Populate the initial records in the Contest Type taxonomy
	 *
	 * @uses wp_insert_term
	 * @uses get_option
	 * @uses set_option
	 */
	public function maybe_seed_contest_type_taxonomy() {

		$seeded = get_option( 'contest_type_seeded', false );

		if ( true === $seeded ) {
			return;
		}

		wp_insert_term(
			'On Air',
			'contest_type',
			array(
				'description' => 'On-air contests generally require a call or, perhaps, text message, from the entrant. The specific requirements and number to text or call can be written directly in the "how to enter" section of the contest.',
			)
		);

		wp_insert_term(
			'Online',
			'contest_type',
			array(
				'description' => '',
			)
		);

		update_option( 'contest_type_seeded', true );

		if ( class_exists( 'GreaterMediaAdminNotifier' ) ) {
			GreaterMediaAdminNotifier::message( __( 'Seeded "Contest Types" taxonomy.', 'greatermedia_contests' ) );
		}

	}

	/**
	 * Exclude notes (comments) on edd_payment post type from showing in Recent
	 * Comments widgets
	 *
	 * @param array $clauses          Comment clauses for comment query
	 * @param obj   $wp_comment_query WordPress Comment Query Object
	 *
	 * @return array $clauses Updated comment clauses
	 */
	public function comments_clauses( $clauses, $wp_comment_query ) {

		global $wpdb;

		$clauses['where'] .= sprintf( ' AND comment_type != "%s"', self::COMMENT_TYPE );

		return $clauses;

	}


	/**
	 * Exclude notes (comments) on edd_payment post type from showing in comment feeds
	 *
	 * @param array $where
	 * @param obj   $wp_comment_query WordPress Comment Query Object
	 *
	 * @return array $where
	 */
	public function comment_feed_where( $where, $wp_comment_query ) {

		global $wpdb;

		$where .= $wpdb->prepare( " AND comment_type != %s", self::COMMENT_TYPE );

		return $where;

	}


	/**
	 * Remove EDD Comments from the wp_count_comments function
	 *
	 * @access public
	 * @param array $stats   (empty from core filter)
	 * @param int   $post_id Post ID
	 *
	 * @return array Array of comment counts
	 */
	public function wp_count_comments( $stats, $post_id ) {

		global $wpdb, $pagenow;

		$post_id = (int) $post_id;

		$stats = wp_cache_get( "comments-{$post_id}", 'counts' );

		if ( false !== $stats ) {
			return $stats;
		}

		$where = sprintf( 'WHERE comment_type != "%s"', self::COMMENT_TYPE );

		if ( $post_id > 0 ) {
			$where .= $wpdb->prepare( " AND comment_post_ID = %d", $post_id );
		}

		$count = $wpdb->get_results( "SELECT comment_approved, COUNT( * ) AS num_comments FROM {$wpdb->comments} {$where} GROUP BY comment_approved", ARRAY_A );

		$total    = 0;
		$approved = array( '0' => 'moderated', '1' => 'approved', 'spam' => 'spam', 'trash' => 'trash', 'post-trashed' => 'post-trashed' );
		foreach ( (array) $count as $row ) {
			// Don't count post-trashed toward totals
			if ( 'post-trashed' != $row['comment_approved'] && 'trash' != $row['comment_approved'] ) {
				$total += $row['num_comments'];
			}
			if ( isset( $approved[$row['comment_approved']] ) ) {
				$stats[$approved[$row['comment_approved']]] = $row['num_comments'];
			}
		}

		$stats['total_comments'] = $total;
		foreach ( $approved as $key ) {
			if ( empty( $stats[$key] ) ) {
				$stats[$key] = 0;
			}
		}

		$stats = (object) $stats;
		wp_cache_set( "comments-{$post_id}", $stats, 'counts' );

		return $stats;

	}

	/**
	 * Convenience method for inserting a "comment" representing a contest entry
	 *
	 * @param array $data
	 * @return int comment ID
	 *
	 * @uses wp_insert_comment
	 */
	public static function insert_contest_entry( array $data ) {

		$data['comment_type'] = self::COMMENT_TYPE;

		$data = apply_filters('gm_contest_entry_data', $data);

		return wp_insert_comment( $data );

	}

}

$GreaterMediaContests = new GreaterMediaContests();
