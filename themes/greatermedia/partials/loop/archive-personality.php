<?php while ( have_posts() ) : the_post(); ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

		<h2>
			<span style="margin-right: 0.5em"><?php gmi_print_personality_photo( null, 50 ); ?></span>
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h2>

	</article>

<?php endwhile;