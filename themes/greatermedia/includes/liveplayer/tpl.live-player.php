<?php
/**
 * The live player sidebar
 *
 * @package Greater Media
 * @since   0.1.0
 */
?>
<aside id="live-player--sidebar" class="live-player">

	<nav class="live-player__stream">
		<ul class="live-player__stream--list">
			<li class="live-player__stream--current">
				Stream<span class="live-player__stream--title">HD1</span>
				<ul class="live-player__stream--available">
					<li class="live-player__stream--item">
						<span class="live-player__stream--title">HD1</span>
						<span class="live-player__stream--desc">A brief description can be used here</span>
					</li>
					<li class="live-player__stream--item">
						<span class="live-player__stream--title">HD2</span>
						<span class="live-player__stream--desc">A brief description can be used here</span>
					</li>
					<li class="live-player__stream--item">
						<span class="live-player__stream--title">HD3</span>
						<span class="live-player__stream--desc">A brief description can be used here</span>
					</li>
					<li class="live-player__stream--item">
						<span class="live-player__stream--title">FM</span>
						<span class="live-player__stream--desc">A brief description can be used here</span>
					</li>
					<li class="live-player__stream--item">
						<span class="live-player__stream--title">FM2</span>
						<span class="live-player__stream--desc">A brief description can be used here</span>
					</li>
				</ul>
			</li>
		</ul>
	</nav>

	<div id="live-player" class="live-player--container">

		<div id="on-air" class="on-air">
			<h2 class="on-air--title">On Air:</h2>
			<h3 class="on-air--show">Preston and Steve Show</h3>
		</div>

		<div class="live-player--controls">
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

		<div id="now-playing" class="now-playing">
			<h4 class="now-playing--title">Track Title</h4>
			<h5 class="now-playing--artist">Artist Name</h5>
		</div>

	</div>

	<div id="live-links" class="live-links">
		<?php dynamic_sidebar( 'liveplayer_sidebar' ); ?>
		<div class="live-link">
			<div class="live-link--type live-link--type_audio"></div>
			<h3 class="live-link--title"><a href="#">WMMR Promo - 10/16/14 - A surprise in the movie "Fury"</a></h3>
		</div>
		<div class="live-link">
			<div class="live-link--type live-link--type_video"></div>
			<h3 class="live-link--title"><a href="#">"Breakdance Conversation" with Jimmy Fallon & Brad Pitt</a></h3>
		</div>
		<div class="live-link">
			<div class="live-link--type live-link--type_link"></div>
			<h3 class="live-link--title"><a href="#">Flyers Charities Halloween 5K will be held on Saturday, October 25</a></h3>
		</div>
	</div>

</aside>