<?php
/**
 * Partial for the Front Page Highlights - Community and Events
 *
 * @package Greater Media
 * @since   0.1.0
 */
?>

<section class="highlights">

	<div class="container">

		<div class="highlights__col">

			<div class="highlights__community">

				<h2 class="highlights__heading"><?php bloginfo( 'name' ); ?><?php _e( ' Community Highlights', 'greatermedia' ); ?><span class="highlights__heading--span"><?php _e( 'YouRock', 'greatermedia' ); ?></span></h2>

				<?php
				$hp_comm_query = \GreaterMedia\HomepageCuration\get_community_query();
				while( $hp_comm_query->have_posts() ) : $hp_comm_query->the_post(); ?>
					<div class="highlights__community--item">

						<div class="highlights__community--thumb">
							<?php the_post_thumbnail( array( 356, 356 ) ); // todo Image Size: 356x356 ?>
						</div>

						<h3 class="highlights__community--title"><?php the_title(); ?></h3>

					</div>
				<?php endwhile; ?>
				<?php wp_reset_query(); ?>
			</div>

			<div class="highlights__events">

				<h2 class="highlights__heading"><?php _e( 'Upcoming', 'greatermedia' ); ?><span class="highlights__heading--span"><?php _e( 'Events', 'greatermedia' ); ?></span></h2>

				<div class="highlights__event--item">

					<div class="highlights__event--thumb">
						<img src="http://placehold.it/156x156&text=image">
					</div>

					<div class="highlights__event--meta">
						<h3 class="highlights__event--title">Preston & Steve’s Camp Out For Hunger 2014</h3>
						<time datetime="<?php the_time( 'c' ); ?>" class="highlights__event--date">Dec 1 - Dec 5</time>
					</div>

				</div>

				<div class="highlights__event--item">

					<div class="highlights__event--thumb">
						<img src="http://placehold.it/156x156&text=image">
					</div>

					<div class="highlights__event--meta">
						<h3 class="highlights__event--title">A.B.A.T.E. Motorcycle Toy Run For The Kids!</h3>
						<time datetime="<?php the_time( 'c' ); ?>" class="highlights__event--date">Nov 2</time>
					</div>

				</div>

			</div>

		</div>

	</div>

</section>