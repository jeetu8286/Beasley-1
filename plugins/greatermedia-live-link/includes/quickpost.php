<?php

class GMR_QuickPost {

	const ADMIN_ACTION = 'gmr_quickpost';

	/**
	 * Singletone instance of the class.
	 *
	 * @since 1.0.0
	 *
	 * @static
	 * @access private
	 * @var GMR_QuickPost
	 */
	private static $_isntance = null;

	/**
	 * Returns class instance. Initializes it if it is not exists yet.
	 *
	 * @since 1.0.0
	 *
	 * @static
	 * @access public
	 * @return GMR_QuickPost The class instance.
	 */
	public static function get_intance() {
		if ( is_null( self::$_isntance ) ) {
			self::$_isntance = new GMR_QuickPost();
			
			add_action( 'admin_action_' . self::ADMIN_ACTION, array( self::$_isntance, 'process_quickpost_popup' ) );
			add_action( 'admin_bar_menu', array( self::$_isntance, 'add_admin_bar_items' ), 100 );
			add_action( 'wp_enqueue_scripts', array( self::$_isntance, 'enqueue_scripts' ) );
		}

		return self::$_isntance;
	}

	/**
	 * Adds bookmarklet link to the admin bar menu.
	 *
	 * @since 1.0.0
	 * @action admin_bar_menu 100
	 *
	 * @access public
	 * @param WP_Admin_Bar $admin_bar The admin bar object.
	 */
	public function add_admin_bar_items( WP_Admin_Bar $admin_bar ) {
		if ( ! is_admin() && current_user_can( 'create_post' ) ) {
			$admin_bar->add_menu( array(
				'id'    => 'add-live-link',
				'title' => 'Create Live Link',
				'href'  => '#',
			) );
		}
	}

	/**
	 * Enqueues scripts.
	 *
	 * @since 1.0.0
	 * @access wp_enqueue_scripts
	 *
	 * @access public
	 */
	public function enqueue_scripts() {
		if ( is_user_logged_in() && current_user_can( 'create_post' ) ) {
			$postfix = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min';
			wp_enqueue_script( 'gmr-quick-live-links', GMEDIA_LIVE_LINK_URL . "/assets/js/quick-links{$postfix}.js", array( 'jquery' ), GMEDIA_LIVE_LINK_VERSION, true );
			wp_localize_script( 'gmr-quick-live-links', 'live_links', array( 'url'  => admin_url( 'admin.php?action=' . self::ADMIN_ACTION ) ) );
		}
	}

	/**
	 * Processes QuickPost popup request.
	 *
	 * @since 1.0.0
	 * @action admin_action_gmr_quickpost
	 *
	 * @access public
	 */
	public function process_quickpost_popup() {
		// die if user don't have permissions
		if ( ! current_user_can( 'edit_posts' ) || ! current_user_can( get_post_type_object( GMR_LIVE_LINK_CPT )->cap->create_posts ) ) {
			wp_die( __( 'Cheatin&#8217; uh?' ) );
		}

		define( 'IFRAME_REQUEST' , true );
		header( sprintf( 'Content-Type: %s; charset=%s', get_option( 'html_type' ), get_option( 'blog_charset' ) ) );

		// Set Variables
		$url = filter_input( INPUT_GET, 'u', FILTER_VALIDATE_URL );
		$title = isset( $_GET['t'] ) ? strip_tags( html_entity_decode( wp_unslash( $_GET['t'] ), ENT_QUOTES ) ) : '';
		$title = trim( preg_replace( '#\W+#', ' ', $title ) );

		// process submitted posts.
		$saved = false;
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			check_admin_referer( 'quickpost' );
			$this->_save_quick_post();
			$saved = true;
		}

		$post = get_default_post_to_edit( GMR_LIVE_LINK_CPT, ! $saved );

		wp_enqueue_style( 'colors' );
		wp_enqueue_script( 'post' );

		remove_action( 'media_buttons', 'media_buttons' );
		
