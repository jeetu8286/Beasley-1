<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

class GreaterMediaGallery {

	public static $strip_shortcodes = false;

	public static function init() {
		if ( ! is_admin() && ! defined( 'WP_CLI' ) && ( ! defined( 'DOING_CRON' ) || ! DOING_CRON ) ) {
			// Override the core gallery shortcode with our own handler, only on the front end
			remove_shortcode( 'gallery' );
			add_shortcode( 'gallery', array( __CLASS__, 'fotorama_gallery_shortcode' ) );
		}

		// If we need to manually render somewhere, like on the top of a single-gallery template
		add_action( 'gmr_gallery', array( __CLASS__, 'do_gallery_action' ) );

		// Remove gallery shortcodes from content, since we have these at the top of single-page
		add_filter( 'the_content', array( __CLASS__, 'strip_for_single_gallery' ) );

		// Register scripts
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'register_scripts' ), 10 );
	}

	/**
	 * Registers gallery scripts to use on the front end.
	 *
	 * @static
	 * @access public
	 * @action wp_enqueue_scripts
	 */
	public static function register_scripts() {
		$postfix = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min';

		/* all js files are being concatenated into a single js file.
		 * This include all js files for cycle2, located in `assets/js/vendor/cycle2/`
		 * and `gmr_gallery.js`, located in `assets/js/src/`
		 */
		wp_enqueue_script( 'gmr-gallery', GREATER_MEDIA_GALLERIES_URL . "assets/js/gmr_gallery{$postfix}.js", array( 'jquery' ), GREATER_MEDIA_GALLERIES_VERSION, true );
		wp_enqueue_style( 'gmr-gallery', GREATER_MEDIA_GALLERIES_URL . "assets/css/gmr_gallery{$postfix}.css", array(), GREATER_MEDIA_GALLERIES_VERSION );
	}

	/**
	 * Strips gallery shortcodes for content, on pages where we know we've run the action instead
	 *
	 * @param string $content
	 *
	 * @return string Final content with galleries removed
	 */
	public static function strip_for_single_gallery( $content ) {
		if ( self::$strip_shortcodes ) {
			$content = preg_replace( '/\[gallery.*?\]/', '', $content );
		}

		return $content;
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
	 * Returns a WP_Query that corresponds to the IDs provided
	 *
	 * @param array $ids Array of image IDs
	 *
	 * @return WP_Query
	 */
	public static function get_query_for_ids( $ids ) {
		$photos = new WP_Query(
			array(
				'ignore_sticky_posts' => true,
				'post__in'            => $ids,
				'post_status'         => 'inherit',
				'post_type'           => 'attachment',
				'posts_per_page'      => count( $ids ),
				'orderby'             => 'post__in',
			)
		);

		return $photos;
	}

	/**
	 * Gets an array of ids for the attachments in the gallery
	 * @param $post
	 * @return Array
	 */
	public static function get_attachment_ids_for_post( $post ) {
		$array_ids = get_post_meta( $post->ID, 'gallery-image' );

		if ( empty( $array_ids ) && preg_match_all( '/\[gallery.*ids=.(.*).\]/', $post->post_content, $ids ) ) {
			foreach( $ids[1] as $match ) {
				$array_id = explode( ',', $match );
				$array_id = array_map( 'intval', $array_id );

				$array_ids = array_merge( $array_ids, $array_id );
			}
		}

		return ! empty( $array_ids )
			? $array_ids
			: null;
	}

	/**
	 * Gets a WP_Query for the attachments in the gallery
	 * @param $post
	 * @return WP_Query
	 */
	public static function get_query_for_post( $post ) {
		$array_ids = get_post_meta( $post->ID, 'gallery-image' );

		if ( empty( $array_ids ) && preg_match_all( '/\[gallery.*ids=.(.*).\]/', $post->post_content, $ids ) ) {
			foreach( $ids[1] as $match ) {
				$array_id = explode( ',', $match );
				$array_id = array_map( 'intval', $array_id );

				$array_ids = array_merge( $array_ids, $array_id );
			}
		}

		return ! empty( $array_ids )
			? self::get_query_for_ids( $array_ids )
			: null;
	}

	/**
	 * Renders a gallery for a post when do_action( 'gmr_gallery' ) is called
	 */
	public static function do_gallery_action( $strip_shortcodes ) {
		// So that we remove the gallery from content, since we're rendering it now
		self::$strip_shortcodes = true;

		$ids = self::get_attachment_ids_for_post( get_queried_object() );
		if ( ! empty( $ids ) ) {
			$attr = array();

			$attr['ids'] = implode( ',', $ids );

			$content = self::fotorama_gallery_shortcode($attr);
			echo apply_filters( 'the_secondary_content', $content );
		}
	}

	public static function fotorama_gallery_shortcode( $attr ) {
		if (!$attr) {
			$attr = array();
		}

		if (array_key_exists('fotorama', $attr) && $attr['fotorama'] == 'false') {
			return gallery_shortcode($attr);
		}

    $post = get_post();

    static $instance = 0;
    $instance++;

    if ( ! empty( $attr['ids'] ) ) {
        // 'ids' is explicitly ordered, unless you specify otherwise.
        if ( empty( $attr['orderby'] ) ) {
            $attr['orderby'] = 'post__in';
        } else {
					if ( 'rand' === $attr['orderby'] ) {
						$attr['orderby'] = '';
						$attr['shuffle'] = 'true';
					}
				}
        $attr['include'] = $attr['ids'];
    }

    /**
     * Filters the default gallery shortcode output.
     *
     * If the filtered output isn't empty, it will be used instead of generating
     * the default gallery template.
     *
     * @since 2.5.0
     * @since 4.2.0 The `$instance` parameter was added.
     *
     * @see gallery_shortcode()
     *
     * @param string $output   The gallery output. Default empty.
     * @param array  $attr     Attributes of the gallery shortcode.
     * @param int    $instance Unique numeric ID of this gallery shortcode instance.
     */
    $output = apply_filters( 'post_gallery', '', $attr, $instance );
    if ( $output != '' ) {
        return $output;
    }

    $html5 = current_theme_supports( 'html5', 'gallery' );
    $atts = shortcode_atts( array(
        'order'      => 'ASC',
        'orderby'    => 'menu_order ID',
        'id'         => $post ? $post->ID : 0,
        'itemtag'    => 'dl',
        'icontag'    => 'dt',
        'captiontag' => 'dd',
        'columns'    => 0,
        'size'       => 'thumbnail',
        'include'    => '',
        'exclude'    => '',
        'link'       => 'file'
    ), $attr, 'gallery' );

    $id = intval( $atts['id'] );

    if ( ! empty( $atts['include'] ) ) {
        $_attachments = get_posts( array( 'include' => $atts['include'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );

        $attachments = array();
        foreach ( $_attachments as $key => $val ) {
            $attachments[$val->ID] = $_attachments[$key];
        }
    } elseif ( ! empty( $atts['exclude'] ) ) {
        $attachments = get_children( array( 'post_parent' => $id, 'exclude' => $atts['exclude'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
    } else {
        $attachments = get_children( array( 'post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
    }

    if ( empty( $attachments ) ) {
        return '';
    }

    if ( is_feed() ) {
        $output = "\n";
        foreach ( $attachments as $att_id => $attachment ) {
            $output .= wp_get_attachment_link( $att_id, $atts['size'], true ) . "\n";
        }
        return $output;
    }

    $itemtag = tag_escape( $atts['itemtag'] );
    $captiontag = tag_escape( $atts['captiontag'] );
    $icontag = tag_escape( $atts['icontag'] );
    $valid_tags = wp_kses_allowed_html( 'post' );
    if ( ! isset( $valid_tags[ $itemtag ] ) ) {
        $itemtag = 'dl';
    }
    if ( ! isset( $valid_tags[ $captiontag ] ) ) {
        $captiontag = 'dd';
    }
    if ( ! isset( $valid_tags[ $icontag ] ) ) {
        $icontag = 'dt';
    }

    $columns = intval( $atts['columns'] );
    $itemwidth = $columns > 0 ? floor(100/$columns) : 100;
    $float = is_rtl() ? 'right' : 'left';

    $selector = "gallery-{$instance}";

    $gallery_style = '';

    /**
     * Filters whether to print default gallery styles.
     *
     * @since 3.1.0
     *
     * @param bool $print Whether to print default gallery styles.
     *                    Defaults to false if the theme supports HTML5 galleries.
     *                    Otherwise, defaults to true.
     */
    if ( apply_filters( 'use_default_gallery_style', ! $html5 ) ) {
        $gallery_style = "
        <style type='text/css'>
            #{$selector} {
                margin: auto;
            }
            #{$selector} .gallery-item {
                float: {$float};
                margin-top: 10px;
                text-align: center;
                width: {$itemwidth}%;
            }
            #{$selector} img {
                border: 2px solid #cfcfcf;
            }
            #{$selector} .gallery-caption {
                margin-left: 0;
            }
            /* see gallery_shortcode() in wp-includes/media.php */
        </style>\n\t\t";
    }

    $size_class = sanitize_html_class( $atts['size'] );
    $gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";

    /**
     * Filters the default gallery shortcode CSS styles.
     *
     * @since 2.5.0
     *
     * @param string $gallery_style Default CSS styles and opening HTML div container
     *                              for the gallery shortcode output.
     */
    $output = apply_filters( 'gallery_style', $gallery_style . $gallery_div );

    $i = 0;
    foreach ( $attachments as $id => $attachment ) {

        $attr = ( trim( $attachment->post_excerpt ) ) ? array( 'aria-describedby' => "$selector-$id" ) : '';

        $image_output = wp_get_attachment_link( $id, 'thumbnail', false, false, false, $attr );

        $image_meta  = wp_get_attachment_metadata( $id );

				$image_attribution = get_post_meta( $attachment->ID, 'gmr_image_attribution', true );
				$download_enabled = empty( $image_attribution ) ? 'true' : 'false';
				$image_output = str_replace( '<a', '<a data-enable-download=\'' . $download_enabled . '\'', $image_output );

        $orientation = '';
        if ( isset( $image_meta['height'], $image_meta['width'] ) ) {
            $orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';
        }
        $output .= "<{$itemtag} class='gallery-item'>";
        $output .= "
            <{$icontag} class='gallery-icon {$orientation}' data-attachment-id='$id''>
                $image_output
            </{$icontag}>";
        if ( $captiontag && trim($attachment->post_excerpt) ) {
            $output .= "
                <{$captiontag} class='wp-caption-text gallery-caption' id='$selector-$id'>
                " . wptexturize($attachment->post_excerpt) . " <span class='attribution'>" . wptexturize($image_attribution) . "</span>
                </{$captiontag}>";
        } else if ( ! empty( $image_attribution ) ) {
						$output .= "
								<{$captiontag} class='wp-caption-text gallery-caption' id='$selector-$id'>
								<span class='attribution'>" . wptexturize($image_attribution) . "</span>
								</{$captiontag}>";
				}
        $output .= "</{$itemtag}>";
        if ( ! $html5 && $columns > 0 && ++$i % $columns == 0 ) {
            $output .= '<br style="clear: both" />';
        }
    }

    if ( ! $html5 && $columns > 0 && $i % $columns !== 0 ) {
        $output .= "
            <br style='clear: both' />";
    }

    $output .= "
        </div>\n";

    $gallery = $output;

		$width = array_key_exists('width', $atts) ? $atts['width'] : '';
		$height = array_key_exists('height', $atts) ? $atts['height'] : '';

		$atts['size'] = 'large';
		preg_match_all('/(<img[^<>]*>).*\n*.*<\/dt/', gallery_shortcode($atts), $images);
		preg_match_all('/href=(\'|")([^"\']+)(\'|").*\n*.*<\/dt/', $gallery, $hrefs);

		for ($i = 0, $l = count($images[0]); $i < $l; $i++) {
			$image = $images[1][$i];
			preg_match('/src=(\'|")([^"\']+)(\'|")/', $image, $src);

			if (!$i) {
				preg_match('/width=(\'|")([^"\']+)(\'|")/', $image, $__width);
				$_width = $__width[2];

				preg_match('/height=(\'|")([^"\']+)(\'|")/', $image, $__height);
				$_height = $__height[2];

				if (!$width) {
					$atts['width'] = $_width;
				}

				if (!$height) {
					$height = $_height;
				}
			}

			$quote = $hrefs[1][$i];
			$full = $hrefs[2][$i];

			$gallery = str_replace($quote . $full . $quote,
				$quote . $src[2] . $quote . ' data-full=' . $quote . $full . $quote,
				$gallery);
		}

		$atts['auto'] = 'false';
		$atts['max-width'] = '100%';
		$atts['ratio'] = array_key_exists('ratio', $atts) ? $atts['ratio'] : ($_width && $_height ? $_width / $_height : '');

		$data = '';
		foreach ($atts as $key => $value) {
			if ($key != 'fotorama') {
				$data .= "data-$key='$value'";
			}
		}

		return "<div class='fotorama--wp' $data>$gallery</div>";
	}

}

GreaterMediaGallery::init();
