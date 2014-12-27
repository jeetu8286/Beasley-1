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

	<script id="contest-submissions--tmpl" type="text/html">
		<div class="contest-submission--expander">
			<div class="contest-submission--expander-inner">
				<span class="contest-submission--close"></span>
				<div class="contest-submission--fullimg">
					<div class="contest-submission--loading"></div>
				</div>
				<div class="contest-submission--details">
					<h3>Veggies sunt bona vobis</h3>
					<p>Komatsuna prairie turnip wattle seed artichoke mustard horseradish taro rutabaga ricebean carrot black-eyed pea turnip greens beetroot yarrow watercress kombu.</p>
					<a href="http://cargocollective.com/jaimemartinez/">Visit website</a>
				</div>
			</div>
		</div>
	</script>

	<ul class="contest-submissions--list">
		<?php while ( $submissions_query->have_posts() ) : ?>
			<?php $submissions_query->the_post(); ?>
			<?php get_template_part( 'partials/contest', 'submission' ); ?>
		<?php endwhile; ?>
	</ul>

	<button type="button" class="contest-submissions--load-more">Load More</button>
</section>

<?php wp_reset_postdata(); ?>