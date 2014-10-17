<?php
/*
Plugin Name: Greater Media QuickPost
Description: QuickPost functionality gives access to an extremely rapid and no-frills way to create new posts formatted as text snippets, links, embedded video, images, or calls to action.
Version: 1.0
Author: 10up
Author URI: http://10up.com/
*/

define( 'GREATER_MEDIA_POST_TYPE_QUICK_POST', 'gmr-quick-post' );

add_action( 'init', 'gmr_qp_register_post_type' );
add_action( 'tool_box', 'gmr_qp_tool_box' );
add_action( 'admin_action_gmr_qp_quickpost', 'gmr_qp_quickpost' );

function gmr_qp_register_post_type() {
	register_post_type( GREATER_MEDIA_POST_TYPE_QUICK_POST, array(
		'public'        => false,
		'show_ui'       => true,
		'rewrite'       => false,
		'menu_position' => 5,
		'label'         => 'Quick Posts',
		'supports'      => array( 'title', 'editor', 'thumbnail', 'post-formats' ),
	) );
}

function gmr_qp_tool_box() {
	if ( ! current_user_can( 'edit_posts' ) )  {
		return;
	}

	$link = admin_url( 'admin.php?action=gmr_qp_quickpost' );
	$link = str_replace( array( "\r", "\n", "\t" ), '', "
			javascript:
			var d=document,
			w=window,
			e=w.getSelection,
			k=d.getSelection,
			x=d.selection,
			s=(e?e():(k)?k():(x?x.createRange().text:0)),
			f='{$link}',
			l=d.location,
			e=encodeURIComponent,
			u=f+'&u='+e(l.href)+'&t='+e(d.title)+'&s='+e(s)+'&v=4';
			a=function(){if(!w.open(u,'t','toolbar=0,resizable=1,scrollbars=1,status=1,width=720,height=570'))l.href=u;};
			if (/Firefox/.test(navigator.userAgent)) setTimeout(a, 0); else a();
			void(0)
	" );

	?><div class="tool-box">
		<h3 class="title">Quick Post</h3>
		
		<p>Quick Post is a bookmarklet: a little app that runs in your browser and lets you grab bits of the web.</p>

		<p class="description">
			Use Quick Post to clip text, images and videos from any web page. Then edit and add more straight from Quick Post before you save or publish it in a post on your site.
			Drag-and-drop the following link to your bookmarks bar or right click it and add it to your favorites for a posting shortcut.
		</p>
		
		<p class="pressthis">
			<a onclick="return false;" oncontextmenu="if(window.navigator.userAgent.indexOf('WebKit')!=-1||window.navigator.userAgent.indexOf('MSIE')!=-1){jQuery('.quickpost-code').show().find('textarea').focus().select();return false;}" href="<?php echo esc_attr( $link ); ?>">
				<span>Quick Post</span>
			</a>
		</p>

		<div class="quickpost-code" style="display:none;">
			<p class="description">If your bookmarks toolbar is hidden: copy the code below, open your Bookmarks manager, create new bookmark, type Quick Post into the name field and paste the code into the URL field.</p>
			<p><textarea rows="5" cols="120" readonly="readonly"><?php echo esc_textarea( $link ); ?></textarea></p>
		</div>
	</div><?php
}

function gmr_qp_get_images_from_uri( $uri ) {
	$uri = preg_replace( '/\/#.+?$/', '', $uri );
	if ( preg_match( '/\.(jpe?g|jpe|gif|png)\b/i', $uri ) && !strpos( $uri, 'blogger.com' ) ) {
		return "'" . esc_attr( html_entity_decode( $uri ) ) . "'";
	}

	$content = wp_remote_fopen( $uri );
	if ( false === $content ) {
		return '';
	}

	$host = parse_url( $uri );
	$pattern = '/<img ([^>]*)src=(\"|\')([^<>\'\"]+)(\2)([^>]*)\/*>/i';
	$content = str_replace( array( "\n", "\t", "\r" ), '', $content );
	preg_match_all( $pattern, $content, $matches );
	if ( empty( $matches[0] ) ) {
		return '';
	}

	$sources = array();
	foreach ( $matches[3] as $src ) {
		// If no http in URL.
		if ( strpos( $src, 'http' ) === false ) {
			// If it doesn't have a relative URI.
			if ( strpos( $src, '../' ) === false && strpos( $src, './' ) === false && strpos( $src, '/' ) === 0 ) {
					$src = 'http://' . str_replace( '//', '/', $host['host'] . '/' . $src );
			} else {
					$src = 'http://' . str_replace( '//', '/', $host['host'] . '/' . dirname( $host['path'] ) . '/' . $src );
			}
		}
		
		$sources[] = esc_url( $src );
	}
	
	return "'" . implode( "','", $sources ) . "'";
}

function gmr_qp_quickpost() {
	if ( ! current_user_can( 'edit_posts' ) || ! current_user_can( get_post_type_object( 'post' )->cap->create_posts ) ) {
		wp_die( __( 'Cheatin&#8217; uh?' ) );
	}

	define( 'IFRAME_REQUEST' , true );
	header( sprintf( 'Content-Type: %s; charset=%s', get_option( 'html_type' ), get_option( 'blog_charset' ) ) );

	// Set Variables
	$url = isset( $_GET['u'] ) ? esc_url( $_GET['u'] ) : '';
	$title = isset( $_GET['t'] ) ? trim( strip_tags( html_entity_decode( wp_unslash( $_GET['t'] ), ENT_QUOTES ) ) ) : '';
	$image = isset( $_GET['i'] ) ? $_GET['i'] : '';

	$selection = '';
	if ( ! empty( $_GET['s'] ) ) {
		$selection = str_replace( '&apos;', "'", wp_unslash( $_GET['s'] ) );
		$selection = trim( htmlspecialchars( html_entity_decode( $selection, ENT_QUOTES ) ) );
	}

	if ( ! empty( $selection ) ) {
		$selection = preg_replace( '/(\r?\n|\r)/', '</p><p>', $selection );
		$selection = '<p>' . str_replace( '<p></p>', '', $selection ) . '</p>';
	}

	// For submitted posts.
	if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
		check_admin_referer( 'quickpost' );
		$posted = $post_ID = gmr_qp_quickpost_it();
	} else {
		$post = get_default_post_to_edit( GREATER_MEDIA_POST_TYPE_QUICK_POST, true );
		$post_ID = $post->ID;
	}

	wp_enqueue_style( 'colors' );
	wp_enqueue_script( 'post' );
	add_thickbox();
	
	$admin_body_class = ( is_rtl() ) ? 'rtl' : '';
	$admin_body_class .= ' locale-' . sanitize_html_class( strtolower( str_replace( '_', '-', get_locale() ) ) );

	gmr_qp_ajax_request( $title, $selection, $image, $url );

	remove_action( 'media_buttons', 'media_buttons' );
	add_action( 'media_buttons', 'gmr_qp_media_buttons' );

	include_once 'quickpost-popup.php';
	exit;
}

