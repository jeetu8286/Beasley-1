<div class="container">
	<section class="content">
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

			<header class="entry__header">
				<time class="entry__date" datetime="<?php echo get_the_time(); ?>"><?php the_date( 'F j, Y' ); ?></time>
				<h2 class="entry__title" itemprop="headline">
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</h2>
				<?php get_template_part( 'partials/social-share' ); ?>
			</header>

			<section class="entry-content" itemprop="articleBody"><?php
				the_content();
				get_template_part( 'partials/ad-in-loop' );
				if ( class_exists( 'GreaterMediaGallery' ) ) :
					$gallery = get_queried_object();
					$ids = \GreaterMediaGallery::get_attachment_ids_for_post( $gallery );
					if ( ! empty( $ids ) ) :
						$image = current( $ids );
						$content = sprintf(
							'<a href="%s/view/%s/">%s</a>',
							esc_attr( untrailingslashit( get_permalink( $gallery ) ) ),
							esc_attr( get_post_field( 'post_name', $image ) ),
							wp_get_attachment_image( $image )
						);

						echo apply_filters( 'the_secondary_content', $content );
					endif;
				endif;
			?></section><?php

			get_template_part( 'partials/article', 'footer' );

			$current_gallery = get_post();
			$parent_album = $post->post_parent;

			$parent_post = get_post( $parent_album );
			if ( $parent_album > 0 ) :
				$args = array(
					'post_type'    => 'gmr_gallery',
					'post_parent'  => $parent_album,
					'post__not_in' => array( $post->ID )
				);

				$siblings = new WP_Query( $args );
				ob_start();

				if ( $siblings->have_posts() ) :
					?><section class="entry__related-posts">
						<h2 class="section-header">
							More Galleries in
							<a href="<?php echo esc_url( post_permalink( $parent_post ) ); ?>"><?php echo esc_html( get_the_title( $parent_post ) ); ?></a>
						</h2>

						<?php while ( $siblings->have_posts() ) : $siblings->the_post(); ?>
							<?php get_template_part( 'partials/gallery-grid' ); ?>
						<?php endwhile; ?>
					</section><?php

					wp_reset_postdata();
				endif;

				$secondary_content = ob_get_clean();
				echo apply_filters( 'the_secondary_content', $secondary_content, $current_gallery );
			endif;
		?></article>
	</section>

	<?php get_sidebar(); ?>
</div>
