<?php

// action hooks
add_action( 'init', 'gmr_survey_register_cpt' );

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