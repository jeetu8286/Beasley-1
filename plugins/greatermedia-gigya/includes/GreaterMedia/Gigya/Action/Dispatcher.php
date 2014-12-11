<?php

namespace GreaterMedia\Gigya\Action;

use GreaterMedia\Gigya\GigyaSession;

class Dispatcher {

	public $page_size = 100;

	function save_action( $action, $user_id = null ) {
		$actions = array( $action );
		$this->save_actions( $actions, $user_id );
	}

	function save_actions( $actions, $user_id = null ) {
		$pages = $this->actions_to_pages( $actions );

		foreach ( $pages as $page ) {
			$this->publish( $page, $user_id );
		}
	}

	function publish( $actions, $user_id = null ) {
		$params    = $this->params_for_page( $actions, $user_id );
		$publisher = new Publisher();

		$publisher->enqueue( $params );
	}

	function actions_to_pages( $actions ) {
		return array_chunk( $actions, $this->page_size );
	}

	function params_for_page( $page, $user_id = null ) {
		$params = array();
		$params['actions'] = $page;

		if ( $user_id === 'logged_in_user' || is_null( $user_id ) ) {
			$user_id = $this->get_user_id();
		}

		$params['user_id'] = $user_id;

		return $params;
	}

	function get_user_id() {
		if ( is_gigya_user_logged_in() ) {
			$user_id = get_gigya_user_id();
		} else {
			$user_id = 'guest';
		}

		return $user_id;
	}

}