		do_action( 'gmr_quickpost_add_metaboxes', 'quickpost', $post->ID );

		_wp_admin_html_begin(); ?>
				<title><?php _e('Quick Post') ?></title>

				<?php if ( $saved ) : ?>
					<script type="text/javascript">window.close();</script>
				<?php endif; ?>
					
				<?php $this->_do_header_actions() ?>

				<script type="text/javascript">
					var wpActiveEditor = 'content';

					(function($) {
						$(document).ready(function() {
							var $contnet = $('#content');

							// Resize screen.
							window.resizeTo(760,580);

							$('#title').unbind();
							$('#publish, #save').click(function() {
								$('.press-this #publishing-actions .spinner').css('display', 'inline-block');
							});

							if ( $( '#wp-content-wrap' ).hasClass( 'html-active' ) && window.switchEditors &&
								( tinyMCEPreInit.mceInit.content && tinyMCEPreInit.mceInit.content.wpautop ) ) {
								// The Text editor is default, run the initial content through pre_wpautop() to convert the paragraphs
								$contnet.text( window.switchEditors.pre_wpautop( $contnet.text() ) );
							}
						});
					})(jQuery);
				</script>
			</head>
			<body class="press-this wp-admin wp-core-ui">
				<form action="<?php echo esc_url( admin_url( 'admin.php?action=' . self::ADMIN_ACTION ) ) ?>" method="post">
					<div id="poststuff" class="metabox-holder">

						<div id="side-sortables" class="press-this-sidebar">
							<div class="sleeve">
								<?php wp_nonce_field( 'quickpost' ) ?>
								<input type="hidden" name="post_type" id="post_type" value="text">
								<input type="hidden" name="autosave" id="autosave">
								<input type="hidden" id="original_post_status" name="original_post_status" value="draft">
								<input type="hidden" id="prev_status" name="prev_status" value="draft">
								<input type="hidden" id="post_id" name="post_id" value="<?php echo (int) $post->ID; ?>">

								<!-- This div holds the photo metadata -->
								<div class="photolist"></div>

								<div id="submitdiv" class="postbox">
									<div class="handlediv" title="<?php esc_attr_e( 'Click to toggle' ); ?>"><br /></div>
									<h3 class="hndle"><?php _e( 'Quick Post' ) ?></h3>
									<div class="inside"><?php

										if ( current_theme_supports( 'post-formats' ) ) :
											$post_formats = get_theme_support( 'post-formats' );
											if ( is_array( $post_formats[0] ) ) :
												if ( preg_match( "/youtube\.com/i", $url ) || preg_match( "/vimeo\.com/i", $url ) ) {
													$default_format = 'video';
												} elseif ( preg_match( "/soundcloud\.com/i", $url ) ) {
													$default_format = 'audio';
												} else {
													$default_format = 'link';
												}

												?><p>
													<label for="post_format"><?php _e( 'Post Format:' ); ?>
														<select name="post_format" id="post_format">
															<option value="0"><?php echo get_post_format_string( 'standard' ); ?></option>
															<?php foreach ( $post_formats[0] as $format ): ?>
																<?php if ( in_array( $format, array( 'link', 'video', 'standard', 'audio' ) ) ) : ?>
																	<option<?php selected( $default_format, $format ); ?> value="<?php echo esc_attr( $format ); ?>">
																		<?php echo esc_html( get_post_format_string( $format ) ); ?>
																	</option>
																<?php endif; ?>
															<?php endforeach; ?>
														</select>
													</label>
												</p><?php
											endif;
										endif;

										do_action( 'gmr_quickpost_submitbox_misc_actions', $post );

										?><p id="publishing-actions">
											<?php submit_button( __( 'Save Draft' ), 'button', 'draft', false, array( 'id' => 'save' ) ); ?>
											<?php
												if ( current_user_can( 'publish_posts' ) ) {
													submit_button( __( 'Publish' ), 'primary', 'publish', false );
												} else {
													echo '<br><br>';
													submit_button( __( 'Submit for Review' ), 'primary', 'review', false );
												}
											?>
											<span class="spinner" style="display: none;"></span>
										</p>
									</div>
								</div>

