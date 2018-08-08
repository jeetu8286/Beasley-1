<?php

use Facebook\InstantArticles\Elements\Analytics;

/**
 * Support class for Ads
 *
 * @since 0.1.0
 *
 */
class Instant_Articles_Analytics {

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
		$this->add_analytics( $instant_article->instant_article );
	}

	/**
	 * @param $instant_article
	 */
	public function add_analytics( $instant_article ) {
		$analytics = apply_filters( 'fbia_analytics_makrup', '' );
		if ( ! empty( $analytics ) ) {
			$document   = new DOMDocument();
			$fragment   = $document->createDocumentFragment();
			$valid_html = @$fragment->appendXML( $analytics );
			if ( $valid_html ) {
				$instant_article->addChild( Analytics::create()->withHTML( $fragment ) );
			}
		}
	}
}
