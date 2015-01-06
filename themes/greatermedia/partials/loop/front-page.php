<?php while ( have_posts() ) : the_post();

	$post = get_post();
	$post_classes = array( 'entry2' );
	if ( ! empty( trim( $post->post_excerpt ) ) ) {
		$post_classes[] = 'has-excerpt';
	}

	if ( has_post_thumbnail() || 'tribe_events' == $post->post_type ) {
		$post_classes[] = 'has-thumbnail';
	}

	?>

	<article id="post-<?php the_ID(); ?>" <?php post_class( $post_classes ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

		<?php if ( has_post_thumbnail() || 'tribe_events' == $post->post_type ) : ?>
			<section class="entry2__thumbnail <?php // greatermedia_post_formats(); ?>">
				<a href="<?php the_permalink(); ?>">
					<?php
					if ( has_post_format( 'audio' ) ) {
						the_post_thumbnail( 'gm-entry-thumbnail-1-1' );
					} else {
						the_post_thumbnail( 'gm-entry-thumbnail-4-3' );
					}
					?>

					<?php if ( 'tribe_events' == $post->post_type): ?>
						<div class='entry2__thumbnail--event-date'>
							<div class='entry2__thumbnail--day-of-week'><?php echo tribe_get_start_date( get_the_ID(), false, 'l' ); ?></div>
							<div class='entry2__thumbnail--month-and-day'><?php echo tribe_get_start_date( get_the_ID(), false, 'M j' ); ?></div>
						</div>
					<?php endif; ?>
				</a>
			</section>
		<?php endif; ?>

		<section class="entry2__meta">
			<?php if ( 'tribe_events' != $post->post_type): ?>
				<time datetime="<?php the_time( 'c' ); ?>" class="entry2__date"><?php the_time( 'F j' ); ?></time>
			<?php endif; ?>
			<h2 class="entry2__title" itemprop="headline"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

			<?php if ( ! empty( trim( $post->post_excerpt ) ) ): ?>
				<div class="entry2__excerpt">
					<?php the_excerpt(); ?>
				</div>
			<?php endif; ?>

			<?php if ( 'tribe_events' == $post->post_type ): ?>
				<ul class="entry2__event--details">
					<li class="entry2__event--item"><?php echo tribe_get_start_time(); ?></li>
					<li class="entry2__event--item"><?php echo tribe_get_venue(); ?></li>
					<li class="entry2__event--item"><?php echo tribe_get_cost(); ?></li>
				</ul>
			<?php endif; ?>
		</section>

		<footer class="entry2__footer">
			<?php
			$category = get_the_category();

			if( isset( $category[0] ) ){
				echo '<a href="' . esc_url( get_category_link($category[0]->term_id ) ) . '" class="entry2__footer--category">' . esc_html( $category[0]->cat_name ) . '</a>';
			}
			?>
		</footer>
	</article>

<?php endwhile;