<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

class GreaterMediaContestEntry {

	private $post;

	private function __construct( self $post_obj = null, $contest_id = null ) {

		if ( null !== $post_obj ) {
			$this->post = $post_obj;
		} else {
			$this->post = new WP_Post( new stdClass() );
		}

		if ( null !== $contest_id ) {

			if ( isset( $this->post->post_parent ) && ! empty( $this->post->post_parent ) ) {
				throw new UnexpectedValueException( 'Underlying "Contest Entry" post already has a parent Contest' );
			}

			$contest = get_post( $contest_id );
			if ( 'contest' !== $contest->post_type ) {
				throw new UnexpectedValueException( 'Contest ID passed as Parent does not reference a "Contest" post' );
			}

			$this->post->post_parent = $contest_id;

		}

	}

	/**
	 * Set up hooks that don't relate to a particular instance of this class
	 */
	public static function register_cpt() {
		add_action( 'init', array( __CLASS__, 'contest_entry' ), 0 );
	}

	/**
	 * Register Custom Post Type
	 */
	public static function contest_entry() {

		$labels = array(
			'name'               => _x( 'Contest Entry', 'Post Type General Name', 'greatermedia_contests' ),
			'singular_name'      => _x( 'Contest Entry', 'Post Type Singular Name', 'greatermedia_contests' ),
			'menu_name'          => __( 'Contest Entry', 'greatermedia_contests' ),
			'parent_item_colon'  => __( 'Parent Contest:', 'greatermedia_contests' ),
			'all_items'          => __( 'All Entries', 'greatermedia_contests' ),
			'view_item'          => __( 'View Entry', 'greatermedia_contests' ),
			'add_new_item'       => __( 'Add New Entry', 'greatermedia_contests' ),
			'add_new'            => __( 'Add New', 'greatermedia_contests' ),
			'edit_item'          => __( 'Edit Entry', 'greatermedia_contests' ),
			'update_item'        => __( 'Update Entry', 'greatermedia_contests' ),
			'search_items'       => __( 'Search Entry', 'greatermedia_contests' ),
			'not_found'          => __( 'Not found', 'greatermedia_contests' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'greatermedia_contests' ),
		);
		$args   = array(
			'label'               => __( 'contest_entry', 'greatermedia_contests' ),
			'description'         => __( 'An entry in a Contest', 'greatermedia_contests' ),
			'labels'              => $labels,
			'supports'            => array( 'title', ),
			'taxonomies'          => array( 'category' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => false,
			'menu_position'       => 5,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
		);

		register_post_type( 'contest_entry', $args );

	}

	/**
	 * Factory method to create a new GreaterMediaContestEntry object for entering a certain contest
	 *
	 * @param int $contest_id
	 *
	 * @return GreaterMediaContestEntry
	 */
	public static function create_for_contest( $contest_id ) {

		$entry = new self( null, $contest_id );

		return $entry;

	}

	/**
	 * Factory method to retrieve a GreaterMediaContestEntry object for a given post ID
	 *
	 * @param int $post_id
	 *
	 * @return GreaterMediaContestEntry
	 * @throws UnexpectedValueException
	 */
	public static function for_post_id( $post_id ) {

		$entry_post = get_post( $post_id );
		if ( 'contest_entry' !== $entry_post->post_type ) {
			throw new UnexpectedValueException( 'Post ID passed does not reference a "Contest" post' );
		}

		$entry = new self( $entry_post );

		return $entry;

	}

}

GreaterMediaContestEntry::register_cpt();