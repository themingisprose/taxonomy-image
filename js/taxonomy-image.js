jQuery(document).ready(function($) {
	// Media Upload
	function taxonomy_image_upload_media( container, button ){
		var clicked_button = false;
		var container_id = false;

		$(button).click(function(event){
			event.preventDefault();
			var clicked_button = $(this);
			var button_id = $(clicked_button).attr('id');
			var container_id = $(clicked_button).siblings(container).attr('id');

			// configuration of the media manager new instance
			wp.media.frames.taxonomy_image_upload_media_frame = wp.media({
				title: taxonomy_image_l10n.upload_title,
				multiple: false,
				library: {
					type: 'image',
				},
				button: {
					text: taxonomy_image_l10n.upload_button,
				}
			});

			// Function used for the object selection and media manager closing
			var taxonomy_image_uploaded_media = function(){
				var selection = wp.media.frames.taxonomy_image_upload_media_frame.state().get('selection');

				// If no selection
				if (!selection) {
					return;
				}

				// iterate through selected elements
				selection.each(function(attachment){
					var file = attachment.attributes.url;
					var element = $('#'+container_id);
					$(element).val(file);
					console.log( attachment.attributes.id )
				})
			};
			// closing event for media manger
			wp.media.frames.taxonomy_image_upload_media_frame.on('close', null);
			// media selection event
			wp.media.frames.taxonomy_image_upload_media_frame.on('select', taxonomy_image_uploaded_media);
			// showing media manager
			wp.media.frames.taxonomy_image_upload_media_frame.open();
		});
	}
	taxonomy_image_upload_media( '.media-url', '.media-selector' );
});
