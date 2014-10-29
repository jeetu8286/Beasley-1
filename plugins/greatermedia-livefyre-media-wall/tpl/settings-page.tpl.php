<?php if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cannot access pages directly.' );
} ?>
<div class="wrap">
	<div id="icon-options-general" class="frmicon icon32"><br></div>
	<h2><?php _e( 'LiveFyre Media Walls', 'greatermedia-livefyre-media-wall' ); ?></h2>

	<form name="livefyre_media_walls" id="livefyre_media_walls" method="post" action="">
		<?php wp_nonce_field( 'livefyre_media_walls_save', 'livefyre_media_walls_save', true, true ); ?>
		<?php settings_fields( 'livefyre_media_walls') ?>
		<?php submit_button( __( 'Save Changes' ), 'primary', 'save', true ); ?>
	</form>
</div>