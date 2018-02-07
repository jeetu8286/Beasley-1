<?php

/**
 * Plugin Name: Omny Studio
 * Description: Podcasts and episodes integration with Omny Studio
 * Version:     1.0.0
 * Author:      10up Inc
 * Author URI:  http://10up.com/
 */

function omny_init() {
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

add_action( 'init', 'omny_init' );
