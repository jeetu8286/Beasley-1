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
				dout($record,true);
		}

		WP_CLI::success( 'done' );
	}

}

WP_CLI::add_command( 'ooyala-migration', 'Beasley_Ooyala_Migration_CLI' );

