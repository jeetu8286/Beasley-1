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
		$redirect_url = $this->get_redirect_url();
		$html = <<<HTML
<meta http-equiv="refresh" content="0; url={$redirect_url}" />
<script type="text/javascript">
	window.location.href = '{$redirect_url}';
</script>
HTML;

		echo $html;
		die();
	}

	function needs_redirect() {
		return ! $this->is_login_page() && ! is_admin() && is_ssl();
	}

	function is_login_page() {
		return in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) );
	}

	function get_redirect_url() {
		$domain   = $_SERVER['SERVER_NAME'];
		$path     = $_SERVER['REQUEST_URI'];

		return 'http://' . $domain . $path;
	}

}

$frontend_http_redirector = new FrontEndHttpRedirector();
$frontend_http_redirector->enable();
