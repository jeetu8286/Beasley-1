<div class="container">

	<?php if (have_posts()) : while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'partials/show-mini-nav' ); ?>

		<?php if ( has_post_thumbnail() ) { ?>

			<div class="entry__thumbnail">

				<?php the_post_thumbnail( 'gmr-album-thumbnail', array( 'class' => 'single__featured-img' ) ); ?>

				<?php

					$image_attr = image_attribution();

					if ( ! empty( $image_attr ) ) {
						echo $image_attr;
					}

				?>

			</div>

		<?php } ?>

		<section class="content">
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
				<header class="entry__header">
					<time class="entry__date" datetime="<?php echo get_the_time(); ?>"><?php the_date('F j, Y'); ?></time>
					<h2 class="entry__title" itemprop="headline"><?php the_title(); ?></h2>
					<?php get_template_part( 'partials/social-share' ); ?>
				</header>
				<section class="entry-content" itemprop="articleBody">
					<?php the_content(); ?>
				</section>
				<!-- begin album galleries -->
				<?php

				// Secondary content needs to go through a filter to allow the
				// restriction plugins to do their work
				ob_start();
				?>
				<section class="inline__gallery__archive">

					<!-- @TODO Eugene: Album featured gallery or first gallery? -->
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'gallery__grid--featured' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

						<a href="<?php the_permalink(); ?>">
							<div class="gallery__grid--thumbnail">
								<?php if ( 'gmr_album' == get_post_type() ) { ?>
									<div class="gallery__grid--album"></div>
								<?php } ?>
								<div class="thumbnail" style="background-image: url(<?php gm_post_thumbnail_url( 'gmr-gallery-grid-thumb', null, true ); ?>)"></div>
							</div>

							<div class="gallery__grid--meta">
								<h3 class="gallery__grid--title">
									<?php the_title(); ?>
								</h3>
							</div>
						</a>

					</article>
					<!-- End featured/first gallery -->


					<div class="gallery__grid gallery__grid-album">

						<?php

							$gallery_content_types = array(
								GreaterMediaGalleryCPT::GALLERY_POST_TYPE,
								'post'
							);

							$child_galleries = array(
								'post_type'         => $gallery_content_types,
								'post_parent'       => $post->ID,
								'posts_per_page'    => 16,
								'orderby'           => 'post_date',
								'order'             => 'DESC',
								'paged'             => get_query_var('paged')
							);

							$gallery_query = new WP_Query( $child_galleries );

							while ($gallery_query->have_posts()) : $gallery_query->the_post();

								get_template_part( 'partials/gallery-grid' );

							endwhile;

							wp_reset_query();
						?>

					</div>

				</section>

				<?php
				$secondary_content = ob_get_clean();
				echo apply_filters( 'the_secondary_content', $secondary_content );
				?>
				<!-- end galleries -->
			</article>
		</section>

	<?php
		endwhile;
		endif;
		wp_reset_query();
	?>



</div>
