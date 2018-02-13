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

function omny_api_request( $url, $args = array() ) {
	$args = wp_parse_args( $args, array(
		'headers' => array(),
		'method'  => 'GET',
	) );

	$args['headers']['Authorization'] = 'OmnyToken ' . get_option( 'omny_token' );

	$response = wp_remote_request( "https://api.omnystudio.com/v0/{$url}", $args );

	if ( wp_remote_retrieve_response_code( $response ) !== 200 ) {
		return new \WP_Error();
	}

	$body = wp_remote_retrieve_body( $response );
	$json = json_decode( $body, true );

	return $json;
}

/**
 *
 * @global \wpdb $wpdb
 * @return type
 */
function omny_syndicate_programs() {
	global $wpdb;

	$token = trim( get_option( 'omny_token' ) );
	if ( empty( $token ) ) {
		return;
	}

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

		$clips = omny_api_request( "programs/{$program_id}/clips?pageSize=100" );
		if ( is_wp_error( $clips ) ) {
			continue;
		}

		$clips = $clips['Clips'];
		foreach ( $clips as $clip ) {
			if ( $clip['PublishState'] != 'Published' ) {
				continue;
			}

			$query = $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->postmeta} WHERE `meta_key` = 'omny-clip-id' AND `meta_value` = %s", $clip['Id'] );
			$found = $wpdb->get_var( $query );
			if ( $found > 0 ) {
				continue;
			}

			$date_gmt = date( 'Y-m-d H:i:s', strtotime( $clip['PublishedUtc'] ) );
			$date = get_date_from_gmt( $date_gmt );

			switch ( $clip['Visibility'] ) {
				case 'Private':
					$status = 'private';
					break;
				default:
					$status = 'publish';
					break;
			}

			$args = array(
				'post_title'    => $clip['Title'],
				'post_content'  => sprintf( '[audio mp3="%s"][/audio]', $clip['AudioUrl'] ),
				'post_excerpt'  => $clip['Description'],
				'post_status'   => $status,
				'post_type'     => 'episode',
				'ping_status'   => 'closed',
				'post_parent'   => $podcast,
				'post_date'     => $date,
				'post_date_gmt' => $date_gmt,
				'guid'          => $clip['Id'],
				'meta_input'    => array(
					'omny-clip-id'   => $clip['Id'],
					'omny-embed-url' => $clip['EmbedUrl'],
					'omny-image-url' => $clip['ImageUrl'],
				),
			);

			$post_id = wp_insert_post( $args, true );
			if ( is_wp_error( $post_id ) ) {
				continue;
			}
		}
	}
}

add_action( 'init', 'omny_init' );
add_action( 'admin_init', 'omny_register_scheduled_events' );
add_action( 'beasley-register-settings', 'omny_register_settings', 1, 2 );
add_action( 'omny_do_syndication', 'omny_syndicate_programs' );