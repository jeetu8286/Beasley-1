<?php
/**
 * Show post type archive template
 *
 */

get_header(); ?>

	<main class="main" role="main">

		<div class="container">

			<section class="content">

				<?php if ( have_posts() ) : ?>

					<div class="gallery-grid">

						<?php get_template_part( 'partials/loop/archive', 'photos' ); ?>

					</div>

					<?php get_template_part( 'partials/pagination' ); ?>

				<?php else : ?>

					<article id="post-not-found" class="hentry cf">

						<header class="article-header">

							<h1><?php _e( 'Oops, Nothing Found!', 'greatermedia' ); ?></h1>

						</header>

						<section class="entry-content">

							<p>We can't find the photos you are looking for. Try double checking things.</p>

						</section>

					</article>

				<?php endif; ?>

			</section>

		</div>

	</main>

<?php get_footer(); ?>