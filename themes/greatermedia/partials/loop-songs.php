<?php

$current_date = null;
while( have_posts() ) :
	the_post();

	$date = get_the_time( 'M j' );
	if ( $current_date != $date ) :
		if ( ! is_null( $current_date ) ) :
				?></ul>
			</div><?php
		endif;

		?><div class="songs__group">
			<div class="songs__group--date">
				<?php echo esc_html( $date ); ?>
			</div>
			<ul class="songs__group--list"><?php

		$current_date = $date;
	endif;

	$artist = get_post_meta( get_the_ID(), 'artist', true );
	$link = get_post_meta( get_the_ID(), 'purchase_link', true );
	
	?><li class="song__item">
		<span class="song__time">
			<?php echo get_the_time( 'h:i A' ); ?>
		</span>

		<?php if ( filter_var( $link, FILTER_VALIDATE_URL ) ) : ?>
			<a href="<?php echo esc_url( $link ); ?>">
				<span class="song__title"><?php the_title(); ?></span>
			</a>
		<?php else : ?>
			<span class="song__title"><?php the_title(); ?></span>
		<?php endif; ?>

		<?php if ( ! empty( $artist ) ) : ?>
			<span class="song__artist">
				&#8212; <?php echo esc_html( $artist ); ?>
			</span>
		<?php endif; ?>
	</li><?php
endwhile;

// close opened .songs__group
	?></ul>
</div>
