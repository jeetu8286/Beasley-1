<div id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting"><?php

	if ( ! ee_is_jacapps() ) :
		ee_the_episode_player();
	endif;

	?><div>
		<div>
			<?php if ( ( $duration = ee_get_episode_meta( null, 'duration' ) ) ) : ?>
				<span class="episode-duration"><?php echo esc_html( $duration ); ?></span>
			<?php endif; ?>

			<span><?php ee_the_date(); ?></span>
		</div>

		<?php get_template_part( 'partials/tile/title' ); ?>
	</div>
</div>
