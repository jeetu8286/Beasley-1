<?php

if ( ! class_exists( 'GreaterMediaGallery' ) ) :
	return;
endif;

$gallery = get_queried_object();
$ids = \GreaterMediaGallery::get_attachment_ids_for_post( $gallery );
$images = array_filter( array_map( 'get_post', $ids ) );
if ( empty( $images ) ) :
	return;
endif;

$base_url = untrailingslashit( get_permalink( $gallery ) );
$slide_index = 0;
?><div class="swiper-container gallery-top loading" data-refresh-interval="3">
    <div class="swiper-wrapper">
		<?php foreach ( $images as $index => $image ) : ?>

			<?php if ( $index > 0 && $index % 2 == 0 ) : ?>
				<div data-index="<?php echo esc_html( $slide_index ); ?>" class="swiper-slide meta-spacer"><div class="swiper-slide meta-spacer"></div></div>
				<?php $slide_index++; ?>
			<?php endif; ?>

			<div data-index="<?php echo esc_html( $slide_index ); ?>"
				 class="swiper-slide"
				 data-slug="<?php echo esc_attr( $base_url ); ?>/view/<?php echo esc_attr( $image->post_name ); ?>/"
				 data-title="<?php echo esc_attr( get_the_title( $image ) ); ?>"
				 data-caption="<?php echo esc_attr( get_the_excerpt( $image ) ); ?>"
				 >
				<?php echo wp_get_attachment_image( $image->ID, 'full', false, array( 'class' => 'swiper-image' ) ); ?>
			</div>
			<?php $slide_index++; ?>

		<?php endforeach; ?>
    </div>
    <!-- .swiper-wrapper -->

	<div class="swiper-sidebar">
		<div class="swiper-sidebar-text">
			<h2 id="js-swiper-sidebar-title"><?php echo esc_html( get_the_title( $images[0] ) ); ?></h2>
			<p id="js-swiper-sidebar-caption"><?php echo esc_attr( get_the_excerpt( $images[0] ) ); ?></p>
		</div>
		<div class="swiper-sidebar-sharing">
			<?php get_template_part( 'partials/social-share' ); ?>
		</div>
		<div class="swiper-sidebar-meta">
			<?php do_action( 'dfp_tag', 'dfp_ad_inlist_infinite' ); ?>
		</div>
		<button id="js-expand" class="swiper-sidebar-expand"><span class="icon-arrow-next"></span> <span class="screen-reader-text">Expand</span></button>
	</div>
	<!-- .swiper-sidebar -->

	<div class="swiper-meta-container">
		<div class="swiper-meta-inner">
			<!-- @TODO Centered ad code here, I put lorem ipsum for now -->
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet provident quam autem, vitae ut delectus et est magnam, odio, a ex quae. Dolorem labore facere distinctio facilis. Corrupti, nulla, dolorem.</p>
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

		<div><div class="swiper-slide" style="background-image:url(<?php echo esc_url( wp_get_attachment_image_url( $image->ID, 'full' ) ); ?>)"></div></div>

	<?php endforeach; ?>
</div>
<!-- .gallery-thumbs -->
