// Required until this bug is fixed https://github.com/Automattic/Edit-Flow/issues/397

jQuery(document).ready(function ($) {
	var $input = $('#_ef_editorial_meta_checkbox_needs-photo');
	if( $input && $input.val() === '1') {
		$input.val(true);
	}
});

