<?php
/**
 * Partial to display live player states statically.
 */
?>

<div class="audio-interface">
	<div class="audio-interface__container">
	  <?php // @TODO Available classes to add to audio-stream: -multiplestreams, -open ?>
		<nav class="audio-stream <?php // if ( count( $streams ) >= 2 ) { ?>-multiplestreams -open<?php //} ?>">
			<ul class="audio-stream__list">
				<li class="audio-stream__current">
					<div class="audio-stream__title"><?php esc_html_e( '$active_stream', 'greatermedia' ); ?></div>
					<ul class="audio-stream__available">
						<?php // foreach ( $streams as $stream => $description ) : ?>
						<?php for ( $i = 1; $i <= 4; $i ++ ) : ?>
							<li class="audio-stream__item">
								<span class="audio-stream__name"><?php esc_html_e( '$stream', 'greatermedia' ); ?></span>
								<span class="audio-stream__desc"><?php esc_html_e( '$description', 'greatermedia' ); ?></span>
							</li>
						<?php endfor; ?>
						<?php // endforeach; ?>
					</ul>
				</li>
			</ul><!-- .audio-stream__list -->

			<div class="audio-stream__sponsored">
				<span class="audio-stream__sponsored--text"><?php esc_html_e( 'Sponsored', 'greatermedia' ); ?></span> <span class="audio-stream__sponsored--name"><?php esc_html_e( 'Company Name', 'greatermedia' ); ?></span>
			</div>
		</nav><!-- .audio-stream -->

		<div id="js-audio-player" class="audio-player">

			<?php // @TODO Available classes to add to live-player__volume: -open ?>
			<div class="audio-volume -open">
				<button class="audio-volume__mute"><span class="audio-volume__text">Mute Volume</span></button>
				<input type="range" name="player-volume" id="player-volume" min="0" max="100" step="1" value="75" title="Volume"/>
				<button class="audio-volume__btn"><span class="audio-volume__text">Volume</span></button>
			</div><!-- .audio-volume -->

			<div class="audio-controls">
				<div id="playButton" class="audio-controls__play" data-action="play-live"></div>
				<div id="loadButton" class="audio-controls__loading"><i class="gmr-icon icon-spin icon-loading"></i></div>
				<div id="pauseButton" class="audio-controls__pause"></div>
			</div><!-- .audio-controls -->

			<div id="js-audio-readout" class="audio-readout">

				<?php // @TODO Available classes to add to audio-readout__notification: -show ?>
				<div id="js-notification-listen" class="audio-readout__notification audio-readout__notification--listen"><?php esc_html_e( 'Listen Live', ' gmliveplayer' ); ?></div>
				<div id="js-notification-preroll" class="audio-readout__notification audio-readout__notification--preroll"><?php esc_html_e( 'Live stream will be available after this brief ad from our sponsors', ' gmliveplayer' ); ?></div>

				<div id="js-now-playing" class="audio-playing">
					<div id="js-track-info" class="audio-playing__track"><?php esc_html_e( 'Track Name', 'greatermedia' ); ?></div>
					<div id="js-artist-info" class="audio-playing__artist"><?php esc_html_e( 'Artist Name', 'greatermedia' ); ?></div>
				</div><!-- .audio-playing -->


				<div id="live-stream__status" class="live-stream__status">
					<div id="live-stream__now-playing" class="live-stream__now-playing--btn"><?php esc_html_e( 'Now Playing', 'greatermedia' ); ?></div>
					<div id="live-stream__listen-now" class="live-stream__listen-now--btn"><?php esc_html_e( 'Listen Live', 'greatermedia' ); ?></div>
				</div>

				<div id="live-player--more" class="live-player--more"><?php esc_html_e( '...', 'greatermedia' ); ?></div>

				<div id="js-audio-time" class="audio-time">
					<div id="js-audio-time__progressbar" class="audio-time__progressbar">
						<span id="js-audio-time__progress" class="audio-time__progress"></span>
					</div>
					<div id="js-audio-time__elapsed" class="audio-time__elapsed"></div>
					<div id="js-audio-time__remaining" class="audio-time__remaining"></div>
				</div><!-- .audio-time -->

		  	<?php // @TODO Available classes to add to audio-ad: -show ?>
				<div id="js-audio-ad" class="audio-ad -show">
					<?php // @TODO Ad code ?>
					Ad code here
				</div>

			</div><!-- .audio-readout -->
		</div><!-- .audio-player -->

	</div>
</div><!-- .audio-interface -->
