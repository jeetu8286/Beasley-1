<?php get_header(); ?>

<?php the_post(); ?>

<?php get_template_part( 'partials/show-block' ); ?>
<?php get_template_part( 'partials/podcast-information' ); ?>

<div>
	<?php if ( ( $feed_url = ee_get_podcast_meta( null, 'feed_url' ) ) ) : ?>
		<a href="<?php echo esc_url( $feed_url ); ?>" target="_blank" rel="noopener noreferrer">
			Podcast Feed
		</a>
	<?php endif; ?>

	<?php if ( ( $itunes_url = ee_get_podcast_meta( null, 'itunes_url' ) ) ) : ?>
		<a href="<?php echo esc_url( $itunes_url ); ?>" target="_blank" rel="noopener noreferrer">
			Subscribe in iTunes
		</a>
	<?php endif; ?>

	<?php if ( ( $google_play_url = ee_get_podcast_meta( null, 'google_play_url' ) ) ) : ?>
		<a href="<?php echo esc_url( $google_play_url ); ?>" target="_blank" rel="noopener noreferrer">
			Subscribe in Google Play
		</a>
	<?php endif; ?>

	<?php get_template_part( 'partials/add-to-favorite' ); ?>
</div>

<div>
	<?php $query = ee_get_episodes_query( null, 'paged=' . get_query_var( 'paged' ) ); ?>
	<?php if ( $query->have_posts() ) : ?>
		<?php ee_the_query_tiles( $query ); ?>
		<?php ee_load_more( $query ); ?>
	<?php endif; ?>
</div>

<?php get_footer(); ?>
