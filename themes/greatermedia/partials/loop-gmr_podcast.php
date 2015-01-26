<?php
	global $wp_query;
	$podcast_query = \GreaterMedia\Shows\get_show_podcast_query();
	while( $podcast_query->have_posts() ) : $podcast_query->the_post(); ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf podcast' ); ?> role="article" itemscope itemtype="http://schema.org/OnDemandEvent">
		<?php GMP_Player::render_podcast_episode(); ?>
	</article>
<?php
	endwhile;
	$wp_query = $podcast_query;
?>