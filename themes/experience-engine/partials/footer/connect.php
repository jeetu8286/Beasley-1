<div>
	<h3>Connect</h3><?php

	if ( has_nav_menu( 'connect-nav' ) ) :
		wp_nav_menu( array( 'theme_location' => 'connect-nav' ) );
	endif;

	?><div>
		<?php if ( ee_has_publisher_information( 'facebook' ) ) : ?>
			<a href="#">Facebook</a>
		<?php endif; ?>

		<?php if ( ee_has_publisher_information( 'twitter' ) ) : ?>
			<a href="#">Twitter</a>
		<?php endif; ?>

		<?php if ( ee_has_publisher_information( 'instagram' ) ) : ?>
			<a href="#">Instagram</a>
		<?php endif; ?>

		<?php if ( ee_has_publisher_information( 'youtube' ) ) : ?>
			<a href="#">Youtube</a>
		<?php endif; ?>
	</div>
</div>
