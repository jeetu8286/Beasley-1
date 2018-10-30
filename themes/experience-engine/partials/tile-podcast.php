<div <?php post_class(); ?>>
	<div style="width: 300px">
		<?php ee_the_lazy_thumbnail(); ?>
	</div>
	<h3>
		<a href="<?php the_permalink(); ?>">
			<?php the_title(); ?>
		</a>
	</h3>
	<div>
		<?php ee_the_latest_episode(); ?>
		<div>
			<?php echo esc_html( ee_get_episodes_count() ); ?> episodes
		</div>
	</div>
</div>
