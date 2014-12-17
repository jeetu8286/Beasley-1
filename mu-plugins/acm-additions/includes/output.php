<?php

namespace GreaterMedia\AdCodeManager;

add_action( 'wp_head', __NAMESPACE__ . '\wp_head' );
add_filter( 'acm_output_html', __NAMESPACE__ . '\render_tag', 15, 2 );

function wp_head() {
	// todo output any JS needed for rendering ads
}

function render_tag( $output_html, $tag_id ) {
	ob_start();

	?>
	<pre><img src="http://placehold.it/300x250"/></pre>
	<?php

	$output = ob_get_clean();

	return $output;
}
