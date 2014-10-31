<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

class GreaterMediaContestEntryGravityForms extends GreaterMediaContestEntry {

	public function render_preview() {
		return "This is a Gravity Forms submission";
	}

}