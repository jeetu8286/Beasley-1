<?php

/**
 * Plugin Name: Omny Studio
 * Description: Podcasts and episodes integration with Omny Studio
 * Version:     1.0.0
 * Author:      10up Inc
 * Author URI:  http://10up.com/
 */

function omny_init() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	$location = array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'podcast',
			),
		),
	);

	acf_add_local_field_group( array(
		'key'                   => 'group_5a7b2b84a6adb',
		'title'                 => 'Omny Studio',
		'position'              => 'side',
		'style'                 => 'default',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'active'                => 1,
		'location'              => $location,
		'fields'                => array(
			array(
				'key'   => 'omny_program_id',
				'label' => 'Program ID',
				'name'  => 'omny_program_id',
				'type'  => 'text',
			),
		),
	) );
}

function omny_register_scheduled_events() {
	if ( ! wp_next_scheduled( 'omny_do_syndication' ) ) {
		wp_schedule_event( current_time( 'timestamp', 1 ), 'hourly', 'omny_do_syndication' );
	}
}

function omny_register_settings( $group, $page ) {
	add_settings_section( 'omny_settings', 'Omny Studio', '__return_false', $page );
	add_settings_field( 'omny_token', 'Access Token', 'beasley_input_field', $page, 'omny_settings', 'name=omny_token' );

	register_setting( $group, 'omny_token', 'sanitize_text_field' );
}

function omny_api_request() {

}

function omny_syndicate_programs() {
	$podcasts = get_posts( array(
		'post_type'      => 'podcast',
		'posts_per_page' => 1000, // should be enough to get all podcasts
		'fields'         => 'ids',
	) );

	foreach ( $podcasts as $podcast ) {
		$program_id = get_post_meta( $podcast, 'omny_program_id', true );
		if ( empty( $program_id ) ) {
			continue;
		}
	}
}

add_action( 'init', 'omny_init' );
add_action( 'admin_init', 'omny_register_scheduled_events' );
add_action( 'beasley-register-settings', 'omny_register_settings', 1, 2 );
add_action( 'omny_do_syndication', 'omny_syndicate_programs' );