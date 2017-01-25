<?php
/**
 * Partial for the Front Page Featured Content for the Music Theme
 *
 * @package Greater Media
 * @since   0.1.0
 */
$hp_featured_query = \GreaterMedia\HomepageCuration\get_featured_query();

// if we still have more posts (we almost always will), render the 3 below the main section
if ( $hp_featured_query->have_posts() ) : ?>
	<div class="cycle-slideshow"
		data-cycle-timeout="5000"
		data-cycle-prev=".slick-prev"
		data-cycle-next=".slick-next"
		data-cycle-slides="> div.feature-post-slide"
		data-cycle-auto-height=container
		data-cycle-pager=".slick-dots" >

		<?php while ( $hp_featured_query->have_posts() ) : $hp_featured_query->the_post(); ?>
			<div class="feature-post-slide">
				<a href="<?php the_permalink(); ?>">
					<div class="slide-content">
						<div class="featured__article--image" style="width:610px;background-size: cover;background-image: url(<?php gm_post_thumbnail_url( 'gmr-featured-primary', null, true ); ?>);">
							<?php image_attribution(); ?>
						</div>
						<div class="featured__article--content">
							<div class="featured__article--heading">
								<?php the_title(); ?>
							</div>
						</div>
					</div>
				</a>
			</div>
		<?php endwhile; ?>
	</div>
	<div class="slick-dots"></div>
<?php
endif;
wp_reset_query();

// Start Countdown Clock
if ( function_exists( 'GreaterMedia\HomepageCountdownClock\render_homepage_countdown_clock' ) ) {
	GreaterMedia\HomepageCountdownClock\render_homepage_countdown_clock();
}
// End Countdown Clock
