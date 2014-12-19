<?php
/**
 * Ensure the API user cant access the front end of the site.
 *
 * @todo This needs to be full-on roles/caps before launch
 */

add_filter( 'restricted_site_access_is_restricted', function( $is_restricted, $wp ) {
	$user = wp_get_current_user();

	$api_user_name = 'nowplayingwmgk';

	if ( $api_user_name == $user->data->user_login ) {
		wp_die("The current user is only authorized for API access");
	}

	return $is_restricted;
}, 10, 2 );
