<?php
/**
 * Utility trait
 *
 * @package Bbgi
 */

namespace Bbgi;

trait Util {
	/**
	 * Checks if a URL is absoltue or not
	 *
	 * @param string $url
	 *
	 * @return boolean
	 */
	protected function is_absolute_url( $url ) {
		$pattern = "/^(?:ftp|https?|feed)?:?\/\/(?:(?:(?:[\w\.\-\+!$&'\(\)*\+,;=]|%[0-9a-f]{2})+:)*
		(?:[\w\.\-\+%!$&'\(\)*\+,;=]|%[0-9a-f]{2})+@)?(?:
		(?:[a-z0-9\-\.]|%[0-9a-f]{2})+|(?:\[(?:[0-9a-f]{0,4}:)*(?:[0-9a-f]{0,4})\]))(?::[0-9]+)?(?:[\/|\?]
		(?:[\w#!:\.\?\+\|=&@$'~*,;\/\(\)\[\]\-]|%[0-9a-f]{2})*)?$/xi";

		return (bool) preg_match( $pattern, $url );
	}

	/**
	 * Checks if the provided URL is internal or not.
	 *
	 * @param string $url The URL to check for.
	 *
	 * @return boolean
	 */
	protected function is_internal_url( $url ) {
		$parsed_home_url = parse_url( home_url() );
		$parsed_url      = parse_url( $url );

		return apply_filters(
			'bbgi_page_endpoint_is_internal_url',
			$parsed_home_url['host'] === $parsed_url['host']
		);
	}

	/**
	 * Checks if the current date is a future date
	 *
	 * @param string $typenow The current post type 
	 *
	 * @return bool True if the current date is a future date, false otherwise  
	 */
	protected function is_future_date($typenow) {
		$post_types = array( 'listicle_cpt', 'affiliate_marketing', 'gmr_gallery'  );

		$today = new \DateTime();
		$today = $today->format("Y-m-d");
		$effective_date = new \DateTime("2022-12-06");
		$effective_date = $effective_date->format("Y-m-d");

	   	// If Current Date is Future Date
	   	if (in_array( $typenow, $post_types ) && $today > $effective_date) {
			return true;
	   	}
	   	return false;
	}

	/**
	 * Convert array or custom post type objects to a string of content
	 *
	 * @param  mixed $contentVal Content from a custom post type.
	 * @param  string $post_type Custom post type name.
	 * @return string Content string
	 */
	protected function stringify_selected_cpt($contentVal, $post_type)
	{
		if (is_array($contentVal) || is_object($contentVal)) {
			if (WP_DEBUG) {
				error_log( 'WARNING: ' . $post_type . ' CONTENT IS AN OBJECT OR ARRAY: ' );
				error_log(print_r($contentVal, true));
			}
			return is_object($contentVal) && isset($contentVal->post_content) ? $contentVal->post_content : print_r($contentVal, true);
		} else {
			return $contentVal;
		}
	}

	/**
	 * Returns object ID to identifier relationship.
	 * 
	 * @param string $post_type Type of the post to check.
	 * @param string $embed_post_type Type of the post to embed.
	 * @param int $attr_object_id The object ID to check against.
	 * @param string $syndication_name Name of the post for reference.
	 *
	 * @return int $objectId The ID of the post related to the given parameters. 
	 */
	protected function getObjectId($post_type, $embed_post_type = "listicle_cpt", $attr_object_id, $syndication_name)
	{
		$objectId = 0;
		if ( $this->is_future_date($post_type) ) {
			return 0;
		}

		if( !empty( $syndication_name ) ) {
			$meta_query_args = array(
				'meta_key'    => 'syndication_old_name',
				'meta_value'  => $syndication_name,
				'post_status' => 'publish',
				'post_type'   => $embed_post_type
			);

			$existing = get_posts( $meta_query_args );

			if ( !empty( $existing ) ) {
				$existing_post = current( $existing );
				$objectId = intval( $existing_post->ID );
			}
		}

		if( empty($objectId) && !empty( $attr_object_id ) && !empty( get_post( $attr_object_id ) ) ) {
			$objectId = intval( $attr_object_id );
		}

		return $objectId;
	}

	/**
	 * Get post metadata from the post
	 *
	 * @param  string $value  Meta key/Post meta data to retrieve
	 * @param  object $post   WP_Post object.
	 * @return mixed|false
	 */
	protected function get_post_metadata_from_post( $value, $post ) {
		$field = get_post_meta( $post->ID, $value, true );

		if (empty($field)) {
            return false;
        }

        return is_array($field) ? stripslashes_deep($field) : stripslashes(wp_kses_decode_entities($field));
	}

	/**
	 * Verifies the post.
	 *
	 * @param   int      $post        The post ID to be verified. 
	 * @param   string   $post_type   Post type of the post to be checked against.
	 * @param   string   $syndication_name  Post name of the post to be checked against.
	 * 
	 * @return  mixed    Post object on success, null on failure.
	 */
	protected function verify_post( $post, $post_type, $syndication_name ) {
		$post = get_post($post);
        
        if( $post->post_type !== $post_type || $post->post_name !== $syndication_name || $post->post_status !== 'publish' ) {
            return null;
        }

        return $post;
	}
}
