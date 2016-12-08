<td class="ugc-moderation-content <?php echo $class; ?>">
	<ul class="ugc-moderation-meta">
		<?php if ( ! empty( $contest ) ) : ?>
			<li class="contest">
				<a href="<?php echo get_edit_post_link( $contest->ID ); ?>">
					<?php echo get_the_title( $contest->ID ); ?>
				</a>
			</li>
			<li>
				<a href="<?php echo admin_url( 'admin.php?page=gmr-contest-winner&contest_id=' . $contest->ID ); ?>">View All Entries</a>
			</li>
		<?php endif; ?>
		<li class="post-date"><?php echo get_the_date( 'm/d/Y', $post->ID ); ?></li>
		<li class="vote-count">
			<?php $vote_count = number_format( get_post_field( 'menu_order', $post->ID ), 0 );
			echo esc_html( $vote_count ); echo ( 1 == $vote_count )  ? ' Vote' : ' Votes'; ?>
		</li>
	</ul>
	<?php echo $preview; ?>
</td>