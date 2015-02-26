<?php

namespace Marketron\Tools;

class AffinityClub extends BaseTool {

	function get_name() {
		return 'affinity_club';
	}

	function get_data_filename() {
		return 'AffinityClub_With_FacebookInfo.XML';
	}

	function parse( $xml_element ) {
		\WP_CLI::success( 'Parse affinity club done' );
	}

}