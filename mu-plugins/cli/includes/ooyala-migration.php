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

		$mapped_ids = $this->_get_csv_mapping( $csv );

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
	 * @synopsis   <csv_file>
	 */
	public function migrate( $args, $assoc_args ) {
		global $wpdb;

		$csv = reset( $args );

		$mapped_ids = $this->_get_csv_mapping( $csv );

		// check for posts that match
		$query = new WP_Query(array(
			's' => '[ooyala ',
			'posts_per_page' => -1,
			'post_type' => 'any',
		));
		if ( $query->have_posts() ) {
			while( $query->have_posts() ) {
				$post = $query->next_post();
				WP_CLI::log( "Checking post {$post->ID}" );

				$shortcodes = array();
				preg_match_all( '/\[ooyala\s[^\]]*\]/i', $post->post_content, $shortcodes );

				if ( ! empty( $shortcodes[0] ) ) {
					foreach( $shortcodes[0] as $shortcode ) {
						$code = array();
						preg_match( '/\scode="([^"]*)"/i', $shortcode, $code );
						$code = $code[1];

						// @todo check if code in array.
						if ( $mapped_ids[ $code ] ) {
							$new_shortcode = $this->_generate_livestream_shortcode( $mapped_ids[ $code ]['livestream_account_id'], $mapped_ids[ $code ]['livestream_event_id'], $mapped_ids[ $code ]['livestream_video_id'] );
							WP_CLI::log( " - Updating post {$post->ID} from $shortcode to $new_shortcode" );
							$post->post_content = str_replace( $shortcode, $new_shortcode, $post->post_content );
							wp_update_post( $post );
						} else {
							WP_CLI::warning( " - Code doesn't match [post_content]: $code" );
						}
					}
				}
			}
		}

		$query = "select * from {$wpdb->postmeta} where meta_key='gmr-player'";
		$results = $wpdb->get_results( $query );

		foreach ( $results as $result ) {
			WP_CLI::log( "Checking Meta ID {$result->meta_id}" );
			$shortcode = $result->meta_value;
			$code = array();
			preg_match( '/\scode="([^"]*)"/i', $shortcode, $code );
			$code = $code[1];

			// @todo check if code in array.
			if ( $mapped_ids[ $code ] ) {
				$new_shortcode = $this->_generate_livestream_shortcode( $mapped_ids[ $code ]['livestream_account_id'], $mapped_ids[ $code ]['livestream_event_id'], $mapped_ids[ $code ]['livestream_video_id'] );
				WP_CLI::log( " - Replacing post meta on post ID {$result->post_id} with New Shortcode: " . $new_shortcode );
				update_post_meta( $result->post_id, $result->meta_key, $new_shortcode );
			} else {
				WP_CLI::warning( " - Code doesn't match [meta]: $code" );
			}
		}

		WP_CLI::success( 'done' );
	}

	protected function _generate_livestream_shortcode( $account_id, $event_id, $video_id ) {
		return sprintf( '[livestream_video account_id="%s" event_id="%s" video_id="%s"]', $account_id, $event_id, $video_id );
	}

	protected function _get_csv_mapping( $csv ) {
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
			$mapped_ids[ $record['video_map_id'] ] = $record;
		}

		return $mapped_ids;
	}

}

WP_CLI::add_command( 'ooyala-migration', 'Beasley_Ooyala_Migration_CLI' );

