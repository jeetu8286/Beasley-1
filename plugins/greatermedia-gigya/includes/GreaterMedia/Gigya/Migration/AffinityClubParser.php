<?php

namespace GreaterMedia\Gigya\Migration;

class AffinityClubParser {

	function parse( $source, $dest ) {
		$errors_dest = str_replace( '.json', '.errors.log', $dest );

		$doc                     = new \DOMDocument();
		$doc->preserveWhitespace = false;
		$doc->formatOutput       = true;
		$doc->load( $source );

		$affinity_club = new AffinityClub();
		$affinity_club->parse( $doc->documentElement );

		$result = $affinity_club->export();
		$json   = json_encode( $result['data'], JSON_PRETTY_PRINT );

		file_put_contents( $dest, $json );

		if ( count( $result['errors'] ) > 0 ) {
			$errors_text = '';

			foreach ( $result['errors'] as $error ) {
				$errors_text .= $error['reason'];
				$errors_text .= "\n";
				$errors_text .= $error['node'];
				$errors_text .= "\n";
			}

			file_put_contents( $errors_dest, $errors_text );
		}

		return $result['data']['settings']['totalRecords'];
	}

}
