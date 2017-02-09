<?php
/**
 * Partial to display live player states statically.
 */
?>

<div class="live-stream">
	<nav class="live-player__stream">
		<ul class="live-player__stream--list">
			<li class="live-player__stream--current">
				<div class="live-player__stream--title"><?php esc_html_e( 'Stream:', 'greatermedia' ); ?></div>
				<div class="live-player__stream--current-name"><?php esc_html_e( '$active_stream', 'greatermedia' ); ?></div>
				<ul class="live-player__stream--available">
					<?php // foreach ( $streams as $stream => $description ) : ?>
					<?php for ( $i = 1; $i <= 4; $i ++ ) : ?>
						<li class="live-player__stream--item">
							<div class="live-player__stream--name"><?php esc_html_e( '$stream', 'greatermedia' ); ?></div>
							<div class="live-player__stream--desc"><?php esc_html_e( '$description', 'greatermedia' ); ?></div>
						</li>
					<?php endfor; ?>
					<?php // endforeach; ?>
				</ul>
			</li>
		</ul>
	</nav>

<!--	<a href="#" class="live-audio__link">Listen Live</a>-->

	<div id="live-player" class="live-player__container">

		<div id="on-air" class="on-air" data-endpoint="<?php echo esc_url( home_url( '/on-air/' ) ); ?>">
			<div class="on-air__title"></div>
			<div class="on-air__show"></div>
		</div>

		<div id="live-player--more" class="live-player--more"><?php esc_html_e( '...', 'greatermedia' ); ?></div>

		<div class="live-stream__player">
			<div class="live-stream__controls">
				<div id="playButton" class="live-stream__btn--play" data-action="play-live"></div>
				<div id="loadButton" class="live-stream__btn--loading"><i class="gmr-icon icon-spin icon-loading"></i></div>
				<div id="pauseButton" class="live-stream__btn--pause"></div>
				<div id="resumeButton" class="live-stream__btn--resume"></div>
			</div>

			<div id="live-stream__container" class="live-stream__container">
				<div id="td_container" class="live-stream__container--player"></div>
				<div class="pre-roll__notification"><?php esc_html_e( 'Live stream will be available after this brief ad from our sponsors', ' gmliveplayer' ); ?></div>
			</div>

			<div id="live-player--volume"></div>
		</div>

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
			<div id="live-stream__now-playing" class="live-stream__now-playing--btn"><?php esc_html_e( 'Now Playing', 'greatermedia' ); ?></div>
			<div id="live-stream__listen-now" class="live-stream__listen-now--btn"><?php esc_html_e( 'Listen Live', 'greatermedia' ); ?></div>
		</div>

	</div>
</div>
