<?php

/**
 * Plugin Name: Omny Studio
 * Description: Podcasts and episodes integration with Omny Studio
 * Version:     1.0.0
 * Author:      10up Inc
 * Author URI:  http://10up.com/
 */

define( 'OMNY_STUDIO_VERSION', '20180319.1' );

function omny_init() {
	if ( function_exists( 'acf_add_local_field_group' ) ) {
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
}

function omny_enqueue_scripts() {
	wp_enqueue_script( 'playerjs', '//cdn.embed.ly/player-0.1.0.min.js', null, null, true );
	wp_enqueue_script( 'omny', plugins_url( '/player.js', __FILE__ ), array( 'jquery', 'playerjs' ), OMNY_STUDIO_VERSION, true );
}

function omny_register_oembed( $providers ) {
	$providers['https://omny.fm/shows/*'] = array( 'https://omny.fm/oembed', false );
	return $providers;
}

function omny_register_scheduled_events() {
	if ( ! wp_next_scheduled( 'omny_start_import_episodes' ) ) {
		wp_schedule_single_event( current_time( 'timestamp', 1 ) + 15 * MINUTE_IN_SECONDS, 'omny_start_import_episodes' );
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

	// @see: https://api.omnystudio.com/api-docs/index
	$response = wp_remote_request( "https://api.omnystudio.com/v0/{$url}", $args );
	if ( wp_remote_retrieve_response_code( $response ) !== 200 ) {
		return new \WP_Error();
	}

	$body = wp_remote_retrieve_body( $response );
	$json = json_decode( $body, true );

	return $json;
}

function omny_start_import_episodes() {
	if ( function_exists( 'wp_async_task_add' ) ) {
		wp_async_task_add( 'omny_run_import_episodes', array(), 'high' );
	} else {
		omny_run_import_episodes();
	}
}

function omny_run_import_episodes() {
	global $wpdb;

	$token = trim( get_option( 'omny_token' ) );
	if ( empty( $token ) ) {
		return;
	}

	$is_wp_cli = defined( 'WP_CLI' ) && WP_CLI;

	delete_option( 'omny_last_import_finished' );
	update_option( 'omny_last_import_started', time(), 'no' );

	$podcasts = get_posts( array(
		'post_type'      => 'podcast',
		'posts_per_page' => 1000, // should be enough to get all podcasts
	) );

	foreach ( $podcasts as $podcast ) {
		$program_id = get_post_meta( $podcast->ID, 'omny_program_id', true );
		if ( empty( $program_id ) ) {
			$is_wp_cli && \WP_CLI::log( sprintf( 'Skipping %s (%s) podcast...', $podcast->post_title, $podcast->ID ) );
			continue;
		}

		$is_wp_cli && \WP_CLI::log( sprintf( 'Processing %s (%s) podcast...', $podcast->post_title, $podcast->ID ) );

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
				$is_wp_cli && \WP_CLI::log( sprintf( 'Skipping %s (%s) episode...', $clip['Title'], $clip['Id'] ) );
				continue;
			}

			$published_utc = strtotime( $clip['PublishedUtc'] );
			$date_gmt = date( 'Y-m-d H:i:s', $published_utc );
			$date = get_date_from_gmt( $date_gmt );

			switch ( $clip['Visibility'] ) {
				case 'Private':
					$status = 'private';
					break;
				default:
					$status = 'publish';
					break;
			}

			if ( $published_utc > time() ) {
				$status = 'future';
			}

			$args = array(
				'post_title'    => html_entity_decode( $clip['Title'] ),
				'post_content'  => sprintf( '[embed]%s[/embed]%s%s', $clip['PublishedUrl'], PHP_EOL, html_entity_decode( $clip['Description'] ) ),
				'post_status'   => $status,
				'post_type'     => 'episode',
				'ping_status'   => 'closed',
				'post_parent'   => $podcast->ID,
				'post_date'     => $date,
				'post_date_gmt' => $date_gmt,
				'guid'          => $clip['Id'],
				'meta_input'    => array(
					'omny-clip-id'     => $clip['Id'],
					'omny-embed-url'   => $clip['EmbedUrl'],
					'omny-publish-url' => $clip['PublishedUrl'],
					'omny-audio-url'   => $clip['AudioUrl'],
					'omny-image-url'   => $clip['ImageUrl'],
				),
			);

			$post_id = wp_insert_post( $args, true );
			if ( is_wp_error( $post_id ) ) {
				continue;
			}

			$is_wp_cli && \WP_CLI::success( sprintf( 'Imported %s (%s) episode...', $clip['Title'], $clip['Id'] ) );

			$url = $clip['ImageUrl'];
			if ( filter_var( $url, FILTER_VALIDATE_URL ) ) {
				$response = wp_remote_head( $url, array( 'redirection' => 0 ) );
				if ( ! is_wp_error( $response ) ) {
					$headers = wp_remote_retrieve_headers( $response );
					if ( ! empty( $headers['location'] ) ) {
						if ( preg_match( '#^/[^/]#', $headers['location'] ) ) {
							$parsed = parse_url( $url );
							$replace = $parsed['path'];

							if ( ! empty( $parsed['query'] ) ) {
								$replace .= '?' . $parsed['query'];
							}

							if ( ! empty( $parsed['fragment'] ) ) {
								$replace .= '#' . $parsed['fragment'];
							}

							$url = str_replace( $replace, $headers['location'], $url );
						} else {
							$url = $headers['location'];
						}
					}
				}

				$url = preg_replace( '#\?.*#', '', $url );
				$key = 'omny-image-id-' . $url;
				$attachment_id = wp_cache_get( $key, 'omny-studio' );
				if ( $attachment_id === false ) {
					$query = $wpdb->prepare( "SELECT `ID` FROM {$wpdb->posts} WHERE `post_type` = 'attachment' AND `guid` = %s LIMIT 1", $url );
					$attachment_id = $wpdb->get_var( $query );
					if ( $attachment_id > 0 ) {
						wp_cache_set( $key, $attachment_id, 'omny-studio' );
					}
				}

				if ( empty( $attachment_id ) ) {
					require_once ABSPATH . 'wp-admin/includes/media.php';
					require_once ABSPATH . 'wp-admin/includes/file.php';
					require_once ABSPATH . 'wp-admin/includes/image.php';

					$file_array = array();
					$file_array['name'] = md5( $url ) . '.jpg';
					$file_array['tmp_name'] = download_url( $url );
					if ( ! is_wp_error( $file_array['tmp_name'] ) ) {
						$attachment_id = media_handle_sideload( $file_array, $post_id, null, array( 'guid' => $url ) );
					}
				}

				if ( $attachment_id > 0 ) {
					set_post_thumbnail( $post_id, $attachment_id );
				}
			}
		}
	}

	update_option( 'omny_last_import_finished', time(), 'no' );
}

function omny_get_episode_audio_url( $url, $post ) {
	if ( empty( $url ) ) {
		$url = get_post_meta( $post->ID, 'omny-audio-url', true );
		$url = filter_var( $url, FILTER_VALIDATE_URL );
	}

	return $url;
}

add_action( 'init', 'omny_init' );
add_action( 'admin_init', 'omny_register_scheduled_events' );
add_action( 'beasley-register-settings', 'omny_register_settings', 1, 2 );
add_action( 'omny_start_import_episodes', 'omny_start_import_episodes' );
add_action( 'omny_run_import_episodes', 'omny_run_import_episodes' );
add_action( 'wp_enqueue_scripts', 'omny_enqueue_scripts' );

add_filter( 'oembed_providers', 'omny_register_oembed', 100 );
add_filter( 'beasley-episode-audio-url', 'omny_get_episode_audio_url', 10, 2 );

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command( 'omny import', 'omny_run_import_episodes' );
}
