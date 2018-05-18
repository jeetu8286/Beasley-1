<?php

class GMR_Syndication_CLI extends WP_CLI_Command {

	/**
	 * Imports posts for all subscriptions with optional date constraints
	 *
	 * ## OPTIONS
	 *
	 * [<start>]
	 * : Import articles that were added on or after this date. YYYY-MM-DD
	 *
	 * [<end>]
	 * : Import articles that were added before this date. YYYY-MM-DD
	 *
	 * [<reload>]
	 * : Forces syndication to reload content.
	 *
	 * ## EXAMPLES
	 *
	 * wp gmr-syndication import
	 *
	 * wp gmr-syndication import 2014-09-30
	 *
	 * @synopsis [<start>] [<end>] [--reload]
	 *
	 * @subcommand import
	 *
	 * @param $args
	 * @param $assoc_args
	 */
	public function import( $args, $assoc_args ) {
		$start = array_shift( $args );
		$end = array_shift( $args );
		$force = ! empty( $assoc_args['reload'] );

		if ( ! empty( $start ) ) {
			if ( ! $this->validate_date( $start ) ) {
				\WP_CLI::error( "Invalid Start Date" );
			}
			$start .= ' 00:00:00';
		}

		if ( ! empty( $end ) ) {
			if ( ! $this->validate_date( $end ) ) {
				\WP_CLI::error( "Invalid End Date" );
			}
			$end .= ' 23:59:59';
		}

		// Do magic here
		$active_subsriptions = BlogData::GetActiveSubscriptions();

		foreach ( $active_subsriptions as $single_subscription ) {

			if ( empty( $start ) ) {
				$start = date( 'Y-m-d H:i:s', mktime( 0, 0, 0, 1, 1, 2012 ) );
			}

			$result = BlogData::QueryContentSite( $single_subscription->ID, $start, $end );

			$taxonomy_names = get_object_taxonomies( 'post', 'objects' );
			$defaults = array(
				'status' => get_post_meta( $single_subscription->ID, 'subscription_post_status', true ),
			);

			foreach ( $taxonomy_names as $taxonomy ) {
				$label = $taxonomy->name;

				// Use get_post_meta to retrieve an existing value from the database.
				$terms = get_post_meta( $single_subscription->ID, 'subscription_default_terms-' . $label, true );
				$terms = explode( ',', $terms );
				$defaults[$label] = $terms;
			}
			$subscription_source = absint( get_post_meta( $single_subscription->ID, 'subscription_source', true ) );
			if ( $subscription_source > 0 ) {
				BlogData::set_content_site_id( $subscription_source );
			} else {
				//reset it to default in case of empty / Legacy subscriptions
				BlogData::get_content_site_id();
			}

			$total = count( $result );
			$notify = new \cli\progress\Bar( "Importing $total articles", $total );

			foreach ( $result as $single_post ) {
				if ( ! empty( $single_post['post_obj'] ) ) {
					BlogData::ImportPosts(
							$single_post['post_obj']
							, $single_post['post_metas']
							, $defaults
							, $single_post['featured']
							, $single_post['attachments']
							, $single_post['gallery_attachments']
							, $single_post['galleries']
							, $single_post['term_tax']
							, $force
					);
				}

				$notify->tick();
			}

			$notify->finish();
		}

		\WP_CLI::success( "Finished Import" );
	}

	/**
	 * : Import articles from a single subscription
	 *
	 * <subscription_id>
	 * : Subscription ID to process
	 *
	 * [<force>]
	 * : Forces syndication to reload content.
	 *
	 * [<start_date>]
	 * : Choose a start date. Y-m-d H:i:s
	 *
	 * ## EXAMPLES
	 *
	 * wp gmr-syndication import-subscription 123
	 *
	 * wp gmr-syndication import-subscription 123 --force --ignore-detached
	 *
	 * @synopsis <subscription_id> [--force] [--ignore-detached] [--start_date=<start-date>]
	 *
	 * @subcommand import-subscription
	 */
	public function import_single_subscription( $args, $assoc_args ) {
		$subscription_id = array_shift( $args );
		$force = ! empty( $assoc_args['force'] );
		$ignore_detached = ! empty( $assoc_args['ignore-detached'] );
		$start_date = ! empty( $assoc_args['start_date'] ) ? $assoc_args['start_date'] : false;

		if ( $start_date ) {
			add_filter( 'beasley_syndication_query_start_date', function( $filter_date ) use ( $start_date ) {
				$start_date = date( 'Y-m-d H:i:s', strtotime( $start_date ) );

				return $start_date;
			} );
		}

		if ( $ignore_detached ) {
			add_filter( 'beasley_syndication_post_is_detached', '__return_false' );
		}

		BlogData::run( $subscription_id, 0, $force );
	}

