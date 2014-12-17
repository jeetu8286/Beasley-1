<span id="<?php echo esc_attr( 'login-restricted-post-' . $post->ID ); ?>" class="login-restricted-content login-restricted-<?php echo esc_attr( $login_restriction ); ?>"><?php echo $content; ?></span>
<span class="login-restricted-shield" id="login-restricted-shield-<?php echo esc_attr($post->ID); ?>">
	<div>Please <a href="<?php echo esc_url($login_url); ?>"><?php _e('Log in'); ?></a> to view this content</div>
</span>