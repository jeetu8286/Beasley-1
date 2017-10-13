<?php

use Facebook\InstantArticles\Elements\Footer;
use Facebook\InstantArticles\Elements\RelatedArticles;
use Facebook\InstantArticles\Elements\RelatedItem;

/**
 * Support class for Related
 *
 * @since 0.1.0
 *
 */

class Instant_Articles_Related {

	/**
	 * Init the compat layer
	 *
	 */
	function setup() {
		add_action( 'instant_articles_after_transform_post', array( $this, 'end' ) );
	}

	/**
	 * remove action/filter after facebook content transform
	 */
	function end( $instant_article ) {
		$this->add_related_articles( $instant_article->instant_article );
	}

	function add_related_articles( $instant_article ) {
		if ( function_exists( 'yarpp_get_related' ) ) {
			$all_related_articles = yarpp_get_related();
		}

		if ( ! empty( $all_related_articles ) ) {
			$i = 0;

			$related_articles = RelatedArticles::create();

			foreach ( $all_related_articles as $related_post ) {
				if ( 3 === $i ) {
					break;
				}

				$related_item = RelatedItem::create()
					->withURL( get_the_permalink( $related_post->ID ) )
					->disableSponsored();

				$related_articles->addRelated( $related_item );
			}

			$footer = Footer::create()
				->withRelatedArticles( $related_articles );

			$instant_article->withFooter( $footer );
		}
	}
}
