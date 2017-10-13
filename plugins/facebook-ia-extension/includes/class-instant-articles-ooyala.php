<?php

use Facebook\InstantArticles\Elements\Ad;

/**
 * Support class for Ooyala
 *
 * @since 0.1.0
 *
 */
class Instant_Articles_Ooyala {

	/**
	 * Init the compat layer
	 *
	 */
	function setup() {
		add_action( 'instant_articles_before_transform_post', array( $this, 'start' ) );
		add_action( 'instant_articles_after_transform_post', array( $this, 'end' ) );
		add_filter( 'instant_articles_transformer_rules_loaded', array(
			'Instant_Articles_Ooyala',
			'transformer_loaded'
		) );
	}

	/**
	 * Hook required action / filter before transform post start
	 * facebook generates article and store in transient on save_posts
	 * so normal is_feed condition will not work
	 */
	function start() {
		add_filter( 'ooyala_video_responsive_player_shortcode', array( $this, 'ooyola_fbia_markup' ), 10, 3 );
	}

	/**
	 * remove action/filter after facebook content transform
	 */
	function end( $instant_article ) {
		$this->add_ads( $instant_article->instant_article );
		remove_filter( 'ooyala_video_responsive_player_shortcode', array( $this, 'ooyola_fbia_markup' ), 10, 3 );
	}

	public function add_ads( $instant_article ) {
		$header = $instant_article->getHeader();

		$ad1 = $this->get_ad_object( 'dfp_ad_incontent_pos1' );
		if ( $ad1 ) {
			$header->addAd( $ad1 );
		}

		$ad2 = $this->get_ad_object( 'dfp_ad_incontent_pos2' );
		if ( $ad2 ) {
			$header->addAd( $ad2 );
		}

		$instant_article->enableAutomaticAdPlacement();
	}

	function get_ad_object( $slot, $width = 300, $height = 250 ) {
		$document = new DOMDocument();
		$fragment = $document->createDocumentFragment();
		$ad       = Ad::create()
		              ->enableDefaultForReuse()
		              ->withWidth( $width )
		              ->withHeight( $height );

		$valid_html = @$fragment->appendXML( $this->get_ad_code( $slot ) );


		if ( $valid_html ) {
			$ad->withHTML(
				$fragment
			);

			return $ad;
		}

		return false;
	}

	function get_ad_code( $slot ) {
		ob_start();
		greatermedia_dfp_footer();
		greatermedia_display_dfp_slot( $slot, false, array(), true, 'ad__in-content ad__in-content--mobile' );
		return ob_get_clean();
	}

	public static function transformer_loaded( $transformer ) {
		// Appends more rules to transformer
		$file_path     = GM_FBIA_URL . 'rules/ooyala-rules-configuration.json';
		$configuration = file_get_contents( $file_path );

		$transformer->loadRules( $configuration );

		return $transformer;
	}

	/**
	 * Change markup for ooyala player as per http://help.ooyala.com/video-platform/concepts/pbv4_facebook_instant_articles.html
	 *
	 * @param $output
	 * @param $attr
	 * @param $shortcode_atts
	 *
	 * @return string
	 */
	public function ooyola_fbia_markup( $output, $attr, $shortcode_atts ) {
		if ( class_exists( 'Ooyala' ) ) {
			$ooyala = Ooyala::instance();

			$settings = $ooyala->get_settings();
			// fill in defaults saved in user settings
			if ( empty( $shortcode_atts['player_id'] ) && ! empty( $settings['player_id'] ) ) {
				$shortcode_atts['player_id'] = $settings['player_id'];
			}
			$shortcode_atts = shortcode_atts( apply_filters( 'ooyala_default_query_args', $ooyala->playerDefaults ), $shortcode_atts );

			$pcode = ! empty( $shortcode_atts['pcode'] ) ? $shortcode_atts['pcode'] : substr( $settings['api_key'], 0, strpos( $settings['api_key'], '.' ) );

			$url    = add_query_arg( array(
				'ec'    => $shortcode_atts['code'],
				'pbid'  => $shortcode_atts['player_id'],
				'pcode' => $pcode,
			), 'https://player.ooyala.com/static/v4/production/skin-plugin/iframe.html' );
			$output = '<iframe width="480" height="320" src="' . $url . '" frameborder="0" allowfullscreen></iframe>';

			return $output;
		}

		return $output;

	}
}
