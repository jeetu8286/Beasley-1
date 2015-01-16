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
		
		<h3 class="title">Add new keyword</h3>
		
		<table class="form-table keywords">
			<tr>
				<th>Keyword</th>
				<td>
					<input type="text" id="keyword" name="keyword" size="25" class="regular-text" />
				</td>
			</tr>
			<tr>
				<th>Linked Post</th>
				<td>					
					<div class="post-search">
						<p>
							<label for="linked_content_search">Search for a post:</label> <br />
							<input type="text" id="linked_content_search" size="25" autocomplete="off" class="post-search__search-field regular-text" />
							<span class="spinner"></span>
						</p>
						
						<ul class="post-search__list regular-text"></ul>
						
						<script type="text/template" class="post-search__list-item-template">
							<li class="post-search__list-item">
								<input type="radio" name="linked_content" value="<%= id %>" id="linked_content_item_<%= id %>" />
								<label for="linked_content_item_<%= id %>"><%= title %></label>
							</li>
						</script>
						
					</div>
				</td>
			</tr>
		</table>
		
		<p class="submit">
			<input type="submit" value="Add" class="button button-primary"/>
			<input type="hidden" name="save_keyword_settings" value="Y" />			
		</p>
	</form>

</div>