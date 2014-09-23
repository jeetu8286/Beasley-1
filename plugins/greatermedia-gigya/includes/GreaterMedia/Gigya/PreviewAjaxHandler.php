<?php

namespace GreaterMedia\Gigya;

//require_once __DIR__ . '/class-ajax-handler.php';
//require_once __DIR__ . '/class-accounts-searcher.php';

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
		// TODO:
		// derive from params,
		// account for * in keywords
		// test cases for serialization
		// research GQL escape sequences
		$query = $_POST['data']['query'];
		$query = str_replace( '\\\'', '\'', $query );

		$count_query = str_replace( '*', 'count(*)', $query );
		$query .= ' limit 5';

		$searcher = new AccountsSearcher();
		$total_response = $searcher->search( $count_query );
		$response = $searcher->search( $query );
		$json = json_decode( $response, true );
		$totals = json_decode( $total_response, true );

		$accounts = array();
		$i = 0;

		foreach ( $json['results'] as $account ) {
			$accounts[] = $account['profile']['email'];
			if ( ++$i >= 5 ) {
				break;
			}
		}

		$to_return = array(
			'accounts' => $accounts,
			'total' => $totals['totalCount'],
		);

		return $to_return;
	}

}
