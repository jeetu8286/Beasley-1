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
<style type="text/css">
	#up-next {
	}
</style>
<aside id="live-player__sidebar" class="live-player">

	<nav class="live-player__stream">
		<ul class="live-player__stream--list">
			<li class="live-player__stream--current">
				<div class="live-player__stream--title"><?php _e( 'Stream:', 'greatermedia' ); ?></div>
				<div class="live-player__stream--current-name"><?php echo esc_html_e( $active_stream ); ?></div>
				<ul class="live-player__stream--available">
					<?php foreach ( $streams as $stream => $description ) : ?>
					<li class="live-player__stream--item">
						<div class="live-player__stream--name"><?php echo esc_html_e( $stream ); ?></div>
						<div class="live-player__stream--desc"><?php echo esc_html_e( $description ); ?></div>
					</li>
					<?php endforeach; ?>
				</ul>
			</li>
		</ul>
	</nav>

	<div id="live-player" class="live-player__container">

		<?php /*
		<div id="up-next" class="up-next">
			<div class="up-next__title"></div>
			<div class="up-next__show"></div>
		</div>
		*/ ?>

		<div id="on-air" class="on-air" data-endpoint="<?php echo esc_url( home_url( '/on-air/' ) ); ?>">
			<i class="fa fa-spin fa-spinner fa-2x"></i>
		</div>

		<div id="live-player--more" class="live-player--more"><?php _e( '...', 'greatermedia' ); ?></div>
		<?php do_action( 'gm_live_player' ); ?>
		<div id="audio__time" class="audio__time">
			<div id="audio__progress-bar" class="audio__progress-bar">
				<span id="audio__progress" class="audio__progress"></span>
			</div>
			<div id="audio__time--elapsed" class="audio__time--elapsed"></div>
			<div id="audio__time--remaining" class="audio__time--remaining"></div>
		</div>
		<div id="nowPlaying" class="now-playing">
			<div id="trackInfo" class="now-playing__info"></div>
			<div id="npeInfo"></div>
		</div>

		<div id="live-stream__status" class="live-stream__status">
			<div id="live-stream__login" class="live-stream__login"><?php _e( 'Log In To', 'greatermedia' ); ?></div>
			<div id="live-stream__now-playing" class="live-stream__now-playing--btn"><?php _e( 'Now Playing', 'greatermedia' ); ?></div>
			<div id="live-stream__listen-now" class="live-stream__listen-now--btn"><?php _e( 'Listen Live', 'greatermedia' ); ?></div>
		</div>

	</div>

	<div id="live-links" class="live-links">

		<h3 class="widget--live-player__title"><?php _e( 'Live Links', 'greatermedia' ); ?></h3>

		<?php dynamic_sidebar( 'liveplayer_sidebar' ); ?>

	</div>

</aside>