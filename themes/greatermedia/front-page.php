<?php
/**
 * The front page template file
 *
 * @package Greater Media
 * @since   0.1.0
 */

get_header(); ?>

	<div class="container">
		<?php do_action( 'do_frontpage_highlights' ); ?>

		<section class="content">

			<?php if ( is_news_site() ) : ?>
				<h2 class="content__heading">News</h2>
			<?php else : ?>
				<h2 class="content__heading">Latest from <?php bloginfo( 'name' ); ?></h2>
			<?php endif; ?>

			<?php get_template_part( 'partials/ad-in-loop' ); ?>

			<?php if ( have_posts() ) : ?>

				<?php get_template_part( 'partials/loop', 'front-page' ); ?>
				<?php greatermedia_load_more_button( array( 'partial_slug' => 'partials/loop', 'auto_load' => true ) ); ?>

			<?php else : ?>

				<article id="post-not-found" class="hentry cf">
					<header class="article-header">
						<h1>Oops, Post Not Found!</h1>
					</header>

					<section class="entry-content">
						<p>Uh Oh. Something is missing. Try double checking things.</p>
					</section>
				</article>

			<?php endif; ?>

		</section>

		<?php get_sidebar(); ?>

	</div>

<?php get_footer(); ?>
