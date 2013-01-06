(function($) {
	$(document).ready(function() {
		var upload = $(".uploaded-file"), frame;

		$('.upload_button').click( function( event ) {
			var $el = $(this);

			event.preventDefault();

			// If the media frame already exists, reopen it.
			if ( frame ) {
				frame.open();
				return;
			}

			// Create the media frame.
			frame = wp.media({
				// Set the title of the modal.
				title: $el.data('choose'),

				// Customize the submit button.
				button: {
					// Set the text of the button.
					text: $el.data('update'),
					// Tell the button not to close the modal, since we're
					// going to refresh the page when the image is selected.
					close: false
				}
			});

			// When an image is selected, run a callback.
			frame.on( 'select', function() {
				// Grab the selected attachment.
				var attachment = frame.state().get('selection').first();
				frame.close();
				$('.upload').val(attachment.attributes.url);
				if ( attachment.attributes.type == 'image' ) {
					$('.screenshot').empty().hide().append('<img src="' + attachment.attributes.url + '"><a class="remove-button">Remove</a>').slideDown('fast');
					remove_button_binding();
				}
			});

			// Finally, open the modal.
			frame.open();
		});
		
		function remove_button_binding() {
			$('.remove-button').on('click', function() { 
				$(this).hide();
		        $(this).parents().parents().children('.upload').attr('value', '');
		        $(this).parents('.screenshot').slideUp();
		        $(this).parents('.screenshot').siblings('.of-background-properties').hide(); //remove background properties
		        return false;
	        });
        }

    });
	
})(jQuery);