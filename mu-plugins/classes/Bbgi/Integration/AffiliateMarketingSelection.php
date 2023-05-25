<?php
/**
 * Sets up settings page and shortcode for Second Street
 */

namespace Bbgi\Integration;

use Bbgi\Util;

class AffiliateMarketingSelection extends \Bbgi\Module {
	use Util;

	/**
	 * Registers this module.
	 *
	 * @access public
	 */
	public function register() {
		// add shortcodes
		add_shortcode( 'select-am', $this( 'render_shortcode' ) );
	}

	/**
	 * Renders select-am shortcode.
	 *
	 * @access public
	 * @param array $atts Array of shortcode arguments.
	 * @return string Shortcode markup.
	 */
	public function render_shortcode( $atts ) {
		$attributes = shortcode_atts( array(
			'am_id' => '',
			'syndication_name' => ''
		), $atts, 'select-am' );

		$post_object = get_queried_object();

		$am_id = $this->getObjectId( $post_object->post_type, "affiliate_marketing", $attributes['am_id'], $attributes['syndication_name'] );
		if( empty( $am_id ) ) {
			return;
		}
		
		$affiliatemarketing_post_object = $this->verify_post( $am_id, "affiliate_marketing", $attributes['syndication_name'] );
		if( empty( $affiliatemarketing_post_object ) ) {
			return;
		}


		$am_item_name 				=	(array) $this->get_post_metadata_from_post( 'am_item_name', $affiliatemarketing_post_object );
		$am_item_description 		=	(array) $this->get_post_metadata_from_post( 'am_item_description', $affiliatemarketing_post_object );
		$am_item_photo 				=	(array) $this->get_post_metadata_from_post( 'am_item_photo', $affiliatemarketing_post_object );
		$am_item_imagetype 			=	(array) $this->get_post_metadata_from_post( 'am_item_imagetype', $affiliatemarketing_post_object );
		$am_item_imagecode 			=	(array) $this->get_post_metadata_from_post( 'am_item_imagecode', $affiliatemarketing_post_object );
		$am_item_order 				=	(array) $this->get_post_metadata_from_post( 'am_item_order', $affiliatemarketing_post_object );
		$am_item_unique_order 		=	(array) $this->get_post_metadata_from_post( 'am_item_unique_order', $affiliatemarketing_post_object );
		$am_item_getitnowtext		=	(array) $this->get_post_metadata_from_post( 'am_item_getitnowtext', $affiliatemarketing_post_object );
		$am_item_buttontext 		=	(array) $this->get_post_metadata_from_post( 'am_item_buttontext', $affiliatemarketing_post_object );
		$am_item_buttonurl 			=	(array) $this->get_post_metadata_from_post( 'am_item_buttonurl', $affiliatemarketing_post_object );
		$am_item_getitnowfromname 	=	(array) $this->get_post_metadata_from_post( 'am_item_getitnowfromname', $affiliatemarketing_post_object );
		$am_item_getitnowfromurl 	=	(array) $this->get_post_metadata_from_post( 'am_item_getitnowfromurl', $affiliatemarketing_post_object );
		$am_item_type 				=	(array) $this->get_post_metadata_from_post( 'am_item_type', $affiliatemarketing_post_object );

		remove_filter( 'the_content', 'ee_add_ads_to_content', 100 );
		$content = apply_filters( 'bbgi_am_content', $affiliatemarketing_post_object, $am_item_name, $am_item_description, $am_item_photo, $am_item_imagetype, $am_item_imagecode, $am_item_order, $am_item_unique_order, $am_item_getitnowtext, $am_item_buttontext, $am_item_buttonurl, $am_item_getitnowfromname, $am_item_getitnowfromurl, $am_item_type, $post_object );
		add_filter( 'the_content', 'ee_add_ads_to_content', 100 );
		if ( ! empty( $content ) ) {
			$content_updated = "<h2 class=\"section-head\"><span>".$affiliatemarketing_post_object->post_title."</span></h2>";
			remove_filter( 'the_content', 'ee_add_ads_to_content', 100 );
			$the_content = apply_filters('the_content', $affiliatemarketing_post_object->post_content);
			add_filter( 'the_content', 'ee_add_ads_to_content', 100 );
			if ( !empty($the_content) ) {
				$content_updated .= "<div class=\"am-embed-description\">".$the_content."</div>";
			}
			$content_updated .= $this->stringify_selected_cpt($content, "MUSTHAVE");
			$content_updated .= "<p>&nbsp;</p><h6><em>Please note that items are in stock and prices are accurate at the time we published this list. Have an idea for a fun theme for a gift idea list you’d like us to create?&nbsp; Drop us a line at <a href=\"mailto:shopping@bbgi.com\" data-uri=\"98cfaf73989c872d3384892acc280543\">shopping@bbgi.com</a>.&nbsp;</em></h6>";
			return $content_updated;
		}

		return $content;
	}

	/**
	 * Verify post is valid or not
	 * @param $post
	 * @return Array
	 */
	public function verify_post( $post, $syndication_name ) {
		$ids = array();

		$post = get_post( $post );
		if( $post->post_type !== 'affiliate_marketing' || $post->post_name !== $syndication_name ) {
			return null;
		}

		if ( !empty( $post ) ) {
			return $post;
		}
		return null;
	}
}
