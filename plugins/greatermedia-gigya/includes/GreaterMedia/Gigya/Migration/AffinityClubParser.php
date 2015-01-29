<?php

namespace GreaterMedia\Gigya\Migration;

class AffinityClubParser {

	function parse( $source, $dest ) {
		$doc                     = new \DOMDocument();
		$doc->preserveWhitespace = false;
		$doc->formatOutput       = true;
		$doc->load( $source );

		$affinity_club = new AffinityClub();
		$affinity_club->parse( $doc->documentElement );

		$data = $affinity_club->export();
		$json = json_encode( $data, JSON_PRETTY_PRINT );

		file_put_contents( $dest, $json );

		return $data['settings']['totalRecords'];
	}

}
