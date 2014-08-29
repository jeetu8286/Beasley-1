<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

class GreaterMediaUGCWPCLI extends WP_CLI_Command {

	/**
	 * Generate test contest entries
	 *
	 * ## OPTIONS
	 *
	 * <contest>
	 * : The contest's post ID
	 *
	 * <n>
	 * : Number of entries to generate
	 *
	 * ## EXAMPLES
	 *
	 *     wp greatermedia_contests generate_entries --contest="123" --n=5
	 *
	 * @synopsis --contest=<post_id> --n=<number>
	 */
	public function generate_ugc( $args, $assoc_args ) {

	}

	public static function random_names( $num = 1 ) {

		// Try to pull data from a service, fall back to just some hard-coded stuff
		$data = wp_remote_get( add_query_arg( 'results', intval( $num ), 'http://api.randomuser.me' ) );
		if ( ! is_wp_error( $data ) ) {
			WP_CLI::line( 'Retrieved fake persona data from the randomuser.me API' );
			$data = json_decode( $data['body'] );

			return $data->results;
		} else {
			WP_CLI::line( 'Error retrieving fake personas from API. Using hard-coded alternative.' );

			$persona                    = new stdClass();
			$persona->user              = new stdClass();
			$persona->user->email       = 'admin@127.0.0.1';
			$persona->user->name        = new stdClass();
			$persona->user->name->title = 'Ms';
			$persona->user->name->first = 'First';
			$persona->user->name->last  = 'Last';
			$persona->user->picture     = 'http://example.com';

			$results = array();
			for ( $entry_index = 0; $entry_index < $num; $entry_index += 1 ) {
				$results[] = $persona;
			}

			return $results;
		}


	}
}

WP_CLI::add_command( 'greatermedia_ugc', 'GreaterMediaUGCWPCLI' );