<span id="<?php echo esc_attr( 'login-restricted-post-' . $post->ID ); ?>" class="login-restricted-content" data-status="<?php echo esc_attr( $login_restriction ); ?>" data-postid="<?php echo esc_attr($post->ID); ?>"><?php echo $content; ?></span>
<span class="login-restricted-shield" data-postid="<?php echo esc_attr($post->ID); ?>" aria-role="hidden" style="display: none;">
	<div>This is what we're hiding the content with</div>
</span>