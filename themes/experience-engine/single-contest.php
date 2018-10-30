<?php get_header(); ?>

<?php the_post(); ?>

<div>
	<?php get_template_part( 'partials/show-block' ); ?>

	<?php get_template_part( 'partials/featured-media' ); ?>
	<h1><?php the_title(); ?></h1>

	<div>
		<?php the_content(); ?>
	</div>

	<?php if ( ( $contest_prize = trim( get_post_meta( get_the_ID(), 'prizes-desc', true ) ) ) ) : ?>
		<div>
			<h3>What you win:</h3>
			<?php echo wpautop( do_shortcode( $contest_prize ) ); ?>
		</div>
	<?php endif; ?>

	<?php if ( ( $enter = trim( get_post_meta( get_the_ID(), 'how-to-enter-desc', true ) ) ) ) : ?>
		<div>
			<h3>How to enter:</h3>
			<?php echo wpautop( do_shortcode( $enter ) ); ?>
		</div>
	<?php endif; ?>
</div>

<?php get_footer(); ?>
