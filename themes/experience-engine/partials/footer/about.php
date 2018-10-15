<?php

if ( ! has_nav_menu( 'about-nav' ) ) :
	return;
endif;

wp_nav_menu( array( 'theme_location' => 'about-nav' ) );