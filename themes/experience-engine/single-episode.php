<?php get_header(); ?>

<?php the_post(); ?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php get_template_part( 'partials/show-block' ); ?>

	<div><?php

		if ( ! ee_is_jacapps() ) :
			ee_the_episode_player();
		endif;

		?><h1><?php the_title(); ?></h1>

		<div>
			<?php if ( ( $duration = ee_get_episode_meta( null, 'duration' ) ) ) : ?>
				<span class="episode-duration"><?php echo esc_html( $duration ); ?></span>
			<?php endif; ?>

			<span><?php ee_the_date(); ?></span>

			<?php get_template_part( 'partials/share' ); ?>
		</div>
	</div>

	<div><?php

		if ( ! ee_is_jacapps() ) :
			add_filter( 'the_content', 'strip_shortcodes', 1 );
			the_content();
			remove_filter( 'the_content', 'strip_shortcodes', 1 );
		else :
			the_content();
		endif;

	?></div>

	<?php get_template_part( 'partials/episode/next-episodes' ); ?>
	<?php get_template_part( 'partials/episode/podcasts' ); ?>
</div>

<?php get_footer(); ?>
