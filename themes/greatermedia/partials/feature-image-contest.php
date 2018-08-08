<?php if ( has_post_thumbnail() ) : ?>
	<div class="contest__thumbnail">
		<img src="<?php gm_post_thumbnail_url( 'gmr-contest-thumbnail' ) ?>" alt="">
		<?php image_attribution(); ?>
	</div>
<?php endif; ?>
