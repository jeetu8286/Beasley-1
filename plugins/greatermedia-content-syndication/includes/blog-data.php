<?php
/**
 * Created by Eduard
 * Date: 06.11.2014 18:19
 */

class BlogData {

	private static $post_types = array( 'post' );

	public static function getTerms() {
		global $switched;

		$terms = array();

		switch_to_blog( 1 );

		foreach( self::$post_types as $post_type ) {
			$taxonomy_names = get_object_taxonomies( $post_type );
			foreach( $taxonomy_names as $taxonomy ) {
				$args = array(
					'get'           => 'all',
					'hide_empty'    => false
				);
				$terms[] = get_terms( $taxonomy , $args);
			}
		}

		restore_current_blog();

		return $terms;
	}
} 