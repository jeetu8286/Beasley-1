<table>
	<tr>
		<th scope="row"><label for="media_wall_id"><?php _e( 'Article ID', 'greatermedia-livefyre-media-wall' ); ?></label></th>
		<td>
			<input type="text" id="media_wall_id" name="media_wall_id" value="<?php echo esc_attr( $media_wall_id ); ?>" size="25" />
		</td>
	</tr>

	<tr>
		<th scope="row">
			<label for="media_wall_initial"><?php _e( 'How many items to show initially', 'greatermedia-livefyre-media-wall' ); ?></label>
		</th>
		<td>
			<input type="number" id="media_wall_initial" name="media_wall_initial" value="<?php echo esc_attr( $media_wall_initial ); ?>" size="3" />
		</td>
	</tr>

	<tr>
		<th scope="row">
			<label for="media_wall_columns"><?php _e( 'Number of columns', 'greatermedia-livefyre-media-wall' ); ?></label>
		</th>
		<td>
			<input type="number" id="media_wall_columns" name="media_wall_columns" value="<?php echo absint( $media_wall_columns ); ?>" size="3" />
		</td>
	</tr>

	<tr>
		<th scope="row">
			<label for="media_wall_allow_modal"><?php _e( 'Open media into a modal?', 'greatermedia-livefyre-media-wall' ); ?></label>
		</th>
		<td>
			<input type="hidden" name="media_wall_allow_modal" value="no-modal" />
			<input type="checkbox" id="media_wall_allow_modal" name="media_wall_allow_modal" value="modal" <?php checked('modal', $media_wall_allow_modal, true); ?> />
		</td>
	</tr>

</table>


