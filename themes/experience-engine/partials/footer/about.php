<div>
	<h3>About</h3><?php

	if ( has_nav_menu( 'about-nav' ) ) :
		wp_nav_menu( array( 'theme_location' => 'about-nav' ) );
	endif;
?></div>
