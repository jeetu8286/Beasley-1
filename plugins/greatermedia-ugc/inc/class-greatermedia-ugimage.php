<?php

class GreaterMediaUserGeneratedImage extends GreaterMediaUserGeneratedContent {

	function __construct( $post_id ) {

		parent::__construct( $post_id );

	}

	public static function first_image( $post_content ) {
		$matches = array();
		$output = preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post_content, $matches );
		if ( isset( $matches[1][0] ) ) {
			$first_img = $matches[1][0];
		}

		if ( empty( $first_img ) ) {
			return '';
		}

		return $first_img;
	}

	/**
	 * Render a representation of this post appropriate for displaying in the moderation queue
	 *
	 * @return string html
	 */
	public function render_moderation_row() {

//		$attachments = $this->get_attachments();
		$html        = '';

//		foreach ( $attachments as $attachment ) {
//			$html .= '<img src="' .
//				esc_attr( $attachment ) .
//				'" class="ugc-moderation-gallery-thumb" />';
//		}

		return $html;

	}

}