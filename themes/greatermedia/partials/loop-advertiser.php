<?php

	$advertiser_link = get_post_meta( get_the_ID(), 'advertiser_link', true );

?>

<article class="advertiser cf">
	<?php if ( has_post_thumbnail() ) { ?>

		<div class="advertiser__logo">

			<a href="<?php echo esc_url( $advertiser_link ); ?>"><?php the_post_thumbnail( 'gmr-advertiser' ); ?></a>

		</div>

	<?php } else { ?>

		<div class="advertiser__name">

			<a href="<?php echo esc_url( $advertiser_link ); ?>"><?php the_title(); ?></a>

		</div>

	<?php } ?>
</article>