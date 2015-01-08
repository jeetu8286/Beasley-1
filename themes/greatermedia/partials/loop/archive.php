<?php while ( have_posts() ) : the_post(); ?>

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

<?php endwhile;