								<?php do_meta_boxes( 'quickpost', 'side', array(
									'post_id' => $post->ID,
									'url'     => $url,
								) ); ?>
							</div>
						</div>

						<div class="posting">
							<div id="wphead">
								<h1 id="site-heading">
									<a href="<?php echo home_url( '/' ); ?>" target="_blank">
										<span id="site-title"><?php bloginfo( 'name' ); ?></span>
									</a>
								</h1>
							</div>

							<div id="titlediv">
								<div class="titlewrap">
									<input name="title" id="title" class="text" type="text" value="<?php echo esc_attr( $title ); ?>">
								</div>
							</div>
							
							<div class="postdivrich"><?php
								$content = sprintf( "<a href='%s'>%s</a>.</p>", esc_url( $url ), esc_html( $title ) );
								
								wp_editor( $content, 'content', array( 'teeny' => true, 'textarea_rows' => '15' ) );
							?></div>
						</div>
					</div>
				</form>

				<?php $this->_do_footer_actions() ?>

				<script type="text/javascript">if (typeof wpOnload == 'function') wpOnload();</script>
			</body>
		</html><?php
		exit;
	}

	/**
	 * Saves new quick post.
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @return int The ID of the quick post.
	 */
	private function _save_quick_post() {
		$post = get_default_post_to_edit( 'post' );
		$post = get_object_vars( $post );
		$post_id = $post['ID'] = (int) $_POST['post_id'];

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			wp_die( __( 'You are not allowed to edit this post.' ) );
		}

		$post['post_category'] = isset( $_POST['post_category'] ) ? $_POST['post_category'] : '';
		$post['tax_input'] = isset( $_POST['tax_input'] ) ? $_POST['tax_input'] : '';
		$post['post_title'] = isset( $_POST['title'] ) ? $_POST['title'] : '';
		$content = isset( $_POST['content'] ) ? $_POST['content'] : '';

		// Set the post_content and status.
		$post['post_content'] = $content;
		if ( isset( $_POST['publish'] ) && current_user_can( 'publish_posts' ) ) {
			$post['post_status'] = 'publish';
		} elseif ( isset( $_POST['review'] ) ) {
			$post['post_status'] = 'pending';
		} else {
			$post['post_status'] = 'draft';
		}

		// Post formats.
		if ( isset( $_POST['post_format'] ) ) {
			if ( current_theme_supports( 'post-formats', $_POST['post_format'] ) ) {
				set_post_format( $post_id, $_POST['post_format'] );
			} elseif ( '0' == $_POST['post_format'] ) {
				set_post_format( $post_id, false );
			}
		}

		$post_id = wp_update_post( apply_filters( 'gmr_quickpost_post_data', $post ) );
		
		do_action( 'gmr_quickpost_post_created', $post_id );

		return $post_id;
	}

	/**
	 * Calls required header actions.
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function _do_header_actions() {
		/** This action is documented in wp-admin/admin-header.php */
		do_action( 'admin_enqueue_scripts', 'quickpost' );

		/** This action is documented in wp-admin/admin-header.php */
		do_action( 'admin_print_styles' );

		/** This action is documented in wp-admin/admin-header.php */
		do_action( 'admin_print_scripts' );

		/** This action is documented in wp-admin/admin-header.php */
		do_action( 'admin_head' );
	}

	/**
	 * Calls required footer actions.
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function _do_footer_actions() {
		/** This action is documented in wp-admin/admin-footer.php */
		do_action( 'admin_footer' );
		/** This action is documented in wp-admin/admin-footer.php */
		do_action( 'admin_print_footer_scripts' );
	}

}

// initialize class instance
GMR_QuickPost::get_intance();