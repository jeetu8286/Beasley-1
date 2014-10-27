<table class="form-table">
	<tr>
		<th scope="row">
			<label for="media_wall_id"><?php _e( 'Article ID', 'greatermedia-livefyre-media-wall' ); ?></label></th>
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
			<label for="media_wall_responsive"><?php _e( 'Display style', 'greatermedia-livefyre-media-wall' ); ?></label>
		</th>
		<td>
			<div id="media_wall_responsive">
				<div>
					<input type="radio" id="media_wall_responsive_min" name="media_wall_responsive" value="min-width" <?php checked( 'min-width', $media_wall_responsive, true ); ?> />
					At least
					<input type="number" id="media_wall_min_width" name="media_wall_min_width" value="<?php echo esc_attr( $media_wall_min_width ); ?>" size="25" />
					pixels wide
				</div>
				<div>
					<input type="radio" id="media_wall_responsive_cols" name="media_wall_responsive" value="columns" <?php checked( 'columns', $media_wall_responsive, true ); ?> />
					Show exactly
					<input type="range" id="media_wall_columns" min="1" max="5" step="1" name="media_wall_columns" value="<?php echo absint( $media_wall_columns ); ?>" />
					<span id="media_wall_columns_output"><?php echo absint( $media_wall_columns ); ?></span>
					columns
				</div>
			</div>
		</td>
	</tr>

	<tr>
		<th scope="row">
			<label for="media_wall_allow_modal"><?php _e( 'Open media into a modal?', 'greatermedia-livefyre-media-wall' ); ?></label>
		</th>
		<td>
			<input type="hidden" name="media_wall_allow_modal" value="no-modal" />
			<input type="checkbox" id="media_wall_allow_modal" name="media_wall_allow_modal" value="modal" <?php checked( 'modal', $media_wall_allow_modal, true ); ?> />
		</td>
	</tr>

</table>


