<?php

$query = ee_get_show_query();
if ( $query->have_posts() ) :
	?><h3>Recent</h3>

	<div class="archive-tiles">
		<?php ee_the_query_tiles( $query ); ?>
	</div><?php

	ee_load_more( $query );
endif;
