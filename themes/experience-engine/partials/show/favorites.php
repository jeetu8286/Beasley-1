<?php

$query = ee_get_show_favorites();
if ( $query->have_posts() ) :
	?><h3>Our Favorites</h3>

	<div class="archive-tiles">
		<?php ee_the_query_tiles( $query ); ?>
	</div><?php
endif;
