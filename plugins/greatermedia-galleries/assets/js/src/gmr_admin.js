(function($){
	$(document).ready(function() {
		var $editButton = $('#manage-gallery-button'),
			$editButtonText = $('#manage-gallery-text'),
			$input = $('#gmr-gallery-ids'),
			gallery = wp.media.gallery,
			$galleryPreview = $('#gmr-gallery-preview'),
			$galleryImages = $('#gmr-gallery-images'),
			galleryItemTemplate = $('#gmr-gallery-item-template').text(),
			$featuredImageInput = $('#gmr-featured-image'),
			selectedClasses = 'details selected';

		$editButton.on('click', function() {
			var content = $input.val(),
				newGallery = content.length > 0 ? false : true,
				frame;

			if ( content.length < 1 ) {
				frame = wp.media({
					frame: "post",
					state: "gallery",
					library : { type : 'image'},
					multiple: true
				});
				frame.open();
			} else {
				frame = gallery.edit( '[gallery ids="' + content + '"]' );
			}

			// Listen for the gallery to be updated - And save the 'shortcode' to the hidden input
			frame.state('gallery-edit').on('update', function( selection ) {
				updateImageIds( selection );

				updateGalleryPreview( selection );

				// Update the button text to 'Edit Gallery' if it is a new gallery and we have some IDs
				if ( newGallery ) {
					$editButtonText.text('Edit Gallery');
				}
			});
		});

		$galleryPreview.on('click', '.gallery-item', function( e ) {
			var $newImage = $( e.currentTarget ).closest('.gallery-item');

			// Remove the classes for the old selected image
			$galleryImages.find('.gallery-item').removeClass(selectedClasses);

			// Add the selected classes to the new item
			$newImage.addClass(selectedClasses);

			// Save the attachment id to the hidden meta box
			$featuredImageInput.val($newImage.attr('data-attachment-id'));
		});

		/**
		 * Update the image ids in the hidden input when the gallery is updated.
		 *
		 * @param selection
		 */
		var updateImageIds = function( selection ) {
			var ids = '';

			for ( var i = 0; i < selection.models.length; i++ ) {
				ids += selection.models[i].id + ',';
			}

			ids = ids.slice( 0, -1 );

			$input.val(ids);
		};

		/**
		 * Regenerate the gallery preview, with the new images.
		 *
		 * {{selected_class}} {{attachment_id}} {{thumbnail_url}}
		 *
		 * @param selection
		 */
		var updateGalleryPreview = function(selection) {
			var html = '',
				currentThumbnail = $featuredImageInput.val(),
				foundFeaturedImage = false; // Will be set to true as long as the featured image is still in the gallery somewhere, otherwise the input val() is cleared

			for ( var i = 0; i < selection.models.length; i++ ) {
				var attachmentId = selection.models[i].id,
					selectedClass = attachmentId === currentThumbnail ? selectedClasses : '',
					thumbnailUrl = selection.models[i].get('sizes').full.url;

				html += galleryItemTemplate.replace( '{{selected_class}}', selectedClass ).replace( '{{attachment_id}}', attachmentId ).replace( '{{thumbnail_url}}', thumbnailUrl );

				if ( attachmentId === currentThumbnail ) {
					foundFeaturedImage = true;
				}
			}

			// The featured image was removed from the gallery, so clear the featured image input
			if ( false === foundFeaturedImage ) {
				$featuredImageInput.val('');
			}

			$galleryImages.html(html);
		};
	});
})(jQuery);
