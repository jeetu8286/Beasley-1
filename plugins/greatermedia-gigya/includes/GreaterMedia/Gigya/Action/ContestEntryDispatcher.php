<?php

namespace GreaterMedia\Gigya\Action;

class ContestEntryDispatcher {

	function register() {
		add_action( 'greatermedia_contest_entry_save', array( $this, 'did_save_contest_entry' ) );
		add_action( 'greatermedia_survey_entry_save', array( $this, 'did_save_survey_entry' ) );
	}

	function publish( $action ) {
		save_gigya_action( $action, 'logged_in_user' );
	}

	function did_save_contest_entry( $entry ) {
		$action = $this->action_for_entry( $entry, 'contest' );
		if ( $action ) {
			$this->publish( $action );
		}
	}

	function did_save_survey_entry( $entry ) {
		$action = $this->action_for_entry( $entry, 'survey' );
		if ( $action ) {
			$this->publish( $action );
		}
	}

	function action_for_entry( $entry, $type = 'contest' ) {
		$data = $this->action_data_for_entry_reference( $entry->entry_reference );
		
		return empty( $data ) ? false : array(
			'actionType' => 'action:' . $type,
			'actionID'   => strval( $entry->post->post_parent ),
			'actionData' => $data,
		);
	}

	function action_data_for_entry_reference( $entry_reference ) {
		if ( is_string( $entry_reference ) ) {
			$entry_reference = json_decode( $entry_reference, true );
		}

		$actionData = array();

		foreach ( $entry_reference as $key => $value ) {
			$actionData[] = array(
				'name'  => (string) $key,
				'value' => $value,
			);
		}

		return $actionData;
	}

}
