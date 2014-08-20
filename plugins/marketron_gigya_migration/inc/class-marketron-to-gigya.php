<?php

class GMMarketronToGigya extends WP_CLI_Command {

	/**
	 * Convert a Marketron export to a Gigya JSON file. Echoes to stdout if no output file is specified
	 *
	 * ## OPTIONS
	 *
	 * <marketron>
	 * : The Marketron XML file to import
	 *
	 * <output>
	 * : The filename for output
	 *
	 * ## EXAMPLES
	 *
	 *     wp marketron_to_gigya convert --marketron="marketron_export.xml" [--output=<filename>]
	 *
	 * @synopsis <convert> --marketron=<filename> --xsl=<filename> [--output=<filename>]
	 */
	public function convert( $args, $assoc_args ) {

		if ( ! isset( $assoc_args['marketron'] ) || empty( $assoc_args['marketron'] ) ) {
			WP_CLI::error( 'Please provide the filename of the Marketron export file using the --marketron parameter' );
		}

		// Load the Marketron export
		$xml = new DOMDocument;
		$xml->load( $assoc_args['marketron'] );

		// Load the XML source
		$xsl = new DOMDocument;
		$xsl->load( '../marketron_to_gigya.xsl' );

		// Configure the transformer
		$proc = new XSLTProcessor;
		$proc->importStyleSheet( $xsl ); // attach the xsl rules

		$transformed_document = $proc->transformToXML( $xml );

		if ( isset( $assoc_args['output'] ) && ! empty( $assoc_args['output'] ) ) {
			file_put_contents( $assoc_args['output'], TidyJSON::tidy( $transformed_document ) );
		} else {
			echo TidyJSON::tidy( $transformed_document );
		}

	}

}

WP_CLI::add_command( 'marketron_to_gigya', 'GMMarketronToGigya' );


