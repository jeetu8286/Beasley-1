<article id="post-<?php the_ID(); ?>" <?php post_class( 'gallery__featured--item' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

	<div class="gallery__featured--thumbnail">
		<a href="<?php the_permalink(); ?>">
			<?php if ( 'gmr_album' == get_post_type() ) { ?>
				<div class="gallery__grid--album"></div>
			<?php } ?>
			<?php if ( has_post_thumbnail() ) : ?>
				<div class='thumbnail' style='background-image: url(<?php gm_post_thumbnail_url( 'gmr-show-featured-primary' ); ?>)'></div>
			<?php else: ?>
				<div class='thumbnail thumbnail-placeholder' style=''></div>
			<?php endif; ?>
		</a>
	</div>

	<div class="gallery__featured--caption">

		<h3 class="gallery__featured--title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>

	</div>

</article>