<?php

namespace GreaterMedia\Gigya;

require_once __DIR__ . '/../../Gigya/GSSDK.php';

/**
 * Account searcher uses the Gigya accounts api to match accounts to a
 * specified GQL query.
 *
 * @package GreaterMedia\Gigya
 */
class AccountsSearcher {

	public $api_key    = '3_e_T7jWO0Vjsd9y0WJcjnsN6KaFUBv6r3VxMKqbitvw-qKfmaUWysQKa1fra5MTb6';
	public $secret_key = 'trS0ufXWUXZ0JBcpr/6umiRfgUiwT7YhJMQSDpUz/p8=';

	public function search( $query ) {
		$request = $this->request_for( 'accounts.search' );
		$request->setParam( 'query',  $query );
		$response = $request->send();

		if ( $response->getErrorCode() === 0 ) {
			return $this->accounts_for_response( $response );
		} else {
			throw new \Exception( $response->getErrorMessage() );
		}
	}

	public function accounts_for_response( $response ) {
		return $response->getResponseText();
	}

	public function request_for( $method ) {
		return new \GSRequest(
			$this->api_key,
			$this->secret_key,
			$method
		);
	}

}
