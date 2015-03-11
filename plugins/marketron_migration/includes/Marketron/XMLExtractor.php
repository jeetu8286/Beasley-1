<?php

namespace Marketron;

class XMLExtractor {

	public $container;

	function extract() {
		$this->unzip();
		$this->format();
	}

	function unzip() {
		$config           = $this->container->config;
		$marketron_export = $config->get_marketron_export_file();
		$dest             = $config->get_marketron_export_extract_dir();
		$update_flag      = $this->container->fresh ? '-u' : '-f';

		system( "mkdir -p \"$dest\" " );
		system( "unzip $update_flag -d \"$dest\" \"$marketron_export\" " );
	}

	function format() {
		$config = $this->container->config;
		$dir    = $config->get_marketron_export_extract_dir();

		$pattern = "$dir/*.{xml,XML}";
		$files   = glob( $pattern, GLOB_BRACE );
		$files   = preg_grep( '/._formatted.xml$/', $files, PREG_GREP_INVERT );

		foreach ( $files as $file ) {
			$outfile = preg_replace( '/.(XML|xml)$/', '_formatted.xml', $file );
			if ( ! file_exists( $outfile ) ) {
				\WP_CLI::log( 'Cleaning up: ' . basename( $file ) );
				system( "xmllint --huge --format --output $outfile $file" );
			}
		}
	}

}
