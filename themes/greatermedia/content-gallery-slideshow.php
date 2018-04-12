<?php

if ( ! class_exists( 'GreaterMediaGallery' ) ) :
	return;
endif;

$current_gallery = get_queried_object();
$ids = \GreaterMediaGallery::get_attachment_ids_for_post( $current_gallery );
$images = array_filter( array_map( 'get_post', $ids ) );
if ( empty( $images ) ) :
	return;
endif;

$ads_interval = filter_var( get_field( 'images_per_ad', $current_gallery ), FILTER_VALIDATE_INT, array( 'options' => array(
	'min_range' => 1,
	'max_range' => 99,
	'default'   => 3,
) ) );

$slide_index = 0;
$base_url = untrailingslashit( get_permalink( $current_gallery ) );

$sponsored_gallery = current( get_posts( array(
	'post_type'      => 'gmr_gallery',
	'post__not_in'   => array( $current_gallery->ID ),
	'posts_per_page' => 1,
	'meta_key'       => 'sponsored_gallery',
	'meta_value'     => 1,
	'orderby'        => array( 'meta_value', 'date' ),
) ) );

$non_sponsored_galleries = get_posts( array(
	'post_type'      => 'gmr_gallery',
	'post__not_in'   => array_filter( array( $current_gallery->ID, $sponsored_gallery ? $sponsored_gallery->ID : 0 ) ),
	'posts_per_page' => $sponsored_gallery ? 4 : 5,
) );

$galleries = array_merge( array( $sponsored_gallery ), $non_sponsored_galleries );

?><h1 class="slideshow-title"><div class="container"><?php the_title(); ?></div></h1>
<div class="swiper-container gallery-top loading" data-refresh-interval="<?php echo esc_attr( $ads_interval ); ?>">
    <div class="swiper-wrapper">
		<?php foreach ( $images as $index => $image ) : ?>

			<?php if ( $index > 0 && $index % $ads_interval == 0 ) : ?>
				<div data-index="<?php echo esc_html( $slide_index ); ?>" class="swiper-slide meta-spacer"><div class="swiper-slide meta-spacer"></div></div>
				<?php $slide_index++; ?>
			<?php endif; ?>

			<div data-index="<?php echo esc_html( $slide_index ); ?>"
				 class="swiper-slide"
				 data-slug="<?php echo esc_attr( $base_url ); ?>/view/<?php echo esc_attr( $image->post_name ); ?>/"
				 data-title="<?php echo esc_attr( get_the_title( $image ) ); ?>"
				 data-caption="<?php echo esc_attr( get_the_excerpt( $image ) ); ?>"
				 >
				<?php echo wp_get_attachment_image( $image->ID, 'gm-article-thumbnail', false, array( 'class' => 'swiper-image' ) ); ?>
			</div>
			<?php $slide_index++; ?>
		<?php endforeach; ?>

		<div data-index="<?php echo esc_html( $slide_index ); ?>" class="swiper-slide last-slide">
			<div class="other-galleries">
				<?php $gallery = current( $galleries ); ?>
				<!-- Featured gallery -->
				<article class="gallery__grid--featured">
					<a href="<?php the_permalink( $gallery ); ?>">
						<div class="gallery__grid--meta">
							<h3 class="gallery__grid--title">
								<?php echo get_field( 'sponsored_gallery', $gallery ) ? 'Sponsored Gallery' : esc_html( get_the_title( $gallery ) ); ?>
							</h3>
						</div>
						<div class="gallery__grid--thumbnail">
							<div class="thumbnail" style="background-image: url('<?php echo esc_attr( wp_get_attachment_image_url( get_post_thumbnail_id( $gallery ), 'gmr-gallery-grid-featured' ) ); ?>')"></div>
						</div>
					</a>
				</article>

				<!-- Other galleries -->
				<h2>More from <?php bloginfo( 'name' ); ?></h2>
				<div class="gallery__grid gallery__grid-album">
					<?php while ( ( $gallery = next( $galleries ) ) ) : ?>
						<article class="gallery__grid--column">
							<a href="<?php the_permalink( $gallery ); ?>">
								<div class="gallery__grid--thumbnail">
									<div class="thumbnail" style="background-image: url('<?php echo esc_attr( wp_get_attachment_image_url( get_post_thumbnail_id( $gallery ), 'gmr-gallery-grid-secondary' ) ); ?>')"></div>
								</div>
								<div class="gallery__grid--meta">
									<h3 class="gallery__grid--title">
										<?php echo esc_html( get_the_title( $gallery ) ); ?>
									</h3>
								</div>
							</a>
						</article>
					<?php endwhile; ?>
				</div>
			</div>
		</div>
    </div>
    <!-- .swiper-wrapper -->

	<div class="swiper-sidebar">
		<div class="swiper-sidebar-text">
			<h2 id="js-swiper-sidebar-title"><?php echo esc_html( get_the_title( $images[0] ) ); ?></h2>
			<p id="js-swiper-sidebar-caption"><?php echo esc_attr( get_the_excerpt( $images[0] ) ); ?></p>
		</div>

		<?php if ( ! get_field( 'hide_social_share' ) ) : ?>
			<div class="swiper-sidebar-sharing">
				<?php get_template_part( 'partials/social-share' ); ?>
			</div>
		<?php endif; ?>

		<div class="swiper-sidebar-meta">
			<?php do_action( 'dfp_tag', 'dfp_ad_inlist_infinite' ); ?>
		</div>
		<button id="js-expand" class="swiper-sidebar-expand"><span class="icon-arrow-next"></span> <span class="screen-reader-text">Expand</span></button>
	</div>
	<!-- .swiper-sidebar -->

	<div class="swiper-meta-container">
		<div class="swiper-meta-inner">
			<?php do_action( 'dfp_tag', 'dfp_ad_inlist_infinite' ); ?>
		</div>
	</div>
	<!-- .swiper-meta-container -->
</div>
<!-- .gallery-top -->

<div class="gallery-thumbs loading">
	<?php foreach ( $images as $index => $image ) : ?>

		<?php if ( $index > 0 && $index % 2 == 0 ) : ?>
			<div><div class="swiper-slide meta-spacer"></div></div>
		<?php endif; ?>

		<div><div class="swiper-slide" style="background-image:url(<?php echo esc_url( wp_get_attachment_image_url( $image->ID, 'gm-article-thumbnail' ) ); ?>)"></div></div>

		<?php if ( $total_images === ( $index + 1 ) ) : ?>
			<?php // Last slide thumbnail placeholder ?>
			<div><div class="swiper-slide meta-spacer"></div></div>
		<?php endif; ?>

	<?php endforeach; ?>
</div>
<!-- .gallery-thumbs -->
