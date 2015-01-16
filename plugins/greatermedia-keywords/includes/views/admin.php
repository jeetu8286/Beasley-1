<?php
/**
 * Created by Eduard
 * Date: 20.11.2014
 */

$options = self::get_keyword_options( $this::$plugin_slug . '_option_name' );
?>
<div class="wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<table class="form-table keywords">
		<tr>
			<td>
				<h3>Keyword</h3>
			</td>
			<td>
				<h3>Linked Content</h3>
			</td>
			<td></td>
		</tr>
		<?php
		if( !empty( $options ) ):
			foreach ( $options as $key => $value ): ?>
				<tr>
					<td><?php echo esc_html( $value['keyword'] ); ?></td>
					<td><?php echo esc_html( get_the_title( $value['post_id'] ) ); ?></td>
					<td><a data-postid="<?php echo esc_attr( $value['post_id'] ); ?>" class="submitdelete" href="#">delete</a></td>
				</tr>
		<?php endforeach;
		endif;
		?>
	</table>

	<form method="post" action="">
		<?php wp_nonce_field( 'save_new_keyword', 'save_new_keyword' ); ?>
		<table class="form-table keywords">
			<tr>
				<td><h3>Add new keyword</h3></td>
			</tr>
			<tr>
				<td>Keyword</td>
				<td>Linked Content</td>
				<td></td>
			</tr>
			<tr>
				<td>
					<input type="text" id="keyword" name="keyword" size="25" />
				</td>
				<td>
					<div>
						<label for="linked_content_search">Search for a post</label>
						<input type="text" id="linked_content_search" size="25" autocomplete="off" />
						<ul id="linked_content_item_container"></ul>
					</div>

					<script type="text/template" id="linked_content_item_template">
						<li>
							<input type="radio" name="linked_content" value="<%= id %>" id="linked_content_item_<%= id %>">
							<label for="linked_content_item_<%= id %>"><%= title %></label>
						</li>
					</script>
				</td>
				<td>
					<input type="submit" value="Add" class="button"/>
				</td>
			</tr>
		</table>
		<input type="hidden" name="save_keyword_settings" value="Y" />
	</form>

</div>