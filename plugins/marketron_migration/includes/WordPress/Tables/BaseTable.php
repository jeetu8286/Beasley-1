<?php

namespace WordPress\Tables;

class BaseTable {

	public $container;
	public $id_counter    = null;
	public $rows          = array();
	public $columns       = array();
	public $indices       = array();
	public $indices_store = array();

	function get_next_id() {
		if ( is_null( $this->id_counter ) ) {
			$this->id_counter = $this->container->config->get_seed_id();
		}

		return $this->id_counter++;
	}

	function get_rows() {
		return $this->rows;
	}

	function get_columns() {
		return $this->columns;
	}

	function get_indices() {
		return $this->indices;
	}

	function add( $fields ) {
		if ( ! array_key_exists( 'ID', $fields ) ) {
			$fields['ID'] = $this->get_next_id();
		}

		$id = $fields['ID'];
		$this->rows[ $id ] = $fields;
		$this->index_row( $fields );

		return $fields;
	}

	function index_row( $fields ) {
		$indices = $this->get_indices();
		$id      = $fields['ID'];

		foreach ( $indices as $index_field ) {
			if ( ! array_key_exists( $index_field, $this->indices_store ) ) {
				$this->indices_store[ $index_field ] = array();
			}

			if ( array_key_exists( $index_field, $fields ) ) {
				$field_value = $fields[ $index_field ];
				$this->indices_store[ $index_field ][ $field_value ] = $id;
			}
		}
	}

	/* Eg:- has_row_with_field( 'slug', 'foo-bar' ) */
	function has_row_with_field( $field_name, $field_value ) {
		if ( array_key_exists( $field_name, $this->indices_store ) ) {
			return array_key_exists( $field_value, $this->indices_store[ $field_name ] );
		} else {
			return false;
		}
	}

	function get_row_with_field( $field_name, $field_value ) {
		if ( $this->has_row_with_field( $field_name, $field_value ) ) {
			$id = $this->indices_store[ $field_name ][ $field_value ];
			return $this->rows[ $id ];
		} else {
			return null;
		}
	}

	function get_row_by_id( $id ) {
		if ( array_key_exists( $id, $this->rows ) ) {
			return $this->rows[ $id ];
		} else {
			return null;
		}
	}

	function get_export_dir() {
		return $this->container->config->get_csv_export_dir();
	}

	function get_table_name() {
		return 'base_table';
	}

	function get_prefixed_table_name() {
		global $wpdb;
		return $wpdb->prefix . $this->get_table_name();
	}

	function get_export_file() {
		return $this->get_export_dir() . '/' . $this->get_prefixed_table_name() . '.csv';
	}

	function export() {
		$csv_file    = $this->get_export_file();
		$file_handle = fopen( $csv_file, 'w' );

		$this->to_csv( $file_handle );
	}

	function get_import_query() {
		$csv_file   = realpath( $this->get_export_file() );
		$table_name = $this->get_prefixed_table_name();
		$columns    = implode( ',', $this->get_columns() );
		$query      = <<<SQL
Load Data Local InFile '$csv_file'
Into Table $table_name
Fields Terminated By ','
Optionally Enclosed By '\"'
($columns)
SQL;

		$query = str_replace( "\n", " ", $query );
		return $query;
	}

	function import() {
		$table_name = $this->get_prefixed_table_name();
		$query      = $this->get_import_query();

		\WP_CLI::log( "Importing $table_name ..." );

		$cmd  = 'mysql';
		$cmd .= ' --user='     . DB_USER;
		$cmd .= ' --password=' . DB_PASSWORD;
		$cmd .= ' --host='     . DB_HOST;
		$cmd .= ' --local-infile';
		$cmd .= ' --database=' . DB_NAME;
		$cmd .= ' --show-warnings';
		$cmd .= ' --verbose';
		$cmd .= ' -e "' . $query . '"';

		system( $cmd );

		\WP_CLI::success( "Imported $table_name" );
	}

	/*
	function import_with_mysqlimport() {
		$table_name = $this->get_prefixed_table_name();

		\WP_CLI::log( "Importing $table_name ..." );

		$cmd  = 'mysqlimport ';
		$cmd .= ' --user='     . DB_USER;
		$cmd .= ' --password=' . DB_PASSWORD;
		$cmd .= ' --host='     . DB_HOST;
		$cmd .= ' --local';
		$cmd .= ' --columns=' . implode( ',', $this->get_columns() );
		$cmd .= ' ' . DB_NAME;
		$cmd .= ' ' . realpath( $this->get_export_file() );

		system( $cmd );

		\WP_CLI::success( "Imported $table_name" );
	}
	 */

	function to_csv( $csv_file_handle ) {
		$columns    = $this->get_columns();
		$rows       = $this->get_rows();
		$total_rows = count( $rows );
		$table_name = $this->get_table_name();
		$notify     = new \cli\progress\Bar( "Generating CSV with $total_rows $table_name", $total_rows );

		foreach ( $rows as $row_id => $row ) {
			$csv_row = $this->to_csv_row( $row, $columns );
			fputcsv(
				$csv_file_handle, $csv_row, ',', '"'
			);

			$notify->tick();
		}

		fclose( $csv_file_handle );
		$notify->finish();
	}

	function to_csv_row( $row, $columns ) {
		//var_dump( $columns );
		$csv_row = array();
		foreach ( $columns as $column ) {
			if ( ! array_key_exists( $column, $row ) ) {
				$value = null;
			} else {
				$type = gettype( $row[ $column ] );
				$value = $row[ $column ];
				//error_log( $column . ' ' . $type );
			}
			// TODO: type conversion
			//
			$csv_row[] = $value;
		}

		//var_dump( $csv_row );
		return $csv_row;
	}


}