function gmr_qp_ajax_request( $title, $selection, $image, $url ) {
	if ( empty( $_REQUEST['ajax'] ) ) {
		return;
	}

	switch ( $_REQUEST['ajax'] ) {
		case 'video':
			?><script type="text/javascript">/* <![CDATA[ */
				jQuery('.select').click(function() {
					append_editor(jQuery('#embed-code').val());
					jQuery('#extra-fields').hide();
					jQuery('#extra-fields').html('');
				});
				jQuery('.close').click(function() {
					jQuery('#extra-fields').hide();
					jQuery('#extra-fields').html('');
				});
			/* ]]> */</script>
			<div class="postbox">
				<h2><label for="embed-code"><?php _e( 'Embed Code' ) ?></label></h2>
				<div class="inside">
					<textarea name="embed-code" id="embed-code" rows="8" cols="40"><?php echo esc_textarea( $selection ); ?></textarea>
					<p id="options"><a href="#" class="select button"><?php _e( 'Insert Video' ); ?></a> <a href="#" class="close button"><?php _e( 'Cancel' ); ?></a></p>
				</div>
			</div><?php
			break;

		case 'photo_thickbox':
			?><script type="text/javascript">/* <![CDATA[ */
				jQuery('.cancel').click(function() {
					tb_remove();
				});
				jQuery('.select').click(function() {
					image_selector(this);
				});
			/* ]]> */</script>
			<h3 class="tb"><label for="tb_this_photo_description"><?php _e( 'Description' ) ?></label></h3>
			<div class="titlediv">
				<div class="titlewrap">
					<input id="tb_this_photo_description" name="photo_description" class="tb_this_photo_description tbtitle text" type="text" onkeypress="if(event.keyCode==13) image_selector(this);" value="<?php echo esc_attr( $title ); ?>"/>
				</div>
			</div>

			<p class="centered">
				<input type="hidden" name="this_photo" value="<?php echo esc_attr( $image ); ?>" id="tb_this_photo" class="tb_this_photo" />
				<a href="#" class="select">
					<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( __( 'Click to insert.' ) ); ?>" title="<?php echo esc_attr( __( 'Click to insert.' ) ); ?>" />
				</a>
			</p>

			<p id="options"><a href="#" class="select button"><?php _e( 'Insert Image' ); ?></a> <a href="#" class="cancel button"><?php _e( 'Cancel' ); ?></a></p><?php
			break;

		case 'photo_images':
			$url = wp_kses( urldecode( $url ), null );
			echo 'new Array(' . gmr_qp_get_images_from_uri( $url ) . ')';
			break;

		case 'photo_js':
			?>// Gather images and load some default JS.
			var last = null
			var img, img_tag, aspect, w, h, skip, i, strtoappend = "";
			if(photostorage == false) {
			var my_src = eval(
				jQuery.ajax({
					type: "GET",
					url: "<?php echo esc_url( admin_url( 'admin.php' ) ); ?>",
					cache : false,
					async : false,
					data: "action=gmr_qp_quickpost&ajax=photo_images&u=<?php echo urlencode( $url ); ?>",
					dataType : "script"
				}).responseText
			);
			if(my_src.length == 0) {
				var my_src = eval(
					jQuery.ajax({
						type: "GET",
						url: "<?php echo esc_url( admin_url( 'admin.php' ) ); ?>",
						cache : false,
						async : false,
						data: "actin=gmr_qp_quickpost&ajax=photo_images&u=<?php echo urlencode( $url ); ?>",
						dataType : "script"
					}).responseText
				);
				if(my_src.length == 0) {
					strtoappend = '<?php _e( 'Unable to retrieve images or no images on page.' ); ?>';
				}
			}
			}
			for (i = 0; i < my_src.length; i++) {
				img = new Image();
				img.src = my_src[i];
				img_attr = 'id="img' + i + '"';
				skip = false;

				maybeappend = '<a href="?ajax=photo_thickbox&amp;i=' + encodeURIComponent(img.src) + '&amp;u=<?php echo urlencode( $url ); ?>&amp;height=400&amp;width=500" title="" class="thickbox"><img src="' + img.src + '" ' + img_attr + '/></a>';

				if (img.width && img.height) {
					if (img.width >= 30 && img.height >= 30) {
						aspect = img.width / img.height;
						scale = (aspect > 1) ? (71 / img.width) : (71 / img.height);

						w = img.width;
						h = img.height;

						if (scale < 1) {
							w = parseInt(img.width * scale);
							h = parseInt(img.height * scale);
						}
						img_attr += ' style="width: ' + w + 'px; height: ' + h + 'px;"';
						strtoappend += maybeappend;
					}
				} else {
					strtoappend += maybeappend;
				}
			}

			function pick(img, desc) {
				if (img) {
					if('object' == typeof jQuery('.photolist input') && jQuery('.photolist input').length != 0) length = jQuery('.photolist input').length;
					if(length == 0) length = 1;
					jQuery('.photolist').append('<input name="photo_src[' + length + ']" value="' + img +'" type="hidden"/>');
					jQuery('.photolist').append('<input name="photo_description[' + length + ']" value="' + desc +'" type="hidden"/>');
					insert_editor( "\n\n" + encodeURI('<p style="text-align: center;"><a href="<?php echo $url; ?>"><img src="' + img +'" alt="' + desc + '" /></a></p>'));
				}
				return false;
			}

			function image_selector(el) {
				var desc, src, parent = jQuery(el).closest('#photo-add-url-div');

				if ( parent.length ) {
					desc = parent.find('input.tb_this_photo_description').val() || '';
					src = parent.find('input.tb_this_photo').val() || ''
				} else {
					desc = jQuery('#tb_this_photo_description').val() || '';
					src = jQuery('#tb_this_photo').val() || ''
				}

				tb_remove();
				pick(src, desc);
				jQuery('#extra-fields').hide();
				jQuery('#extra-fields').html('');
				return false;
			}

			jQuery('#extra-fields').html('<div class="postbox"><h2><?php _e( 'Add Photos' ); ?> <small id="photo_directions">(<?php _e( "click images to select" ) ?>)</small></h2><ul class="actions"><li><a href="#" id="photo-add-url" class="button button-small"><?php _e( "Add from URL" ) ?> +</a></li></ul><div class="inside"><div class="titlewrap"><div id="img_container"></div></div><p id="options"><a href="#" class="close button"><?php _e( 'Cancel' ); ?></a><a href="#" class="refresh button"><?php _e( 'Refresh' ); ?></a></p></div>');
			jQuery('#img_container').html(strtoappend);<?php

			break;
	}
	
	exit;
}

