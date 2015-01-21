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

				<h2 class="content__heading">
					<?php $object = get_queried_object(); ?>
					<?php echo ! empty( $object->labels->name ) ? esc_html( strtolower( $object->labels->name ) ) : ''; ?>
				</h2>

				<?php

					$advertiser_args = array(
						'post_type' => GMR_ADVERTISER_CPT,
						'orderby'   => 'menu_order date',
						'order'     => 'ASC'
					);

					$advertiser_query = new WP_Query( $advertiser_args );

					if ( $advertiser_query->have_posts() ) : while ( $advertiser_query->have_posts() ) : $advertiser_query->the_post();

				?>

					<?php get_template_part( 'partials/loop', 'advertiser' ); ?>

				<?php endwhile; ?>

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