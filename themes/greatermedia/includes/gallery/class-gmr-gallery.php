<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

class GreaterMediaGallery {

	public static function init() {
		add_shortcode( 'gallery', '__return_false' );
		add_action( 'init', array( __CLASS__, 'add_image_sizes' ) );
		add_action( 'gmr_gallery', array( __CLASS__, 'render_gallery' ) );
	}

	/**
	 * Get an array of photos for a gallery.
	 *
	 * @param $post
	 *
	 * @return array
	 */
	public static function get_gallery_photos( $post ) {
		preg_match_all( '/\[gallery.*ids=.(.*).\]/', $post->post_content, $ids );

		$array_ids = array();
		foreach( $ids[1] as $match ) {
			$array_id = explode( ',', $match );
			$array_id = array_map( 'intval', $array_id );

			$array_ids = array_merge( $array_ids, $array_id );
		}

		$photos = array();

		foreach( $array_ids as $id ) {
			$image = wp_get_attachment_image_src( $id, 'gmr-gallery' );
			$thumb = wp_get_attachment_image_src( $id, 'gmr-gallery-thumbnail' );

			if ( ! $image ) {
				continue;
			}

			$photos[] = array(
				'url'       => $image[0],
				'title'     => get_post_field( 'post_excerpt', $id ),
				'thumbnail' => $thumb[0], // 82x46
			);
		}

		return $photos;
	}

	/**
	 * Gets a WP_Query for the attachments in the gallery
	 * @param $post
	 * @return WP_Query
	 */
	public static function get_gallery_loop( $post ) {
		preg_match_all( '/\[gallery.*ids=.(.*).\]/', $post->post_content, $ids );

		$array_ids = array();
		foreach ( $ids[1] as $match ) {
			$array_id = explode( ',', $match );
			$array_id = array_map( 'intval', $array_id );
			$array_ids = array_merge( $array_ids, $array_id );
		}

		$photos = new WP_Query(
			array(
				'ignore_sticky_posts' => true,
				'post__in'            => $array_ids,
				'post_status'         => 'inherit',
				'post_type'           => 'attachment',
				'posts_per_page'      => - 1,
				'orderby'             => 'post__in',
			)
		);

		return $photos;
	}

	/**
	 * Add custom image sizes so WordPress generates images of the appropriate size.
	 */
	public static function add_image_sizes() {
		add_image_size( 'gmr-gallery',               1400, 1400      );
		add_image_size( 'gmr-gallery-thumbnail',     120,  120, true );
	}

