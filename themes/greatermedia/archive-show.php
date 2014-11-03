<?php
/**
 * Show post type archive template
 *
 */

get_header(); ?>

	<main class="main" role="main">

		<div class="container">

			<section class="content">

				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

						<header class="entry-header">

							<h2 class="entry-title" itemprop="headline"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

						</header>

						<div id="logo">
							
							<?php
								global $post;
								$logo_id = get_post_meta($post->ID, 'logo_image', true);
								if( $logo_id ) {
									$logo = get_post( $logo_id );
									echo '<img src="' . $logo->guid . '" />';
								} else {
									echo '<div>No Logo Image</div>';
								}
							?>

						</div>
						<hr>
						<div class="entry-content">
							<div>
							<p>Show Content:</p>
							<?php the_content(); ?>
							</div>
							<hr>
							<?php
							echo '<div>';
							if( get_post_meta($post->ID, 'show_homepage', true) ) {
								if( function_exists( 'TDS\get_related_term' ) ) {
									$term = TDS\get_related_term( $post->ID );
								}
								if( $term ) {
									echo 'Related term is: ' . $term->name
									. '<br/>Term ID: ' . $term->term_id
									. '<br/>Term Slug: ' . $term->slug;
									
								} else {
									echo 'No related term found.
									This is a bug, beacuse SHOW has marked to have homepage';
								}
							} else {
								echo 'Show doesn\'t have home page';
							}
							echo '</div>';
							?>
						</div>

					</article>

				<?php endwhile; ?>

					<div class="posts-pagination">

						<div class="posts-pagination--previous"><?php next_posts_link( '<i class="fa fa-angle-double-left"></i>Previous' ); ?></div>
						<div class="posts-pagination--next"><?php previous_posts_link( 'Next<i class="fa fa-angle-double-right"></i>' ); ?></div>

					</div>

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