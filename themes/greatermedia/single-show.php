<?php

 get_header();

?>

	<main class="main" role="main">

		<div class="container">

			<?php the_post(); ?>

				<?php get_template_part( 'show-header' ); ?>

				<section class="content">

					<section class="show__features">
						<div class="show__feature--primary">
							<a href=""><div class="show__feature">
								<img src="http://placehold.it/570x315&text=show-feature" alt="">
								<div class="show__feature--desc">
									<h3>The Title of the Primary Featured Post on the Show Homepage</h3>
									<time class="show__feature--date" datetime="">23 SEP</time>
								</div>
							</div></a>
						</div>
						<div class="show__feature--secondary">
							<a href=""><div class="show__feature">
								<img src="http://placehold.it/570x315&text=show-feature" alt="">
								<div class="show__feature--desc">
									<h3>The Title of a Secondary Featured Post on the Show Homepage</h3>
									<time class="show__feature--date" datetime="">23 SEP</time>
								</div>
							</div></a>
							<a href=""><div class="show__feature">
								<img src="http://placehold.it/570x315&text=show-feature" alt="">
								<div class="show__feature--desc">
									<h3>The Title of a Secondary Featured Post on the Show Homepage</h3>
									<time class="show__feature--date" datetime="">23 SEP</time>
								</div>
							</div></a>
						</div>
					</section>

					<div class="featured__content">
						<?php
						global $post;
						$events = \GreaterMedia\Shows\get_show_events();
						foreach( $events as $post ): setup_postdata( $post ); ?>
							<div class="featured__content--block">
								<?php if ( has_post_thumbnail() ) : ?>
									<div class="featured__content--image">
										<?php the_post_thumbnail( array( 400, 400 ) ); // todo custom size for this? ?>
									</div>
								<?php endif; ?>
								<div class="featured__content--meta">
									<h3 class="featured__content--title"><?php the_title(); ?></h3>
								</div>
							</div>
						<?php endforeach; ?>
						<?php wp_reset_query(); ?>
			        </div>

			        <div class="row">

				        <aside class="inner-right-col">
					        <section class="show__favorites">
					        	<h2>Our Favorites</h2>
								<div class="featured__content--block">
					                <div class="featured__content--image">
					                    <img src="http://placehold.it/400x400&text=featured+image">
					                </div>
					                <div class="featured__content--meta">
					                    <h3 class="featured__content--title">MMR Rocks the Flyers</h3>
					                </div>
					            </div>
					            <div class="featured__content--block">
					                <div class="featured__content--image">
					                    <img src="http://placehold.it/400x400&text=featured+image">
					                </div>
					                <div class="featured__content--meta">
					                    <h3 class="featured__content--title">Hitch a Ride with Pierre ...and Minerva</h3>
					                </div>
					            </div>
					            <div class="featured__content--block">
					                <div class="featured__content--image">
					                    <img src="http://placehold.it/400x400&text=featured+image">
					                </div>
					                <div class="featured__content--meta">
					                    <h3 class="featured__content--title">Preston and Steve</h3>
					                </div>
					            </div>
					        </section>

						<section class="show__latest-crap">
				        	<h2>Latest Crap</h2>
							<ul>
								<li class="live-link__type--standard">
									<div class="live-link__title">
										<a href=
										"http://wmmr.greatermedia.10uplabs.com/scroll-test/">GMR-278
										test</a>
									</div>
								</li>


								<li class="live-link__type--standard">
									<div class="live-link__title">
										<a href=
										"http://wmmr.greatermedia.10uplabs.com/contest/daves-contest/">Dave’s
										Contest</a>
									</div>
								</li>

								<li class="live-link__type--link">
									<div class="live-link__title">
										<a href=
										"http://wmmr.greatermedia.10uplabs.com/timed-content-test/">test
										test</a>
									</div>
								</li>


								<li class="live-link__type--standard">
									<div class="live-link__title">
										<a href=
										"http://wmmr.greatermedia.10uplabs.com/ontario-highway-401-wikipedia-the-free-encyclopedia/">
										Ontario Highway 401 – Wikipedia, the free encyclopedia</a>
									</div>
								</li>


								<li class="live-link__type--video">
									<div class="live-link__title">
										<a href=
										"http://wmmr.greatermedia.10uplabs.com/?post_type=gmr-live-link&amp;p=633">
										Gladys Knight &amp; The Pips “Midnight Train To Georgia”</a>
									</div>
								</li>


								<li class="live-link__type--video">
									<div class="live-link__title">
										<a href=
										"http://wmmr.greatermedia.10uplabs.com/gladys-knight-the-pips-midnight-train-to-georgia/">
										Gladys Knight &amp; The Pips</a>
									</div>
								</li>


								<li class="live-link__type--video">
									<div class="live-link__title">
										<a href=
										"http://wmmr.greatermedia.10uplabs.com/?post_type=gmr-live-link&amp;p=538">
										Hilarious Volleyball Triple Head Shot – YouTube</a>
									</div>
								</li>
							</ul>
				        </section>

				        </aside>

				        <section class="show__blogroll inner-left-col">
				        	<h2>Blog</h2>

					        <?php
					        $main_query = \GreaterMedia\Shows\get_show_main_query();
					        while( $main_query->have_posts() ): $main_query->the_post(); ?>
						        <article <?php post_class( 'cf' ); ?>>
							        <section class="entry__meta">
								        <time class="entry__date" datetime="<?php the_time( 'c' ); ?>"><?php the_time( 'd F' ); ?></time>

								        <h2 class="entry__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							        </section>

							        <?php if ( has_post_thumbnail() ) : ?>
							        <section class="entry__thumbnail entry__thumbnail--standard">
								        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( array( 300, 580 ) ); // todo probably define an image size for this? ?></a>
							        </section>
							        <?php endif; ?>

							        <footer class="entry__footer">
								        <?php get_template_part( 'partials/category-list' ); ?>
							        </footer>
						        </article>
					        <?php endwhile; ?>
					        <?php wp_reset_query(); ?>

					        <div class="show-main-paging"><?php echo \GreaterMedia\Shows\get_show_endpoint_pagination_links( $main_query ); ?></div>
				        </section>

			        </div>

			</section>

		</div>

	</main>

<?php get_footer();