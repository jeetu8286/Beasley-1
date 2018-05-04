<?php
/**
 * Plugin Name: Beasley Livestream Video
 * Description: Provides shortcode and oembed support for livestream videos
 * Author: 10up
 */

namespace Beasley\LivestreamVideo;

add_action( 'init', function() {
		wp_embed_register_handler( 'livestream-video', '#https?://livestream.com/accounts/([^/]+)/events/([^/]+)/videos/([^/]+)/?#i', __NAMESPACE__ . '\embed_handler' );
		add_shortcode( 'livestream_video', __NAMESPACE__ . '\shortcode_handler' );
});

function embed_handler( $matches ) {
		$account_id = $matches[1];
		$event_id = $matches[2];
		$video_id = $matches[3];

		return get_embed_code( $account_id, $event_id, $video_id );
}

function shortcode_handler( $atts ) {
		if ( ! isset( $atts['account_id'] ) || ! isset( $atts['event_id'] ) || ! isset( $atts['video_id'] ) ) {
				return '';
		}

		return get_embed_code( $atts['account_id'], $atts['event_id'], $atts['video_id'] );
}

function get_embed_code( $account_id, $event_id, $video_id ) {
		$embed_id = rand( 1, getrandmax() );

		ob_start();
		?>
		<div class="livestream livestream-oembed">
				<iframe
						id="ls_embed_<?php echo esc_attr( $embed_id ); ?>"
						src="//livestream.com/accounts/<?php echo esc_attr( $account_id ); ?>/events/<?php echo esc_attr( $event_id ); ?>/videos/<?php echo esc_attr( $video_id ); ?>/player?autoPlay=false&mute=false"
						frameborder="0" scrolling="no" allowfullscreen>
				</iframe>
				<script
						type="text/javascript"
						data-embed_id="ls_embed_<?php echo esc_attr( $embed_id ); ?>"
						src="//livestream.com/assets/plugins/referrer_tracking.js">
				</script>
</div>
		<?php
		return ob_get_clean();
}

