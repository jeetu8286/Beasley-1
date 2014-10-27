<?php

class GreaterMediaLivePlayer {

	public static function init() {
		add_action( 'wp_footer', array( __CLASS__, 'render_live_player' ) );
	}

	public static function render_live_player() {
		include __DIR__ . '/tpl.live-player.php';
	}

}

GreaterMediaLivePlayer::init();