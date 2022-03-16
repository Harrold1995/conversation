/* globals review_details: false */
(function($) {

	if ( 'undefined' === typeof review_details ) {
		return false;
	}

	/**
	 * Reviews Processing Class.
	 *
	 * All processes related to reviews such as add, edit or delete are handled by this class.
	 */
	var ReviewsProcessor = function() {
		// Globals for persisting values across the various modals and maintaining a history of the user progression.
		this.stack    = []; // [globals] remember to reset on modal close button click.
		this.stars    = 0; // [globals] remember to reset on modal close button click.
		this.title    = ''; // [globals] remember to reset on modal close button click.
		this.body     = ''; // [globals] remember to reset on modal close button click.
		this.settings = review_details.settings;
		this.media    = [];// [globals] remember to reset on modal close button click.

		$( document )
			.on( 'click', '.rrf-helper-text, .write-a-review', { reviewsProcessor: this }, this.openReviewModal )
			.on( 'click', '.rrf-modal-content .next', { reviewsProcessor: this }, this.forwardModalNavigation )
			.on( 'click', '.rrf-modal-content .previous', { reviewsProcessor: this }, this.reverseModalNavigation )
			.on( 'click', '.rrf-close-all', { reviewsProcessor: this }, this.closeModal )
			.on( 'click', '.delete-confirm', { reviewsProcessor: this }, this.deleteReview )
			.on( 'click', '.rrf-review-submission', { reviewsProcessor: this }, this.reviewSubmission )
			.on( 'click', '.delete-close', { reviewsProcessor: this }, this.closeDeleteModal )
			.on( $.rrfmodal.OPEN, { reviewsProcessor: this }, this.initProcesses )
			.on( 'keyup change', '.review-description > textarea', { reviewsProcessor: this }, this.descriptionTextRemaining )
			.on( 'click', '.rrf-modal-content .media-upload-square-button-container', { reviewsProcessor: this }, this.triggerFileUpload )
			.on( 'click', '.rrf-modal-content .media-upload-square-button-container > input[type="file"]', { reviewsProcessor: this }, this.stopHiddenInputClickEvent )
			.on( 'click', '.rrf-modal-content .media-upload__delete-button', { reviewsProcessor: this }, this.deleteRemovedMedia )
			.on( 'change', '.rrf-modal-content .media-upload-square-button-container > input[type="file"]', { reviewsProcessor: this }, this.mediaUploadProcessing );
		$( window ).on( 'load', { reviewsProcessor: this }, this.onPageLoad );
	};

	/**
	 * Launch the modals on Edit Review or Leave a review link click.
	 * @param  {object} e Windows Event Object
	 */
	ReviewsProcessor.prototype.openReviewModal = function( e ) {
		e.preventDefault();
		e.stopPropagation();
		var step_type, security, course_id, step_no = 1;
		var self                                    = $( this );
		if ( $( this ).hasClass( 'not-allowed' ) ) {
			return false;
		}
		course_id = $( this ).attr( 'data-course_id' );
		$( this ).blur();
		if ( $( this ).hasClass( 'not-rated' ) ) {
			step_type = 'add';
			security  = review_details.add_review_nonce;
		} else if ( $( this ).hasClass( 'already-rated' ) ) {
			step_type = 'edit';
			security  = review_details.edit_review_nonce;
		}
		$( this ).attr( 'disabled', 'disabled' );
		$( '.review-loader' ).css( {'display': 'inline-block','position': 'absolute','left': '90%', 'top': '0'} );
		e.data.reviewsProcessor.stack.push( {'type': step_type, 'num': step_no} );
		$.post(
			review_details.url,
			{
				action: 'launch_modal',
				step_type: step_type,
				security: security,
				step_no: step_no,
				course_id: course_id
			},
			function( response ) {
				self.removeAttr( 'disabled' );
				$( '.review-loader' ).hide();
				$( response ).appendTo( 'body' ).rrfmodal( e.data.reviewsProcessor.settings );
			}
		);
	};

	/**
	 * Switching between modals for review submission process.(only for the next logical modal not in the backward direction).
	 * @param  {object} e Windows Event Object
	 */
	ReviewsProcessor.prototype.forwardModalNavigation = function( e ) {
		var step_type, security, step_no, current_step, course_id, rrfmodal;
		var self     = $( this );
		rrfmodal        = $( this ).parents( '.rrf-modal-content' );
		course_id    = parseInt( rrfmodal.attr( 'data-course_id' ) );
		step_type    = $( this ).attr( 'data-steptype' );
		current_step = parseInt( rrfmodal.attr( 'data-step' ) );
		if ( rrfmodal.hasClass( 'review-details' ) ) {
			if ( rrfmodal.find( '.review-title.review-headline input[type=text]' ).val() === '' || rrfmodal.find( '.review-description.review-details textarea' ).val() === '' ) {
				return false;
			}
			e.data.reviewsProcessor.title = rrfmodal.find( '.review-title.review-headline input[type=text]' ).val();
			e.data.reviewsProcessor.body  = rrfmodal.find( '.review-description.review-details textarea' ).val();
		}
		if (step_type === 'add') {
			security = review_details.add_review_nonce;
		} else if (step_type === 'edit') {
			security = review_details.edit_review_nonce;
		} else if (step_type === 'delete') {
			current_step = 0;
			security     = review_details.delete_review_nonce;
		}
		step_no = current_step + 1;
		e.data.reviewsProcessor.stack.push( {'type': step_type, 'num': step_no} );
		$( this ).attr( 'disabled', 'disabled' );
		rrfmodal.find( '.review-loader' ).css( {'display': 'inline-block','position': 'absolute','right': '25%', 'bottom': '45px'} );
		let data = e.data;
		$.post(
			review_details.url,
			{
				action: 'launch_modal',
				step_type: step_type,
				security: security,
				step_no: step_no,
				stars: data.reviewsProcessor.stars,
				course_id: course_id,
				title: data.reviewsProcessor.title,
				body: data.reviewsProcessor.body,
				media: data.reviewsProcessor.media
			},
			function( response ) {
				self.removeAttr( 'disabled' );
				$( '.review-loader' ).hide();
				$( response ).appendTo( 'body' ).rrfmodal( data.reviewsProcessor.settings );
				if ( self.hasClass( 'mid-submit-step' ) ) {
					$.post(
						review_details.url,
						{
							action: 'submit_review',
							security: review_details.submit_review_nonce,
							stars: data.reviewsProcessor.stars,
							course_id: course_id,
							title: data.reviewsProcessor.title,
							body: data.reviewsProcessor.body,
							media: data.reviewsProcessor.media
						},
						function( status ) {
							if ( ! status.success ) {
								rrfmodal.find( '.modal-container' ).prepend( '<span class="error-message">' + status.data + '</span>' );
								return;
							}
							// $('[data-id=input-' + course_id + '-rrf]').rating('update', stars);
							// $('[data-id=input-' + course_id + '-rrf]').parents('.ratings-after-title').next().removeClass('not-rated').addClass('already-rated').attr('data-alt', review_details.alt_text).text(review_details.review_text);
							// while ($.modal.isActive()) {
							// 	$.modal.close();
							// 	// target_el.append('<span class="rrf-helper-text ' + ratings.class + '" data-course_id="' + id + '" data-alt="' + ratings.alt_text + '">' + ratings.review_text + '</span>');
							// }
						}
					);
				}
			}
		);
	};

	/**
	 * Go to the previous modal window.
	 */
	ReviewsProcessor.prototype.reverseModalNavigation = function() {
		$.rrfmodal.close();
	};

	/**
	 * Close all modals on close button click.
	 * @param  {object} e Windows Event Object
	 */
	ReviewsProcessor.prototype.closeModal = function( e ) {
		e.preventDefault();
		e.stopPropagation();
		e.data.reviewsProcessor.stack = [];
		e.data.reviewsProcessor.stars = 0;
		e.data.reviewsProcessor.title = '';
		e.data.reviewsProcessor.body  = '';
		e.data.reviewsProcessor.media = [];
		while ( $.rrfmodal.isActive() ) {
			$.rrfmodal.close();
		}
	};

	/**
	 * Delete User's review.
	 */
	ReviewsProcessor.prototype.deleteReview = function() {
		var security  = review_details.delete_review_nonce;
		var course_id = $( this ).parents( '.rrf-modal-content' ).attr( 'data-course_id' );
		$.post(
			review_details.url,
			{
				action: 'delete_review',
				security: security,
				course_id: course_id,
			},
			function( response ) {
				console.log( 'deleted successfully' );
				window.location.reload();
			}
		);
	};

	/**
	 * Submission of the review for insert/update process.
	 */
	ReviewsProcessor.prototype.reviewSubmission = function() {
		window.location.reload();
	};

	/**
	 * Close delete modal.
	 */
	ReviewsProcessor.prototype.closeDeleteModal = function() {
		while ($.rrfmodal.isActive()) {
			$.rrfmodal.close();
		}
	};

	/**
	 * Initialize Ratings library on modal open and keep the next button disabled until a star rating is selected.
	 * @param  {object} event Windows Event Object
	 * @param {object} modal  Modal Object.
	 */
	ReviewsProcessor.prototype.initProcesses = function( event, rrfmodal ) {
		var course_id = parseInt( rrfmodal.$elm.attr( 'data-course_id' ) );
		if ( rrfmodal.$elm.find( '.rating-settings' ).length > 0 ) {
			var rating_settings;
			rating_settings = JSON.parse( rrfmodal.$elm.find( '.rating-settings' ).val() );
			rrfmodal.$elm.find( '[data-id=input-' + course_id + '-rrf]' ).rating( rating_settings );
		}
		if ( rrfmodal.$elm.find( '[data-id=input-' + course_id + '-rrf]' ).length > 0 ) {
			event.data.reviewsProcessor.stars = rrfmodal.$elm.find( '[data-id=input-' + course_id + '-rrf]' ).val();
		}
		rrfmodal.$elm.find( '[data-id=input-' + course_id + '-rrf]' ).on(
			'rating:change',
			{ reviewsProcessor : event.data.reviewsProcessor },
			function( event, value, caption ) {
				rrfmodal.$elm.find( '.next' ).removeAttr( 'disabled' );
				event.data.reviewsProcessor.stars = value;
				if ( rrfmodal.$elm.hasClass( 'star-submission' ) ) {
					rrfmodal.$elm.find( '.next' ).trigger( 'click' );
				}
			}
		);
		if ( jQuery( '.review-description > textarea' ).length > 0 ) {
			var remaining = review_details.maxlength - jQuery( '.review-description > textarea' ).val().length;
			if ( remaining <= 0 ) {
				remaining = 0;
			}
			jQuery( '.review-description > textarea' ).siblings( '.wdm_rrf_remaining_characters' ).find( '.wdm_cff_remaining_count' ).html( remaining );
		}
	};

	/**
	 * Used to show remaining characters count in review description.
	 */
	ReviewsProcessor.prototype.descriptionTextRemaining = function() {
		var remaining = review_details.maxlength - jQuery( this ).val().length;
		if ( remaining <= 0 ) {
			remaining = 0;
		}
		jQuery( this ).siblings( '.wdm_rrf_remaining_characters' ).find( '.wdm_cff_remaining_count' ).html( remaining );
	};

	/**
	 * Used to trigger file upload hidden input click.
	 * @param  {object} evnt Windows Event Object
	 */
	ReviewsProcessor.prototype.triggerFileUpload = function( evnt ) {
		evnt.preventDefault();
		evnt.stopPropagation();
		$( '.media-upload-square-button-container > input[type="file"]' ).click();
	};

	/**
	 * Used to stop dual events on hidden input click and to stop form submission.
	 * @param  {object} evnt Windows Event Object
	 */
	ReviewsProcessor.prototype.stopHiddenInputClickEvent = function( evnt ) {
		// evnt.preventDefault();
		evnt.stopPropagation();
	};

	/**
	 * Method is used to delete media files when user removes them from his/her review.
	 * @param  {object} e Windows Event Object
	 */
	ReviewsProcessor.prototype.deleteRemovedMedia = function( evnt ) {
		evnt.preventDefault();
		evnt.stopPropagation();
		var parent        = jQuery( this ).parents( '.media-upload__thumbnail-container' );
		var attachment_id = parent.find( 'input[type="hidden"]' ).val();
		var index         = evnt.data.reviewsProcessor.media.indexOf( attachment_id );
		if ( index > -1 ) {
			evnt.data.reviewsProcessor.media.splice( index, 1 );
		}
		parent.remove();
		var ajx = $.ajax(
			{
				url: review_details.url,
				type: 'POST',
				data: {
					'action' 		: 'review_attachment_delete',
					'security'		: review_details.delete_media_nonce,
					'attachment_id'	: attachment_id
				},
			}
		).done(
			function( data ) {
				if ( ! data.success ) {
					jQuery( '.modal-container' ).append( '<span class="toast-error" style="color: red;">' + data.data + '</span>' );
					setTimeout(
						function() {
							jQuery( '.toast-error' ).fadeOut( 'slow' );
						},
						3500
					);
					return;
				}
			}
		);
	};

	/**
	 * Method is used to upload media after validating the input file.
	 * @param  {object} e Windows Event Object
	 */
	ReviewsProcessor.prototype.mediaUploadProcessing = function( evnt ) {
		// evnt.preventDefault();
		evnt.stopPropagation();
		if ( this.files && this.files[ 0 ] ) {
			if ( this.files[ 0 ].size >= review_details.max_file_size ) {
				jQuery( '.modal-container' ).append( '<span class="toast-error" style="color: red;">' + review_details.max_file_size_error + '</span>' );
				setTimeout(
					function() {
						jQuery( '.toast-error' ).fadeOut( 'slow' );
					},
					2000
				);
				return;
			}
			var unique_id    = 'preview_tile_' + Math.random().toString( 36 ).substring( 2, 15 );
			var preview_html = '<div class="media-upload__thumbnail-container ' + unique_id + '">' +
				'<div class="media-upload__thumbnail">' +
					'<div class="a-spinner a-spinner-medium media-upload__thumbnail-spinner"></div>' +
					'<button type="button" class="media-upload__delete-button"><i class="fa fa-times"></i></button>' +
				'</div>' +
			'</div>';
			var parent       = $( this ).parents( '.media-upload__thumbnails-container' );
			parent.append( preview_html );
			var FR = new FileReader();
			parent.find( '.' + unique_id + ' .media-upload__thumbnail .a-spinner' ).css( 'background', "url('/wp-content/plugins/wdm-course-review/public/images/loader.gif') 50% 50% no-repeat" );
			FR.addEventListener(
				'load',
				function( e ) {
					if ( e.target.result.includes( 'data:video' ) ) {
						parent.find( '.' + unique_id + ' .media-upload__thumbnail' ).append( '<video preload="auto" autoplay playsinline controls><source src="' + e.target.result + '"/></video>' );
					} else {
						parent.find( '.' + unique_id + ' .media-upload__thumbnail' ).css( 'background-image', 'url(' + e.target.result + ')' );
					}
				}
			);
			FR.readAsDataURL( this.files[0] );
			var form_data = new FormData();
			form_data.append( 'media', this.files[ 0 ] );
			form_data.append( 'action', 'review_attachment_upload' );
			form_data.append( 'security', review_details.upload_media_nonce );
			var ajx = $.ajax(
				{
					url: review_details.url,
					type: 'POST',
					timeout:120000, //3000=3 60000=60 seconds timeout
					contentType: false,
					processData: false,
					data: form_data,
				}
			).done(
				function( data ) {
					parent.find( '.' + unique_id + ' .media-upload__thumbnail .a-spinner' ).css( 'background', 'none' );
					if ( ! data.success ) {
						parent.find( '.' + unique_id ).remove();
						jQuery( '.modal-container' ).append( '<span class="toast-error" style="color: red;">' + data.data + '</span>' );
						setTimeout(
							function(){
								jQuery( '.toast-error' ).fadeOut( 'slow' );
							},
							3500
						);
						return;
					}
					evnt.data.reviewsProcessor.media.push( data.data.ID );
					parent.find( '.' + unique_id ).append( '<input type="hidden" value="' + data.data.ID + '"/>' );
				}
			).fail(
				function( jqXHR, textStatus, errorThrown ) {
					parent.find( '.' + unique_id + ' .media-upload__thumbnail .a-spinner' ).css( 'background', 'none' );
					parent.find( '.' + unique_id ).remove();
					jQuery( '.modal-container' ).append( '<span class="toast-error" style="color: red;">' + errorThrown + '</span>' );
					setTimeout(
						function(){
							jQuery( '.toast-error' ).fadeOut( 'slow' );
						},
						2000
					);
				}
			);
		}
	};

	/**
	 * Processes that are run on window load.
	 *
	 * In this case, it is used to automatically open the modal window on course visit.
	 */
	ReviewsProcessor.prototype.onPageLoad = function() {
		var $review_button = $( 'button.write-a-review' );
		if ( $review_button.length > 0 ) {
			var dismissed_courses = getCookie( 'dismissed_courses' );
			var current_course    = $review_button.attr( 'data-course_id' );
			var is_dismissed      = true;
			if ( dismissed_courses == '' || -1 == jQuery.inArray(current_course.toString(), JSON.parse(dismissed_courses)) ) {
				is_dismissed = false;
			}
			if ( $review_button.hasClass( 'not-rated' ) && ! $review_button.hasClass( 'not-allowed' ) && !is_dismissed ) {
				// Delayed Trigger.
				setTimeout(function () {
					$review_button.click();
				}, 1000);
				var triggered_courses = [];
				if ( dismissed_courses != '' )  {
					triggered_courses = JSON.parse( dismissed_courses );
				}
				triggered_courses.push( current_course );
				// Only Prompt once every month for each course.
				setCookie( 'dismissed_courses', JSON.stringify( triggered_courses ), 30 );
			}
		}
	};

	$( document ).ready(
		function() {
			var reviewsProcessor = new ReviewsProcessor();
		}
	);
	/**
	 * This method is used to create cookies.
	 * @param string  cname  Cookie Name.
	 * @param mixed   cvalue Cookie Value.
	 * @param integer exdays Expiry Days.
	 */
	function setCookie(cname, cvalue, exdays) {
		var d = new Date();
		d.setTime(d.getTime() + (exdays*24*60*60*1000));
		var expires = "expires="+ d.toUTCString();
		document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
	}

	/**
	 * This method is used to fetch cookies.
	 * @param  string cname Cookie Name.
	 * @return mixed  value Cookie Value.
	 */
	function getCookie(cname) {
		var name = cname + "=";
		var ca = document.cookie.split(';');
		for(var i = 0; i < ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') {
			  c = c.substring(1);
			}
			if (c.indexOf(name) == 0) {
			  return c.substring(name.length, c.length);
			}
		}
		return "";
	}
})( jQuery );
