<?php

// include list table class file if it hasn't been included yet
require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';

// action hooks
add_action( 'admin_menu', 'gmr_contests_register_winners_page' );
add_action( 'admin_action_gmr_promote_winner', 'gmr_contest_promote_winner' );
add_action( 'admin_action_gmr_unpromote_entry', 'gmr_contest_unpromote_entry' );
add_action( 'admin_action_gmr_disqualify_entry', 'gmr_contest_disqualify_entry' );
add_action( 'admin_action_gmr_mark_winner', 'gmr_contest_mark_as_winner' );
add_action( 'admin_action_gmr_unmark_winner', 'gmr_contest_unmark_as_winner' );

// filter hooks
add_filter( 'post_row_actions', 'gmr_contests_add_table_row_actions', 10, 2 );
add_filter( 'parent_file', 'gmr_contests_adjust_winners_page_admin_menu' );

/**
 * Adds table row actions to contest records.
 *
 * @filter post_row_actions
 * @param array $actions The initial array of post actions.
 * @param WP_Post $post The post object.
 * @return array The array of post actions.
 */
function gmr_contests_add_table_row_actions( $actions, WP_Post $post ) {
	// do nothing if it is not a contest object
	if ( GMR_CONTEST_CPT != $post->post_type ) {
		return $actions;
	}

	// add contest winners action
	$link = admin_url( 'admin.php?page=gmr-contest-winner&contest_id=' . $post->ID );
	$actions['gmr-contest-winner'] = '<a href="' . esc_url( $link ) . '">Entries</a>';

	return $actions;
}

/**
 * Registers contest winner page in the system.
 *
 * @action admin_menu
 * @global array $_registered_pages The array of already registered pages.
 */
function gmr_contests_register_winners_page() {
	global $_registered_pages;

	$page_hook = get_plugin_page_hookname( 'gmr-contest-winner', '' );
	$_registered_pages[ $page_hook ] = true;

	add_action( $page_hook, 'gmr_contests_render_winner_page' );
}

/**
 * Renders contest winner selection page.
 *
 * @global string $typenow The current post type.
 * @global string $post_type_object The current post type object.
 */
function gmr_contests_render_winner_page() {
	global $typenow, $post_type_object;

	$contest = filter_input( INPUT_GET, 'contest_id', FILTER_VALIDATE_INT );
	if ( ! $contest || ! ( $contest = get_post( $contest ) ) || GMR_CONTEST_CPT != $contest->post_type ) {
		wp_die( 'Contest has not been found.' );
	}

	// fake post type to make standard WP_Posts_List_Table class working properly
	$_GET['post_type'] = GMR_CONTEST_ENTRY_CPT;

	// override globals to make posts table class working properly
	$post_type = $typenow = GMR_CONTEST_ENTRY_CPT;
	$post_type_object = get_post_type_object( $post_type );

	// create table class
	$wp_list_table = _get_list_table( 'WP_Posts_List_Table', array( 'screen' => GMR_CONTEST_ENTRY_CPT ) );
	$wp_list_table->prepare_items();

	?><div id="contest-winner-selection" class="wrap">
		<h2>
			Entries:
			<a href="<?php echo get_edit_post_link( $contest->ID ); ?>"><?php echo esc_html( $contest->post_title ); ?></a>
			<a class="add-new-h2" href="<?php echo esc_url( admin_url( 'edit.php?post_type=' . GMR_CONTEST_CPT ) ); ?>">All Contests</a>
		</h2>

		<form id="posts-filter">
			<input type="hidden" name="post_status" class="post_status_page" value="<?php echo ! empty( $_REQUEST['post_status'] ) ? esc_attr( $_REQUEST['post_status'] ) : 'all'; ?>">

			<?php $wp_list_table->search_box( $post_type_object->labels->search_items, 'post' ); ?>

			<?php $wp_list_table->display(); ?>
		</form>
	</div><?php
}

/**
 * Adjustes parent and submenu files.
 *
 * @filter parent_file
 * @global string $submenu_file The current submenu page.
 * @param string $parent_file The parent file name.
 * @return string The parent file.
 */
function gmr_contests_adjust_winners_page_admin_menu( $parent_file ) {
	global $submenu_file;

	if ( ! empty( $_REQUEST['page'] ) && 'gmr-contest-winner' == $_REQUEST['page'] ) {
		$parent_file = 'edit.php?post_type=' . GMR_CONTEST_CPT;
		$submenu_file = 'edit.php?post_type=' . GMR_CONTEST_CPT;
	}

	return $parent_file;
}