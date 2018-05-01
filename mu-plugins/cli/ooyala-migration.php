<?php

use League\Csv\Reader;

class Beasley_Ooyala_Migration_CLI {

	/**
	 * Migrates old Ooyala content to livestream based on a CSV mapping
	 *
         * @subcommand migrate
         * @synopsis <csv_file>
         */
	public function migrate( $args, $assoc_args ) {
		$csv = reset( $args );

		if ( ! file_exists( $csv ) ) {
			WP_CLI::error( "Unable to read {$csv}" );
		}

		if ( ! ini_get( "auto_detect_line_endings" ) ) {
			ini_set( "auto_detect_line_endings", '1' );
		}

		$csv = Reader::createFromPath( $csv, 'r' );
		$csv->setHeaderOffset(0);

		$records = $csv->getRecords();

		foreach( $records as $record ) {
			$this->_replace_ooyala_shortcodes( $record );
		}

		WP_CLI::success( 'done' );
	}

	public function _replace_ooyala_shortcodes( $record ) {
		global $wpdb;
		$ooyala_id = $record['video_map_id'];
		$account_id = $record['livestream_account_id'];
		$event_id = $record['livestream_event_id'];
		$video_id = $record['livestream_video_id'];

		WP_CLI::log( "Checking for $ooyala_id" );

		// check for post meta that matches
		$query = "select * from {$wpdb->postmeta} where meta_value like '%de=\"{$ooyala_id}%'";
		$results = $wpdb->get_results( $query );
		if ( count( $results ) > 0 ) {
			$new_shortcode = $this->_generate_livestream_shortcode( $account_id, $event_id, $video_id );
			WP_CLI::log( " - New Shortcode: " . $new_shortcode );
			foreach( $results as $result ) {
				if ( $result->meta_key == 'gmr-player' ) {
					WP_CLI::log( " - - Replacing post meta on {$result->post_id}" );
					update_post_meta( $result->post_id, $result->meta_key, $new_shortcode );
				} else {
					WP_CLI::warning( " - - Unexpected meta key: {$result->meta_key} for post {$result->post_id}" );
				}
			}
		}

		// check for posts that match
		$query = new WP_Query(array(
			's' => 'code="' . $ooyala_id . '"',
			'posts_per_page' => 500,
		));
		if ( $query->have_posts() ) {
			while( $query->have_posts() ) {
				$post = $query->next_post();
				dout($post,true);
				// @todo pickup here.. Not finding these IDs in post content. Waiting on https://tenup.teamwork.com/#tasks/17126172
			}
		}
	}

	public function _generate_livestream_shortcode( $account_id, $event_id, $video_id ) {
		return sprintf( '[livestream_video account_id="%s" event_id="%s" video_id="%s"]', $account_id, $event_id, $video_id );
	}

}

WP_CLI::add_command( 'ooyala-migration', 'Beasley_Ooyala_Migration_CLI' );

