<article id="post-<?php the_ID(); ?>" <?php post_class( 'gallery__grid--column' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

	<div class="gallery__grid--thumbnail">
		<a href="<?php the_permalink(); ?>">
			<?php if ( 'gmr_album' == get_post_type() ) { ?>
				<div class="gallery__grid--album"></div>
			<?php } ?>
			<?php the_post_thumbnail( 'gmr-gallery-grid-thumb' ); ?>
		</a>
	</div>

	<div class="gallery__grid--meta">
		<h3 class="gallery__grid--title">
			<a href="<?php the_permalink(); ?>">
				<?php
				$thetitle = explode( ' ', get_the_title() );
				$output = array_slice( $thetitle, 0, 15 );
				echo implode( ' ', $output );
				if( $thetitle > 15 ) {
					echo '...';
				}
				?>
			</a>
		</h3>
	</div>

</article>