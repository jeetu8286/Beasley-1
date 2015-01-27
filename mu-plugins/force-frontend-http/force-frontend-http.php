<?php
/**
 * Redirects HTTPS requests on the frontend to the corresponding
 * non-http version.
 */

class FrontEndHttpRedirector {

	function enable() {
		add_action( 'init', array( $this, 'run' ) );
	}

	function run() {
		if ( $this->needs_redirect() ) {
			$this->redirect();
		}
	}

	function redirect() {
		wp_safe_redirect( $this->get_redirect_url() );
		die();
	}

	function needs_redirect() {
		return ! is_admin() && is_ssl();
	}

	function get_redirect_url() {
		$host = $_SERVER['SERVER_NAME'];
		$uri  = $_SERVER['REQUEST_URI'];

		return 'http://' . $host . $uri;
	}

}

$frontend_http_redirector = new FrontEndHttpRedirector();
$frontend_http_redirector->enable();
