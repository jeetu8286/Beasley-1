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
		return ! is_admin() && is_ssl() && ! $this->is_core_file();
	}

	function is_core_file() {
		$path = $_SERVER['REQUEST_URI'];
		return strpos( $path, '/wp-' ) === 0;
	}

	function get_redirect_url() {
		$domain   = $_SERVER['SERVER_NAME'];
		$path     = $_SERVER['REQUEST_URI'];

		return 'http://' . $domain . $path;
	}

}

$frontend_http_redirector = new FrontEndHttpRedirector();
$frontend_http_redirector->enable();
