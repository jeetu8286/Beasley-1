<?php
/**
 * The live player sidebar
 *
 * @package Greater Media
 * @since   0.1.0
 */
?>
<aside id="live-player--sidebar" class="live-player">

	<div class="now-playing--logo">

	</div>

	<div id="live-player" class="live-player--container">

		<?php

		if ( defined( 'GREATER_MEDIA_GIGYA_TEST_UI' ) && GREATER_MEDIA_GIGYA_TEST_UI ) {
			echo '<div id="live-player--listen_now" class="live-player--listen_btn" style="visibility:visible">Listen Now</div>';
			do_action( 'gm_live_player' );
			do_action( 'gm_live_player_test_ui' );
		} else {

			if ( is_gigya_user_logged_in() ) {
				do_action( 'gm_live_player' );
			} else {
				echo '<div id="live-player--listen_now" class="live-player--listen_btn" style="visibility:visible">Listen Now</div>';
			}
		}

		?>
	</div>

	<div class="now-playing--title">

	</div>

	<div class="now-playing--artist">

	</div>

	<div class="live-player--social">

		<ul>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
		</ul>

	</div>

	<div class="live-player--next">
		Up Next: <span class="live-player--next--artist">Pierre Robert</span>
	</div>

</aside>