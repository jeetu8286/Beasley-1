<?php
/**
 * Page endpoint for the hybrid react implementation
 *
 * @package Bbgi
 */
namespace Bbgi\Endpoints;

use Bbgi\Module;

class Page extends Module {

	/**
	 * Register the custom rest endpoint
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}

	/**
	 * Register our custom routes.
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			'bbgi/v1',
			'page',
			[
				'methods'  => \WP_REST_Server::READABLE,
				'callback' => [ $this, 'get_page' ],
				'args' => [
					'url'       => [
						'type'     => 'string',
						'required' => true,
					],
					'redirects' => [
						'type'     => 'boolean',
						'required' => false,
					]
				]
			]
		);
	}

	/**
	 * Fetches a page.
	 *
	 * @param string $url The URL to be fetched.
	 *
	 * @return string|false
	 */
	public function fetch_page( $url ) {
		$page_response = wp_remote_request( $url );

		if ( is_wp_error( $page_response ) ) {
			return false;
		}

		if ( ! in_array( $page_response['response']['code'], [ 200, 201 ] ) ) {
			return false;
		}

		return $page_response;
	}

	/**
	 * Fetches a page for react.
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return \WP_REST_Response
	 */
	public function get_page( \WP_REST_Request $request ) {
		$url       = $request->get_param( 'url' );
		$redirects = (bool) $request->get_param( 'redirects' );

		$response = [
			'status'    => '',
			'redirects' => [],
			'html'      => false,
		];

		$page_response = $this->fetch_page( $url );
		// check if url is internal.
		$response['html'] = wp_remote_retrieve_body( $page_response );
		$response['status'] = $page_response['response']['code'];

		if ( $redirects ) {
			// fetch redirects
		}

		return rest_ensure_response( $response );
	}
}
