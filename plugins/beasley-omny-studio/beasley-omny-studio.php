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
				'key'   => 'omny_playlist_id',
				'label' => 'Playlist ID',
				'name'  => 'omny_playlist_id',
				'type'  => 'text',
			),
		),
	) );
}

function omny_register_settings( $group, $page ) {
	add_settings_section( 'omny_settings', 'Omny Studio', '__return_false', $page );
	add_settings_field( 'omny_organization', 'Organization ID', 'omny_render_settings', $page, 'omny_settings' );

	register_setting( $group, 'omny_organization_id', 'sanitize_text_field' );
}

function omny_render_settings() {
	$organization_id = get_option( 'omny_organization_id' );

	?><input type="text" name="omny_organization_id" class="regular-text" value="<?php echo esc_attr( $organization_id ); ?>"><?php
}

add_action( 'init', 'omny_init' );
add_action( 'beasley-register-settings', 'omny_register_settings', 1, 2 );
