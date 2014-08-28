<?php

class GreaterMediaUserGeneratedGallery extends GreaterMediaUserGeneratedContent {

	function __construct( $post_id ) {

		parent::__construct( $post_id );

	}

	/**
	 * Get gallery attachments for this post
	 *
	 * @return array
	 */
	protected function get_attachments() {

		$attachments = get_post_gallery_images( $this->post_id );

		return $attachments;

	}

	/**
	 * Render a representation of this post appropriate for displaying in the moderation queue
	 *
	 * @return string html
	 */
	public function render_moderation_row() {

		$attachments = $this->get_attachments();
		$html        = '';

		foreach ( $attachments as $attachment ) {
			$html .= '<img src="' .
				esc_attr( $attachment ) .
				'" class="ugc-moderation-gallery-thumb" />';
		}

		return $html;

	}

}