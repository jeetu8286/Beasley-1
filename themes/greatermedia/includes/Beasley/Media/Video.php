<?php

namespace Beasley\Media;

class Video extends \Beasley\Module {

	protected static $_accounts = array(
		'bbgi-philadelphia' => '27204544',
		'bbgi-boston'       => '27204552',
		'bbgi-detroit'      => '27204550',
		'bbgi-charlotte'    => '27204562',
		'bbgi-fayetteville' => '27204582',
		'bbgi'              => '27106536',
		'bbgi-nj'           => '27204560',
		'bbgi-augusta'      => '27204585',
		'bbgi-fort-myers'   => '27204580',
		'bbgi-tampa'        => '27204573',
		'bbgi-las-vegas'    => '27204579',
		'bbgi-wilmington'   => '27204589',
	);

	/**
	 * Registers current module.
	 *
	 * @access public
	 */
	public function register() {
		add_action( 'init', $this( 'setup_embeds' ) );
		add_action( 'init', $this( 'setup_shortcodes' ) );
		add_action( 'beasley-register-settings', $this( 'register_settings' ), 10, 2 );
	}

	/**
	 * Registers embeds for livestream videos.
	 *
	 * @access public
	 * @action init
	 */
	public function setup_embeds() {
		wp_embed_register_handler(
			'livestream-video-id',
			'#https?://livestream.com/accounts/([^/]+)/events/([^/]+)/videos/([^/]+)/?#i',
			$this( 'account_id_embed_handler' )
		);

		wp_embed_register_handler(
			'livestream-video-name',
			'#https?://livestream.com/([^/]+)/events/([^/]+)/videos/([^/]+)/?#i',
			$this( 'account_name_embed_handler' )
		);
	}

	/**
	 * Registers shortcodes for livestream videos.
	 *
	 * @access public
	 * @action init
	 */
	public function setup_shortcodes() {
		add_shortcode( 'livestream_video', $this( 'shortcode_handler' ) );
	}

	/**
	 * Registers Livestream video settings.
	 *
	 * @access public
	 * @action beasley-register-settings
	 * @param string $group
	 * @param string $page
	 */
	public function register_settings( $group, $page ) {
		$section_id = 'beasley_livestream_settings';

		add_settings_section( $section_id, 'Livestream', '__return_false', $page );
		add_settings_field( 'livestream_secret_key', 'Secret Key', 'beasley_input_field', $page, $section_id, 'name=livestream_secret_key' );
		register_setting( $group, 'livestream_secret_key', 'sanitize_text_field' );
	}

	/**
	 * Renders embed code for livestream video URL with account id.
	 *
	 * @access public
	 * @param array $matches
	 * @return string
	 */
	public function account_id_embed_handler( $matches ) {
		$account_id = $matches[1];
		$event_id = $matches[2];
		$video_id = $matches[3];

		return $this->get_embed_code( $account_id, $event_id, $video_id );
	}

	/**
	 * Renders embed code for livestream video URL with account name.
	 *
	 * @access public
	 * @param array $matches
	 * @return string
	 */
	public function account_name_embed_handler( $matches ) {
		$account_id = isset( self::$_accounts[ $matches[1] ] )
			? self::$_accounts[ $matches[1] ]
			: false;

		if ( ! $account_id ) {
			return '';
		}

		$event_id = $matches[2];
		$video_id = $matches[3];

		return $this->get_embed_code( $account_id, $event_id, $video_id );
	}

	/**
	 * Renders embed code for livestream video shortcode.
	 *
	 * @access public
	 * @param array $atts
	 * @return string
	 */
	public function shortcode_handler( $atts ) {
		if ( empty( $atts['account_id'] ) || empty( $atts['event_id'] ) || empty( $atts['video_id'] ) ) {
			return '';
		}

		return $this->get_embed_code( $atts['account_id'], $atts['event_id'], $atts['video_id'] );
	}

	/**
	 * Returns embed code for a Livestream video.
	 *
	 * @access public
	 * @param string $account_id
	 * @param string $event_id
	 * @param string $video_id
	 * @return string
	 */
	public function get_embed_code( $account_id, $event_id, $video_id ) {
		$key = get_option( 'livestream_secret_key' );
		return ! empty( $key )
			? $this->_get_videojs_embed( $key, $account_id, $event_id, $video_id )
			: $this->_get_iframe_embed( $account_id, $event_id, $video_id );
	}

	/**
	 * Returns fallback iframe embed when Livestream secret key is not provided.
	 *
	 * @access protected
	 * @param string $account_id
	 * @param string $event_id
	 * @param string $video_id
	 * @return string
	 */
	protected function _get_iframe_embed( $account_id, $event_id, $video_id ) {
		$embed_id = rand( 1, getrandmax() );

		ob_start();

		?><div class="livestream livestream-oembed">
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
		</div><?php

		return ob_get_clean();
	}

	/**
	 * Returns embed code to use with videojs.
	 *
	 * @access protected
	 * @param string $key
	 * @param string $account_id
	 * @param string $event_id
	 * @param string $video_id
	 * @return string
	 */
	protected function _get_videojs_embed( $key, $account_id, $event_id, $video_id ) {
		$json = \Beasley\Cache::get( func_get_args(), function() use ( $key, $account_id, $event_id, $video_id ) {
			$response = wp_remote_get( "https://{$key}:@livestreamapis.com/v3/accounts/{$account_id}/events/{$event_id}/videos/{$video_id}" );

			return ! is_wp_error( $response )
				? wp_remote_retrieve_body( $response )
				: '';
		}, DAY_IN_SECONDS );

		return ! empty( $json )
			? sprintf( '<div class="livestream-video-player" data-json="%s"></div>', esc_attr( $json ) )
			: '';
	}

}
