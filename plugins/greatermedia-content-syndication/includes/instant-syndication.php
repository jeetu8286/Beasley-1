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
			add_action( 'save_post', array( __CLASS__, 'save_post' ) );
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

		if ( in_array( $post->post_type, SyndicationCPT::$supported_subscriptions ) && in_array( $post->post_status, SyndicationCPT::$supported_syndication_statuses ) ) {
			if ( function_exists( 'wp_async_task_add' ) ) {
				wp_async_task_add( self::$network_subscription_check_action, 'high' );
			}
		}
	}

	/**
	 * Iterates over all sites in the network, and checks for any new content in any active subscriptions
	 */
	public static function check_all_network_subscriptions() {
		$args = array(
			'fields' => 'ids',
			'number' => 500,
		);

		if ( defined( 'GMR_CONTENT_SITE_ID' ) ) {
			$args['site__not_in'] = GMR_CONTENT_SITE_ID;
		}

		$sites = get_sites( $args );

		foreach( $sites as $site_id ) {
			switch_to_blog( $site_id );
			// @todo need to pass tags, categories, and collections for the post, so we can queue up ONLY RELEVANT subscriptions to speed this up!
			wp_async_task_add( self::$single_site_sub_check_action, array( 'site_id' => $site_id ), 'high' );
			restore_current_blog();
		}
	}

	public static function check_single_site( $args ) {
		// should already be on the proper site, because we called switch_to_blog before queuing the job, but just in case
		$site_id = $args['site_id'];

		if ( $site_id != get_current_blog_id() ) {
			throw new Exception( "Site ID does not match expected site ID" );
		}

		CronTasks::run_syndication();
	}

}

// Adhering to current code style
InstantSyndication::init();
