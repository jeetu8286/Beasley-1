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

?><div class="swiper-container gallery-top" data-refresh-interval="3">
    <div class="swiper-wrapper">
		<?php foreach ( $images as $index => $image ) : ?>
			<div data-index="<?php echo esc_html( $index ); ?>"
				 class="swiper-slide"
				 data-slug="<?php echo esc_url( $base_url ); ?>/view/<?php echo esc_attr( $image->post_name ); ?>/"
				 data-title="<?php echo esc_attr( get_the_title( $image ) ); ?>"
				 data-caption="<?php echo esc_attr( get_the_excerpt( $image ) ); ?>"
				 >
				<?php echo wp_get_attachment_image( $image->ID, 'full', false, array( 'class' => 'swiper-image' ) ); ?>
			</div>

			<?php if ( $index > 0 && $index % 2 == 0 ) : ?>
				<div data-index="2" class="swiper-slide meta-spacer"></div>
			<?php endif; ?>
		<?php endforeach; ?>
    </div>
    <!-- .swiper-wrapper -->

	<!--
	@TODO Notes
	The initial sidebar information must be filled with the first slide's information.
	This will be updated with JS
	-->
	<div class="swiper-sidebar">
		<div class="swiper-sidebar-text">
			<h2 id="js-swiper-sidebar-title">Slide 1 title</h2>
			<p id="js-swiper-sidebar-caption">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis, maiores, perferendis. Nam, ex, quia. Et excepturi veritatis, earum atque laboriosam enim provident eos vel libero fugiat cumque reiciendis, repellat alias.</p>
		</div>
		<div class="swiper-sidebar-sharing">
			<?php get_template_part( 'partials/social-share' ); ?>
		</div>
		<div class="swiper-sidebar-meta">
			<!-- @TODO Not sure if this is the right ad to use? -->
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

<div class="gallery-thumbs">
	<?php foreach ( $images as $index => $image ) : ?>
		<div><div class="swiper-slide" style="background-image:url(<?php echo esc_url( wp_get_attachment_image_url( $image->ID, 'full' ) ); ?>)"></div></div>

		<?php if ( $index > 0 && $index % 2 == 0 ) : ?>
			<div><div class="swiper-slide meta-spacer"></div></div>
		<?php endif; ?>
	<?php endforeach; ?>
</div>
<!-- .gallery-thumbs -->
