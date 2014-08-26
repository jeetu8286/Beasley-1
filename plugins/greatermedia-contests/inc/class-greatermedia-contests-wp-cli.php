<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

class GreaterMediaContestsWPCLI extends WP_CLI_Command {

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
	public function generate_entries( $args, $assoc_args ) {

		global $timestart;

		$contest_id  = intval( $assoc_args['contest'] );
		$num_entries = intval( $assoc_args['n'] );

		$contest = get_post( $contest_id, OBJECT );
		if ( 'contest' !== $contest->post_type ) {
			WP_CLI::error( sprintf( 'Post %d is not a Contest', $contest_id ) );
		}

		$personas = self::random_names( $num_entries );

		for ( $entry_index = 0; $entry_index < $num_entries; $entry_index += 1 ) {

			$comment                                   = GreaterMediaContestEntry::for_comment_data(
				$contest_id,
				$personas[$entry_index]->user->picture,
				ucfirst( $personas[$entry_index]->user->name->title ) . ' ' .
				ucfirst( $personas[$entry_index]->user->name->first ) . ' ' .
				ucfirst( $personas[$entry_index]->user->name->last ),
				$personas[$entry_index]->user->email
			);
			$comment->comment_data['comment_parent']   = 0;
			$comment->comment_data['comment_agent']    = 'WP-CLI';
			$comment->comment_data['comment_approved'] = 1;

			$comment->comment_data['comment_content'] = json_encode( $personas[$entry_index] );

			$entry_id = $comment->save();
			WP_CLI::line( sprintf( 'Created entry %d', $entry_id ) );

		}

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

WP_CLI::add_command( 'greatermedia_contests', 'GreaterMediaContestsWPCLI' );