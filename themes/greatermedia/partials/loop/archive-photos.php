<?php while ( have_posts() ) : the_post(); ?>

	<div id="post-<?php the_ID(); ?>" class="gallery-grid__column" >

		<div class="gallery-grid__thumbnail">
			<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( array( 254, 186 ) ); // todo Image Size 254x186 ?></a>
		</div>

		<div class="gallery-grid__meta">
			<h3 class="gallery-grid__title">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h3>
		</div>

	</div>

<?php endwhile;