<?php get_header(); ?>

	<main class="main" role="main">

		<div class="container">

			<?php the_post(); ?>

			<?php get_template_part( 'show-header' ); ?>

			<section class="content">

				<section class="show__live-links cf">

					<h2 class="section-header">Live Links Archive</h2>

					<?php $live_links_query = \GreaterMedia\Shows\get_show_live_links_archive_query(); ?>

					<ul>
						<?php while ( $live_links_query->have_posts() ) : $live_links_query->the_post(); ?>
							<li class="live-link__type--<?php echo ( $format = get_post_format() ) ? $format : 'standard'; ?>">
								<div class="live-link__title">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</div>
							</li>
						<?php endwhile; ?>
						<?php wp_reset_query(); ?>
					</ul>

				</section>

				<div class="live-link-paging"><?php echo \GreaterMedia\Shows\get_show_endpoint_pagination_links( $live_links_query ); ?></div>

			</section>

		</div>

	</main>

<?php get_footer();