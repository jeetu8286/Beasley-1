<?php
/**
 * Archive template file for Galleries
 *
 * @package Greater Media
 * @since   0.1.0
 *
 * @todo this template file still needs to be layed out according to the design
 */

get_header(); ?>

	<main class="main" role="main">

		<div class="container">

			<section class="gallery__archive">

				<h2 class="page__title" itemprop="headline"><?php _e( 'Galleries', 'greatermedia' ); ?></h2>

				<?php if ( ! get_query_var( 'paged' ) || get_query_var( 'paged' ) < 2 ) {

					include locate_template( 'partials/gallery-featured.php' );

				} ?>

				<div class="gallery__grid">

					<?php

					if ( have_posts() ) : while ( have_posts() ) : the_post();

						get_template_part( 'partials/gallery-grid' );

					endwhile;

						greatermedia_gallery_album_nav();

						wp_reset_postdata();

					else : ?>

						<article id="post-not-found" class="hentry cf">
							<header class="article-header">
								<h1><?php _e( 'Oops, Post Not Found!', 'antenna_theme' ); ?></h1>
							</header>
							<section class="entry-content">
								<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'antenna_theme' ); ?></p>
							</section>
						</article>

					<?php endif; ?>

				</div>

			</section>

		</div>

	</main>

<?php get_footer(); ?>