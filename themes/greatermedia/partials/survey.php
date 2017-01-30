<?php

$contest_id = get_the_ID();

?><article id="post-<?php echo esc_attr( $contest_id ); ?>" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
	<section class="col__inner--left">
		<?php Greater_Media\Flexible_Feature_Images\feature_image_preference_is( $contest_id, 'top' ) && get_template_part( 'partials/feature-image-contest' ); ?>

		<header class="entry__header">
			<time class="entry__date" datetime="<?php echo get_the_time(); ?>"><?php the_date( 'F j, Y' ); ?></time>

			<h2 class="entry__title" itemprop="headline">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h2>

			<?php get_template_part( 'partials/social-share' ); ?>
		</header>

		<?php Greater_Media\Flexible_Feature_Images\feature_image_preference_is( $contest_id, 'inline' ) && get_template_part( 'partials/feature-image-contest' ); ?>

		<?php the_content(); ?>
		<?php get_template_part( 'partials/article', 'footer' ); ?>
	</section>

	<section class="col__inner--right">
		<section id="contest-form" class="contest__form"></section>
		<div class="desktop">
			<?php do_action( 'dfp_tag', 'dfp_ad_right_rail_pos1' ); ?>
		</div>
	</section>
</article>
