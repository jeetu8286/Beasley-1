<?php
/**
 * Created by Eduard
 * Date: 06.11.2014 18:19
 */

class BlogData {

	public static $taxonomies = array( 'post_tag', 'collection');
	public static $content_site_id = 1;

	public static function init() {
		add_action( 'admin_init', array( __CLASS__, 'register_subscription_setting' ) );
		self::$content_site_id = defined( 'GMR_CONTENT_SITE_ID' ) ? GMR_CONTENT_SITE_ID : self::$content_site_id;
		update_option( 'syndication_last_performed', 0 );
	}


	// Rgister setting to store last syndication timestamp
	public static function register_subscription_setting() {
		register_setting( 'syndication_last_performed', 'syndication_last_performed', 'intval' );
	}

	/**
	 * Get all terms of all taxonomies from the content site
	 *
	 * @return array
	 */
	public static function getTerms() {
		global $switched;

		$terms = array();
		switch_to_blog( self::$content_site_id );

		foreach( self::$taxonomies as $taxonomy ) {
			if( taxonomy_exists( $taxonomy ) ) {
				$args = array(
					'get'        => 'all',
					'hide_empty' => false
				);

				$terms[ $taxonomy ] = get_terms( $taxonomy, $args );
			}
		}

		restore_current_blog();

		return $terms;
	}


	/**
	 * Get all active subscription ordered by default post status
	 * Defualt post status "Draft" has higher priority as it's less public
	 *
	 * @return array of post objects
	 */
	public static function GetActiveSubscriptions() {

		$args = array(
			'post_type' => 'subscription',
			'post_status' => 'publish',
			'meta_key' => 'subscription_post_status',
			'orderby' => 'meta_value',
			'order' => 'ASC',
		);

		$active_subscriptions = get_posts( $args );

		return $active_subscriptions;
	}

	/**
	 * Query content site and return full query result
	 *
	 * @param int    $subscription_id
	 * @param string $post_type
	 *
	 * @return array WP_Post objects
	 */
	public static function QueryContentSite( $subscription_id, $post_type = 'post' ) {
		global $switched;

		$result = array();

		$last_queried = get_option( 'syndication_last_performed', 0);

		// query args
		$args = array(
			'post_type'     =>  $post_type,
			'post_status'   =>  'publish',
			'posts_per_page' => -1,
			'tax_query'     =>  array(
				'relation'  =>  'OR'
			),
			'date_query'    => array(
				'after'     => $last_queried
			)
		);

		// get filters to query content site
		foreach( self::$taxonomies as $taxonomy ) {
			$subscription_filter = get_post_meta( $subscription_id, 'subscription_filter_terms-' . $taxonomy, true );
			$tax_query['taxonomy'] = $taxonomy;
			$tax_query['field'] = 'id';
			$tax_query['terms'] = explode( ',', $subscription_filter );
			array_push( $args['tax_query'], $tax_query );
		}

		// switch to content site
		switch_to_blog( self::$content_site_id );

		// get all postst matching filters
		$query = get_posts( $args );
		
		// get all metas
		foreach ( $query as $single_result ) {
			$metas	= get_metadata( $post_type, $single_result->ID, true );
			$result[] = array(
				'post_obj'      => $single_result,
				'post_metas'    => $metas
			);
		}

		restore_current_blog();

		return $result;
	}

	/**
	 * TODO:        Assign default terms
	 * @param       $post
	 * @param array $metas
	 * @param       $defaults
	 */
	public static function ImportPosts( $post, $metas = array(), $defaults ) {

		// prepare arguments for wp_insert_post
		$args = array(
			'post_title'    =>  $post->post_title,
			'post_content'  =>  $post->post_content,
			'post_type'     =>  $post->post_type,
			'post_name'     =>  $post->post_name,
			'post_status'   =>  $defaults['status']
		);

		// create unique meta value for imported post
		$post_hash = trim( $post->post_title ) . $post->post_modified;
		$post_hash = md5( $post_hash );

		// query to check whether post already exist
		$check_args   =   array(
			'post_name'     =>  $post->post_name,
			'post_type'     =>  $post->post_type,
		);

		$existing = get_posts( $check_args );

		// check whether post with that name exist
		if( !empty( $existing ) ) {
			$existing_id = $existing[0]->ID;
			$hash_value = get_post_meta( $existing_id, 'syndication_import', true );
			if( $hash_value != $post_hash ) {
				// post has been updated, override existing one
				$args['ID'] = $existing_id;
				wp_insert_post( $args );
				if( !empty( $metas ) ) {
					foreach ( $metas as $meta_key => $meta_value ) {
						update_post_meta( $existing_id, $meta_key, $meta_value );
					}
				}
				update_post_meta( $existing_id, 'syndication_import', $post_hash );
			}
		} else {
			$new_post_id = wp_insert_post( $args );
			if( is_numeric( $new_post_id ) && !empty( $metas ) ) {
				foreach ( $metas as $meta_key => $meta_value ) {
					update_post_meta( $new_post_id, $meta_key, $meta_value );
				}
			}
			update_post_meta( $new_post_id, 'syndication_import', $post_hash );
		}
	}
}

BlogData::init();