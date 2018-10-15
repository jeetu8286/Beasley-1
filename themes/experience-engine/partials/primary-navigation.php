<?php

if ( ! has_nav_menu( 'primary-nav' ) ) :
	return;
endif;

wp_nav_menu( array( 'theme_location' => 'primary-nav' ) );
