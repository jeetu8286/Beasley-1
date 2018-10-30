<?php get_header(); ?>

<?php the_post(); ?>

<div>
	<?php get_template_part( 'partials/show-block' ); ?>

	<div>
		<?php ee_the_episode_player(); ?>
		<h1><?php the_title(); ?></h1>

		<div>
			<?php if ( ( $duration = ee_get_episode_meta( null, 'duration' ) ) ) : ?>
				<span style="margin-right: 5em"><?php echo esc_html( $duration ); ?></span>
			<?php endif; ?>

			<span><?php ee_the_date(); ?></span>

			<?php get_template_part( 'partials/share' ); ?>
		</div>
	</div>

	<div><?php
		add_filter( 'the_content', 'strip_shortcodes', 1 );
		the_content();
		remove_filter( 'the_content', 'strip_shortcodes', 1 );
	?></div>

	<?php get_template_part( 'partials/episode/next-episodes' ); ?>
	<?php get_template_part( 'partials/episode/podcasts' ); ?>
</div>

<?php get_footer(); ?>
