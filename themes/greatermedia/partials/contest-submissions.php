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

<?php echo paginate_links( array(
	'base'    => str_replace( PHP_INT_MAX, '%#%', esc_url( get_pagenum_link( PHP_INT_MAX ) ) ),
	'format'  => '?paged=%#%',
	'current' => max( 1, $submissions_query->get( 'paged' ) ),
	'total'   => $submissions_query->max_num_pages,
) ); ?>

<?php wp_reset_postdata(); ?>