<?php
/**
 * Plugin Name: Beasley Livestream Video
 * Description: Provides shortcode and oembed support for livestream videos
 * Author: 10up
 */

namespace Beasley\LivestreamVideo;

add_action( 'init', function() {
	wp_embed_register_handler( 'livestream-video-id', '#https?://livestream.com/accounts/([^/]+)/events/([^/]+)/videos/([^/]+)/?#i', __NAMESPACE__ . '\account_id_embed_handler' );
	wp_embed_register_handler( 'livestream-video-name', '#https?://livestream.com/([^/]+)/events/([^/]+)/videos/([^/]+)/?#i', __NAMESPACE__ . '\account_name_embed_handler' );
	add_shortcode( 'livestream_video', __NAMESPACE__ . '\shortcode_handler' );
});

function get_account_id_from_name( $account_name ) {
	$mapping = array(
		'bbgi-philadelphia' => '27204544',
		'bbgi-boston' => '27204552',
		'bbgi-detroit' => '27204550',
		'bbgi-charlotte' => '27204562',
		'bbgi-fayetteville' => '27204582',
		'bbgi' => '27106536',
		'bbgi-nj' => '27204560',
		'bbgi-augusta' => '27204585',
		'bbgi-fort-myers' => '27204580',
		'bbgi-tampa' => '27204573',
		'bbgi-las-vegas' => '27204579',
		'bbgi-wilmington' => '27204589',
	);

	if ( isset( $mapping[ $account_name ] ) ) {
		return $mapping[ $account_name ];
	}

	return false;
}

function account_id_embed_handler( $matches ) {
	$account_id = $matches[1];
	$event_id = $matches[2];
	$video_id = $matches[3];

	return get_embed_code( $account_id, $event_id, $video_id );
}

function account_name_embed_handler( $matches ) {
	$account_id = get_account_id_from_name( $matches[1] );

	if ( ! $account_id ) {
		return '';
	}

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

