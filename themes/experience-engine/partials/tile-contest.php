<div id="post-<?php the_ID(); ?>" <?php post_class( 'contest-tile' ); ?>>
	<?php get_template_part( 'partials/tile/thumbnail' ); ?>
	<?php get_template_part( 'partials/tile/title' ); ?>
	<?php ee_the_contest_dates(); ?>
</div>
