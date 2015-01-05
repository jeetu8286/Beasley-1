<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

/**
 * Class GreaterMediaContests
 * @see  https://core.trac.wordpress.org/ticket/12668#comment:27
 */
class GreaterMediaContests {

	public function __construct() {
		add_action( 'init', array( $this, 'register_contest_type_taxonomy' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'restrict_manage_posts', array( $this, 'admin_contest_type_filter' ) );
		add_action( 'pre_get_posts', array( $this, 'admin_filter_contest_list' ) );
		add_action( 'pre_get_posts', array( $this, 'adjust_contest_entries_query' ) );
		add_action( 'manage_' . GMR_CONTEST_ENTRY_CPT . '_posts_custom_column', array( $this, 'render_contest_entry_column' ), 10, 2 );

		add_filter( 'manage_' . GMR_CONTEST_ENTRY_CPT . '_posts_columns', array( $this, 'filter_contest_entry_columns_list' ) );
		add_filter( 'parent_file', array( $this, 'adjust_current_admin_menu' ) );
		add_filter( 'gmr_live_link_suggestion_post_types', array( $this, 'extend_live_link_suggestion_post_types' ) );
	}
	
	/**
	 * Adjustes parent and submenu files.
	 *
	 * @filter parent_file
	 * @global string $submenu_file The current submenu page.
	 * @global string $typenow The current post type.
	 * @global string $pagenow The current admin page.
	 * @return string The parent file.
	 */
	public function adjust_current_admin_menu( $parent_file ) {
		global $submenu_file, $typenow, $pagenow;

		if ( in_array( $pagenow, array( 'post-new.php', 'post.php' ) ) && GMR_SUBMISSIONS_CPT == $typenow ) {
			$parent_file = 'edit.php?post_type=' . GMR_CONTEST_CPT;
			$submenu_file = 'edit.php?post_type=' . $typenow;
		} elseif ( GMR_CONTEST_ENTRY_CPT == $typenow && 'edit.php' == $pagenow ) {
			$parent_file = 'edit.php?post_type=' . GMR_CONTEST_CPT;
			$submenu_file = 'edit.php?post_type=' . GMR_CONTEST_CPT;
		}

		return $parent_file;
	}

	/**
	 * Adjustes contest entries query to display entries only for selected contest.
	 *
	 * @action pre_get_posts
	 * @global string $typenow The current post type.
	 * @global string $pagenow The current admin page.
	 * @param WP_Query $query The contest entry query.
	 */
	public function adjust_contest_entries_query( WP_Query $query ) {
		global $typenow, $pagenow;

		if ( GMR_CONTEST_ENTRY_CPT == $typenow && 'edit.php' == $pagenow && $query->is_main_query() ) {
			$contest = filter_input( INPUT_GET, 'contest_id', FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 1 ) ) );
			if ( $contest && ( $contest = get_post( $contest ) ) && GMR_CONTEST_CPT == $contest->post_type ) {
				$query->set( 'post_parent', $contest->ID );
			}
		}
	}

	/**
	 * Adds columns to the contest entries table.
	 *
	 * @param array $columns Initial array of columns.
	 * @return array The array of columns.
	 */
	public function filter_contest_entry_columns_list( $columns ) {
		$contest = filter_input( INPUT_GET, 'contest_id', FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 1 ) ) );
		if ( ! $contest || ! ( $contest = get_post( $contest ) ) || GMR_CONTEST_CPT != $contest->post_type ) {
			return $columns;
		}

		$form = get_post_meta( $contest->ID, 'embedded_form', true );
		if ( empty( $form ) ) {
			return $columns;
		}

		if ( is_string( $form ) ) {
			$clean_form = trim( $form, '"' );
			$form = json_decode( $clean_form );
		}

		unset( $columns['title'], $columns['date'] );

		foreach ( $form as $field ) {
			$columns[ $field->cid ] = $field->label;
		}

		$columns['date'] = 'Date';

		return $columns;
	}

	/**
	 * Renders custom columns for the contest entries table.
	 *
	 * @param string $column_name The column name which is gonna be rendered.
	 * @param int $post_id The post id.
	 */
	public function render_contest_entry_column( $column_name, $post_id ) {
		$entry = get_post( $post_id );
		$fields = GreaterMediaFormbuilderRender::parse_entry( $entry->post_parent, $entry->ID );
		if ( isset( $fields[ $column_name ] ) ) {
			echo esc_html( $fields[ $column_name ]['value'] );
		} else {
			echo '&#8212;';
		}
	}

	/**
	 * Register a custom taxonomy representing Contest Types
	 * @uses register_taxonomy
	 */
	public function register_contest_type_taxonomy() {
		$labels = array(
			'name'                       => 'Contest Types',
			'singular_name'              => 'Contest Type',
			'menu_name'                  => 'Contest Type',
			'all_items'                  => 'All Contest Types',
			'parent_item'                => 'Parent Contest Type',
			'parent_item_colon'          => 'Parent Contest Type:',
			'new_item_name'              => 'New Contest Type Name',
			'add_new_item'               => 'Add New Contest Type',
			'edit_item'                  => 'Edit Contest Type',
			'update_item'                => 'Update Contest Type',
			'separate_items_with_commas' => 'Separate items with commas',
			'search_items'               => 'Search Contest Types',
			'add_or_remove_items'        => 'Add or remove contest types',
			'choose_from_most_used'      => 'Choose from the most used contest types',
			'not_found'                  => 'Not Found',
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

		register_taxonomy( 'contest_type', array( GMR_CONTEST_CPT ), $args );

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
		if ( $seeded ) {
			return;
		}

		wp_insert_term( 'On Air', 'contest_type', array( 'description' => 'On-air contests generally require a call or, perhaps, text message, from the entrant. The specific requirements and number to text or call can be written directly in the "how to enter" section of the contest.' ) );
		wp_insert_term( 'Online', 'contest_type', array( 'description' => '' ) );

		update_option( 'contest_type_seeded', 1 );

		if ( class_exists( 'GreaterMediaAdminNotifier' ) ) {
			GreaterMediaAdminNotifier::message( __( 'Seeded "Contest Types" taxonomy.', 'greatermedia_contests' ) );
		}
	}

	public function admin_enqueue_scripts() {
		wp_enqueue_style( 'greatermedia-contests-admin', trailingslashit( GREATER_MEDIA_CONTESTS_URL ) . 'css/greatermedia-contests-admin.css' );
	}

	/**
	 * Add a dropdown on the contest list page to filter by contest type.
	 */
	public function admin_contest_type_filter() {
		global $typenow;
		$contest_type_tax_id = 0;

		if ( GMR_CONTEST_CPT !== $typenow || ! is_admin() ) {
			return;
		}

		if ( isset( $_GET['type_filter'] ) ) {
			// If user selected a term in the filter drop-down on the contest list page
			$contest_type_tax_id = intval( $_GET['type_filter'] );
		} else if ( isset( $_GET['contest_type'] ) ) {
			// If user clicked on the post count next to the taxonomy term
			$term = get_term_by( 'slug', $_GET['contest_type'], 'contest_type' );

			if ( false !== $term ) {
				$contest_type_tax_id = intval( $term->term_id );
			}
		}

		$args = array(
			'show_option_all' => __( 'All contest types', 'greatermedia_contests' ),
			'hierarchical'    => true,
			'name'            => 'type_filter',
			'id'              => 'type-filter',
			'class'           => 'postform',
			'orderby'         => 'name',
			'taxonomy'        => 'contest_type',
			'hide_if_empty'   => true,
			'selected'        => $contest_type_tax_id,
		);

		wp_dropdown_categories( $args );
	}

	/**
	 * Handle the request to filter contests by type.
	 *
	 * @param  WP_Query $wp_query
	 */
	public function admin_filter_contest_list( $wp_query ) {
		global $typenow;

		$contest_type_tax_id = isset( $_GET['type_filter'] ) ? intval( $_GET['type_filter'] ) : 0;

		if ( GMR_CONTEST_CPT !== $typenow || ! is_admin() || empty( $contest_type_tax_id ) ) {
			return;
		}

		$args = array(
			array(
				'taxonomy' => 'contest_type',
				'field'    => 'id',
				'terms'    => $contest_type_tax_id,
			),
		);

		$wp_query->set( 'tax_query', $args );
	}

	/**
	 * Extends live link suggestion post types.
	 *
	 * @static
	 * @access public
	 *
	 * @param array $post_types The array of already registered post types.
	 *
	 * @return array The array of extended post types.
	 */
	public function extend_live_link_suggestion_post_types( $post_types ) {
		$post_types[] = GMR_CONTEST_CPT;
		return $post_types;
	}

}

$GreaterMediaContests = new GreaterMediaContests();