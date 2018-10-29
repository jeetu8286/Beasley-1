<div>
	<div ></div>
	<h3>
		<a href="<?php the_permalink(); ?>">
			<?php the_title(); ?>
		</a>
	</h3>
	<div>
		<span>
			<?php echo esc_html( ee_get_episodes_count() ); ?> episodes
		</span>
	</div>
</div>
