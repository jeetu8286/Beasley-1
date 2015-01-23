<?php
/**
 * Class GMP_Meta
 *
 * This class constructs a meta box for episodes and saves data entered into the fields of the meta box.
 */
class GreaterMediaGalleryMetaboxes {

	protected static $_images = array();

	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public static function init() {
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
		add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_box' ) );
		add_action( 'edit_form_after_title', array( __CLASS__, 'inline_instructions' ) );

		self::add_save_post_actions();
	}

	public static function add_save_post_actions() {
		add_action( 'save_post_' . GreaterMediaGalleryCPT::GALLERY_POST_TYPE, array( __CLASS__, 'save_meta_box' ), 10, 2 );
	}

	public static function remove_save_post_actions() {
		remove_action( 'save_post_' . GreaterMediaGalleryCPT::GALLERY_POST_TYPE, array( __CLASS__, 'save_meta_box' ), 10 );
	}

	/**
	 * Adds the meta box container for Episodes.
	 *
	 * @param $post_type
	 */
	public static function add_meta_box( $post_type ) {
		$post_types = array( GreaterMediaGalleryCPT::GALLERY_POST_TYPE );
		if ( in_array( $post_type, $post_types ) ) {
			add_meta_box( 'gmp_albums_meta_box', 'Album', array( __CLASS__, 'render_parent_metabox' ), $post_type, 'side' );
			add_meta_box( 'gmp_gallery_preview', 'Preview / Featured Image Selection', array( __CLASS__, 'gallery_preview' ), $post_type, 'advanced', 'core' );
		}
	}

	/**
	 * Enqueues admin scripts and styles.
	 *
	 * @global string $typenow The current type.
	 * @global string $pagenow The current page.
	 */
	public static function enqueue_scripts() {
		global $typenow, $pagenow;

		if ( GreaterMediaGalleryCPT::GALLERY_POST_TYPE == $typenow && in_array( $pagenow, array( 'post.php', 'post-new.php' ) ) ) {
			$postfix = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min';

			wp_enqueue_style( 'gmr-gallery-admin', GREATER_MEDIA_GALLERIES_URL . "assets/css/gmr_gallery_admin{$postfix}.css", null, GREATER_MEDIA_GALLERIES_VERSION );

			wp_enqueue_media();
			wp_enqueue_script( 'gmr-gallery-admin', GREATER_MEDIA_GALLERIES_URL . "assets/js/gmr_admin{$postfix}.js", array( 'jquery' ), GREATER_MEDIA_GALLERIES_VERSION, true );
		}
	}

	/**
	 * Returns the images in the gallery, in the order they should be displayed.
	 *
	 * @param int $post_id The current post object.
	 *
	 * @return array Array of WP_Post objects corresponding to the gallery attachments.
	 */
	public static function get_images_in_order( $post_id = null ) {
		if ( isset( self::$_images[ $post_id ] ) ) {
			return self::$_images[ $post_id ];
		}

		if ( is_null( $post_id ) ) {
			$post_id = get_the_ID();
		}

		$post = get_post( $post_id );
		if ( ! $post ) {
			return array();
		}

		$matches = array();
		if ( ! preg_match( '/\[gallery.*?ids\=\"(.*?)\".*?\]/im', $post->post_content, $matches ) ) {
			return array();
		}

		$attachment_ids = array_filter( array_map( 'intval', explode( ',', $matches[1] ) ) );
		if ( empty( $attachment_ids ) ) {
			return array();
		}

		self::$_images[ $post_id ] = get_posts( array(
			'include'        => $attachment_ids,
			'post_status'    => 'inherit',
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'orderby'        => 'post__in',
			'posts_per_page' => count( $attachment_ids ),
			'no_found_rows'  => true,
		) );

		return self::$_images[ $post_id ];
	}
	
