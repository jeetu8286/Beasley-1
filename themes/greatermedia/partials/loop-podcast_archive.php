<?php
	global $post;
	$args = array(
		'numberposts' => 500,
		'offset' => 0,
		'orderby' => 'post_date',
		'order' => 'DESC',
		'post_type' => GMP_CPT::EPISODE_POST_TYPE,
		'post_status' => 'publish',
		'post_parent' => $post->ID
	);

	$pattern = get_shortcode_regex();
	$episodes =  new WP_Query( $args );
	$recent_posts = $episodes->posts[0];
	$episode_date = strtotime( $recent_posts->post_date );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf podcast podcast__archive' ); ?> role="article" itemscope itemtype="http://schema.org/OnDemandEvent">
		<?php
		if ( preg_match_all( '/'. $pattern .'/s', $recent_posts->post_content, $matches )
			&& array_key_exists( 2, $matches )
			&& in_array( 'audio', $matches[2] ) ) :
			?>
			<?php echo do_shortcode( $matches[0][0] ); ?>
		<?php
		endif;
		?>
</article>