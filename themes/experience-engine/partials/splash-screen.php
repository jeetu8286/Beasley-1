<?php

$custom_logo_id = get_theme_mod( 'custom_logo' );

?><div id="splash-screen">
	<style>
		html, body {
			overflow: hidden;
		}

		#splash-screen {
			position: fixed;
			left: 0;
			top: 0;
			right: 0;
			bottom: 0;
			background-color: #fff;
			z-index: 999999;
		}

		.splash-screen-logo {
			position: absolute;
			left: 50%;
			top: 50%;
			width: 250px;
			height: 250px;
			transform: translate(-50%, -50%);
			background-repeat: no-repeat;
			background-position: center;
			background-size: contain;
			<?php if ( $custom_logo_id ) : ?>
			background-image: url(<?php echo esc_url( bbgi_get_image_url( $custom_logo_id, 250, 250, 'crop', true ) ); ?>);
			<?php endif; ?>
		}
	</style>
	<div class="splash-screen-logo"></div>
</div>
