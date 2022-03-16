<?php
/**
 * This file is the template for adding review details.
 *
 * @package RatingsReviewsFeedback\Public\Reviews
 *
 * $step_number : Step Count
 * $step_type : Type of step i.e., add/edit/delete in this case it will always be add.
 * $steps : Registered steps.
 * $stars : Number of stars.
 */

?>
<div class="rrf-modal-content modal review-details" data-course_id="<?php echo esc_attr( $course_id ); ?>" data-step="<?php echo esc_attr( $step_number ); ?>" data-steptype="<?php echo esc_attr( $step_type ); ?>">
	<?php
	global $rrf_ratings_settings;
	$rrf_ratings_settings['displayOnly']  = false;
	$rrf_ratings_settings['size']         = 'lg';
	$rrf_ratings_settings['showClear']    = false;
	$rrf_ratings_settings['starCaptions'] = array(
		1 => __( 'Awful, not what I expected at all', 'wdm_ld_course_review' ),
		2 => __( 'Poor, pretty disappointed', 'wdm_ld_course_review' ),
		3 => __( 'Average, could be better', 'wdm_ld_course_review' ),
		4 => __( 'Good, what I expected', 'wdm_ld_course_review' ),
		5 => __( 'Amazing, above expectations!', 'wdm_ld_course_review' ),
	);
	$input_id                             = 'input-' . $course_id . '-rrf';
	$user_id                              = get_current_user_id();
	$review                               = rrf_get_user_course_review_id( $user_id, $course_id );
	if ( empty( $review ) ) {
		$review_title       = '';
		$review_description = '';
	} else {
		$review_title       = $review->post_title;
		$review_description = $review->post_content;
		if ( empty( $stars ) ) {
			$stars = intval( get_post_meta( $review->ID, 'wdm_course_review_review_rating', true ) );
		}
	}
	?>
	<div class="modal-container">
		<div class="prompt-text"><?php echo esc_html__( 'Why did you leave this rating?', 'wdm_ld_course_review' ); ?></div>
		<input type="hidden" class="rating-settings" value='<?php echo json_encode( $rrf_ratings_settings, JSON_HEX_APOS ); ?>'>
		<input data-id="<?php echo esc_attr( $input_id ); ?>" class="rating rating-loading" value="<?php echo esc_attr( $stars ); ?>">
		<div class="review-title review-headline">
			<label for="review-title"><?php esc_html_e( 'Add a headline*', 'wdm_ld_course_review' ); ?></label>
			<input type="text" name="review-title" maxlength='<?php echo esc_attr( RRF_REVIEW_HEADLINE_MAX_LENGTH ); ?>' placeholder="<?php esc_attr_e( 'What\'s most important to know?', 'wdm_ld_course_review' ); ?>" value="<?php echo esc_attr( $review_title ); ?>"/>
		</div>
		<div class="review-description review-details">
			<label for="review-details"><?php echo esc_html__( 'Write your review*', 'wdm_ld_course_review' ); ?></label>
			<textarea name="review-details" maxlength="<?php echo esc_attr( RRF_REVIEW_DETAILS_MAX_LENGTH ); ?>" cols="30" rows="5" placeholder="<?php esc_attr_e( 'What did you like or dislike? What were your expectations?', 'wdm_ld_course_review' ); ?>"><?php echo esc_html( $review_description ); ?></textarea>
			<div class="wdm_rrf_remaining_characters">
				<span class="wdm_cff_remaining_count"><?php echo esc_html( RRF_REVIEW_DETAILS_MAX_LENGTH ); ?></span>
				<span><?php esc_html_e( 'remaining character(s)', 'wdm_ld_course_review' ); ?></span>
			</div>
		</div>
		<div class="review-media review-images review-videos">
			<label><?php echo esc_html__( 'Add a photo or video', 'wdm_ld_course_review' ); ?></label>
			<span><?php echo esc_html__( 'People find images and videos more helpful than text alone.', 'wdm_ld_course_review' ); ?></span>
			<div class="media-upload__thumbnails-container">
				<?php
				$images = get_attached_media( 'image', $review->ID );
				$videos = get_attached_media( 'video', $review->ID );
				if ( ! empty( $images ) ) {
					foreach ( $images as $image_id => $image_obj ) {
						$unique_id = 'preview_tile_' . uniqid();
						?>
						<div class="media-upload__thumbnail-container <?php echo esc_attr( $unique_id ); ?>">
							<div class="media-upload__thumbnail" style="background-image: url(<?php echo esc_url( wp_get_attachment_url( $image_id ) ); ?>);">
								<div class="a-spinner a-spinner-medium media-upload__thumbnail-spinner"></div>
								<button type="button" class="media-upload__delete-button"><i class="fa fa-times"></i></button>
							</div>
							<input type="hidden" value="<?php echo esc_attr( $image_id ); ?>">
						</div>
						<?php
					}
				}
				if ( ! empty( $videos ) ) {
					foreach ( $videos as $video_id => $video_obj ) {
						$unique_id = 'preview_tile_' . uniqid();
						?>
						<div class="media-upload__thumbnail-container <?php echo esc_attr( $unique_id ); ?>">
							<div class="media-upload__thumbnail">
								<div class="a-spinner a-spinner-medium media-upload__thumbnail-spinner"></div>
								<button type="button" class="media-upload__delete-button"><i class="fa fa-times"></i></button>
								<video preload="auto" autoplay playsinline controls>
									<source src="<?php echo esc_attr( wp_get_attachment_url( $video_id ) ); ?>" type="<?php echo esc_attr( $video_obj->post_mime_type ); ?>"/>
								</video>
							</div>
							<input type="hidden" value="<?php echo esc_attr( $video_id ); ?>">
						</div>
						<?php
					}
				}
				?>
				<div class="media-upload-square-button">
					<div class="a-section a-spacing-small media-upload-square-button-container" style="background-image: url(&quot;data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPHN2ZyB3aWR0aD0iMjZweCIgaGVpZ2h0PSIyNnB4IiB2aWV3Qm94PSIwIDAgMjYgMjYiIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayI+CiAgICA8IS0tIEdlbmVyYXRvcjogU2tldGNoIDUwLjIgKDU1MDQ3KSAtIGh0dHA6Ly93d3cuYm9oZW1pYW5jb2RpbmcuY29tL3NrZXRjaCAtLT4KICAgIDx0aXRsZT5TaGFwZTwvdGl0bGU+CiAgICA8ZGVzYz5DcmVhdGVkIHdpdGggU2tldGNoLjwvZGVzYz4KICAgIDxkZWZzPjwvZGVmcz4KICAgIDxnIGlkPSJzaHJpbmtJbWFnZUNUQS04MCIgc3Ryb2tlPSJub25lIiBzdHJva2Utd2lkdGg9IjEiIGZpbGw9Im5vbmUiIGZpbGwtcnVsZT0iZXZlbm9kZCI+CiAgICAgICAgPGcgaWQ9ImV4cGwtY29weS0yMjkiIHRyYW5zZm9ybT0idHJhbnNsYXRlKC00Ny4wMDAwMDAsIC0zMjMuMDAwMDAwKSIgZmlsbD0iI0FBQjdCOCIgZmlsbC1ydWxlPSJub256ZXJvIj4KICAgICAgICAgICAgPGcgaWQ9ImFzaW5NZXRhIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwLjAwMDAwMCwgMTE5LjAwMDAwMCkiPgogICAgICAgICAgICAgICAgPGcgaWQ9ImFkZE1lZGlhIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwLjAwMDAwMCwgMTAwLjAwMDAwMCkiPgogICAgICAgICAgICAgICAgICAgIDxnIGlkPSJHcm91cCIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMTcuMDAwMDAwLCAxNy4wMDAwMDApIj4KICAgICAgICAgICAgICAgICAgICAgICAgPGcgaWQ9Ikdyb3VwLTIiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAuMDAwMDAwLCA1Ny4wMDAwMDApIj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxwb2x5Z29uIGlkPSJTaGFwZSIgcG9pbnRzPSI0NC4zIDQxLjcgNDQuMyAzMCA0MS43IDMwIDQxLjcgNDEuNyAzMCA0MS43IDMwIDQ0LjMgNDEuNyA0NC4zIDQxLjcgNTYgNDQuMyA1NiA0NC4zIDQ0LjMgNTYgNDQuMyA1NiA0MS43Ij48L3BvbHlnb24+CiAgICAgICAgICAgICAgICAgICAgICAgIDwvZz4KICAgICAgICAgICAgICAgICAgICA8L2c+CiAgICAgICAgICAgICAgICA8L2c+CiAgICAgICAgICAgIDwvZz4KICAgICAgICA8L2c+CiAgICA8L2c+Cjwvc3ZnPg==&quot;);">
						<input type="file" accept="image/*,video/*" class="hidden-input" />
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-navigation">
		<button class="previous"><?php esc_html_e( 'Back', 'wdm_ld_course_review' ); ?></button>
		<button class="next mid-submit-step" data-steptype="add"><?php esc_html_e( 'Save & Continue', 'wdm_ld_course_review' ); ?></button>
	</div>
	<img class="review-loader" src="<?php echo esc_url( RRF_PLUGIN_URL . '/public/images/loader.gif' ); ?>" style="width: 50px;height: auto;display: none;" />
</div>
