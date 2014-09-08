<?php
/*
Plugin Name: Greater Media Gigya
Description: Greater Media Gigya
Author: 10up
*/

class GreaterMediaGigya {

	function __construct() {

	}

	function enable() {
		add_action( 'admin_menu', array( $this, 'onAdminMenu' ) );
	}

	function onAdminMenu() {
		add_management_page(
			'Greater Media Gigya',
			'Greater Media Gigya',
			'manage_options',
			'greater-media-gigya',
			array( $this, 'showSegmentsPage' )
		);
	}

	function showSegmentsPage() {
		echo "<style type='text/css'>";
		require_once( __DIR__ . '/templates/mockup.css' );
		echo "</style>";
		require_once( __DIR__ . '/templates/mockup.html' );
	}

}

$greater_media_gigya = new GreaterMediaGigya();
$greater_media_gigya->enable();
