<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

if ( !class_exists( "Breaking_News" ) ) {
	class Breaking_News {

		public function __construct() {
	    	add_action( 'post_submitbox_misc_actions', array( $this, 'add_meta_checkbox' ) );
	    	add_action( 'save_post', array( $this, 'save_breaking_news_meta_option' ) );
	    	add_action( 'send_breaking_news_notices', array( $this, 'send_breaking_news_notices' ) );
	    	add_action( 'show_breaking_news_banner', array( $this, 'show_breaking_news_banner' ) );
	    	add_action( 'show_latest_breaking_news_item', array( $this, 'show_latest_breaking_news_item' ) );
	    	add_action( 'wp_enqueue_scripts', array( $this, 'breaking_news_enqueue_scripts' ) );
	    }

	    /**
	     * Enqueue supporing scripts.
	     *
	     * @return void
	     */
	    public function breaking_news_enqueue_scripts() {
	    	/*
	    	if ( is_Admin() && 'post' === get_post_type() ) {
				wp_enqueue_script( 'breaking-news-admin-js', BREAKING_NEWS_URL . 'assets/js/breaking-news.min.js', array( 'jquery'), false, true );
			}
			*/

	        wp_enqueue_style( 'breaking-news',
				BREAKING_NEWS_URL . 'assets/css/breaking-news.min.css',
				array(), BREAKING_NEWS_VERSION, 'all' );
		}

		/**
		 * Add meta meta fields to the post edit page.
		 *
		 * @return void
		 */
	    public static function add_meta_checkbox() {
			global $post;

			if ( 'post' !== get_post_type() ) {
				return;
			}

			wp_nonce_field( 'save_breaking_news_meta', 'breaking_news_nonce' );

			$is_breaking_news = self::sanitize_boolean( get_post_meta( $post->ID, '_is_breaking_news', true ) );
			$show_site_wide_notification = self::sanitize_boolean( get_post_meta( $post->ID, '_show_in_site_wide_notification', true ) );
			?>

			<style>
				#post-body #site-wide-notification-meta {
					padding-left: 35px;
				}
			</style>

			<div id="breaking-news-meta-fields">
				<div id="breaking-news-meta" class="misc-pub-section">
					<input type="checkbox" name="breaking_news_option" id="breaking_news_option" value="1" <?php checked( 1, $is_breaking_news ); ?> /> <label for="breaking_news_option"><?php _e( 'Breaking News', 'breaking_news' ); ?></label>
				</div>

				<div id="site-wide-notification-meta" class="misc-pub-section">
					<input type="checkbox" name="site_wide_notification_option" id="site_wide_notification_option" value="1" <?php checked( 1, $show_site_wide_notification ); ?> /> <label for="site_wide_notification_option"><?php _e( 'Site-wide Notification', 'breaking_news' ); ?></label>
				</div>
			</div>

			<?php
		}

		/**
		 * Handle saving the post meta.
		 *
		 * @param  int $post_id
		 * @return void
		 */
		public function save_breaking_news_meta_option( $post_id ) {
			global $post;

			if ( ! isset( $_POST['breaking_news_nonce'] ) || ! wp_verify_nonce( $_POST['breaking_news_nonce' ], 'save_breaking_news_meta' ) ) {
				return;
			}

			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}

			$is_breaking_news = $this->sanitize_boolean( $_POST['breaking_news_option'] );
			$show_site_wide_notification = $this->sanitize_boolean( $_POST['site_wide_notification_option'] );

			update_post_meta( $post_id, '_is_breaking_news', $is_breaking_news );
			update_post_meta( $post_id, '_show_in_site_wide_notification', $show_site_wide_notification );

			// Send notices
			if ( 1 === $is_breaking_news ) {
				// do_action( 'send_breaking_news_notices' );
			}
		}

		/**
		 * Show a site-wide breaking news banner.
		 *
		 * @return void
		 */
		public function show_breaking_news_banner() {
			$post = $this->get_latest_breaking_news_item();

			if ( ! empty( $post ) ) {
				$show_banner = self::sanitize_boolean( get_post_meta( $post->ID, '_show_in_site_wide_notification', true ) );

				if ( 1 === $show_banner ) {
			?>
				<div id="breaking-news-banner">
					<div class="breaking-news-item">
						<a href="<?php echo get_permalink( $post->ID ); ?>"><?php echo get_the_title( $post->ID ); ?></a>
					</div>
				</div>
			<?php
				}
			}
		}

		/**
		 * Show latest breaking news item on homepage or somewhere else. To override, add a function called breaking_news_get_latest_item() to override.
		 *
		 * @return void
		 */
		public function show_latest_breaking_news_item() {
			if ( function_exists( 'breaking_news_get_latest_item' ) ) {
				breaking_news_get_latest_item();
			} else {
				$args = array(
					'post_type' => 'post',
					'posts_per_page' => 1,
					'order' => 'DESC',
					'orderby' => 'post_date',
					'meta_query' => array(
						array(
							'key'     => '_is_breaking_news',
							'value'   => 1,
							'compare' => '=',
						),
					),
				);

				$query = new WP_Query( $args );

				if ( $query->have_posts() ) {
				?>
					<div id="latest-breaking-news">

				<?php

					while ( $query->have_posts() ) {
						$query->the_post()

					?>
						<article class="breaking-news-item">
							<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<?php the_excerpt( 'read more >' ); ?>
						</article>
					<?php
					}
					?>
						</div>
					<?php

					wp_reset_postdata();
				}
			}
		}

		/**
		 * Get latest breaking news item.
		 *
		 * @return WP_Post|null
		 */
		public static function get_latest_breaking_news_item() {
			$args = array(
				'post_type' => 'post',
				'posts_per_page' => 1,
				'order' => 'DESC',
				'orderby' => 'post_date',
				'meta_query' => array(
					array(
						'key'     => '_is_breaking_news',
						'value'   => 1,
						'compare' => '=',
					),
				),
			);

			$posts = get_posts( $args );

			if ( ! empty( $posts ) ) {
				return $posts[0];
			}

			return null;
		}

		/**
		 * Send notifications via email, sms, etc.
		 *
		 * @return void
		 */
		public function send_breaking_news_notices() {
			// Nothing yet
		}

	    /**
	     * Sanitize a boolean option.
	     *
	     * @param  int|string $input
	     * @return int
	     */
	    public static function sanitize_boolean( $input ) {
	        $new_input = 0;
	        $input = absint( intval( $input ) );

	        if ( in_array( $input, array( 0, 1 ) ) ) {
	            $new_input = $input;
	        }

	        return $new_input;
	    }

	    public static function init() {
	        if ( ! isset( $this ) || null === $this ) {
	            new self;
	        }
	    }
	}

	Breaking_News::init();
}
