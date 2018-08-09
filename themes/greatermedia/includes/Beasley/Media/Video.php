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
	 * Renders embed code for livestream video.
	 *
	 * @access public
	 * @param string $account_id
	 * @param string $event_id
	 * @param string $video_id
	 * @return string
	 */
	public function get_embed_code( $account_id, $event_id, $video_id ) {
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

}
