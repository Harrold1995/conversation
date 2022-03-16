function ajax_send_helpful_count(button_obj, review_id, answer) {
	jQuery.ajax(
		{
			url: helpful_object.url,
			type: 'POST',
			data: {
				'action' : helpful_object.action,
				'answer': answer,
				'review_id': review_id,
				'security' : helpful_object.nonce,
			},
		}
	).done(
		function(data){
			var result          = JSON.parse( data );
			var display_message = button_obj.parents( '.review-meta-wrap' ).find( '.review-helpful-count' );
			if (result.success == true && result.message != '') {
				button_obj.toggleClass( 'hide' );
				button_obj.siblings( '.review-helpful-icon-wrap' ).toggleClass( 'hide' );
				if (result.display_msg.length == 0) {
					display_message.addClass( 'is-not-voted' );
				} else {
					display_message.removeClass( 'is-not-voted' );
				}
				display_message.text( result.display_msg ).show();
			} else {
				window.location.href = result.redirecturl;
			}
		}
	);
}
jQuery( document ).ready(
	function(){
		var current_page_no, max_page_no;
		current_page_no = 1;
		max_page_no     = parseInt( jQuery( '.max_page_no' ).val() );
		// Ajax call for review was helpful
		jQuery( 'body' ).on(
			'click',
			'.wdm_helpful_yes',
			function(e) {
				e.preventDefault();
				var button_obj = jQuery( this );
				var review_id  = button_obj.attr( 'data-review_id' );
				ajax_send_helpful_count( button_obj, review_id, 'yes' );
			}
		);
		jQuery( 'body' ).on(
			'click',
			'.wdm_helpful_no' ,
			function(e) {
				e.preventDefault();
				var button_obj = jQuery( this );
				var review_id  = button_obj.attr( 'data-review_id' );
				ajax_send_helpful_count( button_obj, review_id, 'no' );
			}
		);
		//  Comment display toggle.
		jQuery( "body" ).on(
			"click",
			".comment-toggle-alt",
			function(e){
				e.preventDefault();
				jQuery( this ).closest( ".wdm-review-replies" ).children( ".review-comment-list:not(:first-of-type)" ).toggleClass( 'hide' );
				jQuery( this ).toggleClass( 'hide' );
				jQuery( this ).siblings( "a" ).toggleClass( 'hide' );
			}
		);
		// Initial comment display hide.
		jQuery( '.wdm-review-replies' ).each(
			function(){
				jQuery( this ).children( '.review-comment-list:not(:first-child)' ).addClass( 'hide' );
			}
		);
		// Reviews sorting logic.
		jQuery( '.sort_results' ).on(
			'change',
			function(e){
				current_page_no = 1;
				var self     = jQuery( this );
				var orderby  = self.val();
				var filterby = jQuery( this ).parents( '.filter-options' ).find( '.filter_results' ).val();
				var filterbycourse = jQuery( this ).parents( '.filter-options' ).find( '.filter_by_course' ).val();
				jQuery( '.inside-course-reviews-section' ).hide();
				jQuery( '.loader' ).removeClass( 'hide' );
				jQuery.get(
					reviews_filter_query.current_url,
					{
						filterby: filterby,
						orderby: orderby,
						course_id: filterbycourse,
					},
					function(data) {
						jQuery('#course-reviews-section').html(jQuery(jQuery.parseHTML(data)).find('#course-reviews-section').html());
						jQuery( '.inside-course-reviews-section' ).show();
						jQuery( '.loader' ).addClass( 'hide' );
						// jQuery('.rating-loading').rating('refresh');
						jQuery( '.wdm-review-replies' ).each(
							function(){
								jQuery( this ).children( '.review-comment-list:not(:first-child)' ).addClass( 'hide' );
							}
						);
						max_page_no = parseInt( jQuery( '.max_page_no' ).val() );
						if (1 == max_page_no) {
							jQuery( '.next' ).hide();
						}
						jQuery( '.prev' ).hide();
					}
				);
			}
		);
		// Reviews filter by course handler.
		jQuery( '.filter_by_course' ).on(
			'change',
			function(e){
				current_page_no = 1;
				var filterbycourse     = jQuery( this ).val();
				var orderby  = jQuery( this ).parents( '.filter-options' ).find( '.sort_results' ).val();
				var filterby = jQuery( this ).parents( '.filter-options' ).find( '.filter_results' ).val();
				jQuery( '.inside-course-reviews-section' ).hide();
				jQuery( '.loader' ).removeClass( 'hide' );
				jQuery.get(
					reviews_filter_query.current_url,
					{
						filterby: filterby,
						orderby: orderby,
						course_id: filterbycourse,
					},
					function(data) {
						jQuery('#course-reviews-section').html(jQuery(jQuery.parseHTML(data)).find('#course-reviews-section').html());
						jQuery( '.inside-course-reviews-section' ).show();
						jQuery( '.loader' ).addClass( 'hide' );
						// jQuery('.rating-loading').rating('refresh');
						jQuery( '.wdm-review-replies' ).each(
							function(){
								jQuery( this ).children( '.review-comment-list:not(:first-child)' ).addClass( 'hide' );
							}
						);
						max_page_no = parseInt( jQuery( '.max_page_no' ).val() );
						if (1 == max_page_no) {
							jQuery( '.next' ).hide();
						}
						jQuery( '.prev' ).hide();
					}
				);
			}
		);
		// Reviews filtering logic.
		jQuery( '.filter_results' ).on(
			'change',
			function(e){
				current_page_no = 1;
				var self     = jQuery( this );
				var filterby = self.val();
				var orderby  = jQuery( this ).parents( '.filter-options' ).find( '.sort_results' ).val();
				var filterbycourse = jQuery( this ).parents( '.filter-options' ).find( '.filter_by_course' ).val();
				jQuery( '.inside-course-reviews-section' ).hide();
				jQuery( '.loader' ).removeClass( 'hide' );
				jQuery.get(
					reviews_filter_query.current_url,
					{
						filterby: filterby,
						orderby: orderby,
						course_id: filterbycourse,
					},
					function(data) {
						jQuery('#course-reviews-section').html(jQuery(jQuery.parseHTML(data)).find('#course-reviews-section').html());
						jQuery( '.inside-course-reviews-section' ).show();
						jQuery( '.loader' ).addClass( 'hide' );
						// jQuery('.rating-loading').rating('refresh');
						jQuery( '.wdm-review-replies' ).each(
							function(){
								jQuery( this ).children( '.review-comment-list:not(:first-child)' ).addClass( 'hide' );
							}
						);
						max_page_no = parseInt( jQuery( '.max_page_no' ).val() );
						if (1 == max_page_no) {
							jQuery( '.next' ).hide();
						}
						jQuery( '.prev' ).hide();
					}
				);
			}
		);
		// Reviews Pagination logic.
		if (1 == max_page_no) {
			jQuery( '.rrf_prev_next_links .next' ).hide();
		}
		jQuery( '.rrf_prev_next_links .prev' ).hide();

		jQuery( 'body' ).on(
			'click',
			'.rrf_prev_next_links .next',
			function(evnt){
				evnt.preventDefault();
				current_page_no = current_page_no + 1;
				var orderby      = jQuery('.sort_results').val();
				var filterby    = jQuery('.filter_results' ).val();
				var filterbycourse = jQuery('.filter_by_course' ).val();
				jQuery( '.inside-course-reviews-section' ).hide();
				jQuery( '.loader' ).removeClass( 'hide' );
				jQuery.get(
					reviews_filter_query.current_url,
					{
						filterby: filterby,
						orderby: orderby,
						course_id: filterbycourse,
						pno: current_page_no
					},
					function(data) {
						jQuery('#course-reviews-section').html(jQuery(jQuery.parseHTML(data)).find('#course-reviews-section').html());
						jQuery( '.inside-course-reviews-section' ).show();
						jQuery( '.loader' ).addClass( 'hide' );
						// jQuery('.rating-loading').rating('refresh');
						jQuery( '.wdm-review-replies' ).each(
							function(){
								jQuery( this ).children( '.review-comment-list:not(:first-child)' ).addClass( 'hide' );
							}
						);
						max_page_no = parseInt( jQuery( '.max_page_no' ).val() );
						if (current_page_no == max_page_no) {
							jQuery( '.next' ).hide();
							jQuery( '.prev' ).show();
						}
					}
				);
			}
		);
		jQuery( 'body' ).on(
			'click',
			'.rrf_prev_next_links .prev',
			function(evnt){
				evnt.preventDefault();
				current_page_no = current_page_no - 1;
				var orderby      = jQuery('.sort_results').val();
				var filterby    = jQuery('.filter_results' ).val();
				var filterbycourse = jQuery('.filter_by_course' ).val();
				jQuery( '.inside-course-reviews-section' ).hide();
				jQuery( '.loader' ).removeClass( 'hide' );
				jQuery.get(
					reviews_filter_query.current_url,
					{
						filterby: filterby,
						orderby: orderby,
						course_id: filterbycourse,
						pno: current_page_no
					},
					function(data) {
						jQuery('#course-reviews-section').html(jQuery(jQuery.parseHTML(data)).find('#course-reviews-section').html());
						jQuery( '.inside-course-reviews-section' ).show();
						jQuery( '.loader' ).addClass( 'hide' );
						// jQuery('.rating-loading').rating('refresh');
						jQuery( '.wdm-review-replies' ).each(
							function(){
								jQuery( this ).children( '.review-comment-list:not(:first-child)' ).addClass( 'hide' );
							}
						);
						max_page_no = parseInt( jQuery( '.max_page_no' ).val() );
						if (current_page_no == 1) {
							jQuery( '.prev' ).hide();
							jQuery( '.next' ).show();
						}
					}
				);
			}
		);

		//Reviews Image lightbox logic.
		var $is_open = false;
		// Preview Attachment.
		jQuery( 'body' ).on(
			'click',
			'.review-image-tile-section img',
			function() {
				var full_image = jQuery( this ).attr( 'data-full' );
				jQuery( '.preview-modal-content img' ).attr( 'src', full_image );
				jQuery( '.preview-modal' ).show();
				$is_open = true;
			}
		);
		// Close on clicking outside content.
		jQuery( 'body' ).on(
			'click',
			function( event ) {
				if ($is_open) {
					$is_open = false;
					return;
				}
				if( ! jQuery( event.target ).closest( '.preview-modal-content img' ).length && !jQuery(event.target).is('.preview-modal-content img')) {
					jQuery( '.preview-modal' ).hide();
				}
			}
		);
		// Close on clicking close icon.
		jQuery( 'body' ).on(
			'click',
			'.preview-modal .close',
			function() {
				jQuery( '.preview-modal' ).hide();
			}
		);
		// Close on pressing esc key.
		jQuery(document).keydown( function( event ) { 
			if ( event.keyCode == 27 ) { 
				jQuery( '.preview-modal' ).hide();
			}
		});
	}
);
