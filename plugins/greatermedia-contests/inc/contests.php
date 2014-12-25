<?php

// action hooks
add_action( 'init', 'gmr_contests_register_post_type' );
add_action( 'init', 'gmr_contests_register_endpoint' );
add_action( 'wp_enqueue_scripts', 'gmr_contests_register_scripts' );
add_action( 'template_redirect', 'gmr_contests_process_action' );
add_action( 'gmr_contest_load', 'gmr_contests_render_form' );

/**
 * Registers custom post types related to contests area.
 *
 * @action init
 */
function gmr_contests_register_post_type() {
	$labels = array(
		'name'               => 'Contests',
		'singular_name'      => 'Contest',
		'menu_name'          => 'Contests',
		'parent_item_colon'  => 'Parent Contest:',
		'all_items'          => 'All Contests',
		'view_item'          => 'View Contest',
		'add_new_item'       => 'Add New Contest',
		'add_new'            => 'Add New',
		'edit_item'          => 'Edit Contest',
		'update_item'        => 'Update Contest',
		'search_items'       => 'Search Contests',
		'not_found'          => 'Not found',
		'not_found_in_trash' => 'Not found in Trash'
	);
	
	$args   = array(
		'label'               => 'Contests',
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail' ),
		'taxonomies'          => array( 'contest_type' ),
		'public'              => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-forms',
		'can_export'          => true,
		'has_archive'         => 'contests',
		'rewrite'             => array( 'slug' => 'contest', 'ep_mask' => EP_GMR_CONTEST ),
	);

	register_post_type( GMR_CONTEST_CPT, $args );
	add_post_type_support( GMR_CONTEST_CPT, 'timed-content' );
}

/**
 * Registers endpoints for contests related tasks.
 *
 * @action init
 */
function gmr_contests_register_endpoint() {
	add_rewrite_endpoint( 'action', EP_GMR_CONTEST );
}

/**
 * Registers contests related scripts.
 *
 * @action wp_enqueue_scripts
 */
function gmr_contests_register_scripts() {
	if ( is_singular( GMR_CONTEST_CPT ) ) {
		$base_path = trailingslashit( GREATER_MEDIA_CONTESTS_URL );
		$postfix = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min';
		$permalink = untrailingslashit( get_permalink() );
			
		wp_enqueue_style( 'greatermedia-contests', "{$base_path}css/greatermedia-contests.css", array( 'datetimepicker', 'parsleyjs' ), GREATER_MEDIA_CONTESTS_VERSION );
		
		wp_enqueue_script( 'greatermedia-contests', "{$base_path}js/greatermedia-contests{$postfix}.js", array( 'jquery', 'datetimepicker', 'parsleyjs', 'parsleyjs-words' ), GREATER_MEDIA_CONTESTS_VERSION, true );
		wp_localize_script( 'greatermedia-contests', 'GreaterMediaContests', array(
			'selectors' => array(
				'container' => '#contest-form',
				'form'      => '.' . GreaterMediaFormbuilderRender::FORM_CLASS,
			),
			'endpoints' => array(
				'load'   => "{$permalink}/action/load/",
				'submit' => "{$permalink}/action/submit/",
			),
		) );
	}
}

/**
 * Processes contest actions triggered from front end.
 *
 * @action template_redirect
 */
function gmr_contests_process_action() {
	// do nothing if it is a regular request
	$action = get_query_var( 'action' );
	if ( ! is_singular( GMR_CONTEST_CPT ) || empty( $action ) ) {
		return;
	}

	// disable batcache if it is activated
	if ( function_exists( 'batcache_cancel' ) ) {
		batcache_cancel();
	}

	// define doing AJAX if it was not defined yet
	if( ! defined( 'DOING_AJAX' ) && ! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ) {
		define( 'DOING_AJAX', true );
	}

	// do contest action
	do_action( "gmr_contest_{$action}" );
	exit;
}

/**
 * Renders contest form.
 *
 * @action gmr_contest_load
 */
function gmr_contests_render_form() {
	$form = get_post_meta( get_the_ID(), 'embedded_form', true );
	$error = GreaterMediaFormbuilderRender::render( get_the_ID(), $form );
	if ( is_wp_error( $error ) ) :
		echo '<p>', $error->get_error_message(), '</p>';
	endif;
}