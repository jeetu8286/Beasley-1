<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

class GreaterMediaContestEntryInstagram extends GreaterMediaContestEntry {

	public function render_preview() {
		return "This is an Instagram submission";
	}

}