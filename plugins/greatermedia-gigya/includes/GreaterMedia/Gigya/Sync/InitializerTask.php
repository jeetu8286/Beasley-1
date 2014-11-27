<?php

namespace GreaterMedia\Gigya\Sync;

use GreaterMedia\Gigya\MemberQuery;

class InitializerTask extends SyncTask {

	public $member_query = null;
	public $task_factory = null;
	public $conjunction  = null;

	function get_task_name() {
		return 'sync_initializer';
	}

	function run() {
		$this->sentinel->reset();
		$this->enqueue_subqueries();
	}

	function enqueue_subqueries() {
		foreach ( $this->get_subqueries() as $subquery ) {
			$this->enqueue_subquery( $subquery );
		}
	}

	function enqueue_subquery( $subquery ) {
		$task   = $this->get_task_for_store_type( $subquery['store_type'] );
		$params = $this->get_params_for_subquery( $subquery );

		$task->enqueue( $params );
	}

	function get_params_for_subquery( $subquery ) {
		$params                = $this->params;
		$params['query']       = $subquery['query'];
		$params['conjunction'] = $this->get_subquery_conjunction();

		return $params;
	}

	function get_task_for_store_type( $store_type ) {
		return $this->get_task_factory()->build( $store_type );
	}

	function get_member_query() {
		if ( is_null( $this->member_query ) ) {
			$this->member_query = new MemberQuery( $this->get_member_query_id() );
		}

		return $this->member_query;
	}

	function get_subqueries() {
		return $this->get_member_query()->to_subqueries();
	}

	function get_subquery_conjunction() {
		if ( is_null( $this->conjunction ) ) {
			$member_query      = $this->get_member_query();
			$this->conjunction = $member_query->get_subquery_conjunction();
		}

		return $this->conjunction;
	}

}
