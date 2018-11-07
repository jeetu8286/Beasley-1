<div id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
	<?php get_template_part( 'partials/tile/thumbnail' ); ?>
	<?php get_template_part( 'partials/tile/title' ); ?>

	<div><?php

		if ( ! ee_is_jacapps() ) :
			ee_the_latest_episode();
		endif;

		?><div>
			<?php echo esc_html( ee_get_episodes_count() ); ?> episodes
		</div>
	</div>
</div>
