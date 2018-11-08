<?php

$query = ee_get_show_featured();
if ( $query->have_posts() ) :
	?><h3>Featured</h3>

	<div class="archive-tiles">
		<?php ee_the_query_tiles( $query ); ?>
	</div><?php
endif;
