<?php

use Seld\JsonLint\JsonParser;

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
	 * @synopsis --marketron=<filename> [--output=<filename>]
	 */
	public function convert( $args, $assoc_args ) {

		self::check_dependencies();

		if ( ! isset( $assoc_args['marketron'] ) || empty( $assoc_args['marketron'] ) ) {
			WP_CLI::error( 'Please provide the filename of the Marketron export file using the --marketron parameter' );
		}

		// Load the Marketron export
		$xml = new DOMDocument;
		$xml->load( $assoc_args['marketron'] );

		// Load the XML source
		$xsl = new DOMDocument;
		$xsl->load( trailingslashit( MARKETRON_TO_GIGYA_PATH ) . 'marketron_to_gigya.xsl' );

		// Configure the transformer
		$proc = new XSLTProcessor;
		$proc->importStyleSheet( $xsl ); // attach the xsl rules

		// Perform the XSL Transformation
		$transformed_document = $proc->transformToXML( $xml );

		// XSLTProcessor assumes it's translating XML->XML so it adds an XML declaration
		$transformed_document = str_replace( '<?xml version="1.0"?>', '', $transformed_document );

		$tidied_document = TidyJSON::tidy( $transformed_document );

		// Fill in a placeholder password
		$tidied_document = str_replace( '%password%', md5( '12345' ), $tidied_document );

//		self::jsonlint( $tidied_document );
//die();
		if ( isset( $assoc_args['output'] ) && ! empty( $assoc_args['output'] ) ) {
			// Output a file
			file_put_contents( $assoc_args['output'], $tidied_document );
		} else {
			// Output to stdout
			echo $tidied_document;
		}

	}

	public static function check_dependencies() {

		if ( ! class_exists( 'XSLTProcessor' ) ) {
			WP_CLI::error( "PHP's XSLT support is required to run the convertor. Please install it ('apt-get install php5-xsl' on Linux)." );
			die();
		}

	}

	public static function jsonlint( $json ) {

		$parser = new JsonParser();

		// returns null if it's valid json, or a ParsingException object.
		$result = $parser->lint( $json );
		if ( null !== $result ) {
			print_r( $result );
		}

	}

}

WP_CLI::add_command( 'marketron_to_gigya', 'GMMarketronToGigya' );


