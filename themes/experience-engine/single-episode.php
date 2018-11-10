<?php get_header();

	// $show = ee_get_current_show();
	// if ( ! $show ) :
	// 	return;
	// endif;
?>

<?php the_post(); ?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php get_template_part( 'partials/show/header' ); ?>

	<div class="episode-info">
		<div class="show-meta">
			<p class="show"><?php echo esc_html( get_the_title( get_post_field( 'post_parent' ) ) ); ?></p>
			<button class="btn -empty -nobor -icon">
				<svg width="15" height="15" viewBox="0 0 15 15" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd" clip-rule="evenodd" d="M8.5 0H6.5V6.5H0V8.5H6.5V15H8.5V8.5H15V6.5H8.5V0Z" />
				</svg>
				Add podcast to my feed
			</button>
		</div>
		<div class="show-actions">
			<?php if ( ! ee_is_jacapps() ) :
				ee_the_episode_player();
			endif;

			?>
			<h1>
				<span><?php the_title(); ?></span>
				<?php if ( ee_is_jacapps() ) : ?>
					<a class="btn -empty" href="#">Download</a>
				<?php endif; ?>
			</h1>
		</div>

		<div class="episode-meta">
			<?php if ( ( $duration = ee_get_episode_meta( null, 'duration' ) ) ) : ?>
				<!-- @TODO :: Ensure this value is pulled from the meta -->
				<span class="duration"><?php echo esc_html( $duration ); ?></span>
			<?php endif; ?>

			<?php if ( ! ee_is_jacapps() ) :?>
				<a class="btn -empty -nobor" href="#">Download</a>
			<?php endif; ?>

			<span class="date"><?php ee_the_date(); ?></span>

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
