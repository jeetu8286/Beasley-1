<?php

namespace GreaterMedia\Gigya\Commands;

use GreaterMedia\Gigya\Migration\AffinityClubParser;

/**
 * Marketron to Gigya Migration.
 */
class MarketronCommand extends \WP_CLI_Command {

	function import_profiles( $args, $opts ) {
		$source = $args[0];
		$dest   = $args[1];

		$parser = new AffinityClubParser();
		$count  = $parser->parse( $source, $dest );

		\WP_CLI::success( "$count profiles were written to $dest" );
	}

}
