<ul>
	<?php foreach ( $entries as $entry ): ?>
		<?php $entry_obj = GreaterMediaContestEntry::for_post_id($entry->ID); ?>
		<li class="contest-entry-preview"><?php echo $entry_obj->render_preview(); ?></li>
	<?php endforeach; ?>
</ul>