<?php get_header() ?>

<div>
	<?php while ( have_posts() ) : the_post(); ?>
		<div>
			<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'thumbnail' ); ?></a>
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</div>
	<?php endwhile; ?>

	<div>
		<?php previous_posts_link(); ?>
		<?php next_posts_link(); ?>
	</div>
</div>

<?php get_footer() ?>
