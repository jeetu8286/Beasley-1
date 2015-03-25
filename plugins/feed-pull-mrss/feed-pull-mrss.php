<?php
/*
Plugin Name: Feed Pull MRSS
Description: Extends Feed Pull plugin by adding ability to extract media information from RSS feeds.
Version: 1.0.0
Author: 10up
Author URI: http://10up.com/
*/

/**
 * Catches SimpleXML element to use in the next steps.
 *
 * @global SimpleXMLElement $fpmrss_feed_item Current SimpleXML element.
 * @param array $pre_filter_post_value The array of post data.
 * @param array $field Mapping field array.
 * @param SimpleXMLElement $xml_element The current xml element.
 * @return array The inital post data.
 */
function fpmrss_catch_feed_item_xml( $pre_filter_post_value, $field, $xml_element ) {
	global $fpmrss_feed_item;
	$fpmrss_feed_item = $xml_element;

	return $pre_filter_post_value;
}
add_filter( 'fp_pre_post_insert_value', 'fpmrss_catch_feed_item_xml', 10, 3 );

/**
 * Fetches thumbnail image for a media item.
 *
 * @global SimpleXMLElement $fpmrss_feed_item The current SimpleXML element.
 * @global array $fpmrss_feed_thumbnails The array of feed thumbnails to import.
 * @param int $post_id Newly imported post id.
 */
function fpmrss_fetch_thumbnail( $post_id ) {
	global $fpmrss_feed_item, $fpmrss_feed_thumbnails;

	// do nothing if an xml element is not caught
	if ( ! $fpmrss_feed_item ) {
		return;
	}

	// init feed thumbnails array if it isn't
	if ( ! is_array( $fpmrss_feed_thumbnails ) ) {
		$fpmrss_feed_thumbnails = array();
	}


	$thumbnail = current( (array) $fpmrss_feed_item->xpath( 'media:content/media:thumbnail/@url' ) );
	if ( $thumbnail ) {
		$thumbnail = (string) $thumbnail['url'];
		if ( filter_var( $thumbnail, FILTER_VALIDATE_URL ) ) {
			$fpmrss_feed_thumbnails[] = array( $thumbnail, $post_id );
		}
	}

	$fpmrss_feed_item = null;
}
add_action( 'fp_created_post', 'fpmrss_fetch_thumbnail' );
add_action( 'fp_updated_post', 'fpmrss_fetch_thumbnail' );

/**
 * Lauches async task to import thumbnails.
 *
 * @global array $fpmrss_feed_thumbnails The array of feed thumbnails to import.
 */
function fpmrss_launch_async_thumbnails_import() {
	global $fpmrss_feed_thumbnails;

	// do nothing if feed thumbnails array if empty
	if ( empty( $fpmrss_feed_thumbnails ) ) {
		return;
	}

	// try to launch async task if available, otherwise schedule single event
	if ( function_exists( 'wp_async_task_add' ) ) {
		wp_async_task_add( 'fpmrss_import_thumbnails', $fpmrss_feed_thumbnails );
	} else {
		wp_schedule_single_event( current_time( 'timestamp', 1 ), 'fpmrss_import_thumbnails', array( $fpmrss_feed_thumbnails ) );
	}
}
add_action( 'fp_post_feed_pull', 'fpmrss_launch_async_thumbnails_import' );

/**
 * Performs thumbnails import.
 *
 * @param array $thumbnails Array of arrays of thumbnail urls and post ids.
 */
function fpmrss_import_thumbnails( $thumbnails ) {
	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';

	foreach ( $thumbnails as $thumbnail ) {
		echo $thumbnail[0] . PHP_EOL;
		
		$thumbnail_id = fpmrss_download_image( $thumbnail[0], $thumbnail[1] );
		if ( $thumbnail_id && ! is_wp_error( $thumbnail_id ) ) {
			set_post_thumbnail( $thumbnail[1], $thumbnail_id );
		}
	}
}
add_action( 'fpmrss_import_thumbnails', 'fpmrss_import_thumbnails' );

/**
 * Downloads remote image or just returns attachment ID if it has been already imported.
 *
 * @param string $image The remote image URL.
 * @param int $post_id The post id to which the image would be assigned.
 * @return int The attachment ID on success, otherwise FALSE.
 */
function fpmrss_download_image( $image, $post_id ) {
	$query = new WP_Query( array(
		'post_type'   => 'attachment',
		'post_status' => 'inherit',
		'meta_query'  => array(
			array(
				'key'     => 'fp_orig_image',
				'value'   => $image,
				'compare' => '='
			),
		),
	) );

	if ( $query->have_posts() ) {
		return $query->next_post()->ID;
	}

	// Download file to temp location
	$tmp = download_url( $image );
	if ( is_wp_error( $tmp ) ) {
		return false;
	}

	// Set variables for storage, fix file filename for query strings
	$matches = array();
	preg_match( '/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $image, $matches );
	$file_array['name'] = fpmrss_generate_image_name( $image );
	$file_array['tmp_name'] = $tmp;
	$file_array['error'] = 0;

	$post_data = array();
	$post = get_post( $post_id );
	if ( $post ) {
		$post_data['post_author'] = $post->post_author;
	}

	$image_id = media_handle_sideload( $file_array, $post_id, null, $post_data );
	if ( is_wp_error( $image_id ) ) {
		@unlink( $file_array['tmp_name'] );
	} else {
		add_post_meta( $image_id, 'fp_orig_image', $image );
	}

	return $image_id;
}

/**
 * Generates image name based on its file path.
 *
 * @param string $image The image path.
 * @return string The image name.
 */
function fpmrss_generate_image_name( $image ) {
	$file_name = '';
	$matches = array();

	preg_match( '/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $image, $matches );
	if ( is_array( $matches ) && count( $matches ) ) {
		$file_name = str_replace( '%20', '-', basename( $matches[0] ) );
	} else {
		$sizes = getimagesize( $image );
		if ( is_array( $sizes ) && isset( $sizes['mime'] ) && ! empty( $sizes['mime'] ) ) {
			$ext = image_type_to_extension( $sizes[2] );
			$file_name = substr( sanitize_title( pathinfo( $image, PATHINFO_FILENAME ) ), 0, 254 ) . $ext;
		}
	}
	
	return $file_name;
}