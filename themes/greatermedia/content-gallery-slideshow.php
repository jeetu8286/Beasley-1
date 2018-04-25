<?php

if ( ! class_exists( 'GreaterMediaGallery' ) ) :
	return;
endif;

$current_gallery = get_queried_object();
$ids = \GreaterMediaGallery::get_attachment_ids_for_post( $current_gallery );
if ( ! is_array( $ids ) ) :
	$ids = array();
endif;

$sponsored_image = get_field( 'sponsored_image', $current_gallery );
if ( ! empty( $sponsored_image ) ) :
	array_unshift( $ids, $sponsored_image );
endif;

$images = array_filter( array_map( 'get_post', array_values( $ids ) ) );
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

$galleries = get_posts( array(
	'post_type'      => 'gmr_gallery',
	'post__not_in'   => array( $current_gallery->ID ),
	'posts_per_page' => 4,
) );

?><h1 class="slideshow-title"><div class="container"><?php the_title(); ?></div></h1>

<div class="gallery-thumbs loading">
	<?php foreach ( $images as $index => $image ) : ?>
		<?php if ( $index > 0 && $index % $ads_interval == 0 ) : ?>
			<div><div class="swiper-slide meta-spacer"></div></div>
		<?php endif; ?>

		<div>
			<div class="swiper-slide" style="background-image:url(<?php echo esc_url( wp_get_attachment_image_url( $image->ID, 'thumbnail' ) ); ?>)"></div>
		</div>
	<?php endforeach; ?>

	<?php // Last slide thumbnail placeholder ?>
	<div><div class="swiper-slide meta-spacer"></div></div>
</div>
<!-- .gallery-thumbs -->

<div class="swiper-container gallery-top loading" data-refresh-interval="<?php echo esc_attr( $ads_interval ); ?>" data-share-photos="<?php echo get_field( 'share_photos', $current_gallery ) ? 1 : 0; ?>">
    <div class="swiper-wrapper"><?php
		foreach ( $images as $index => $image ) :
			if ( $index > 0 && $index % $ads_interval == 0 ) :
				?><div data-index="<?php echo esc_attr( $slide_index ); ?>" class="swiper-slide meta-spacer">
					<div class="swiper-slide meta-spacer"></div>
				</div><?php
				$slide_index++;
			endif;

			$data = wp_get_attachment_image_src( $image->ID, 'gm-article-thumbnail' );
			if ( empty( $data ) ) :
				continue;
			endif;

			$src = $data[0];
			$width = $data[1];
			$height = $data[2];

			?><div class="swiper-slide"
				 data-index="<?php echo esc_attr( $slide_index ); ?>"
				 data-slug="<?php echo esc_attr( $base_url ); ?>/view/<?php echo esc_attr( $image->post_name ); ?>/"
				 data-title="<?php echo esc_attr( get_the_title( $image ) ); ?>"
				 data-caption="<?php echo esc_attr( get_the_excerpt( $image ) ); ?>"
				 data-source="<?php echo esc_url( wp_get_attachment_image_url( $image->ID, 'full' ) ); ?>"
				 data-width="<?php echo esc_attr( $width ); ?>"
				 data-height="<?php echo esc_attr( $height ); ?>"
				 >
				<img src="<?php echo esc_url( $src ); ?>" width="<?php echo esc_attr( $width ); ?>" height="<?php echo esc_attr( $height ); ?>" alt="<?php echo esc_attr( get_the_excerpt( $image ) ); ?>" class="swiper-image">
			</div><?php

			$slide_index++;
		endforeach;

		if ( is_singular( 'gmr_gallery' ) ) :
			?><div data-index="<?php echo esc_attr( $slide_index ); ?>" class="swiper-slide last-slide">
				<div class="other-galleries">
					<h2>More from <?php bloginfo( 'name' ); ?></h2>
					<div class="gallery__grid gallery__grid-album">
						<?php foreach ( $galleries as $gallery ) : ?>
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
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>
    </div>
    <!-- .swiper-wrapper -->

	<div class="swiper-sidebar">
		<div class="swiper-sidebar-text">
			<h2 id="js-swiper-sidebar-title">
				<?php echo esc_html( get_the_title( $images[0] ) ); ?>
			</h2>

			<p id="js-swiper-sidebar-caption">
				<?php echo esc_attr( get_the_excerpt( $images[0] ) ); ?>
			</p>

			<button id="js-expand" class="swiper-sidebar-expand">
				<span class="expand-label">Read more</span>
				<span class="close-label">Read less</span>
				<span class="icon-arrow-next"></span>
			</button>

			<?php if ( ! get_field( 'hide_download_link', $current_gallery ) ) : ?>
				<p>
					<a href="<?php echo esc_url( wp_get_attachment_image_url( $images[0]->ID, 'full' ) ); ?>" id="js-swiper-sidebar-download" download target="_blank">
						<span class="icon-download-arrow"></span> Download image
					</a>
				</p>
			<?php endif; ?>
		</div>

		<?php if ( ! get_field( 'hide_social_share', $current_gallery ) ) : ?>
			<div class="swiper-sidebar-sharing">
				<?php get_template_part( 'partials/social-share' ); ?>
			</div>
		<?php endif; ?>

		<div class="swiper-sidebar-meta">
			<?php do_action( 'dfp_tag', 'dfp_ad_sidegallery' ); ?>
		</div>

		<button id="js-fullscreen" class="swiper-sidebar-fullscreen">
			<svg class="enter-fullscreen" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
				<path d="M4.5 11H3v4h4v-1.5H4.5V11zM3 7h1.5V4.5H7V3H3v4zm10.5 6.5H11V15h4v-4h-1.5v2.5zM11 3v1.5h2.5V7H15V3h-4z"/>
			</svg>

			<svg class="exit-fullscreen" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
				<path d="M3 12.5h2.5V15H7v-4H3v1.5zm2.5-7H3V7h4V3H5.5v2.5zM11 15h1.5v-2.5H15V11h-4v4zm1.5-9.5V3H11v4h4V5.5h-2.5z"/>
			</svg>

			<span class="screen-reader-text">Fullscreen</span>
		</button>
	</div>
	<!-- .swiper-sidebar -->

	<div class="swiper-meta-container">
		<div class="swiper-meta-inner">
			<?php do_action( 'dfp_tag', 'dfp_ad_ingallery' ); ?>
		</div>
	</div>
	<!-- .swiper-meta-container -->
</div>
<!-- .gallery-top -->
