<?php
	global $post;
	$current_page = get_query_var( 'post_parent' ) ?: 1;
	$args = array(
			'post_type' => GMP_CPT::EPISODE_POST_TYPE,
			'post_parent' => $post->ID,
			'paged' => $current_page,
		);

	$query = new WP_Query( $args );

	while( $query->have_posts() ) : $query->the_post(); ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf episode' ); ?> role="article" itemscope itemtype="http://schema.org/OnDemandEvent">
		<?php GMP_Player::render_podcast_episode(); ?>
	</article>
	<?php
	endwhile;

	greatermedia_load_more_button( array( 'page_link_template' => home_url( 'episode/post_parent/%d/' ),  'partial_slug' => 'partials/loop-gmr_podcast_episode', 'auto_load' => false, 'query' => $query ) );
	wp_reset_query();
	?>