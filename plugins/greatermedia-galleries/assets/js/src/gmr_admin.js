(function ($) {
	$(document).ready(function () {
		var tmpl = $('#gallery-item-tmpl').html(),
			$add_button = $('.add-gallery-item'),
			uploader;
		
		if ($.fn.sortable) {
			$('.gallery-preview')
				.sortable({
					items: '> .gallery-image',
					containment: 'parent',
					cursor: 'move',
					distance: 20,
					placeholder: 'gallery-item gallery-item-placeholder'
				})
				.disableSelection();
		}

		uploader = wp.media({
			title: 'Add image to the gallery',
			multiple: true,
			library: {
				type: 'image'
			},
			button: {
				text: 'Select Image'
			}
		});

		uploader.on( 'select', function() {
			var selection = uploader.state().get('selection');

			if (selection) {
				selection.each(function(attachment) {
					var attributes = attachment.attributes,
						sizes = attributes.sizes,
						size = sizes.medium || sizes.full || sizes.thumbnail,
						title = attributes.caption || attributes.title,
						new_item;

					new_item = tmpl
						.replace('%id%', attributes.id)
						.replace('%title%', encodeURIComponent(title))
						.replace('%image%', size.url);

					$(new_item).insertBefore($add_button.parent());
				});
			}
		});

		$add_button.click(function() {
			uploader.open();
			return false;
		});

		$(document).on('click', '.remove-gallery-item', function() {
			$(this).parent().remove();
		});
	});
})(jQuery);