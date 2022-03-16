<?php
/**
 * This file is the feedback form template.
 *
 * @package RatingsReviewsFeedback\Public\Feedback
 */

$course = get_post( $course_id );

$review_status = get_post_meta( $course->ID, 'is_ratings_enabled', true );
?>
<button class="btn wdm_feedback_form_pop"><?php echo esc_html( $btn_text ); ?></button>
<div id="wdm_feedback_form_pop_content" style="display:none">
	<form class="wdm_feedback_form" enctype="multipart/form-data">
		<div>
			<label>
				<?php
				/* translators: %s : Course Title. */
				echo sprintf( esc_html__( 'Your feedback on %s', 'wdm_ld_course_review' ), $course->post_title );//phpcs:ignore
				?>
			</label>
		</div>
		<div>
			<div class="text-error"></div>
			<textarea id="wdm_course_feedback_text" class="wdm_course_feedback_textarea" name="wdm_course_feedback_text" rows="5" placeholder="<?php esc_attr_e( 'Provide your feedback', 'wdm_ld_course_review' ); ?>" maxlength="<?php echo esc_attr( $maxlength ); ?>"></textarea>
			<div class="wdm_rrf_remaining_characters">
				<span class="wdm_cff_remaining_count"><?php echo esc_html( $maxlength ); ?></span>
				<span><?php esc_html_e( 'remaining character(s)', 'wdm_ld_course_review' ); ?></span>
			</div>
		</div>
		<div class="feedback-image-submission">
			<input type="file" name="feedback_images[]" id="feedback_images" accept="image/*" multiple/>
			<div class="upload-error"></div>
		</div>
			<button class="btn wdm_feedback_submission" name="wdm_feedback_sub_btn"><?php esc_html_e( 'Send Feedback', 'wdm_ld_course_review' ); ?></button>
			<p class="wdm_rrf_feedback_notice_msg">
				<?php
				echo esc_html( apply_filters( 'rrf_feedback_modal_notice', sprintf( __( '*This feedback will be privately emailed to the author.', 'wdm_ld_course_review' ) ) ) );
				?>
				<?php
				if ( ! empty( $review_status ) && 'no' !== $review_status ) :
					$review_url  = trailingslashit( get_permalink( $course_id ) ) . '#course-reviews-section';
					$review_link = '<a class="feedback_review_link" href="' . $review_url . '">' . __( 'click here', 'wdm_ld_course_review' ) . '</a>';
					/* translators: %1$s : Course Label Title %2$s: Review Link */
					echo sprintf( __( 'If you want to review/rate the %1$s please %2$s', 'wdm_ld_course_review' ), strtolower( rrf_get_course_label() ), $review_link );//phpcs:ignore
				endif;
				?>
			</p>
	</form>
</div>
