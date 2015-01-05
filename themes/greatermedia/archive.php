<?php
/**
 * Archive template file
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

					<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

						<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

							<?php

								if ( has_post_format( 'video' ) ) {

									get_template_part( 'partials/post', 'video' );

								} elseif ( has_post_format( 'audio') ) {

									get_template_part( 'partials/post', 'audio' );

								} elseif ( has_post_format( 'link') ) {

									get_template_part( 'partials/post', 'link' );

								} elseif ( has_post_format( 'gallery') ) {

									get_template_part( 'partials/post', 'gallery' );

								} else {

									get_template_part( 'partials/post', 'standard' );

								}

							?>

							<footer class="entry__footer">

								<?php if( ( $category = get_the_category() ) && isset( $category[0] ) ) : ?>
									<a href="<?php echo esc_url( get_category_link($category[0]->term_id ) ); ?>" class="entry__footer--category">
										<?php echo esc_html( $category[0]->cat_name ); ?>
									</a>
								<?php endif; ?>

							</footer>

						</article>

					<?php endwhile; ?>

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

		</div>

	</main>

<?php get_footer(); ?>