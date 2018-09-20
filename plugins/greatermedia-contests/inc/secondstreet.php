<?php

// add action hooks
add_action( 'admin_head', 'gmr_setup_secondstreet_tinymce' );

/**
 * Registers secondstreet tinymce scripts.
 *
 * @global string $typenow
 */
function gmr_setup_secondstreet_tinymce() {
	global $typenow;

	if ( GMR_CONTEST_CPT == $typenow || GMR_SURVEY_CPT == $typenow ) {
		$post_type = get_post_type_object( $typenow );
		$rich_editing = filter_var( get_user_option( 'rich_editing' ), FILTER_VALIDATE_BOOLEAN );
		$can_edit_pages = current_user_can( $post_type->cap->edit_posts );
		if ( $rich_editing && $can_edit_pages ) {
			add_filter( 'mce_external_plugins', 'gmr_register_mce_plugins' );
			add_filter( 'mce_buttons', 'gmr_register_mce_buttons' );
		}
	}
}

/**
 * Adds secondstreet button.
 *
 * @param array $buttons
 * @return array
 */
function gmr_register_mce_buttons( $buttons ) {
	array_push( $buttons, 'secondstreet' );
	return $buttons;
}

/**
 * Adds secondstreet plugin for tinymce editor.
 *
 * @param array $plugin_array
 */
function gmr_register_mce_plugins( $plugin_array ) {
	$min = defined( 'SCRIPT_DEBUG' ) && filter_var( SCRIPT_DEBUG, FILTER_VALIDATE_BOOLEAN ) ? '' : '.min';
	$plugin_array['secondstreet'] = trailingslashit( GREATER_MEDIA_CONTESTS_URL ) . "js/contests-mce{$min}.js";

	return $plugin_array;
}