	protected function validate_date( $date ) {
		$pattern = '/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/';
		if ( ! preg_match( $pattern, $date ) ) {
			return false;
		}
		return $date;
	}

	/**
	 * Updates syndicated post with the latest version of original post.
	 *
	 * ## OPTIONS
	 *
	 * [<id>]
	 * : Post id to update.
	 *
	 *
	 * ## EXAMPLES
	 *
	 * wp gmr-syndication reload
	 *
	 * wp gmr-syndication reload 20109
	 *
	 * @synopsis [<id>]
	 *
	 * @param $args
	 * @param $assoc_args
	 */
	public function reload( $args, $assoc_args ) {
		if ( empty( $args ) ) {
			\WP_CLI::error( 'Please, provide post id to update.' );
		}

		$syndicated_post = get_post( $args[0] );
		if ( empty( $syndicated_post ) ) {
			\WP_CLI::error( 'The post has not been found.' );
		}

		$info = get_post_meta( $syndicated_post->ID, 'syndication_old_data', true );
		if ( empty( $info ) ) {
			\WP_CLI::error( 'Syndication data has not been found.' );
		} else {
		}

		$info = unserialize( $info );
		switch_to_blog( $info['blog_id'] );
		$original_post = get_post( $info['id'] );
		$data = BlogData::PostDataExtractor( $original_post );
		restore_current_blog();

		BlogData::ImportPosts(
			$data['post_obj']
			, $data['post_metas']
			, array( 'status' => $syndicated_post->post_status )
			, $data['featured']
			, $data['attachments']
			, $data['gallery_attachments']
			, $data['galleries']
			, $data['term_tax']
			, true
		);

		\WP_CLI::success( 'The post has been updated.' );
	}

	/**
	 * Adds metadata that detaches the post from the original content factory source based on modified date
	 *
	 * ## OPTIONS
	 *
	 * <csv_file>
	 * : Path to write csv with detached posts to
	 *
	 * [--dry-run]
	 * :Don't save the metadata, just report on what would change and write the CSV
	 *
	 * [--network-wide]
	 * : Run on the whole network
	 *
	 * ## EXAMPLES
	 *
	 * wp gmr-syndication detach-posts-by-modified ./output.csv
	 *
	 * wp gmr-syndication detach-posts-by-modified ./output.csv --network-wide
	 *
	 * wp gmr-syndication detach-posts-by-modified ./output.csv --dry-run
	 *
	 * @synopsis <csv_file> [--network-wide] [--dry-run]
	 *
	 * @subcommand detach-posts-if-modified
	 */
	public function detach_posts_if_modified( $args, $assoc_args ) {
		$network_wide = isset( $assoc_args['network-wide'] ) ? true : false;
		$dry_run = isset( $assoc_args['dry-run'] ) ? true : false;

		$csv_file = fopen( $args[0], 'w' );

		$headers = array(
			'blog_id',
			'post_id',
			'post_title',
			'post_url',
			'source_site_id'
		);

		fputcsv( $csv_file, $headers );

		$query_args = array(
			'post_type' => SyndicationCPT::$supported_subscriptions,
			'post_status' => 'any',
		);

		if ( $network_wide ) {

			$network_args = array(
				'number' => 500,
				'fields' => 'ids',
			);
			$site_query = new WP_Site_Query( $network_args );

			foreach ( $site_query->get_sites() as $site_id ) {
				\Cmmarslender\PostIterator\Logger::log( "----------------------------------------");
				\Cmmarslender\PostIterator\Logger::log( "Switching to Site {$site_id}");
				\Cmmarslender\PostIterator\Logger::log( "----------------------------------------");
				switch_to_blog( $site_id );

				$iterator = new \Beasley\Syndication\CLI\DetachPostIterator( $query_args, $csv_file, $dry_run );
				$iterator->go();

				restore_current_blog();
			}
		} else {
			$iterator = new \Beasley\Syndication\CLI\DetachPostIterator( $query_args, $csv_file, $dry_run );
			$iterator->go();
		}

		fclose( $csv_file );
	}

}

WP_CLI::add_command( 'gmr-syndication', 'GMR_Syndication_CLI' );
