<?php
/**
 * The live player sidebar
 *
 * @package Greater Media
 * @since   0.1.0
 */
?>
<aside id="live-player--sidebar" class="live-player">

	<div class="on-air">
		<h2 class="on-air--title">On Air:</h2>
		<h3 class="on-air--show">Preston and Steve Show</h3>
	</div>

	<div id="live-player" class="live-player--container">

		<?php

		if ( defined( 'GREATER_MEDIA_GIGYA_TEST_UI' ) && GREATER_MEDIA_GIGYA_TEST_UI ) {
			do_action( 'gm_live_player' ); ?>
			<div id="live-player--listen_now" class="live-player--listen_btn"><?php _e( 'Listen Now', 'greatermedia' ); ?></div>
			<?php do_action( 'gm_live_player_test_ui' );
		} else {

			if ( is_gigya_user_logged_in() ) { ?>
				<div id="live-player--listen_now" class="live-player--listen_btn"><?php _e( 'Listen Now', 'greatermedia' ); ?></div>
				<?php do_action( 'gm_live_player' );
			} else { ?>
				<div id="live-player--listen_now" class="live-player--listen_btn"><?php _e( 'Listen Now', 'greatermedia' ); ?></div>
			<?php }
		}

		?>
	</div>

	<?php
		if ( dynamic_sidebar( 'liveplayer_sidebar' ) ) : else : endif;
	?>
	
	<div class="now-playing">
		<h4 class="now-playing--title">Track Title</h4>
		<h5 class="now-playing--artist">Artist Name</h5>
	</div>

	<div class="live-links">

	</div>
</aside>