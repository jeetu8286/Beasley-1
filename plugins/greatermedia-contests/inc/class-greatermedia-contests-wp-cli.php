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

		for ( $entry_index = 0; $entry_index < $num_entries; $entry_index += 1 ) {

			$commentdata = array(
				'comment_post_ID'      => $contest_id,
				'comment_author'       => 'admin',
				'comment_author_email' => 'admin@admin.com',
				'comment_author_url'   => 'http://example.com',
				'comment_content'      => 'content here',
				'comment_type'         => '',
				'comment_parent'       => 0,
				'user_id'              => 1,
				'comment_author_IP'    => '127.0.0.1',
				'comment_agent'        => 'WP-CLI',
				'comment_date'         => date( 'Y-m-d H:i:s', intval( $timestart ) ),
				'comment_approved'     => 1,

			);

			$entry_id = GreaterMediaContests::insert_contest_entry( $commentdata );
			WP_CLI::line( sprintf( 'Created entry %d', $entry_id ) );

		}

	}
}

WP_CLI::add_command( 'greatermedia_contests', 'GreaterMediaContestsWPCLI' );