function gmr_qp_quickpost_it() {
	$post = get_default_post_to_edit( GREATER_MEDIA_POST_TYPE_QUICK_POST );
	$post = get_object_vars( $post );
	$post_ID = $post['ID'] = (int) $_POST['post_id'];

	if ( ! current_user_can( 'edit_post', $post_ID ) ) {
		wp_die( __( 'You are not allowed to edit this post.' ) );
	}

	$post['post_category'] = isset( $_POST['post_category'] ) ? $_POST['post_category'] : '';
	$post['tax_input'] = isset( $_POST['tax_input'] ) ? $_POST['tax_input'] : '';
	$post['post_title'] = isset( $_POST['title'] ) ? $_POST['title'] : '';
	$content = isset( $_POST['content'] ) ? $_POST['content'] : '';

	$upload = false;
	if ( ! empty( $_POST['photo_src'] ) && current_user_can( 'upload_files' ) ) {
		foreach ( (array) $_POST['photo_src'] as $key => $image ) {
			// See if files exist in content - we don't want to upload non-used selected files.
			if ( strpos( $_POST['content'], htmlspecialchars( $image ) ) !== false ) {
				$desc = isset( $_POST['photo_description'][$key] ) ? $_POST['photo_description'][$key] : '';
				$upload = media_sideload_image( $image, $post_ID, $desc );

				// Replace the POSTED content <img> with correct uploaded ones. Regex contains fix for Magic Quotes
				if ( !is_wp_error( $upload ) ) {
					$content = preg_replace( '/<img ([^>]*)src=\\\?(\"|\')' . preg_quote( htmlspecialchars( $image ), '/' ) . '\\\?(\2)([^>\/]*)\/*>/is', $upload, $content );
				}
			}
		}
	}
	// Set the post_content and status.
	$post['post_content'] = $content;
	if ( isset( $_POST['publish'] ) && current_user_can( 'publish_posts' ) ) {
		$post['post_status'] = 'publish';
	} elseif ( isset( $_POST['review'] ) ) {
		$post['post_status'] = 'pending';
	} else {
		$post['post_status'] = 'draft';
	}

	// Error handling for media_sideload.
	if ( is_wp_error( $upload ) ) {
		wp_delete_post( $post_ID );
		wp_die( $upload );
	} else {
		// Post formats.
		if ( isset( $_POST['post_format'] ) ) {
			if ( current_theme_supports( 'post-formats', $_POST['post_format'] ) ) {
				set_post_format( $post_ID, $_POST['post_format'] );
			} elseif ( '0' == $_POST['post_format'] ) {
				set_post_format( $post_ID, false );
			}
		}

		$post_ID = wp_update_post( $post );
	}

	return $post_ID;
}

