<?php

namespace GreaterMedia\Commands;

use GreaterMedia\MigrationConfig;
use GreaterMedia\Utils\Downloader;
use Marketron\MappingCollection;
use Marketron\Tools\Factory as ToolFactory;
use GreaterMedia\Import\Factory as ImporterFactory;

class Migrator {

	public $default_opts = array(
		'config_file'              => 'wmgk.json',
		'marketron_export'    => 'wmgk.zip',
		'tool'                => 'feed',
		'fresh'               => false,
		'migration_cache_dir' => 'migration_cache',
		'mapping_file'        => 'wmgk_mapping.csv',
	);

	public $default_tools = array(
		'feed',
		//'affinity_club',
	);

	public $tool_factory;
	public $importer_factory;
	public $downloader;
	public $config;
	public $opts;

	function _test_downloader( $args, $opts ) {
		$downloader = new Downloader( 'migration_cache/downloads' );
		$tmp_file = $downloader->download( 'https://www.google.co.in/images/srpr/logo10w.png' );
		\WP_CLI::log( 'First tmp file = ' . $tmp_file );

		$tmp_file = $downloader->download( 'https://www.google.co.in/images/srpr/logo10w.png' );
		\WP_CLI::log( 'Second tmp file = ' . $tmp_file );
	}

	function test_media_downloader( $args, $opts ) {
		$downloader = new Downloader(
			'/projects/10up/greater_media/migration_cache/wmgk/downloads',
			'/projects/10up/greater_media/migration_cache/wmgk/media'
		);
		$tmp_file = $downloader->download( 'http://media.wmgk.com/' . urlencode('Blogs/1001280/Watch The Mummers Strut (More Than A Tradition)mastered.mp3'  ) );
		\WP_CLI::log( 'First tmp file = ' . $tmp_file );

		$tmp_file = $downloader->download( 'http://media.wmgk.com/' . urlencode('Blogs/1001280/Watch The Mummers Strut (More Than A Tradition)mastered.mp3'  ) );
		\WP_CLI::log( 'Second tmp file = ' . $tmp_file );

	}

	function test_mapping( $args, $opts ) {
		$this->mapping_collection = new MappingCollection();
		$this->mapping_collection->load( 'wmgk_mapping.csv' );
	}

	function migrate( $args, $opts ) {
		$opts          = wp_parse_args( $opts, $this->default_opts );
		$opts['fresh'] = filter_var( $opts['fresh'], FILTER_VALIDATE_BOOLEAN );

		$migration_cache_dir   = $opts['migration_cache_dir'];
		$marketron_export      = realpath( $opts['marketron_export'] );
		$marketron_export_dest = $migration_cache_dir . '/marketron_export';
		$fresh                 = $opts['fresh'];
		$config_file           = $opts['config_file'];
		$tool                  = $opts['tool'];

		$config_loader = new MigrationConfig();
		$this->opts    = $opts;
		$this->config  = $config_loader->load( $config_file );

		$mapping_file = $opts['mapping_file'];
		$this->mapping_collection = new MappingCollection();
		$this->mapping_collection->load( $mapping_file );

		$this->create_migration_cache_dir( $migration_cache_dir, $fresh );
		$this->extract( $marketron_export, $marketron_export_dest, $fresh );
		$this->format( $marketron_export_dest, $fresh );

		if ( $tool === 'all' ) {
			$tools_to_load = $this->default_tools;
		} else {
			$tools_to_load = array( $tool );
		}

		$this->downloader       = new Downloader( $migration_cache_dir . '/downloads' );
		$this->tool_factory     = new ToolFactory( $this );
		$this->importer_factory = new ImporterFactory( $this );

		$this->load_tools( $tools_to_load );
		$this->import_tools( $tools_to_load );
	}

	private function load_tools( $tools_to_load ) {
		foreach ( $tools_to_load as $tool_name ) {
			$tool = $this->tool_factory->build( $tool_name );
			$tool->load();
		}
	}

	private function import_tools( $tools_to_import ) {
		foreach ( $tools_to_import as $tool_name ) {
			$tool = $this->importer_factory->build( $tool_name );
			$tool->import();
		}
	}

	private function create_migration_cache_dir( $migration_cache_dir, $fresh = false ) {
		$migration_cache_dir = $migration_cache_dir;
		$has_cache_dir       = is_dir( $migration_cache_dir );

		if ( ! $has_cache_dir ) {
			\WP_CLI::log( 'Creating cache dir ...' );
			system( "mkdir -p \"{$migration_cache_dir}\"" );
			system( "mkdir -p \"{$migration_cache_dir}\"/marketron_export" );
			system( "mkdir -p \"{$migration_cache_dir}\"/downloads" );
		}
	}

	private function extract( $marketron_export, $dest, $fresh = false ) {
		$update_flag = $fresh ? '-u' : '-f';
		system( "unzip $update_flag -d \"$dest\" \"$marketron_export\" " );
	}

	private function format( $dir, $fresh = false ) {
		$pattern = "$dir/*.{xml,XML}";
		$files   = glob( $pattern, GLOB_BRACE );
		$files   = preg_grep( '/._formatted.xml$/', $files, PREG_GREP_INVERT );

		foreach ( $files as $file ) {
			$outfile = preg_replace( '/.(XML|xml)$/', '_formatted.xml', $file );
			if ( ! file_exists( $outfile ) || $fresh ) {
				\WP_CLI::log( 'Cleaning up: ' . basename( $file ) );
				system( "xmllint --huge --format --output $outfile $file" );
			}
		}
	}

}
