<ul>
	<?php foreach ( $entries as $entry ): ?>
		<?php $entry_ugc = GreaterMediaUserGeneratedContent::for_post_id( $entry->ID ); ?>
		<li><?php echo $entry_ugc->render_moderation_row(); ?></li>
	<?php endforeach; ?>
</ul>