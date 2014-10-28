jQuery(function () {
	jQuery('body').on('change', '#media_wall_columns', function () {
		jQuery('#media_wall_columns_output').text(this.value);
	});
});