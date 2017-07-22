<?php
/**
 * Responsible for ensuring that posts are syndicated across the network as quickly as possible.
 *
 * When we're on the content factory and a post is saved of one of the supported post types, we queue a job
 * in the job server to check all active subscriptions on all the network sites.
 */

class InstantSyndication {

	public static $network_subscription_check_action = 'instant-syndication-network-subscription-check';

	public static $single_site_sub_check_action = 'instant-syndication-check-single-site';

	public static function init() {
		if ( ! is_multisite() ) {
			return;
		}

		if ( defined( 'GMR_CONTENT_SITE_ID' ) && GMR_CONTENT_SITE_ID == get_current_blog_id() ) {
			add_action( 'save_post', array( __CLASS__, 'save_post' ), 100 );
		}

		add_action( self::$network_subscription_check_action, [ __CLASS__, 'check_all_network_subscriptions'] );
		add_action( self::$single_site_sub_check_action, [ __CLASS__, 'check_single_site' ] );
	}

	/**
	 * Checks if the post type is supported for syndication and a valid status, and if so, check all subscriptions
	 *
	 * @param $post_id
	 */
	public static function save_post( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		$post = get_post( $post_id );

		$categories = wp_list_pluck( (array) get_the_terms( $post_id, 'category' ), 'name' );
		$tags = wp_list_pluck( (array) get_the_terms( $post_id, 'post_tag' ), 'name' );
		$collections = wp_list_pluck( (array) get_the_terms( $post_id, 'collection' ), 'name' );

		if ( in_array( $post->post_type, SyndicationCPT::$supported_subscriptions ) && in_array( $post->post_status, SyndicationCPT::$supported_syndication_statuses ) ) {
			if ( function_exists( 'wp_async_task_add' ) ) {
				wp_async_task_add( self::$network_subscription_check_action, array( 'categories' => $categories, 'tags' => $tags, 'collections' => $collections ),'high' );
			}
		}
	}

	/**
	 * Iterates over all sites in the network, and checks for any new content in any active subscriptions
	 */
	public static function check_all_network_subscriptions( $async_args ) {
		$query_args = array(
			'fields' => 'ids',
			'number' => 500,
		);

		if ( defined( 'GMR_CONTENT_SITE_ID' ) ) {
			$args['site__not_in'] = GMR_CONTENT_SITE_ID;
		}

		$sites = get_sites( $query_args );

		foreach( $sites as $site_id ) {
			switch_to_blog( $site_id );
			wp_async_task_add( self::$single_site_sub_check_action, array_merge( $async_args, array( 'site_id' => $site_id ) ), 'high' );
			restore_current_blog();
		}
	}

	/**
	 * Checks for any subscriptions on this site that seem to match the categories, tags, or collections assigned to the
	 * post (based on data in args). If they match, we process the syndication for that site.
	 *
	 * Avoids having to queue all the subscription when only a small handful will likely match the post that was just
	 * edited on the content site.
	 *
	 * @param $args
	 *
	 * @throws Exception
	 */
	public static function check_single_site( $args ) {
		// should already be on the proper site, because we called switch_to_blog before queuing the job, but just in case
		$site_id = $args['site_id'];

		if ( $site_id != get_current_blog_id() ) {
			throw new Exception( "Site ID does not match expected site ID" );
		}

		$categories = $args['categories'];
		$tags = $args['tags'];
		$collections = $args['collections'];

		// Find subscriptions that match categories
		$cat_subs = BlogData::GetActiveSubscriptions(array(
			'meta_query' => array(
				array(
					'key' => 'subscription_enabled_filter',
					'value' => 'category',
				),
				array(
					'key' => 'subscription_filter_terms-category',
					'value' => $categories,
					'compare' => 'IN',
				),
			)
		));
		if ( ! empty( $cat_subs ) ) {
			self::queue_subscriptions( $cat_subs );
		}

		// Find subscriptions that match any of the tags on the post
		$all_tag_subs = BlogData::GetActiveSubscriptions(array(
			'meta_query' => array(
				array(
					'key' => 'subscription_enabled_filter',
					'value' => 'post_tag',
				),
			)
		));
		if ( ! empty( $all_tag_subs ) ) {
			foreach( $all_tag_subs as $tag_sub ) {
				$tag_filters = get_post_meta( $tag_sub->ID, 'subscription_filter_terms-post_tag', true );
				foreach( explode( ',', $tag_filters ) as $tag_filter ) {
					if ( in_array( $tag_filter, $tags ) ) {
						self::queue_subscriptions( array( $tag_sub ) );
						break;
					}
				}
			}
		}

		// Find subscriptions that match the collections
		$collection_subs = BlogData::GetActiveSubscriptions( array(
			'meta_query' => array(
				array(
					'key' => 'subscription_enabled_filter',
					'value' => 'collection',
				),
				array(
					'key' => 'subscription_filter_terms-collection',
					'value' => $collections,
					'compare' => 'IN',
				),
			)
		) );
		if ( ! empty( $collection_subs ) ) {
			self::queue_subscriptions( $collection_subs );
		}
	}

	public static function queue_subscriptions( $subscriptions ) {
		foreach( $subscriptions as $subscription ) {
			if ( ! isset( $subscription->ID ) ) {
				continue;
			}

			wp_async_task_add( 'gmr_do_syndication', array( 'subscription' => $subscription->ID ), 'high' );
		}
	}

}

// Adhering to current code style
InstantSyndication::init();
