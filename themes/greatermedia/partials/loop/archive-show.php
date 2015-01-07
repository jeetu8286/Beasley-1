<?php  while ( have_posts() ) : the_post(); ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

		<header class="entry-header">

			<h2 class="entry-title" itemprop="headline"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

		</header>

		<div id="logo">

			<?php
				global $post;
				$logo_id = get_post_meta($post->ID, 'logo_image', true);
				if( $logo_id ) {
					$logo = get_post( $logo_id );
					echo '<img src="' . $logo->guid . '" />';
				} else {
					echo '<div>No Logo Image</div>';
				}
			?>

		</div>
		<hr>
		<div class="entry-content">
			<div>
			<p>Show Content:</p>
			<?php the_content(); ?>
			</div>
			<hr>
			<?php
			echo '<div>';
			if( get_post_meta($post->ID, 'show_homepage', true) ) {
				if( function_exists( 'TDS\get_related_term' ) ) {
					$term = TDS\get_related_term( $post->ID );
				}
				if( $term ) {
					echo 'Related term is: ' . $term->name
					. '<br/>Term ID: ' . $term->term_id
					. '<br/>Term Slug: ' . $term->slug;

				} else {
					echo 'No related term found.
					This is a bug, beacuse SHOW has marked to have homepage';
				}
			} else {
				echo 'Show doesn\'t have home page';
			}
			echo '</div>';
			?>
		</div>

	</article>

<?php endwhile; 