<?php

// Allows overriding options with constants
add_filter( 'configure_smtp__options', function( $options ) {
	/*
	 *  Set any of the following values in the constant, ONLY if you want to override the UI defined values
	 *
	 *  [use_gmail] =>
	 *  [host] => localhost
	 *  [port] => 25
	 *  [smtp_secure] => None
	 *  [smtp_auth] =>
	 *  [smtp_user] =>
	 *  [smtp_pass] =>
	 *  [wordwrap] =>
	 *  [debug] =>
	 *  [from_email] =>
	 *  [from_name] =>
	 *  [_version] => 3.1
	 */
	if ( defined('CONFIGURE_SMTP_OVERRIDES' ) && is_array( CONFIGURE_SMTP_OVERRIDES ) ) {
		$options = wp_parse_args( CONFIGURE_SMTP_OVERRIDES, $options );
	}

	return $options;
});
