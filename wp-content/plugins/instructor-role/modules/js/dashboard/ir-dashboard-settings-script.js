(function($){
    $('#ir_dashboard_header').on('change', function(){
        if ( 'image' === $(this).val() ) {
            $('.ir-dashboard-image-field').show();
            $('.ir-dashboard-text-field').hide();
        } else if ( 'text' === $(this).val() ) {
            $('.ir-dashboard-text-field').show();
            $('.ir-dashboard-image-field').hide();
        } else {
            $('.ir-dashboard-image-field').hide();   
            $('.ir-dashboard-text-field').hide();
        }
    });

    if ( $('.ir-color-picker').length ) {
        $('.ir-color-picker').wpColorPicker();
    }

    $('.ir_upload_image').on('click', function(event){
		event.preventDefault();

		var button = $(this),
		custom_uploader = wp.media({
			title: 'Select Image',
			library : {
				// uploadedTo : wp.media.view.settings.post.id, // attach to the current post?
				type : 'image'
			},
			button: {
				text: 'Use this image' // button label text
			},
			multiple: false
		}).on('select', function() { // it also has "open" and "close" events
			var attachment = custom_uploader.state().get('selection').first().toJSON();
			button.html('<img src="' + attachment.url + '">').next().removeClass('ir-hide').next().val(attachment.id).next().val(attachment.id);
		}).open();
	});

	$('.ir_remove_image').on('click', function(event){
		event.preventDefault();

		var button = $(this);
		button.next().val(''); // emptying the hidden field
		button.addClass('ir-hide').prev().html('Upload');
	})
})(jQuery);
