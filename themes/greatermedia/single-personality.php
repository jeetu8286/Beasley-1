<?php
/**
 * Single Post template file
 *
 * @package Greater Media
 * @since   0.1.0
 */

get_header(); ?>

	<main class="main" role="main">

		<div class="container">

			<section class="content">

				<?php

					if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

						<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

							<header class="entry-header">

								<h2 class="entry-title" itemprop="headline"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

							</header>

							<?php
							$facebook_url = gmi_get_personality_facebook_url();
							$twitter_url = gmi_get_personality_twitter_url();

							gmi_print_personality_photo( null, 100 );

							the_content();
							?>

							<?php
							if ( ! empty( $facebook_url ) ) {
								?>
								<p><a href="<?php echo esc_url( $facebook_url ); ?>">Facebook</a></p>
							<?php
							}

							if ( ! empty( $twitter_url ) ) {
								?>
								<p><a href="<?php echo esc_url( $twitter_url ); ?>">Twitter</a></p>
							<?php
							}
							?>

							<?php
							$slug = get_post( $post )->post_name;

							$args = array(
								'post_type' => 'post',
								GMI_Personality::SHADOW_TAX_SLUG => $slug,
								'posts_per_page' => 50,
							);

							$post_query = new WP_Query( $args );

							if ( $post_query->have_posts() ) :
								?>
								<h3>Posts for this personality:</h3>

								<?php
								while ( $post_query->have_posts() ) : $post_query->the_post();
									?>

									<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>

								<?php
								endwhile; // have_posts()

								wp_reset_postdata();

							endif;
							?>

							<?php
							// GMI_Gigya_Share::display_share_buttons();

							if ( is_singular( 'post' ) ) {
								comments_template();
							}
							?>

						</article>

					<?php endwhile;

					else : ?>

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

<?php get_footer();