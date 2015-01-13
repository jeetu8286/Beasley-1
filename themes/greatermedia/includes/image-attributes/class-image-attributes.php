<?php
/**
 * This will add an additional field to the native WordPress media uploader.
 * This will allow the editor to add photo credits to each image.
 */

class GMRImageAttr {

	public static function init() {
		add_filter( 'attachment_fields_to_edit', array( __CLASS__, 'attachment_field_credit' ), 10, 2 );
		add_filter( 'attachment_fields_to_save', array( __CLASS__, 'attachment_field_credit_save' ), 10, 2 );
	}

	/**
	 * Add Image Attribution fields to media uploader
	 *
	 * @param $form_fields array, fields to include in attachment form
	 * @param $post object, attachment record in database
	 * @return $form_fields, modified form fields
	 */
	public static function attachment_field_credit( $form_fields, $post ) {
		$form_fields['gmr_image_attribution'] = array(
			'label' => 'Image Attribution',
			'input' => 'text',
			'value' => get_post_meta( $post->ID, 'gmr_image_attribution', true ),
			'helps' => 'If provided, image attribution will display',
		);

		return $form_fields;
	}


	/**
	 * Save values of Image Attribution in the media uploader
	 *
	 * @param $post array, the post data for database
	 * @param $attachment array, attachment fields from $_POST form
	 * @return $post array, modified post data
	 */

	public static function attachment_field_credit_save( $post, $attachment ) {
		if( isset( $attachment['gmr_image_attribution'] ) )
			update_post_meta( $post['ID'], 'gmr_image_attribution', $attachment['gmr_image_attribution'] );

		return $post;
	}

}

GMRImageAttr::init();
