<?php

namespace GreaterMedia\Gigya\Sync;

class Task {

	public $params      = array();
	public $max_retries = 3;
	public $aborted     = false;

	function get_task_name() {
		return 'task';
	}

	function get_async_action() {
		return $this->get_task_name() . '_async_job';
	}

	function register() {
		add_action(
			$this->get_async_action(), array( $this, 'execute' )
		);
	}

	function enqueue( $params = array() ) {
		$this->params = $params;

		return wp_async_task_add(
			$this->get_async_action(),
			$params
		);
	}

	function execute( $params ) {
		$this->params = $params;

		try {
			$proceed = $this->before();

			if ( $proceed ) {
				$this->log_attempt();

				$result = $this->run();
				$this->after( $result );
			} else {
				$this->aborted = true;
			}
		} catch (\Exception $err) {
			if ( ! defined( 'PHPUNIT_RUNNER' ) ) {
				error_log(
					'Task Failed - ' . $this->get_task_name() . ' - ' . $err->getMessage()
				);
			}
			$this->recover( $err );
		}
	}

	function before() {
		return true;
	}

	function run() {

	}

	function after( $result ) {

	}

	function recover( $err ) {
		$this->retry();
	}

	function retry() {
		if ( $this->can_retry() ) {
			$this->enqueue( $this->params );
		}
	}

	function can_retry() {
		if ( $this->max_retries <= 0 ) {
			return false;
		} else {
			return $this->get_retries() < $this->max_retries;
		}
	}

	function log_attempt() {
		if ( array_key_exists( 'retries', $this->params ) ) {
			$retries = $this->params['retries'];
		} else {
			$retries = 0;
		}

		$this->params['retries'] = ++$retries;
	}

	function get_param( $key ) {
		return $this->params[ $key ];
	}

	function set_param( $key, $value ) {
		$this->params[ $key ] = $value;
	}

	function has_param( $key ) {
		return array_key_exists( $key, $this->params );
	}

	function get_retries() {
		if ( $this->has_param( 'retries' ) ) {
			return $this->get_param( 'retries' );
		} else {
			return 0;
		}
	}

}
