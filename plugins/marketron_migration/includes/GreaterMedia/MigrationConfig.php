<?php

namespace GreaterMedia;

class MigrationConfig {

	public $container;
	public $input_dir;
	public $output_dir;
	public $site_dir;
	public $data;

	function __construct( $site_dir ) {
		$this->site_dir   = $site_dir;
		$this->input_dir  = $site_dir . '/input';
		$this->output_dir = $site_dir . '/output';
	}

	function get_config_file() {
		return $this->input_dir . '/config.json';
	}

	function get_mapping_file() {
		return $this->input_dir . '/mapping.csv';
	}

	function get_tags_file() {
		return $this->input_dir . '/tags.csv';
	}

	function get_marketron_export_file() {
		return $this->input_dir . '/marketron_export.zip';
	}

	function get_marketron_export_extract_dir() {
		return $this->output_dir . '/marketron_export';
	}

	function get_csv_export_dir() {
		return $this->output_dir . '/csv';
	}

	function get_site_domain() {
		return $this->data['site']['domain'];
	}

	function get_email_domain() {
		return $this->data['site']['email_domain'];
	}

	function get_seed_id() {
		return $this->data['database']['seed_id'];
	}

	function load() {
		$config_file = $this->get_config_file();
		\WP_CLI::log( "Loading Config File: $config_file" );

		if ( file_exists( $config_file ) ) {
			$json       = file_get_contents( $config_file, 'r' );
			$this->data = $this->parse( $json );
		} else {
			\WP_CLI::error( "Config File not found - $config_file" );
			return false;
		}
	}

	function parse( $json ) {
		$data = json_decode( $json, true );

		if ( json_last_error() === JSON_ERROR_NONE ) {
			return $data;
		} else {
			\WP_CLI::error( 'Failed to parse config JSON' );
			return false;
		}
	}

}
