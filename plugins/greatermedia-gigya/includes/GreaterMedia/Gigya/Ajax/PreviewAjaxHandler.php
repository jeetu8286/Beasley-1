<?php

namespace GreaterMedia\Gigya\Ajax;

use GreaterMedia\Gigya\AccountsSearcher;

/**
 * PreviewAjaxHandler is the ajax handler invoked by the client to
 * preview a member query.
 *
 * It receives the client generated GQL query as a parameter. And
 * returns the corresponding user accounts using the Gigya API.
 *
 * @namespace GreaterMedia\Gigya
 */
class PreviewAjaxHandler extends AjaxHandler {

	/**
	 * The name of the ajax action. Corresponds to the WordPress action,
	 * wp_ajax_preview_member_query.
	 *
	 * @access public
	 * @return string
	 */
	public function get_action() {
		return 'preview_member_query';
	}

	/**
	 * Runs a GQL query through the Gigya accounts search API and
	 * returns its result to the client.
	 *
	 * Preview results are limited to 5 results for better performance.
	 *
	 * @access public
	 * @param array $params The JSON stringified parameters from the client.
	 * @return array
	 */
	public function run( $params ) {
		$query    = $params['query'];
		$searcher = new AccountsSearcher();
		$response = $searcher->search( $query, false, 5 );
		$json     = json_decode( $response, true );
		$accounts = array();
		$i        = 0;

		foreach ( $json['results'] as $account ) {
			$accounts[] = array( 'email' => $account['profile']['email'] );
			if ( ++$i >= 5 ) {
				break;
			}
		}

		return array(
			'accounts' => $accounts,
			'total'    => $json['totalCount'],
		);
	}

}
