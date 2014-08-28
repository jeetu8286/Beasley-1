<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

/**
 * Class GreaterMediaUserGeneratedContent
 * Implements functionality for Listener Submissions / User-Generated Content and can be instantiated to represent a
 * particular post.
 *
 * To change how UGC is rendered in different contexts, subclass this class and name your new class
 * 'GreaterMediaUserGenerated{$post_format}' (i.e. 'GreaterMediaUserGeneratedGallery') then override the
 * render_* methods.
 */
class GreaterMediaUserGeneratedContent {

	protected $post_id;
	protected $post;

	/**
	 * Constructor is protected so it's only called from the factory method for_post_id() or a child class
	 *
	 * @param int $post_id
	 */
	protected function __construct( $post_id ) {

		$this->post_id = $post_id;
		$this->post    = get_post( $post_id );

	}

	/**
	 * Set up hooks to register the custom post type and add its screens to the admin menus
	 */
	public static function register_cpt() {

		add_action( 'init', array( __CLASS__, 'user_generated_content' ), 0 );
		add_action( 'init', array( __CLASS__, 'admin_endpoints' ) );
		add_action( 'wp', array( __CLASS__, 'wp' ), - 100 );
		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ), 0 );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_enqueue_scripts' ) );

	}

	/**
	 * Register the custom post type
	 */
	public static function user_generated_content() {

		$labels = array(
			'name'               => _x( 'Listener Submissions', 'Post Type General Name', 'greatermedia_ugc' ),
			'singular_name'      => _x( 'Listener Submission', 'Post Type Singular Name', 'greatermedia_ugc' ),
			'menu_name'          => __( 'Listener Submissions', 'greatermedia_ugc' ),
			'parent_item_colon'  => __( 'Parent Submission:', 'greatermedia_ugc' ),
			'all_items'          => __( 'All Submissions', 'greatermedia_ugc' ),
			'view_item'          => __( 'View Submission', 'greatermedia_ugc' ),
			'add_new_item'       => __( 'Add New Submission', 'greatermedia_ugc' ),
			'add_new'            => __( 'Add New', 'greatermedia_ugc' ),
			'edit_item'          => __( 'Edit Submission', 'greatermedia_ugc' ),
			'update_item'        => __( 'Update Submission', 'greatermedia_ugc' ),
			'search_items'       => __( 'Search Submission', 'greatermedia_ugc' ),
			'not_found'          => __( 'Not found', 'greatermedia_ugc' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'greatermedia_ugc' ),
		);
		$args   = array(
			'label'               => __( 'user_generated_content', 'greatermedia_ugc' ),
			'description'         => __( 'Listener Submissions', 'greatermedia_ugc' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'post-formats', ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => false,
			'menu_position'       => 5,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'capability_type'     => 'page',
		);
		register_post_type( 'listener_submissions', $args );

	}

	/**
	 * Add custom admin pages to the admin menu
	 */
	public static function admin_menu() {
		add_submenu_page( 'edit.php?post_type=listener_submissions', 'Listener Submission Moderation', 'Listener Submission Moderation', 'delete_posts', GreaterMediaUserGeneratedContentModerationTable::PAGE_NAME, array( __CLASS__, 'moderation_ui' ) );
	}

	public static function admin_enqueue_scripts() {
		wp_enqueue_style( 'greatermedia-ugc', GREATER_MEDIA_UGC_URL . 'css/greatermedia-ugc-moderation.css' );
	}

	/**
	 * Render the UI for the Moderation page
	 */
	public static function moderation_ui() {

		$wp_list_table = new GreaterMediaUserGeneratedContentModerationTable();
		$wp_list_table->prepare_items();

		$pagenum     = $wp_list_table->get_pagenum();
		$total_pages = $wp_list_table->get_pagination_arg( 'total_pages' );

		if ( $pagenum > $total_pages && $total_pages > 0 ) {
			wp_redirect( add_query_arg( 'paged', $total_pages ) );
			exit;
		}

		include trailingslashit( GREATER_MEDIA_UGC_PATH ) . 'tpl/moderation.tpl.php';

	}

	public static function admin_endpoints() {

		global $wp, $wp_rewrite;
		$wp->add_query_var( 'ugc' );
		$wp->add_query_var( 'ugc_action' );

		$rewrite_regex = '^ugc/(.*)/approve';
		add_rewrite_rule( $rewrite_regex, 'index.php?ugc_action=approve&ugc=$matches[1]', 'top' );

		// flush rewrite rules only if our rules is not registered
		$registered_rules = $wp_rewrite->wp_rewrite_rules();
		if ( ! isset( $registered_rules[$rewrite_regex] ) ) {
			flush_rewrite_rules( true );
		}

	}

	public static function wp() {
		global $wp;

		$ugc_id     = intval( get_query_var( 'ugc' ) );
		$ugc_action = get_query_var( 'ugc_action' );

		if ( empty( $ugc_id ) || empty( $ugc_action ) ) {
			return;
		}

		if ( 'approve' === $ugc_action ) {

			$ugc = self::for_post_id( $ugc_id );
			$ugc->approve();
			wp_redirect(
				add_query_arg(
					'page',
					'moderate-ugc',
					add_query_arg(
						'post_type',
						'listener_submissions',
						admin_url( 'edit.php' )
					)
				)
			);
		}

	}

	/**
	 * Return an instance of this class or an appropriate subclass based on Post Format
	 *
	 * @param int $post_id Post ID
	 *
	 * @return GreaterMediaUserGeneratedContent
	 */
	public static function for_post_id( $post_id ) {

		$post_formats = wp_get_post_terms( $post_id, 'post_format' );
		$post_format  = array_pop( $post_formats );
		if ( $post_format ) {
			$potential_subclass_name = 'GreaterMediaUserGenerated' . ucfirst( $post_format->name );
		}

		if ( isset( $potential_subclass_name ) && is_subclass_of( $potential_subclass_name, __CLASS__ ) ) {
			$ugc = new $potential_subclass_name( $post_id );
		} else {
			$ugc = new self( $post_id );
		}

		return $ugc;

	}

	public function approve() {
		$this->post->post_status = 'publish';
		wp_update_post( $this->post );
	}

	/**
	 * Render a representation of this post appropriate for displaying in the moderation queue
	 *
	 * @return string html
	 */
	public function render_moderation_row() {
		return 'Generic result';
	}

}

GreaterMediaUserGeneratedContent::register_cpt();
