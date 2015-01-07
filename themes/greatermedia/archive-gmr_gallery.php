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

			<section class="content">

				<h2 class="content__heading">Latest from WMMR</h2>

				<?php if ( have_posts() ) : ?>

					<?php get_template_part( 'partials/loop/archive', 'gmr_gallery' ); ?>
					<?php get_template_part( 'partials/pagination' ); ?>

				<?php else : ?>

					<article id="post-not-found" class="hentry cf">

						<header class="article-header">

							<h1><?php _e( 'Oops, Post Not Found!', 'greatermedia' ); ?></h1>

						</header>

						<section class="entry-content">

							<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'greatermedia' ); ?></p>

						</section>

					</article>

				<?php endif; ?>

			</section>

			<section class="gallery-grid">

				<div class="gallery-grid__column">

					<div class="gallery-grid__thumbnail">
						<a href="#">
							<img src="http://placehold.it/254x186">
						</a>
					</div>

					<div class="gallery-grid__meta">
						<h3 class="gallery-grid__title">
							<a href="#"><?php _e( 'Title Goes Here', 'greatermedia' ); ?></a>
						</h3>
					</div>

				</div>

				<div class="gallery-grid__column">

					<div class="gallery-grid__thumbnail">
						<a href="#">
							<img src="http://placehold.it/254x186">
						</a>
					</div>

					<div class="gallery-grid__meta">
						<h3 class="gallery-grid__title">
							<a href="#"><?php _e( 'Title Goes Here', 'greatermedia' ); ?></a>
						</h3>
					</div>

				</div>

				<div class="gallery-grid__column">

					<div class="gallery-grid__thumbnail">
						<a href="#">
							<img src="http://placehold.it/254x186">
						</a>
					</div>

					<div class="gallery-grid__meta">
						<h3 class="gallery-grid__title">
							<a href="#"><?php _e( 'Title Goes Here', 'greatermedia' ); ?></a>
						</h3>
					</div>

				</div>

				<div class="gallery-grid__column">

					<div class="gallery-grid__thumbnail">
						<a href="#">
							<img src="http://placehold.it/254x186">
						</a>
					</div>

					<div class="gallery-grid__meta">
						<h3 class="gallery-grid__title">
							<a href="#"><?php _e( 'Title Goes Here', 'greatermedia' ); ?></a>
						</h3>
					</div>

				</div>

				<div class="gallery-grid__column">

					<div class="gallery-grid__thumbnail">
						<a href="#">
							<img src="http://placehold.it/254x186">
						</a>
					</div>

					<div class="gallery-grid__meta">
						<h3 class="gallery-grid__title">
							<a href="#"><?php _e( 'Title Goes Here', 'greatermedia' ); ?></a>
						</h3>
					</div>

				</div>

				<div class="gallery-grid__column">

					<div class="gallery-grid__thumbnail">
						<a href="#">
							<img src="http://placehold.it/254x186">
						</a>
					</div>

					<div class="gallery-grid__meta">
						<h3 class="gallery-grid__title">
							<a href="#"><?php _e( 'Title Goes Here', 'greatermedia' ); ?></a>
						</h3>
					</div>

				</div>

				<div class="gallery-grid__column">

					<div class="gallery-grid__thumbnail">
						<a href="#">
							<img src="http://placehold.it/254x186">
						</a>
					</div>

					<div class="gallery-grid__meta">
						<h3 class="gallery-grid__title">
							<a href="#"><?php _e( 'Title Goes Here', 'greatermedia' ); ?></a>
						</h3>
					</div>

				</div>

				<div class="gallery-grid__column">

					<div class="gallery-grid__thumbnail">
						<a href="#">
							<img src="http://placehold.it/254x186">
						</a>
					</div>

					<div class="gallery-grid__meta">
						<h3 class="gallery-grid__title">
							<a href="#"><?php _e( 'Title Goes Here', 'greatermedia' ); ?></a>
						</h3>
					</div>

				</div>

			</section>

		</div>

	</main>

<?php get_footer(); ?>