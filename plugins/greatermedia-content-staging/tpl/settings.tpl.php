<h3>Content Staging</h3>
<table id="menu" class="form-table">
	<tbody>
	<tr>
		<th scope="row">Content Staging Site</th>
		<td>
			<select name="gm_content_staging[staging_blog]">
				<?php foreach ( $all_sites as $site_index => $site ) : ?>
					<option value="<?php echo intval( $site['blog_id'] ); ?>" <?php selected( $staging_blog, $site['blog_id'], true ); ?>>
						<?php echo $site['domain']; ?>
					</option>
				<?php endforeach; ?>
			</select>
		</td>
	</tr>
	</tbody>
</table>