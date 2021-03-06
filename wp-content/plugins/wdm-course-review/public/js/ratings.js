/* globals rating_settings: false, rating_details: false */
(function($) {
	var show_ratings = function(ajaxRatingSettings = false) {
		var applied_rating_settings = rating_settings;
		if (false !== ajaxRatingSettings) {
			applied_rating_settings = ajaxRatingSettings;
		}
		var id, ratings, rating_value, course_instance, selectors;
		selectors = applied_rating_settings.selectors.join( ',' );
		course_instance = [];
		for ( var key in rating_details ) {
			course_instance = $( selectors ).filter(
				function() {
					if ($( this ).parents( 'aside' ).length > 0) {
						return false;
					}
					if ($( this ).parents( 'header.site-header' ).length > 0) {
						return false;
					}
					if ($( this ).parents( '#wpadminbar' ).length > 0) {
						return false;
					}
					if ($( this ).parents('.ld-tabs-content').length > 0) {
						return false;
					}
					var cloned_title = $( this ).clone().children().remove().end().text().trim();
					var target_title = rating_details[ key ].title;
					// Because .text() converts quotes.
					cloned_title = cloned_title.replaceAll( '”', '"' );
					cloned_title = cloned_title.replaceAll( "’", "'" );
					cloned_title = cloned_title.replaceAll( '“', '"' );
					cloned_title = cloned_title.replaceAll( '–', '-' );

					target_title = target_title.replaceAll( '”', '"' );
					target_title = target_title.replaceAll( "’", "'" );
					target_title = target_title.replaceAll( '“', '"' );
					target_title = target_title.replaceAll( '–', '-' );
					
					return cloned_title == target_title;

					// return $(this).clone().children().remove().end().text().trim() === rating_details[key].title;
				}
			);

			if (course_instance.length === 0) {
				continue;
			}
			id      = key;
			ratings = rating_details[key];
			course_instance.each(
				function(ind, el){
					var target_el = $( el );
					/* Show Total Reviews Count */
					if (applied_rating_settings.showTotalReviews) {
						if (target_el.find( '.ratings-after-title' ).length === 0) {
							target_el.append(
								'<div class="ratings-after-title">' +
								'<input data-id="input-' + id + '-rrf" class="rating rating-loading" value="' + ratings.average_rating + '">' +
								'<span>(' + ratings.total_count + ')</span>' +
								'</div>'
							);
						}
						/* Show Average rating + Rating submission prompts. */
					} else {
						rating_value = ratings.average_rating;
						if (applied_rating_settings.allowReviewSubmission && ratings.can_submit_rating) {
							rating_value = ratings.user_rating;
						}
						if (target_el.find( '.ratings-after-title' ).length === 0) {
							target_el.append(
								'<div class="ratings-after-title">' +
								'<input data-id="input-' + id + '-rrf" class="rating rating-loading" value="' + rating_value + '">' +
								'</div>'
							);
							// target_el.append('<span class="rrf-helper-text ' + ratings.class + '" data-course_id="' + id + '" data-alt="' + ratings.alt_text + '">' + ratings.review_text + '</span>');
						}
					}
					/* Scroll to the total reviews section. */
					if (applied_rating_settings.hasSeparateReviewsSections) {
						$( '[data-id=input-' + id + '-rrf]' ).on(
							'rating:rendered',
							function(){
								$( '.rating-stars' ).addClass( 'is-clickable' );
								$( '.rating-stars.is-clickable' ).off( 'click.namespace' ).on(
									'click.namespace',
									function(){
										if ($( '#course-reviews-section' ).length) {
											$( 'html, body' ).animate(
												{
													scrollTop: $( '#course-reviews-section' ).offset().top
												},
												1000
											);
										}
									}
								);
							}
						);
					}
					$( '[data-id=input-' + id + '-rrf]' ).rating( applied_rating_settings ).trigger( 'rating:rendered' );
					if ( typeof $.rrfmodal === 'undefined' || ! $.rrfmodal.isActive() ) {
						$( '[data-id=input-' + id + '-rrf]' ).remove();
					}
				}
			);
		}
	};
	function IsJsonString(str) {
	    try {
	        JSON.parse(str);
	    } catch (e) {
	        return false;
	    }
	    return true;
	}
	$( document ).ready(
		function() {
			show_ratings();
			$( document ).ajaxSuccess(
				function(event, xhr){
					// xhr.responseText\
					if (xhr.hasOwnProperty( 'responseJSON' )) {
						show_ratings();
						return;
					}
					if (!xhr.responseText.includes('<div')){
						show_ratings();
						return;
					}
					if (IsJsonString( xhr.responseText )) {
						show_ratings();
						return;
					}
					var responseSettings = jQuery( xhr.responseText ).find( '.rating-settings' );
					if (responseSettings.length === 0) {
						show_ratings();
						return;
					}
					show_ratings( JSON.parse( responseSettings.val() ) );
				}
			);
			/* Review submission text toggle */
			/*$('body').on('mouseover mouseout', '.rrf-helper-text', function(){
				var alt_text = $(this).attr('data-alt');
				var text = $(this).text();
				$(this).attr('data-alt', text);
				$(this).text(alt_text);
			});*/
		}
	);
})( jQuery );