function gmr_qp_header_actions() {
	/** This action is documented in wp-admin/admin-header.php */
	do_action( 'admin_enqueue_scripts', 'quickpost' );

	/** This action is documented in wp-admin/admin-header.php */
	do_action( 'admin_print_styles' );

	/** This action is documented in wp-admin/admin-header.php */
	do_action( 'admin_print_scripts' );

	/** This action is documented in wp-admin/admin-header.php */
	do_action( 'admin_head' );
}

function gmr_qp_footer_actions() {
	/** This action is documented in wp-admin/admin-footer.php */
	do_action( 'admin_footer' );
	/** This action is documented in wp-admin/admin-footer.php */
	do_action( 'admin_print_footer_scripts' );
}

function gmr_qp_media_buttons() {
	_e( 'Add:' );

	if ( current_user_can('upload_files') ) {
		?>
		<a id="photo_button" title="<?php esc_attr_e('Insert an Image'); ?>" href="#">
		<img alt="<?php esc_attr_e('Insert an Image'); ?>" src="<?php echo esc_url( admin_url( 'images/media-button-image.gif?ver=20100531' ) ); ?>"/></a>
		<?php
	}
	?>
	<a id="video_button" title="<?php esc_attr_e('Embed a Video'); ?>" href="#"><img alt="<?php esc_attr_e('Embed a Video'); ?>" src="<?php echo esc_url( admin_url( 'images/media-button-video.gif?ver=20100531' ) ); ?>"/></a>
	<?php
}