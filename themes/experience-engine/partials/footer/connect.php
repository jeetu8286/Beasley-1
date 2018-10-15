<?php

$has_menu = has_nav_menu( 'connect-nav' );
$has_youtube = bbgi_has_publisher_information( 'youtube' );
$has_twitter = bbgi_has_publisher_information( 'twitter' );
$has_instagram = bbgi_has_publisher_information( 'instagram' );
$has_facebook = bbgi_has_publisher_information( 'facebook' );

if ( $has_menu || $has_facebook || $has_youtube || $has_twitter || $has_instagram ) :
	?><div>
		<?php if ( $has_menu ) : ?>
			<div>
				<?php wp_nav_menu( array( 'theme_location' => 'connect-nav' ) ); ?>
			</div>
		<?php endif; ?>

		<?php if ( $has_facebook || $has_youtube || $has_twitter || $has_instagram ) : ?>
			<div>
				<?php if ( $has_facebook ) : ?>
					<a href="#">Facebook</a>
				<?php endif; ?>

				<?php if ( $has_twitter ) : ?>
					<a href="#">Twitter</a>
				<?php endif; ?>

				<?php if ( $has_instagram ) : ?>
					<a href="#">Instagram</a>
				<?php endif; ?>

				<?php if ( $has_youtube ) : ?>
					<a href="#">Youtube</a>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div><?php
endif;
