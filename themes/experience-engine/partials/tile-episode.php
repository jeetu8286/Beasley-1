<div>
	<?php ee_the_episode_player(); ?>
	<div>
		<div>
			<span>[duration]</span>
			<span><?php ee_the_date(); ?></span>
		</div>
		<h3>
			<a href="<?php the_permalink(); ?>">
				<?php the_title(); ?>
			</a>
		</h3>
		<?php the_excerpt(); ?>
	</div>
</div>
