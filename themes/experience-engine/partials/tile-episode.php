<div>
	<?php ee_the_episode_player(); ?>
	<div>
		<div>
			<?php if ( ( $duration = ee_get_episode_meta( null, 'duration' ) ) ) : ?>
				<span style="margin-right: 5em"><?php echo esc_html( $duration ); ?></span>
			<?php endif; ?>

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
