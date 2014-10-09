<?php

namespace GreaterMedia\Gigya;

class FormEntryPublisher {

	public $job_name = 'publish_form_entry_async_job';

	function enable() {
		add_action(
			'gform_after_submission',
			array( $this, 'did_form_submission' ),
			10, 2
		);

		add_action(
			$this->job_name,
			array( $this, 'publish_form_entry' )
		);
	}

	function did_form_submission( $entry, $form ) {
		if ( ! is_gigya_user_logged_in() ) {
			error_log( 'Not logged in to Gigya' );
			return;
		}

		// TODO: associate via contest cpt meta, current form itself
		$params = array(
			'form_id'           => $form['id'],
			'entry_meta'        => array(
				'entry_type'    => 'contest',
				'entry_type_id' => $form['id'],
			),
			'entry' => $entry,
			'user_id' => gigya_user_id(),
		);

		wp_async_task_add( $this->job_name, $params );
	}

	function publish_form_entry( $params ) {
		$form_id    = $params['form_id'];
		$form       = \GFAPI::get_form( $form_id );
		$entry      = $params['entry'];
		$entry_meta = $params['entry_meta'];
		$user_id    = $params['user_id'];

		$account_data = $this->get_account_data( $user_id );
		$builder      = new AccountEntriesBuilder( $form, $entry, $entry_meta );
		$entries      = $builder->build();

		if ( array_key_exists( 'form_entries', $account_data ) ) {
			$form_entries = array_merge( $account_data['form_entries'], $entries );
		} else {
			$form_entries = $entries;
		}

		// for overwriting
		//$form_entries = $entries;
		$account_data['form_entries'] = $form_entries;
		$this->update_account_data( $user_id, $account_data );
	}

	function update_account_data( $user_id, $account_data ) {
		$request = $this->request_for( 'accounts.setAccountInfo' );
		$request->setParam( 'UID', $user_id );
		$request->setParam( 'data', json_encode( $account_data  ) );
		$response = $request->send();

		if ( $response->getErrorCode() === 0 ) {
			error_log( 'Updated account data for: ' . $user_id );
		} else {
			error_log( 'Failed to update account data for: ' . $user_id );
			error_log( $response->getResponseText() );
		}
	}

	function get_account_data( $user_id ) {
		$request = $this->request_for( 'accounts.getAccountInfo' );
		$request->setParam( 'UID', $user_id );
		$response = $request->send();

		if ( $response->getErrorCode() === 0 ) {
			$account_info_json = $response->getResponseText();
			$account_info      = json_decode( $account_info_json, true );

			if ( array_key_exists( 'data', $account_info ) ) {
				return $account_info['data'];
			} else {
				return array();
			}
		} else {
			throw new \Exception( $response->getErrorMessage() );
		}
	}

	function request_for( $method ) {
		$request = new \GSRequest(
			GMR_GIGYA_API_KEY,
			GMR_GIGYA_SECRET_KEY,
			$method
		);

		return $request;
	}

}
