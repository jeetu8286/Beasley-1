<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

// Remove the gallery shortcode from the front-end so it doesn't conflict
add_shortcode( 'gallery', '__return_false' );


class GreaterMediaGallery {

	public static function init() {
		add_action( 'save_post', array( __CLASS__, 'gallery_save_meta_box' ) );
		add_action( 'init', array( __CLASS__, 'gallery_add_image_sizes' ) );
	}

	/**
	 * Get an array of photos for a gallery.
	 *
	 * @param WP_Post|WEF_Post $post
	 *
	 * @return array
	 */
	public static function get_gallery_photos( $post ) {
		preg_match_all( '/\[gallery.*ids=.(.*).\]/', $post->post_content, $ids );

		$array_ids = array();
		foreach( $ids[1] as $match ) {
			$array_id = explode( ',', $match );
			$array_id = array_map( 'intval', $array_id );

			$array_ids = array_merge( $array_ids, $array_id );
		}

		$photos = array();

		foreach( $array_ids as $id ) {
			$image = wp_get_attachment_image_src( $id, 'gmr-gallery' );
			$thumb = wp_get_attachment_image_src( $id, 'gmr-gallery-thumb' );

			if ( ! $image ) {
				continue;
			}

			$photos[] = array(
				'url'       => $image[0],
				'title'     => get_post_field( 'post_excerpt', $id ),
				'thumbnail' => $thumb[0], // 82x46
			);
		}

		return $photos;
	}

	/**
	 * Gets a WP_Query for the attachments in the gallery
	 * @param $post
	 * @return WP_Query
	 */
	public static function get_gallery_loop( $post ) {
		preg_match_all( '/\[gallery.*ids=.(.*).\]/', $post->post_content, $ids );

		$array_ids = array();
		foreach ( $ids[1] as $match ) {
			$array_id = explode( ',', $match );
			$array_id = array_map( 'intval', $array_id );
			$array_ids = array_merge( $array_ids, $array_id );
		}

		$photos = new WP_Query(
			array(
				'ignore_sticky_posts' => true,
				'post__in'            => $array_ids,
				'post_status'         => 'inherit',
				'post_type'           => 'attachment',
				'posts_per_page'      => - 1,
				'orderby'             => 'post__in',
			)
		);

		return $photos;
	}

	/**
	 * Add custom image sizes so WordPress generates images of the appropriate size.
	 */
	public static function gallery_add_image_sizes() {
		add_image_size( 'gmr-gallery',               1400, 1400      );
		add_image_size( 'gmr-gallery-thumb',     120,  120, true );
	}

}