	public static function render_gallery() {
		$gallery = self::get_gallery_loop( get_queried_object() );
		if ( $gallery->have_posts() ):
			wp_enqueue_script( 'cycle', get_template_directory_uri() . '/assets/js/vendor/cycle2/jquery.cycle2.min.js', array( 'jquery' ), '2.1.6', true );
			wp_enqueue_script( 'cycle-center', get_template_directory_uri() . '/assets/js/vendor/cycle2/jquery.cycle2.center.min.js', array( 'cycle' ), '20141007', true );
			wp_enqueue_script( 'cycle-swipe', get_template_directory_uri() . '/assets/js/vendor/cycle2/jquery.cycle2.swipe.min.js', array( 'cycle' ), '20141007', true );
			wp_enqueue_script( 'cycle-carousel', get_template_directory_uri() . '/assets/js/vendor/cycle2/jquery.cycle2.carousel.min.js', array( 'cycle' ), '20141007', true );
			wp_register_script( 'gmr-gallery', get_template_directory_uri() . '/assets/js/src/greater_media_gallery.js', array( 'jquery' ), GREATERMEDIA_VERSION, true );
			wp_localize_script( 'gmr-gallery', 'GMR_Button_Text', array(
				'fullscreen'      => __( 'Fullscreen', 'greatermedia' ),
				'exit_fullscreen' => __( 'Exit Fullscreen', 'greatermedia' ),
				'widescreen'      => __( 'Widescreen', 'greatermedia' ),
				'exit_widescreen' => __( 'Exit Widescreen', 'greatermedia' ),
				'show_thumbnails' => __( 'Show Thumbnails', 'greatermedia' ),
				'hide_thumbnails' => __( 'Hide Thumbnails', 'greatermedia' ),
				'show_info'       => __( 'Show Info', 'greatermedia' ),
				'hide_info'       => __( 'Hide Info', 'greatermedia' ),
			) );
			wp_enqueue_script( 'gmr-gallery' );

			$main_post_id         = get_queried_object_id();
			$main_post_title      = get_the_title( $main_post_id );
			$main_post_short_link = wp_get_shortlink( $main_post_id );

			$thumbnails_per_page = 15;

			$image_count_text = sprintf( __( 'Image %s of %s', 'greatermedia' ), '{{slideNum}}</span>', '{{slideCount}}' );
			?>
			<div class="gallery">
				<div class="container">
					<div class="gallery__slides cycle-slideshow"
					     data-cycle-log="false"
					     data-slides="> .slide"
					     data-cycle-prev=".prev-img"
					     data-cycle-next=".next-img"
					     data-cycle-timeout="0"
					     data-cycle-caption=".slide-paging-text"
					     data-cycle-caption-template="<span class='highlight'><?php echo esc_attr( $image_count_text ); ?>"
					     data-cycle-center-horz="true"
					     data-cycle-center-vert="true"
					     data-cycle-manual-speed="200"
					     data-cycle-swipe="true"
					     data-cycle-fx="scrollHorz"
					     data-cycle-swipe-fx="scrollHorz"
					     data-cycle-manual-fx="scrollHoriz">
						<span class="prev-img slide-overlay-control-nohide"><span class="fa fa-angle-left"></span></span>
						<span class="next-img slide-overlay-control-nohide"><span class="fa fa-angle-right"></span></span>
						<?php
						while ( $gallery->have_posts() ) {
							$gallery->the_post();
							$slide_hash = get_post_field( 'post_name', get_the_ID() );
							$slide_link = get_permalink( $main_post_id ) . '#' . $slide_hash;

							$attr = array(
								'data-cycle-hash'           => get_post_field( 'post_name', get_the_ID() ),
								'data-cycle-slide_shorturl' => $slide_link,
								'data-cycle-slide_title'    => urlencode( get_the_title() ),
							);
							$image = wp_get_attachment_image_src( get_the_ID(), 'gmr-gallery', false );
							$image_url = $image[0];
							?>
							<div class="gallery__slide--image"
								<?php
								foreach ( $attr as $attr_name => $attr_value ) {
									echo $attr_name . '="' . esc_attr( $attr_value ) . '" ';
								}
								?>
								 style="background-image: url(<?php echo esc_url( $image_url ); ?>);">
							</div>
						<?php

						}
						$gallery->rewind_posts();
						?>
					</div>
					<div class="gallery-toolbar slide-overlay-control">
						<?php /* removing for now, may be added later <div class="toolbar-item embed">
					<a id="load-iframe" href="#iframe-source">
						<span class="toolbar-icon fa fa-code"></span>
						<span class="toolbar-text"><?php _e( 'Embed', 'greatermedia' ); ?></span>
					</a>
				</div> */ ?>
						<div class="toolbar-item fullscreen">
							<span class="toolbar-icon fa fa-arrows-alt"></span>
							<span class="toolbar-text"><?php _e( 'Fullscreen', 'greatermedia' ); ?></span>
						</div>
						<div class="toolbar-item widescreen">
							<span class="toolbar-icon fa fa-arrows-h"></span>
							<span class="toolbar-text"><?php _e( 'Widescreen', 'greatermedia' ); ?></span>
						</div>
						<div class="toolbar-item share">
							<span class="toolbar-icon fa fa-share-square-o"></span>
							<span class="toolbar-text"><?php _ex( 'Share', 'verb', 'greatermedia' ); ?></span>
							<div class="sharing-overlay">
							<span class="sharing-option">
								<input type="radio" name="what-to-share" id="share-image" checked="checked" />
								<span class="fake-radio fa fa-check"></span>
								<label for="share-image"><?php _e( 'Share image only', 'greatermedia' ); ?></label>
							</span>
							<span class="sharing-option">
								<input type="radio" name="what-to-share" id="share-gallery" />
								<span class="fake-radio"></span>
								<label for="share-gallery"><?php _e( 'Share gallery', 'greatermedia' ); ?></label>
							</span>
								<div id="gallery-share-icons">
									<a href="#" target="_blank" class="fa fa-twitter"></a>
									<a href="#" target="_blank" class="fa fa-facebook"></a>
									<a href="#" target="_blank" class="fa fa-linkedin"></a>
								</div>
								<input type="hidden" class="slide-url" value="" />
								<input type="hidden" class="slide-title" value="" />
								<input type="hidden" class="gallery-url" value="<?php echo esc_attr( $main_post_short_link ); ?>" />
								<input type="hidden" class="gallery-title" value="<?php echo esc_attr( urlencode( $main_post_title ) ); ?>" />
								<div class="short-url"></div>
							</div>
						</div>
						<div class="toolbar-item thumbnails">
							<span class="toolbar-icon fa fa-th-large"></span>
							<span class="toolbar-text"><?php _e( 'Hide Thumbnails', 'greatermedia' ); ?></span>
						</div>
						<div class="toolbar-item info">
							<span class="toolbar-icon fa fa-info-circle"></span>
							<span class="toolbar-text"><?php _e( 'Hide Info', 'greatermedia' ); ?></span>
						</div>
						<div class="toolbar-thumbnails"></div>
					</div>
				</div>
				<div class="sidebar">
					<?php
					$pager_text = sprintf( _x( 'Group %s', 'noun: group number', 'greatermedia' ), '{{slideNum}}' );
					?>
					<div class="slide-paging-previews cycle-slideshow"
					     data-cycle-log="false"
					     data-slides=".slide-previews-group"
					     data-cycle-prev=".prev-group"
					     data-cycle-next=".next-group"
					     data-cycle-pager=".slide-group-pager"
					     data-cycle-pager-template="<button class='btn btn-link indicator'><i class='dot'><?php echo esc_attr( $pager_text ); ?></i></button>"
					     data-cycle-pager-active-class="current"
					     data-cycle-timeout="0"
					     data-cycle-manual-speed="200"
					     data-cycle-swipe="true"
					     data-cycle-fx="scrollHorz"
					     data-cycle-swipe-fx="scrollHorz">

						<div class="slide-previews-group">
							<?php
							$image_count = 0;
							while ( $gallery->have_posts() ) {
								$gallery->the_post();
								$thumb_url = wp_get_attachment_image_src( get_the_ID(), 'gmr-gallery-thumbnail' );

								if ( $image_count > 0 && $image_count % $thumbnails_per_page == 0 ) {
									echo '</div><div class="slide-previews-group">';
								}

								echo '<div id="preview-' . $image_count . '" style="background-image: url(' . $thumb_url[0] . ');" data-cycle-hash="' . get_post_field( 'post_name', get_the_ID() ) . '" data-cycle-index="' . $image_count . '"></div>';
								$image_count++;
							}
							$gallery->rewind_posts();
							?>
						</div>
					</div>
					<div class="slide-paging">
						<span class="slide-paging-text"></span>
						<div class="slide-paging-arrows carousel-controls">
							<button type="button" class="btn btn-link btn-left prev-group"><i class="arrow-left"><?php _ex( 'Left', 'direction', 'greatermedia' ); ?></i></button>
							<button type="button" class="btn btn-link btn-right next-group"><i class="arrow-right"><?php _ex( 'Right', 'direction', 'greatermedia' ); ?></i></button>
							<div class="slide-group-pager"></div>
						</div>
					</div>
					<div class="caption cycle-slideshow"
					     data-cycle-log="false"
					     data-cycle-slides="> div"
					     data-cycle-prev=".prev"
					     data-cycle-next=".next"
					     data-cycle-timeout="0"
					     data-cycle-auto-height="false"
					     data-cycle-manual-speed="1">
						<?php
						while ( $gallery->have_posts() ){
							$gallery->the_post();
							$slide_hash = get_post_field( 'post_name', get_the_ID() );
							echo '<div data-cycle-hash="' . $slide_hash . '">';
							?>
							<?php
							the_excerpt();
							echo '</div>';
						}
						$gallery->rewind_posts();
						?>
					</div>
				</div> <!-- / gallery sidebar -->
				<?php wp_reset_postdata(); ?>
				<div class="slideshow-info">
					<h1><?php the_title(); ?></h1>
					<div class="meta-social">
						<time datetime="<?php the_time( 'c' ); ?>"><?php the_date(); ?></time>
						<span class="byline"><?php the_author(); ?></span>
						<a href="<?php the_permalink(); ?>#disqus_thread" class="comments comments-number">Comments</a>

						<div class="article-share">
							<?php
							if ( function_exists( 'sharing_display' ) ) {
								echo sharing_display();
							}
							?>
						</div>

					</div>
				</div>
			</div><!-- / gallery -->
		<?php
		endif;
	}

}

GreaterMediaGallery::init();
