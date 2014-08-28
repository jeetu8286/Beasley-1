<?php if ( ! defined( 'ABSPATH' ) ) die( 'Cannot access pages directly.' ); ?>
<?php $emails = get_option( 'optus-emails', array() ); ?>
<div class="wrap">
	<div id="icon-options-general" class="frmicon icon32"><br></div>
	<h2><?php _e( 'Listener Submission Moderation', 'greatermedia_ugc' ); ?></h2>

	<table class="wp-list-table widefat fixed posts listener-submissions">
		<?php $wp_list_table->display_rows(); ?>
	</table>
</div>