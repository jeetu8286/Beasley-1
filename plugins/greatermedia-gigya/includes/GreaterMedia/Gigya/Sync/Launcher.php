<?php

namespace GreaterMedia\Gigya\Sync;

use GreaterMedia\Gigya\MemberQuery;

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
		$params      = $this->get_launch_params( $member_query_id, $mode );
		$initializer = $this->get_task( 'initializer' );
		$initializer->enqueue( $params );
	}

	function preview( $constraints ) {
		$member_query_id = $this->create_preview( $constraints );
		$this->launch( $member_query_id, 'preview' );

		return $member_query_id;
	}

	function create_preview( $constraints ) {
		$params = array(
			'post_type'   => 'member_query_preview',
			'post_status' => 'draft',
		);

		$post_id      = wp_insert_post( $params );
		$member_query = new MemberQuery( $post_id, $constraints );
		$json         = json_encode( $constraints );

		$member_query->save( $json );

		return $post_id;
	}

	function get_launch_params( $member_query_id, $mode = 'preview' ) {
		return array(
			'site_id'         => get_current_blog_id(),
			'member_query_id' => $member_query_id,
			'mode'            => $mode,
			'checksum'        => md5( strtotime( 'now' ) )
		);
	}

}
