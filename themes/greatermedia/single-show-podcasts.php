<?php get_header(); ?>

	<main class="main" role="main">

		<div class="container">

			<?php the_post(); ?>

			<?php get_template_part( 'show-header' ); ?>

			<section class="content">

				<div class="podcasts">

					<h2>Podcasts</h2>
					<?php
					global $post;
					$series_slug = $post->post_name;
					$feed_url = home_url( '/' ) . '?feed=podcast&podcast_series=' . $series_slug;
					echo '<a href="' . $feed_url . '" target="_blank">Subscribe</a>';
					?>
					<?php
					$podcast_query = \GreaterMedia\Shows\get_show_podcast_query();

					while( $podcast_query->have_posts() ) : $podcast_query->the_post(); ?>
						<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf podcast' ); ?> role="article" itemscope itemtype="http://schema.org/OnDemandEvent">

							<?php $podcast_obj = GMP_Player::render_podcasts(); ?>

						</article>
						
					<?php
					endwhile;
					echo GMP_Player::custom_pagination( $podcast_obj->max_num_pages );
					wp_reset_query();
					?>

					<div class="podcast-paging"><?php echo \GreaterMedia\Shows\get_show_endpoint_pagination_links( $podcast_query ); ?></div>

				</div>

			</section>

		</div>

	</main>

<?php get_footer();