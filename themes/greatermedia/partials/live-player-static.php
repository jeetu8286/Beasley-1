<?php
/**
 * Partial to display live player states statically.
 */
?>

<div class="audio-interface">
	<div class="audio-interface__container">
	  <?php // @TODO Available classes to add to audio-stream: -multiple, -open ?>
		<nav class="audio-stream <?php // if ( count( $streams ) >= 2 ) { ?>-multiple -open<?php //} ?>">
			<ul class="audio-stream__list">
				<li class="audio-stream__current">
					<div class="audio-stream__title"><?php esc_html_e( '$active_stream', 'greatermedia' ); ?></div>
					<ul class="audio-stream__available">
						<?php // @TODO uncomment foreach ( $streams as $stream => $description ) : ?>
						<?php for ( $i = 1; $i <= 4; $i ++ ) : ?>
							<li class="audio-stream__item">
								<span class="audio-stream__name"><?php esc_html_e( '$stream', 'greatermedia' ); ?></span>
								<span class="audio-stream__desc"><?php esc_html_e( '$description', 'greatermedia' ); ?></span>
							</li>
						<?php endfor; ?>
						<?php // @TODO uncomment endforeach; ?>
					</ul>
				</li>
			</ul><!-- .audio-stream__list -->

			<div class="audio-stream__sponsored">
				<span class="audio-stream__sponsored--text"><?php esc_html_e( 'Sponsored', 'greatermedia' ); ?></span> <span class="audio-stream__sponsored--name"><?php esc_html_e( 'Company Name', 'greatermedia' ); ?></span>
			</div>
		</nav><!-- .audio-stream -->

		<div id="js-audio-player" class="audio-player">

			<?php // @TODO Available classes to add to audio-volume: -open ?>
			<div id="js-audio-volume" class="audio-volume -open">
				<button id="audio-volume-mute" class="audio-volume__mute"><span class="audio-volume__text">Mute Volume</span></button>
				<input type="range" name="player-volume" id="audio-volume-slider" min="0" max="100" step="1" value="75" title="Volume"/>
				<button id="js-audio-volume-button" class="audio-volume__btn"><span class="audio-volume__text">Volume</span></button>
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

		  	<?php // @TODO Available classes to add to audio-playing: -show ?>
				<div id="js-audio-playing" class="audio-playing -show">
					<div id="js-track-info" class="audio-playing__track"><?php esc_html_e( 'Track Name', 'greatermedia' ); ?></div>
					<div id="js-artist-info" class="audio-playing__artist"><?php esc_html_e( 'Artist Name', 'greatermedia' ); ?></div>
				</div><!-- .audio-playing -->

				<?php // @TODO Available classes to add to audio-status: -show ?>
				<div id="js-audio-status" class="audio-status -show">
					<div id="js-audio-status-listen" class="audio-status__btn"><?php esc_html_e( 'Listen Live', 'greatermedia' ); ?></div>
				</div>

				<div id="js-audio-more" class="audio-more"><a href="#"><?php esc_html_e( '...', 'greatermedia' ); ?></a></div>

				<div id="js-audio-time" class="audio-time">
					<div id="js-audio-time__progressbar" class="audio-time__progressbar">
						<span id="js-audio-time__progress" class="audio-time__progress"></span>
					</div>
					<div id="js-audio-time__elapsed" class="audio-time__elapsed"></div>
					<div id="js-audio-time__remaining" class="audio-time__remaining"></div>
				</div><!-- .audio-time -->

		  	<?php // @TODO Available classes to add to audio-ad: -show ?>
				<div id="js-audio-ad" class="audio-ad">
					<?php // @TODO Ad code ?>
					Ad code here
				</div>

			</div><!-- .audio-readout -->
		</div><!-- .audio-player -->

	</div>
</div><!-- .audio-interface -->
