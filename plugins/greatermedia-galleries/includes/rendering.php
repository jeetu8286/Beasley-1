<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

class GreaterMediaGallery {

	public static $strip_shortcodes = false;

	public static function init() {
		if ( ! is_admin() && ! defined( 'WP_CLI' ) && ( ! defined( 'DOING_CRON' ) || ! DOING_CRON ) ) {
			// Override the core gallery shortcode with our own handler, only on the front end
			remove_shortcode( 'gallery' );
			add_shortcode( 'gallery', array( __CLASS__, 'render_gallery' ) );
		}

		// If we need to manually render somewhere, like on the top of a single-gallery template
		add_action( 'gmr_gallery', array( __CLASS__, 'do_gallery_action' ) );

		// Remove gallery shortcodes from content, since we have these at the top of single-page
		add_filter( 'the_content', array( __CLASS__, 'strip_for_single_gallery' ) );

		// Register scripts
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'register_scripts' ), 10 );
	}

	/**
	 * Registers gallery scripts to use on the front end.
	 *
	 * @static
	 * @access public
	 * @action wp_enqueue_scripts
	 */
	public static function register_scripts() {
		$postfix = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min';

		/* all js files are being concatenated into a single js file.
		 * This include all js files for cycle2, located in `assets/js/vendor/cycle2/`
		 * and `gmr_gallery.js`, located in `assets/js/src/`
		 */
		wp_enqueue_script( 'gmr-gallery', GREATER_MEDIA_GALLERIES_URL . "assets/js/gmr_gallery{$postfix}.js", array( 'jquery' ), GREATER_MEDIA_GALLERIES_VERSION, true );
		wp_enqueue_style( 'gmr-gallery', GREATER_MEDIA_GALLERIES_URL . "assets/css/gmr_gallery{$postfix}.css", array(), GREATER_MEDIA_GALLERIES_VERSION );
	}

	/**
	 * Strips gallery shortcodes for content, on pages where we know we've run the action instead
	 *
	 * @param string $content
	 *
	 * @return string Final content with galleries removed
	 */
	public static function strip_for_single_gallery( $content ) {
		if ( self::$strip_shortcodes ) {
			$content = preg_replace( '/\[gallery.*?\]/', '', $content );
		}

		return $content;
	}

	/**
	 * Get an array of photos for a gallery.
	 *
	 * @param $post
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
			$thumb = wp_get_attachment_image_src( $id, 'gmr-gallery-thumbnail' );

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
	 * Returns a WP_Query that corresponds to the IDs provided
	 *
	 * @param array $ids Array of image IDs
	 *
	 * @return WP_Query
	 */
	public static function get_query_for_ids( $ids ) {
		$photos = new WP_Query(
			array(
				'ignore_sticky_posts' => true,
				'post__in'            => $ids,
				'post_status'         => 'inherit',
				'post_type'           => 'attachment',
				'posts_per_page'      => count( $ids ),
				'orderby'             => 'post__in',
			)
		);

		return $photos;
	}

	/**
	 * Gets an array of ids for the attachments in the gallery
	 * @param $post
	 * @return Array
	 */
	public static function get_attachment_ids_for_post( $post ) {
		$array_ids = get_post_meta( $post->ID, 'gallery-image' );

		if ( empty( $array_ids ) && preg_match_all( '/\[gallery.*ids=.(.*).\]/', $post->post_content, $ids ) ) {
			foreach( $ids[1] as $match ) {
				$array_id = explode( ',', $match );
				$array_id = array_map( 'intval', $array_id );

				$array_ids = array_merge( $array_ids, $array_id );
			}
		}

		return ! empty( $array_ids )
			? $array_ids
			: null;
	}

	/**
	 * Gets a WP_Query for the attachments in the gallery
	 * @param $post
	 * @return WP_Query
	 */
	public static function get_query_for_post( $post ) {
		$array_ids = get_post_meta( $post->ID, 'gallery-image' );

		if ( empty( $array_ids ) && preg_match_all( '/\[gallery.*ids=.(.*).\]/', $post->post_content, $ids ) ) {
			foreach( $ids[1] as $match ) {
				$array_id = explode( ',', $match );
				$array_id = array_map( 'intval', $array_id );

				$array_ids = array_merge( $array_ids, $array_id );
			}
		}

		return ! empty( $array_ids )
			? self::get_query_for_ids( $array_ids )
			: null;
	}

	/**
	 * Renders a gallery for a post when do_action( 'gmr_gallery' ) is called
	 */
	public static function do_gallery_action( $strip_shortcodes ) {
		// So that we remove the gallery from content, since we're rendering it now
		self::$strip_shortcodes = true;

		$ids = self::get_attachment_ids_for_post( get_queried_object() );
		if ( ! empty( $ids ) ) {
			$content = self::render_gallery( array( 'ids' => implode( ',', $ids ) ) );
			echo apply_filters( 'the_secondary_content', $content );
		}
	}

	public static function render_gallery( $attr ) {
		$attr = shortcode_atts( array( 'ids' => '' ), $attr );
		$ids = explode( ',', $attr['ids'] );
		$ids = array_filter( array_map( 'intval', $ids ) );
		if ( empty( $ids ) ) {
			return gallery_shortcode( $attr );
		}

		$post = get_queried_object();
		$sponsored_image = get_field( 'sponsored_image', $post );

		$image = current( $ids );
		$content = sprintf(
			'<a href="%s/view/%s/"><div>%s</div>',
			esc_attr( untrailingslashit( get_permalink( $post ) ) ),
			esc_attr( get_post_field( 'post_name', $sponsored_image ? $sponsored_image : $image ) ),
			wp_get_attachment_image( $image, 'gmr-gallery-grid-featured' )
		);

		$content .= '<div style="display:flex">';

		for ( $i = 1, $len = count( $ids ); $i < $len && $i < 6; $i++ ) {
			$content .= '<div style="max-width:20%">' . wp_get_attachment_image( $ids[ $i ], 'gmr-gallery-grid-thumb' ) . '</div>';
		}

		$content .= '</div></a>';

		return $content;
	}

}

GreaterMediaGallery::init();
