<?php
/**
 * Single Post template file
 *
 * @package Greater Media
 * @since   0.1.0
 */

get_header(); ?>

	<main class="main" role="main">

		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

			<?php do_action( 'gmr_gallery' ); ?>

		<div class="container">

			<section class="content">

				<h2 class="content__heading">Latest from WMMR</h2>

				<?php endwhile; ?>

					<div class="posts-pagination">

						<div class="posts-pagination--previous"><?php next_posts_link( '<i class="fa fa-angle-double-left"></i>Previous' ); ?></div>
						<div class="posts-pagination--next"><?php previous_posts_link( 'Next<i class="fa fa-angle-double-right"></i>' ); ?></div>

					</div>

				<?php else : ?>

					<article id="post-not-found" class="hentry cf">

						<header class="entry__header">

							<h2 class="entry__title"><?php _e( 'Oops, Post Not Found!', 'greatermedia' ); ?></h2>

						</header>

						<section class="entry__content">

							<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'greatermedia' ); ?></p>

						</section>

					</article>

			</section>

		</div>

		<?php endif; ?>

	</main>

<?php get_footer();