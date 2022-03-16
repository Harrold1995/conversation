<?php
/**
 * This file is used to require all the files needed for admin-side logic.
 *
 * @package Admin
 */

/**
 * Include all adminside files as well as functional logic.
 */
require_once 'class-course-review-cpt.php'; // Review CPT.
require_once 'class-course-metabox.php';    // For adding meta box on course.
require_once 'class-course-feedback-cpt.php'; // Feedback CPT.
require_once 'class-instructor-handler.php'; // IR complatibility.
require_once 'class-review-prompt-email.php'; // Review Prompt Email.
require_once 'class-review-reminder-cron.php'; // Review Reminder Email.
/**
 * This method is used to add additional links for support, documentation and changelog.
 *
 * @param  array  $links All the link for a plugin.
 * @param  string $file  basename for the plugin.
 * @return array        Updated Links.
 */
function rrf_add_additional_links( $links, $file ) {
	if ( plugin_basename( RRF_PLUGIN_FILE ) === $file ) {
		$row_meta = array(
			'docs'      => '<a href="' . esc_url( 'https://wisdmlabs.com/docs/product/wisdm-ratings-reviews-feedback/' ) . '" aria-label="' . esc_attr__( 'View Ratings, Reviews & Feedback documentation', 'wdm_ld_course_review' ) . '">' . esc_html__( 'Docs', 'wdm_ld_course_review' ) . '</a>',
			'support'   => '<a href="' . esc_url( 'https://wisdmlabs.com/product-support/#contact' ) . '" aria-label="' . esc_attr__( 'Visit customer support', 'wdm_ld_course_review' ) . '">' . esc_html__( 'Plugin support', 'wdm_ld_course_review' ) . '</a>',
			'changelog' => '<a href="' . esc_url( 'https://wisdmlabs.com/docs/article/wisdm-ratings-reviews-feedback/changelog-ratings-reviews-feedback/changelog-3/' ) . '" aria-label="' . esc_attr__( 'Visit Changelog', 'wdm_ld_course_review' ) . '">' . esc_html__( 'Changelog', 'wdm_ld_course_review' ) . '</a>',
		);

		return array_merge( $links, $row_meta );
	}

	return (array) $links;
}
add_filter( 'plugin_row_meta', 'rrf_add_additional_links', 10, 2 );
require_once 'class-rrf-survey-cpt.php'; // Survey CPT.
require_once 'class-rrf-question-cpt.php'; // Survey Question CPT.
