<?php

namespace GreaterMedia\Gigya\Sync;

class Sentinel {

	public $member_query_id;
	public $meta_prefix = 'mqsm'; // member_query_sync_meta

	function __construct( $member_query_id ) {
		$this->member_query_id = $member_query_id;
	}

	function get_task_meta( $key ) {
		$key   = "{$this->meta_prefix}_$key";
		$value = get_post_meta( $this->member_query_id, $key, true );

		return $value;
	}

	function set_task_meta( $key, $value ) {
		$key = "{$this->meta_prefix}_$key";
		update_post_meta( $this->member_query_id, $key, $value );
	}

	function clear_task_meta( $key ) {
		$key = "{$this->meta_prefix}_$key";
		delete_post_meta( $this->member_query_id, $key );
	}

	function set_checksum( $checksum ) {
		$this->set_task_meta( 'checksum', $checksum );
	}

	function get_checksum() {
		return $this->get_task_meta( 'checksum' );
	}

	function get_task_progress( $task_type ) {
		$progress = $this->get_task_meta( $task_type . '_progress' );
		return intval( $progress );
	}

	function set_task_progress( $task_type, $progress ) {
		$this->set_task_meta( $task_type . '_progress', $progress );
	}

	function is_task_type_complete( $task_type ) {
		$progress = $this->get_task_progress( $task_type );
		return $progress === 100;
	}

	function get_progress() {
		$parts = array(
			$this->get_task_progress( 'profile' ),
			$this->get_task_progress( 'data_store' ),
			$this->get_task_progress( 'compile_results' ),
		);

		$mode = $this->get_task_meta( 'mode' );
		if ( $mode === 'export' ) {
			$this->get_task_progress( 'export_results' );
		}

		$total_parts    = count( $parts );
		$total_progress = array_sum( $parts );
		$total          = 100 * $total_parts;
		$progress       = $total_progress / $total * 100;

		return (int)$progress;
	}

	function can_compile_results() {
		return $this->get_task_progress( 'profile' ) === 100 &&
			$this->get_task_progress( 'data_store' ) === 100;
	}

	function can_export_results() {
		return $this->can_compile_results() &&
			$this->get_task_progress( 'compile_results' ) === 100;
	}

	function verify_checksum( $checksum ) {
		return $checksum !== '' && $checksum === $this->get_checksum();
	}

	function reset() {
		$this->clear_task_meta( 'mode' );
		$this->clear_task_meta( 'profile_progress' );
		$this->clear_task_meta( 'data_store_progress' );
		$this->clear_task_meta( 'compile_results_progress' );
		$this->clear_task_meta( 'export_results_progress' );
	}

}
