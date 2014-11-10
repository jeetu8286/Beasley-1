<?php
/**
 * Partial for the Live Player section on the Style Guide
 *
 * @package Greater Media
 * @since 0.1.0
 */
?>
<section id="live-player" class="sg-layout sg-sections">
	<div class="sg-content">
		<h2 class="sg-section-title"><?php _e( 'Live Player', 'greatermedia' ); ?></h2>

		<div class="live-player__col">
			<div id="live-player--sidebar" class="live-player">

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

				<div id="live-player" class="live-player--container">

					<div id="on-air" class="on-air">
						<div id="on-air" class="on-air">
							<span class="on-air--title">On Air:</span><span class="on-air--show">Preston and Steve Show</span>
						</div>
					</div>

					<div class="live-player--controls">
						<div class="gm-liveplayer">

							<div class="gm-liveplayer--container">

								<div class="container">
									<div id="gm-liveplayer--controls">
										<div id="playButton" class="gm-liveplayer--btn play" data-station="WKLBFM"></div>
									</div>

									<div class="live-player__status">
										<div id="live-player--listen_now" class="live-player--listen_btn" style="display: inline-block;">Listen Live</div>
									</div>

									<div id="gm-liveplayer--now_playing">
										<div id="nowPlaying">
											<div id="trackInfo" class="now-playing">
												<h4 class="now-playing__title">Track Title</h4>
												<h5 class="now-playing__artist">Artist Name</h5>
											</div>
											<div id="npeInfo"></div>
										</div>
									</div>

									<!-- Player placeholder -->
									<div id="td_container"></div>

								</div>

							</div>

						</div>

					</div>

					<div class="live-player__volume">
						<div class="live-player__volume--btn"></div>
						<div class="live-player__volume--level"></div>
						<div class="live-player__volume--up"></div>
					</div>

				</div>

				<div id="live-links" class="live-links">
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

			</div>

		</div>

		<div class="live-player__col">
			<div id="live-player--sidebar" class="live-player">

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

				<div id="live-player" class="live-player--container">

					<div id="on-air" class="on-air">
						<div id="on-air" class="on-air">
							<span class="on-air--title">On Air:</span><span class="on-air--show">Preston and Steve Show</span>
						</div>
					</div>

					<div class="live-player--controls">
						<div class="gm-liveplayer">

							<div class="gm-liveplayer--container">

								<div class="container">
									<div id="gm-liveplayer--controls">
										<div id="pauseButton" class="gm-liveplayer--btn" data-station="WKLBFM"></div>
									</div>

									<div class="live-player__status">
										<div id="live-player__now-playing" class="live-player__now-playing--btn" style="display: inline-block;">Now Playing</div>
									</div>

									<div id="gm-liveplayer--now_playing">
										<div id="nowPlaying">
											<div id="trackInfo" class="now-playing">
												<h4 class="now-playing__title">Track Title</h4>
												<h5 class="now-playing__artist">Artist Name</h5>
											</div>
											<div id="npeInfo"></div>
										</div>
									</div>

									<!-- Player placeholder -->
									<div id="td_container"></div>

								</div>

							</div>

						</div>

					</div>

					<div class="live-player__volume">
						<div class="live-player__volume--btn"></div>
						<div class="live-player__volume--level"></div>
						<div class="live-player__volume--up"></div>
					</div>

				</div>

				<div id="live-links" class="live-links">
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

			</div>
		</div>

		<div class="live-player__col">

			<div id="live-player--sidebar" class="live-player">

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

				<div id="live-player" class="live-player--container">

					<div class="live-player__resume">
						<div id="live-player__resume-live" class="live-player__resume--btn">Listen Live</div>
					</div>

					<div class="live-player--controls">
						<div class="gm-liveplayer">

							<div class="gm-liveplayer--container">

								<div class="container">
									<div id="gm-liveplayer--controls">
										<div id="resumeButton" class="gm-liveplayer--btn" data-station="WKLBFM"></div>
									</div>

									<div class="live-player__status">

									</div>

									<div id="gm-liveplayer--now_playing">
										<div id="nowPlaying">
											<div id="trackInfo" class="now-playing">
												<h4 class="now-playing__title">Jaxon’s Local Shots</h4>
												<h5 class="now-playing__artist">PODCAST - 8.23.14</h5>
											</div>
											<div id="npeInfo"></div>
										</div>
									</div>

									<!-- Player placeholder -->
									<div id="td_container"></div>

								</div>

							</div>

						</div>

					</div>

					<div class="live-player__volume">
						<div class="live-player__volume--btn"></div>
						<div class="live-player__volume--level"></div>
						<div class="live-player__volume--up"></div>
					</div>

				</div>

				<div id="live-links" class="live-links">
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

			</div>
		</div>

		<div class="live-player__col">
			<div id="live-player--sidebar" class="live-player">

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

				<div id="live-player" class="live-player--container">

					<div class="live-player__resume">
						<div id="live-player__resume-live" class="live-player__resume--btn">Listen Live</div>
					</div>

					<div class="live-player--controls">
						<div class="gm-liveplayer">

							<div class="gm-liveplayer--container">

								<div class="container">
									<div id="gm-liveplayer--controls">
										<div id="resumeButton" class="gm-liveplayer--btn" data-station="WKLBFM"></div>
									</div>

									<div class="live-player__status">

									</div>

									<div id="gm-liveplayer--now_playing">
										<div id="nowPlaying">
											<div id="trackInfo" class="now-playing">
												<h4 class="now-playing__title">Interview with Ozzy</h4>
												<h5 class="now-playing__artist"></h5>
											</div>
											<div id="npeInfo"></div>
										</div>
									</div>

									<!-- Player placeholder -->
									<div id="td_container"></div>

								</div>

							</div>

						</div>

					</div>

					<div class="live-player__volume">
						<div class="live-player__volume--btn"></div>
						<div class="live-player__volume--level"></div>
						<div class="live-player__volume--up"></div>
					</div>

				</div>

				<div id="live-links" class="live-links">
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

			</div>

		</div>

		<div class="live-player__col">
			<div id="live-player--sidebar" class="live-player">

				<nav class="live-player__stream">
					<ul class="live-player__stream--list">
						<li class="live-player__stream--current open">
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

				<div id="live-player" class="live-player--container">

					<div class="live-player__resume">
						<div id="live-player__resume-live" class="live-player__resume--btn">Listen Live</div>
					</div>

					<div class="live-player--controls">
						<div class="gm-liveplayer">

							<div class="gm-liveplayer--container">

								<div class="container">
									<div id="gm-liveplayer--controls">
										<div id="resumeButton" class="gm-liveplayer--btn" data-station="WKLBFM"></div>
									</div>

									<div class="live-player__status">
										<div id="live-player__now-playing" class="live-player__now-playing--btn" style="display: inline-block;">Now Playing</div>
									</div>

									<div id="gm-liveplayer--now_playing">
										<div id="nowPlaying">
											<div id="trackInfo" class="now-playing">
												<h4 class="now-playing__title">Track Title</h4>
												<h5 class="now-playing__artist">Artist Name</h5>
											</div>
											<div id="npeInfo"></div>
										</div>
									</div>

									<!-- Player placeholder -->
									<div id="td_container"></div>

								</div>

							</div>

						</div>

					</div>

					<div class="live-player__volume">
						<div class="live-player__volume--btn"></div>
						<div class="live-player__volume--level"></div>
						<div class="live-player__volume--up"></div>
					</div>

				</div>

				<div id="live-links" class="live-links">
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

			</div>

		</div>

	</div>
</section>