<?php _wp_admin_html_begin(); ?>
		<title><?php _e('Quick Post') ?></title>

		<script type="text/javascript">
			//<![CDATA[
			addLoadEvent = function(func){if(typeof jQuery!="undefined")jQuery(document).ready(func);else if(typeof wpOnload!='function'){wpOnload=func;}else{var oldonload=wpOnload;wpOnload=function(){oldonload();func();}}};
			var ajaxurl = '<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>', pagenow = 'quickpost', isRtl = <?php echo (int) is_rtl(); ?>;
			var photostorage = false;
			//]]>
		</script>

		<?php gmr_qp_header_actions() ?>

		<script type="text/javascript">
			var wpActiveEditor = 'content';

			function insert_plain_editor(text) {
				if ( typeof(QTags) != 'undefined' )
					QTags.insertContent(text);
			}
			
			function set_editor(text) {
				if ( '' == text || '<p></p>' == text )
					text = '<p><br /></p>';

				if ( tinyMCE.activeEditor )
					tinyMCE.execCommand('mceSetContent', false, text);
			}

			function insert_editor(text) {
				if ( '' != text && tinyMCE.activeEditor && ! tinyMCE.activeEditor.isHidden()) {
					tinyMCE.execCommand('mceInsertContent', false, '<p>' + decodeURI(tinymce.DOM.decode(text)) + '</p>', {format : 'raw'});
				} else {
					insert_plain_editor(decodeURI(text));
				}
			}

			function append_editor(text) {
				if ( '' != text && tinyMCE.activeEditor && ! tinyMCE.activeEditor.isHidden()) {
					tinyMCE.execCommand('mceSetContent', false, tinyMCE.activeEditor.getContent({format : 'raw'}) + '<p>' + text + '</p>');
				} else {
					insert_plain_editor(text);
				}
			}

			function show(tab_name) {
				jQuery('#extra-fields').html('');
				switch(tab_name) {
					case 'video' :
						jQuery('#extra-fields').load('<?php echo admin_url( 'admin.php?action=gmr_qp_quickpost&ajax=video&s=' . urlencode( $selection ) ); ?>', function() {
							<?php
							$content = '';
							if ( preg_match("/youtube\.com\/watch/i", $url) ) {
								list($domain, $video_id) = explode("v=", $url);
								$video_id = esc_attr($video_id);
								$content = '<object width="425" height="350"><param name="movie" value="http://www.youtube.com/v/' . $video_id . '"></param><param name="wmode" value="transparent"></param><embed src="http://www.youtube.com/v/' . $video_id . '" type="application/x-shockwave-flash" wmode="transparent" width="425" height="350"></embed></object>';

							} elseif ( preg_match("/vimeo\.com\/[0-9]+/i", $url) ) {
								list($domain, $video_id) = explode(".com/", $url);
								$video_id = esc_attr($video_id);
								$content = '<object width="400" height="225"><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="movie" value="http://www.vimeo.com/moogaloop.swf?clip_id=' . $video_id . '&amp;server=www.vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1" />	<embed src="http://www.vimeo.com/moogaloop.swf?clip_id=' . $video_id . '&amp;server=www.vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="400" height="225"></embed></object>';

								if ( trim($selection) == '' )
									$selection = '<p><a href="http://www.vimeo.com/' . $video_id . '?pg=embed&sec=' . $video_id . '">' . $title . '</a> on <a href="http://vimeo.com?pg=embed&sec=' . $video_id . '">Vimeo</a></p>';

							} elseif ( strpos( $selection, '<object' ) !== false ) {
								$content = $selection;
							}
							?>
							jQuery('#embed-code').prepend('<?php echo htmlentities($content); ?>');
						});
						jQuery('#extra-fields').show();
						return false;
						break;
					case 'photo' :
						function setup_photo_actions() {
							jQuery('.close').click(function() {
								jQuery('#extra-fields').hide();
								jQuery('#extra-fields').html('');
							});
							jQuery('.refresh').click(function() {
								photostorage = false;
								show('photo');
							});
							jQuery('#photo-add-url').click(function(){
								var form = jQuery('#photo-add-url-div').clone();
								jQuery('#img_container').empty().append( form.show() );
							});
							jQuery('#waiting').hide();
							jQuery('#extra-fields').show();
						}

						jQuery('#waiting').show();
						if(photostorage == false) {
							jQuery.ajax({
								type: "GET",
								cache : false,
								url: "<?php echo esc_url( admin_url( 'admin.php' ) ); ?>",
								data: {
									action: 'gmr_qp_quickpost',
									ajax: 'photo_js',
									u: '<?php echo $url ?>'
								},
								dataType : "script",
								success : function(data) {
									eval(data);
									photostorage = jQuery('#extra-fields').html();
									setup_photo_actions();
								}
							});
						} else {
							jQuery('#extra-fields').html(photostorage);
							setup_photo_actions();
						}
						return false;
						break;
				}
			}

			jQuery(document).ready(function($) {
				var $contnet = $( '#content' );

				// Resize screen.
				window.resizeTo(760,580);

				// Set button actions.
				jQuery('#photo_button').click(function() { show('photo'); return false; });
				jQuery('#video_button').click(function() { show('video'); return false; });

				// Auto select.
				<?php if ( preg_match("/youtube\.com\/watch/i", $url) ) { ?>
					show('video');
				<?php } elseif ( preg_match("/vimeo\.com\/[0-9]+/i", $url) ) { ?>
					show('video');
				<?php } elseif ( preg_match("/flickr\.com/i", $url) ) { ?>
					show('photo');
				<?php } ?>
				jQuery('#title').unbind();
				jQuery('#publish, #save').click(function() { jQuery('.press-this #publishing-actions .spinner').css('display', 'inline-block'); });

				$('#tagsdiv-post_tag, #categorydiv').children('h3, .handlediv').click(function(){
					$(this).siblings('.inside').toggle();
				});

				if ( $( '#wp-content-wrap' ).hasClass( 'html-active' ) && window.switchEditors &&
					( tinyMCEPreInit.mceInit.content && tinyMCEPreInit.mceInit.content.wpautop ) ) {
					// The Text editor is default, run the initial content through pre_wpautop() to convert the paragraphs
					$contnet.text( window.switchEditors.pre_wpautop( $contnet.text() ) );
				}
			});
		</script>
	</head>
	<body class="press-this wp-admin wp-core-ui <?php echo $admin_body_class; ?>">
		<form action="<?php echo esc_url( admin_url( 'admin.php?action=gmr_qp_quickpost' ) ) ?>" method="post">
			<div id="poststuff" class="metabox-holder">

				<div id="side-sortables" class="press-this-sidebar">
					<div class="sleeve">
						<?php wp_nonce_field( 'quickpost' ) ?>
						<input type="hidden" name="post_type" id="post_type" value="text">
						<input type="hidden" name="autosave" id="autosave">
						<input type="hidden" id="original_post_status" name="original_post_status" value="draft">
						<input type="hidden" id="prev_status" name="prev_status" value="draft">
						<input type="hidden" id="post_id" name="post_id" value="<?php echo (int) $post_ID; ?>">

						<!-- This div holds the photo metadata -->
						<div class="photolist"></div>

						<div id="submitdiv" class="postbox">
							<div class="handlediv" title="<?php esc_attr_e( 'Click to toggle' ); ?>"><br /></div>
							<h3 class="hndle"><?php _e( 'Quick Post' ) ?></h3>
							<div class="inside">
								<p id="publishing-actions">
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
								</p><?php

								if ( current_theme_supports( 'post-formats' ) ) :
									$post_formats = get_theme_support( 'post-formats' );
									if ( is_array( $post_formats[0] ) ) :
										if ( ! empty( $selection ) ) {
											$default_format = '0';
										} elseif ( preg_match( "/youtube\.com\/watch/i", $url ) || preg_match( "/vimeo\.com\/[0-9]+/i", $url ) ) {
											$default_format = 'video';
										} elseif ( preg_match( "/flickr\.com/i", $url ) ) {
											$default_format = 'image';
										} else {
											$default_format = 'link';
										}

										?><p>
											<label for="post_format"><?php _e( 'Post Format:' ); ?>
												<select name="post_format" id="post_format">
													<option value="0"><?php echo get_post_format_string( 'standard' ); ?></option>
													<?php foreach ( $post_formats[0] as $format ): ?>
														<option<?php selected( $default_format, $format ); ?> value="<?php echo esc_attr( $format ); ?>">
															<?php echo esc_html( get_post_format_string( $format ) ); ?>
														</option>
													<?php endforeach; ?>
												</select>
											</label>
										</p><?php
									endif;
								endif;
							?></div>
						</div>

						<?php do_meta_boxes( 'quickpost', 'side', $post_ID ); ?>
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

					<?php if ( isset( $posted ) && intval( $posted ) ) : ?>
						<?php $post_ID = intval( $posted ); ?>
						<div id="message" class="updated">
							<p>
								<strong><?php _e( 'Your post has been saved.' ); ?></strong>
								<a onclick="window.opener.location.replace(this.href); window.close();" href="<?php echo get_permalink( $post_ID ); ?>"><?php _e( 'View post' ); ?></a>
								| <a href="<?php echo get_edit_post_link( $post_ID ); ?>" onclick="window.opener.location.replace(this.href); window.close();"><?php _e( 'Edit Post' ); ?></a>
								| <a href="#" onclick="window.close();"><?php _e( 'Close Window' ); ?></a>
							</p>
						</div>
					<?php endif; ?>

					<div id="titlediv">
						<div class="titlewrap">
							<input name="title" id="title" class="text" type="text" value="<?php echo esc_attr( $title ); ?>">
						</div>
					</div>

					<div id="waiting" style="display: none"><span class="spinner"></span> <span><?php esc_html_e( 'Loading&hellip;' ); ?></span></div>
					<div id="extra-fields" style="display: none"></div>

					<div class="postdivrich"><?php
						$content = $selection ? $selection : '';
						if ( $url ) {
							$content .= $selection ? '<p>' . __( 'via ' ) : '<p>';
							$content .= sprintf( "<a href='%s'>%s</a>.</p>", esc_url( $url ), esc_html( $title ) );
						}

						wp_editor( $content, 'content', array( 'teeny' => true, 'textarea_rows' => '15' ) );
					?></div>
				</div>
			</div>
		</form>

		<div id="photo-add-url-div" style="display:none;">
			<table>
				<tr>
					<td><label for="this_photo"><?php _e( 'URL' ) ?></label></td>
					<td><input type="text" id="this_photo" name="this_photo" class="tb_this_photo text" onkeypress="if(event.keyCode==13) image_selector(this);"></td>
				</tr>
				<tr>
					<td><label for="this_photo_description"><?php _e( 'Description' ) ?></label></td>
					<td><input type="text" id="this_photo_description" name="photo_description" class="tb_this_photo_description text" onkeypress="if(event.keyCode==13) image_selector(this);" value="<?php echo esc_attr( $title );?>"></td>
				</tr>
				<tr>
					<td><input type="button" class="button" onclick="image_selector(this)" value="<?php esc_attr_e( 'Insert Image' ); ?>"></td>
				</tr>
			</table>
		</div>

		<?php gmr_qp_footer_actions() ?>

		<script type="text/javascript">if(typeof wpOnload=='function')wpOnload();</script>
	</body>
</html>