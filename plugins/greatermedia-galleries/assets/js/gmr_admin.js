(function ($) {
	var $document = $(document);

	$document.ready(function () {
		var tmpl = $('#gallery-item-tmpl').html(),
			$add_button = $('.add-gallery-item'),
			uploader, setDirty;

		setDirty = function() {
			var editor = typeof tinymce !== 'undefined' && tinymce.get('content');

			if (editor) {
				editor.isNotDirty = false;
			}
		};

		if ($.fn.sortable) {
			$('.gallery-preview')
				.sortable({
					items: '> .gallery-image',
					containment: 'parent',
					cursor: 'move',
					distance: 20,
					placeholder: 'gallery-item gallery-item-placeholder',
					stop: setDirty
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

		uploader.on('select', function() {
			var selection = uploader.state().get('selection');

			if (selection) {
				selection.each(function(attachment) {
					var attributes = attachment.attributes,
						sizes = attributes.sizes,
						size = sizes.medium || sizes.full || sizes.thumbnail,
						new_item;

					new_item = tmpl
						.replace('%id%', attributes.id)
						.replace('%image%', size.url);

					$(new_item).insertBefore($add_button.parent());
				});
			}
		});

		$add_button.click(function() {
			setDirty();
			uploader.open();

			return false;
		});

		$document.on('click', '.remove-gallery-item', function() {
			setDirty();
			$(this).parent().remove();
		});

		$document.on('click', '.gallery-preview .gallery-item', function() {
			var $this = $(this);
			var selected = $this.find('input').val();

			var frame = wp.media({
				title: 'Change gallery image',
				button: {
					text: 'Select Image'
				},
				states: [
					new wp.media.controller.Library({
						title: 'Change gallery image',
						multiple: false,
						selection: 'single',
						content: 'browse',
					})
				]
			});

			frame.on('open', function() {
				var selection = frame.state().get('selection');

				if (selected) {
					var attachment = wp.media.attachment(selected);
					attachment.fetch().then(function() {
						selection.add(attachment);
					});
				}
			});

			frame.on('select', function() {
				var selection = frame.state().get('selection');

				if (selection) {
					selection.each(function(attachment) {
						var attributes = attachment.attributes,
							sizes = attributes.sizes,
							size = sizes.medium || sizes.full || sizes.thumbnail;

						$this.find('input').val(attributes.id)
						$this.css('background-image', 'url(' + size.url + ')');

						setDirty();
					});
				}
			});

			frame.open();
		});
	});
})(jQuery);
