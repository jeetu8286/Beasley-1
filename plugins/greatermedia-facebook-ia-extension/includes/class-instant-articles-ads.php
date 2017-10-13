<?php

use Facebook\InstantArticles\Elements\Ad;

/**
 * Support class for Ads
 *
 * @since 0.1.0
 *
 */
class Instant_Articles_Ads {

	/**
	 * Init the compat layer
	 *
	 */
	function setup() {
		add_action( 'instant_articles_after_transform_post', array( $this, 'end' ) );
	}

	/**
	 * Add Ads
	 */
	function end( $instant_article ) {
		$this->add_ads( $instant_article->instant_article );
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

		$ad = Ad::create()
			->enableDefaultForReuse()
			->withWidth( $width )
			->withHeight( $height );
		$network_id = trim( get_option( 'dfp_network_code' ) );
		$slot_code  = get_option( $slot );

		if ( ! $network_id || ! $slot_code ) {
			return;
		}

		$source = "http://pubads.g.doubleclick.net/gampad/adx?iu=/{$network_id}/{$slot_code}";

		$source = add_query_arg(
			array(
				'iu' => '/' . $network_id . '/' . $slot_code,
				'sz' => $width . 'x' . $height,
			),
		$source );

		$ad->withSource( $source );

		return $ad;
	}
}
