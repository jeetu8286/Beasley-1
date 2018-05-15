<?php

use League\Csv\Reader;

class Beasley_Ooyala_Migration_CLI {

	/**
	 * Verifies that shortcodes in post_content and meta are in the mapping files
	 *
	 * @subcommand verify
	 * @synopsis <csv_file>
	 */
	public function verify( $args, $assoc_args ) {
		global $wpdb;

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

		// We'll dump the mapped codes in this array, for easy checking later
		$mapped_ids = array();

		WP_CLI::log( "Building array of IDs" );
		foreach( $records as $record ) {
			$mapped_ids[ $record['video_map_id'] ] = true;
		}

		// check for posts that match
		$query = new WP_Query(array(
			's' => '[ooyala ',
			'posts_per_page' => -1,
			'post_type' => 'any',
		));
		if ( $query->have_posts() ) {
			while( $query->have_posts() ) {
				$post = $query->next_post();

				$shortcodes = array();
				preg_match_all( '/\[ooyala\s[^\]]*\]/i', $post->post_content, $shortcodes );

				if ( ! empty( $shortcodes[0] ) ) {
					foreach( $shortcodes[0] as $shortcode ) {
						$code = array();
						preg_match( '/\scode="([^"]*)"/i', $shortcode, $code );
						$code = $code[1];

						// @todo check if code in array.
						if ( $mapped_ids[ $code ] ) {
							WP_CLI::success( "Matched Code [post_content]: $code" );
						} else {
							WP_CLI::warning( "Code doesn't match [post_content]: $code" );
						}
					}
				}
			}
		}

		$query = "select * from {$wpdb->postmeta} where meta_key='gmr-player'";
		$results = $wpdb->get_results( $query );

		foreach ( $results as $result ) {
			$shortcode = $result->meta_value;
			$code = array();
			preg_match( '/\scode="([^"]*)"/i', $shortcode, $code );
			$code = $code[1];

			// @todo check if code in array.
			if ( $mapped_ids[ $code ] ) {
				WP_CLI::success( "Matched Code [meta]: $code" );
			} else {
				WP_CLI::warning( "Code doesn't match [meta]: $code" );
			}
		}

		WP_CLI::success( 'done' );

	}

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

	protected function _replace_ooyala_shortcodes( $record ) {
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
			'post_type' => 'any',
		));
		if ( $query->have_posts() ) {
			while( $query->have_posts() ) {
				$post = $query->next_post();

				$matches = array();
				preg_match_all( '/\[ooyala\s[^\]]*\]/i', $post->post_content, $matches );

				if ( ! empty( $matches[0] ) ) {
					foreach( $matches[0] as $shortcode ) {
						// Since we're only working with one ooyala code at a time, we'll ignore any shortcodes that don't match
						if ( stripos( $shortcode, $ooyala_id ) !== false ) {
							$new_shortcode = $this->_generate_livestream_shortcode( $account_id, $event_id, $video_id );
							WP_CLI::log( " - Updating post {$post->ID} from $shortcode to $new_shortcode" );
							$post->post_content = str_replace( $shortcode, $new_shortcode, $post->post_content );
							wp_update_post( $post );
						}
					}
				}
			}
		}
	}

	protected function _generate_livestream_shortcode( $account_id, $event_id, $video_id ) {
		return sprintf( '[livestream_video account_id="%s" event_id="%s" video_id="%s"]', $account_id, $event_id, $video_id );
	}

}

WP_CLI::add_command( 'ooyala-migration', 'Beasley_Ooyala_Migration_CLI' );

