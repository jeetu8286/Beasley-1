<?php
/**
 * Partial for contest submissions.
 *
 * @package Greater Media
 * @since 0.1.0
 */

$submissions_query = apply_filters( 'gmr_contest_submissions_query', null );
if ( ! $submissions_query || ! $submissions_query->have_posts() ) {
	return;
}

?>

<section class="contest-submissions">
	<h4 class="contest-submissions--title">All Entries</h4>
	<ul class="contest-submissions--list">
		<?php while ( $submissions_query->have_posts() ) : ?>
			<?php $submissions_query->the_post(); ?>
			<li class="contest-submission">
				<a href="<?php the_permalink(); ?>">
					<?php echo wp_get_attachment_image( get_post_thumbnail_id() ); ?>
				</a>
			</li>
		<?php endwhile; ?>
	</ul>
</section>

<?php wp_reset_postdata(); ?>