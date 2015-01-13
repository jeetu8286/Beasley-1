<?php

// action hooks
add_action( 'admin_menu', 'gmr_contests_register_winners_page' );

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
	$actions['gmr-contest-winner'] = '<a href="' . esc_url( $link ) . '">Winners</a>';

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
 */
function gmr_contests_render_winner_page() {

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
