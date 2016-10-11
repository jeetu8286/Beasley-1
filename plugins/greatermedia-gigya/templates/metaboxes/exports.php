<?php

$imports = get_post_meta( $this->data->post_id, 'import' );
if ( ! empty( $imports ) ) :
	$settings = get_option( 'member_query_settings' );
	$settings = json_decode( $settings, true );

	$baseurl = sprintf(
		'https://%s:%s@api.e2ma.net/%s/members/imports/',
		esc_url( $settings['emma_public_key'] ),
		esc_url( $settings['emma_private_key'] ),
		esc_url( $settings['emma_account_id'] )
	);

	$offset = get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;

	usort( $imports, function( $a, $b ) {
		if ( $a['timestamp'] == $b['timestamp'] ) {
			return 0;
		}

		return $a['timestamp'] < $b['timestamp'] ? -1 : 1;
	} );

	?><div class="preview-member-query-results" style="overflow-y:auto;max-height:400px">
		<ul class="member-query-results collection-list">
			<?php foreach ( $imports as $import ) : ?>
				<li>
					<span class="preview-result-name">
						<a href="<?php echo esc_attr( $baseurl . $import['id'] ); ?>" target="_blank">
							#<?php echo esc_html( $import['id'] ); ?>
						</a>
					</span>
					<span class="preview-result-email">
						<?php echo esc_html( date( 'M d, g:i a', $import['timestamp'] + $offset ) ); ?>
					</span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div><?php
else :
	?><p class="count-status">No exports were found...</p><?php
endif;