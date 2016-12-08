<?php

// action hooks
add_action( 'init', 'gmr_survey_register_cpt' );
add_action( 'template_redirect', 'gmr_surveys_process_action' );
add_action( 'gmr_survey_load', 'gmr_surveys_render_form' );

// filter hooks
add_filter( 'gmr-homepage-curation-post-types', 'gmr_survey_register_curration_post_type' );
add_filter( 'gmr-show-curation-post-types', 'gmr_survey_register_curration_post_type' );

/**
 * Registers custom post type for survey.
 */
function gmr_survey_register_cpt() {

	$labels = array(
		'name'                => 'Surveys',
		'singular_name'       => 'Survey',
		'add_new'             => 'Add New Survey',
		'add_new_item'        => 'Add New Survey',
		'edit_item'           => 'Edit Survey',
		'new_item'            => 'New Survey',
		'view_item'           => 'View Survey',
		'search_items'        => 'Search Surveys',
		'not_found'           => 'No surveys found',
		'not_found_in_trash'  => 'No surveys found in Trash',
		'parent_item_colon'   => 'Parent Survey:',
		'menu_name'           => 'Surveys',
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
		'menu_position'       => 33,
		'menu_icon'           => 'dashicons-welcome-write-blog',
		'show_in_nav_menus'   => true,
		'publicly_queryable'  => true,
		'exclude_from_search' => false,
		'has_archive'         => true,
		'query_var'           => true,
		'can_export'          => true,
		'rewrite'             => array( 'slug' => 'surveys', 'ep_mask' => EP_GMR_SURVEY ),
		'capability_type'     => array( 'survey', 'surveys' ),
		'map_meta_cap'        => true,
		'supports'            => array( 'title', 'editor', 'thumbnail' ),
	);

	register_post_type( GMR_SURVEY_CPT, $args );
}

/**
 * Registers survey post type in the curration types list.
 *
 * @filter gmr-homepage-curation-post-types
 * @filter gmr-show-curation-post-types
 * @param array $types Array of already registered types.
 * @return array Extended array of post types.
 */
function gmr_survey_register_curration_post_type( $types ) {
	$types[] = GMR_SURVEY_CPT;
	return $types;
}

/**
 * Processes survey actions triggered from front end.
 *
 * @action template_redirect
 */
function gmr_surveys_process_action() {
	// do nothing if it is a regular request
	if ( ! is_singular( GMR_SURVEY_CPT ) ) {
		return;
	}

	$action = get_query_var( 'action' );
	if ( ! empty( $action ) ) {
		// disable batcache if it is activated
		if ( function_exists( 'batcache_cancel' ) ) {
			batcache_cancel();
		}

		// disble HTTP cache
		nocache_headers();


		// do contest action
		do_action( "gmr_survey_{$action}" );
		exit;
	}
}

/**
 * Displays survey container attributes required for proper work of survey JS.
 *
 * @param WP_Post|int $post The contest id or object.
 */
function gmr_survey_container_attributes( $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return;
	}

	$permalink = untrailingslashit( get_permalink( $post->ID ) );

	$endpoints = array(
		'load' => "{$permalink}/action/load/",
	);

	foreach ( $endpoints as $attribute => $value ) {
		echo sprintf( ' data-%s="%s"', $attribute, esc_url( $value ) );
	}
}

/**
 * Renders survey form.
 *
 * @action gmr_survey_load
 */
function gmr_surveys_render_form() {
	// check if user has to be logged in
	wp_send_json_error( array( 'restriction' => 'signin' ) );

	$survey_id = get_the_ID();

	// check if user already submitted survey response
	if ( function_exists( 'has_user_entered_survey' ) && has_user_entered_survey( $survey_id ) ) {
		wp_send_json_error( array( 'restriction' => 'one-entry' ) );
	}

	$form = get_post_meta( $survey_id, 'survey_embedded_form', true );
	if ( is_string( $form ) ) {
		$form = json_decode( trim( $form, '"' ) );
	}

	// render the form
	wp_send_json_success( array(
		'html' => GreaterMediaFormbuilderRender::render( $survey_id, $form, false ),
	) );
}

/**
 * Verifies form submission.
 */
function gmr_survey_verify_form_submission( $form ) {
	_deprecated_function( 'gmr_survey_verify_form_submission', '1.1.3', 'gmr_verify_form_submission' );
	gmr_verify_form_submission( $form );
}