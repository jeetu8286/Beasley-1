<?php
/**
 * The live player sidebar
 *
 * @package Greater Media
 * @since   0.1.0
 */

$streams = apply_filters( 'gmr_live_player_streams', array() );
$active_stream = key( $streams );
if ( empty( $active_stream ) ) {
	$active_stream = 'None';
}

?>
<aside id="live-player__sidebar" class="live-player">

	<nav class="live-player__stream">
		<ul class="live-player__stream--list">
			<li class="live-player__stream--current">
				<div class="live-player__stream--title">Stream</div>
				<div class="live-player__stream--current-name"><?php echo esc_html( $active_stream ); ?></div>
				<ul class="live-player__stream--available">
					<?php foreach ( $streams as $stream => $description ) : ?>
					<li class="live-player__stream--item">
						<div class="live-player__stream--name"><?php echo esc_html( $stream ); ?></div>
						<div class="live-player__stream--desc"><?php echo esc_html( $description ); ?></div>
					</li>
					<?php endforeach; ?>
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

	</div>

</aside>