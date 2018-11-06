<div>
	<?php if ( has_custom_logo() ) : ?>
		<?php the_custom_logo(); ?>
	<?php endif; ?>

	<h3>Download our app</h3>
	<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt.</p>

	<?php if ( ee_has_publisher_information( 'itunes_app' ) ) : ?>
		<div>
			<a href="#">[download on the appstore image]</a>
		</div>
	<?php endif; ?>

	<?php if ( ee_has_publisher_information( 'play_app' ) ) : ?>
		<div>
			<a href="#">[get it on google play image]</a>
		</div>
	<?php endif; ?>
</div>
