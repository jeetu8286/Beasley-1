<?php

namespace GreaterMedia\Gigya\Sync;

class Launcher {

	public $tasks = null;

	function get_tasks() {
		if ( is_null( $this->tasks ) ) {
			$this->tasks = array(
				'initializer' => new InitializerTask()
			);
		}

		return $this->tasks;
	}

	function register() {
		foreach ( $this->get_tasks() as $task ) {
			$task->register();
		}
	}

	function get_task( $task_id ) {
		$tasks = $this->get_tasks();
		return $tasks[ $task_id ];
	}

	function launch( $member_query_id, $mode = 'preview' ) {
		$params = array(
			'site_id'         => get_current_blog_id(),
			'member_query_id' => $member_query_id,
			'mode'            => $mode,
			'checksum'        => md5( strtotime( 'now' ) )
		);

		$initializer = $this->get_task( 'initializer' );
		$initializer->enqueue( $params );
	}

}
