<?php

namespace GreaterMedia\Gigya\Sync;

class QueryTask extends SyncTask {

	public $cache_retries     = 0;
	public $max_cache_retries = 5;
	public $cache_retry_delay = 5;
	public $page_size         = 1000;
	public $preview_page_size = 5;
	public $collector         = null;
	public $message_types     = array(
		'enqueue',
		'execute',
		'retry',
		'abort',
		'error',
	);

	function get_task_name() {
		return 'query_task';
	}

	function run() {
		$store_type     = $this->get_store_type();

		if ( ! $this->is_fast_preview() ) {
			$query          = $this->get_query();
			$cursor         = $this->get_cursor();
			$subquery_count = $this->get_subquery_count();

			$paginator = new QueryPaginator( $store_type, $this->page_size );
			$matches   = $paginator->fetch( $query, $cursor );
			$users     = $this->find_users( $matches['results'] );

			$this->save_users( $users );

			return $matches;
		} else if ( $store_type === 'profile' ) {
			return $this->run_fast_profile_preview();
		} else if ( $store_type === 'data_store' ) {
			return $this->run_fast_data_store_preview();
		}
	}

	function run_fast_profile_preview() {
		$cursor     = $this->get_cursor();
		$query      = $this->get_query();
		$query      = str_replace( 'select *', 'select profile.email, profile.firstName, profile.lastName, UID', $query );

		$paginator  = new QueryPaginator( 'profile', $this->preview_page_size );
		$results    = $paginator->fetch( $query, $cursor );
		$users      = $this->find_preview_users( $results['results'] );

		$this->save_preview_users( $users, $results['total_results'] );

		return $results;
	}

	function run_fast_data_store_preview() {
		$cursor    = $this->get_cursor();
		$query     = $this->get_query();

		$paginator = new QueryPaginator( 'data_store', $this->preview_page_size );
		$results   = $paginator->fetch( $query, $cursor );
		$user_ids  = $this->find_users( $results['results'] );

		$finder    = new GigyaUserFinder();
		$users     = $finder->find( $user_ids );

		$this->save_preview_users( $users, $results['total_results'] );

		return $results;
	}

	function find_users( $results ) {
		if ( function_exists( 'array_column' ) ) {
			return array_column( $results, 'UID' );
		} else {
			$users = array();

			foreach ( $results as $result ) {
				$users[] = $result['UID'];
			}

			return $users;
		}
	}

	function find_preview_users( $results ) {
		$finder = new GigyaUserFinder();
		return $finder->results_to_users( $results );
	}

	function save_users( $users ) {
		$collector = $this->get_collector();
		$collector->collect( $users, $this->get_store_type() );
	}

	// KLUDGE - Duplication
	function save_preview_users( $users, $count ) {
		$results = array(
			'total' => $count,
			'users' => $users,
		);

		$json = json_encode( $results );

		update_post_meta(
			$this->get_member_query_id(),
			'member_query_preview_results',
			$json
		);
	}

	function after( $results ) {
		$sentinel = $this->get_sentinel();

		if ( $this->is_fast_preview() ) {
			$sentinel->set_task_progress( 'profile', 100 );
			$sentinel->set_task_progress( 'data_store', 100 );
			$sentinel->set_task_progress( 'compile_results', 100 );
			$sentinel->set_task_progress( 'preview_results', 100 );
			return;
		}

		if ( array_key_exists( 'progress', $results ) ) {
			$sentinel->set_task_progress(
				$this->get_store_type(),
				$results['progress']
			);
		}

		if ( $results['has_next'] ) {
			$params           = $this->export_params();
			$params['cursor'] = $results['cursor'];

			$this->enqueue( $params );
		} else if ( $this->get_sentinel()->can_compile_results() ) {
			$params = $this->export_params();
			$params['cursor'] = 0;
			$compile_results_task = new InMemoryCompileResultsTask();
			$compile_results_task->enqueue( $params );
		} else if ( $this->cache_retries++ < $this->max_cache_retries ){
			$sentinel->clear_task_meta_cache();
			sleep( $this->cache_retry_delay );
			$this->after( $results );
		}
	}

	function get_query() {
		return $this->get_param( 'query' );
	}

	function get_cursor() {
		if ( $this->has_param( 'cursor' ) ) {
			return $this->get_param( 'cursor' );
		} else {
			return 0;
		}
	}

	function get_store_type() {
		return $this->get_param( 'store_type' );
	}

	function get_collector() {
		if ( is_null( $this->collector ) ) {
			$this->collector = new UserCollector(
				$this->get_site_id(),
				$this->get_member_query_id()
			);
		}

		return $this->collector;
	}

	function get_subquery_count() {
		return $this->get_param( 'subquery_count' );
	}

	function is_fast_preview() {
		return $this->get_mode() === 'preview' &&
			$this->get_subquery_count() === 1;
	}

}
