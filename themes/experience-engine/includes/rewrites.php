<?php

add_filter( 'do_parse_request', 'ee_check_domain_in_request_url' );

if ( ! function_exists( 'ee_check_domain_in_request_url' ) ) :
	function ee_check_domain_in_request_url( $do_parse_request ) {
		global $ee_blog_id;

		$sites = get_sites();

		$domains = wp_list_pluck( $sites, 'domain' );
		$domains = array_map( 'preg_quote', $domains );
		$domains = implode( '|', $domains );
		if ( preg_match( "#^/({$domains})(/.+)#i", $_SERVER['REQUEST_URI'], $matches ) ) {
			foreach ( $sites as $site ) {
				if ( $site->domain == $matches[1] && !! $site->public ) {
					$ee_blog_id = $site->blog_id;
					$_SERVER['REQUEST_URI'] = $matches[2];
					break;
				}
			}
		}

		return $do_parse_request;
	}
endif;
