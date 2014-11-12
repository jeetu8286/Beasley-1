<?php
/**
 * The live player sidebar
 *
 * @package Greater Media
 * @since   0.1.0
 */
?>
<aside id="live-player__sidebar" class="live-player">

	<nav class="live-player__stream">
		<ul class="live-player__stream--list">
			<li class="live-player__stream--current">
				<div class="live-player__stream--title">Stream</div>
				<div class="live-player__stream--current-name">HD1</div>
				<ul class="live-player__stream--available">
					<li class="live-player__stream--item">
						<div class="live-player__stream--name">HD1</div>
						<div class="live-player__stream--desc">A brief description can be used here</div>
					</li>
					<li class="live-player__stream--item">
						<div class="live-player__stream--name">HD2</div>
						<div class="live-player__stream--desc">A brief description can be used here</div>
					</li>
					<li class="live-player__stream--item">
						<div class="live-player__stream--name">HD3</div>
						<div class="live-player__stream--desc">A brief description can be used here</div>
					</li>
					<li class="live-player__stream--item">
						<div class="live-player__stream--name">FM</div>
						<div class="live-player__stream--desc">A brief description can be used here</div>
					</li>
					<li class="live-player__stream--item">
						<div class="live-player__stream--name">FM2</div>
						<div class="live-player__stream--desc">A brief description can be used here</div>
					</li>
				</ul>
			</li>
		</ul>
	</nav>

	<div id="live-player" class="live-player__container">

		<div id="on-air" class="on-air">
			<span class="on-air__title">On Air:</span><span class="on-air__show">Preston and Steve Show</span>
		</div>

		<div id="up-next" class="up-next">
			<span class="up-next__title">Up Next:</span><span class="up-next__show">Pierre Robert</span>
		</div>

		<div class="live-stream">
			<?php

				if ( defined( 'GREATER_MEDIA_GIGYA_TEST_UI' ) && GREATER_MEDIA_GIGYA_TEST_UI ) {
					do_action( 'gm_live_player' );?>
					<div class="live-stream__status">
						<div id="live-stream__listen-now" class="live-stream__listen-now--btn"><?php _e( 'Listen Live', 'greatermedia' ); ?></div>
						<div id="live-stream__now-playing" class="live-stream__now-playing--btn">Now Playing</div>
					</div>
					<div id="nowPlaying" class="now-playing">
						<div id="trackInfo" class="now-playing__info"></div>
						<div id="npeInfo"></div>
					</div>
					<div id="now-playing" class="now-playing">
						<div class="now-playing__title">Track Title</div>
						<div class="now-playing__artist">Artist Name</div>
					</div>
					<?php do_action( 'gm_live_player_test_ui' );
				} else {

					if ( is_gigya_user_logged_in() ) { ?>
						<?php do_action( 'gm_live_player' ); ?>
						<div class="live-stream__status">
							<div id="live-stream__now-playing" class="live-stream__now-playing--btn">Now Playing</div>
						</div>
						<div id="nowPlaying" class="now-playing">
							<div id="trackInfo" class="now-playing__info"></div>
							<div id="npeInfo"></div>
						</div>
					<?php } else { ?>
						<div class="live-stream__status">
							<div id="live-stream__listen-now" class="live-stream__listen-now--btn"><?php _e( 'Listen Live', 'greatermedia' ); ?></div>
						</div>
					<?php }
				}

			?>
		</div>

		<?php /* <div class="live-player__volume">
			<div class="live-player__volume--btn"></div>
			<div class="live-player__volume--level"></div>
			<div class="live-player__volume--up"></div>
		</div> */ ?>

	</div>

	<div id="live-links" class="live-links">
		<h3 class="widget--live-player__title"><?php _e( 'Live Links', 'greatermedia' ); ?></h3>
		<?php dynamic_sidebar( 'liveplayer_sidebar' ); ?>
		<div class="widget--live-player">
			<ul>
				<li class="live-link__type--audio">
					<div class="live-link__title"><a href="#">WMMR Promo - 10/16/14 - A surprise in the movie "Fury"</a></div>
				</li>
				<li class="live-link__type--video">
					<div class="live-link__title"><a href="#">"Breakdance Conversation" with Jimmy Fallon & Brad Pitt</a></div>
				</li>
				<li class="live-link__type--link">
					<div class="live-link__title"><a href="#">Flyers Charities Halloween 5K will be held on Saturday, October 25</a></div>
				</li>
			</ul>
		</div>
	</div>

</aside>