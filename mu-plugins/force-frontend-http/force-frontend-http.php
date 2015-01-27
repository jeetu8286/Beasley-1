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
		$site_url = site_url();
		$path     = $_SERVER['REQUEST_URI'];
		$full_url = $site_url . $path;

		return str_replace( 'https://', 'http://', $full_url );
	}

}

$frontend_http_redirector = new FrontEndHttpRedirector();
$frontend_http_redirector->enable();