	/**
	 * Renders thumbnails, to preview the gallery, and select the featured image.
	 *
	 * @param WP_Post $post The current post object.
	 */
	public static function gallery_preview( $post ) {
		$featured_image_id = get_post_thumbnail_id( $post->ID );
		$gallery_ids = get_post_meta( $post->ID, 'gmr-gallery-ids', true );
		$attachments = self::get_images_in_order( $post->ID );

		$label = empty( $gallery_ids ) ? 'Create Gallery' : 'Edit Gallery';

		wp_nonce_field( 'save-gallery', 'gmr-gallery-nonce' );

		?><div class="gmr-manage-gallery-container">
			<div class="wp-media-buttons">
				<a href="#" id="manage-gallery-button" class="button insert-media add_media">
					<span class="wp-media-buttons-icon"></span>
					<?php echo esc_html( $label ); ?>
				</a>
			</div>
			<input type="hidden" id="gmr-gallery-ids" name="gmr-gallery-ids" value="<?php echo esc_attr( $gallery_ids ); ?>">
			<input type="hidden" id="gmr-featured-image" name="gmr-featured-image" value="<?php echo esc_attr( $featured_image_id ); ?>">
		</div>

		<p><strong>Select a featured image for the gallery below.</strong></p>

		<div class="gallery-preview" id="gmr-gallery-images">
			<?php foreach ( $attachments as $attachment ) : ?>
				<div class="gallery-item attachment <?php echo $featured_image_id == $attachment->ID ? 'details selected' : ''; ?>" data-attachment-id="<?php echo esc_attr( $attachment->ID ); ?>" style="background-image:url('<?php echo esc_url( current( wp_get_attachment_image_src( $attachment->ID ) ) ); ?>')"></div>
			<?php endforeach; ?>

			<?php // Template used when re-rendering the thumbnails after updating the gallery in JS ?>
			<script type="text/template" id="gmr-gallery-item-template">
				<div class="gallery-item attachment {{selected_class}}" data-attachment-id="{{attachment_id}}" style="background-image: url('{{thumbnail_url}}')"></div>
			</script>
		</div><?php
	}

	/**
	 * Render Meta Box content for Episodes.
	 *
	 * @param $post
	 */
	public static function render_parent_metabox( \WP_Post $post ) {
		$album_args = array(
			'post_type' => GreaterMediaGalleryCPT::ALBUM_POST_TYPE,
			'posts_per_page' => 100,
			'paged' => 0,
		);

		wp_nonce_field( 'save_gallery_parent', 'gallery_parent_nonce' );

		?>
		<select name="gallery-parent" id="gallery-parent">
		<option value="0">&mdash; Select an Album &mdash;</option>
		<?php

		do {
			$album_args['paged']++;
			$album_query = new WP_Query( $album_args );
			while( $album_query->have_posts() ) {
				$album = $album_query->next_post();

				?><option value="<?php echo intval( $album->ID ); ?>" <?php selected( $album->ID, $post->post_parent ); ?>><?php echo esc_html( $album->post_title ); ?></option><?php
			}
		} while ( $album_args['paged'] < $album_query->max_num_pages );

		?></select><?php
	}

	/**
	 * Save the meta when the post is saved for Episodes.
	 *
	 * @param $post_id
	 */
	public static function save_meta_box( $post_id, \WP_Post $post ) {
		if ( ! isset( $_POST['gallery_parent_nonce'] ) || ! wp_verify_nonce( $_POST['gallery_parent_nonce'], 'save_gallery_parent' ) ) {
			return;
		}

		if ( ! isset( $_POST['gallery-parent'] ) ) {
			return;
		}

		$parent_id = intval( $_POST['gallery-parent'] );

		$post->post_parent = $parent_id;

		self::remove_save_post_actions();
		wp_update_post( $post );
		self::add_save_post_actions();
	}

	/**
	 * Output instructions on creating a gallery
	 */
	public static function inline_instructions( $post ) {

		// These instructions are applicable to adding a gallery anywhere, but unlike a post
		// it's a bit unclear where to start, so we'll only output them on the gallery post type
		if ( GreaterMediaGalleryCPT::GALLERY_POST_TYPE !== $post->post_type ) {
			return;
		}

		?>
		<h3>To add a gallery:</h3>
		<ol>
			<li>Click the <strong>Add Media</strong> button</li>
			<li>Select "Create Gallery" in the left menu</li>
			<li>Upload or select photos from the media library</li>
			<li>Click on "Create a new Gallery"</li>
			<li>Click on "Insert Gallery"</li>
		</ol>

		<p>
			All the gallery images will be extracted and displayed above the gallery title and any text.
		</p>


	<?php

	}
}

GreaterMediaGalleryMetaboxes::init();
