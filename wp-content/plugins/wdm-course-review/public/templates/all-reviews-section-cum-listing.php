<?php
/**
 * This file is the template for showing reviews on the course single page.
 *
 * @package RatingsReviewsFeedback\Public\Reviews
 */

$is_review_comments_enabled = get_option( 'wdm_course_review_setting', 1 );
$rating_args                = array(
	'size'         => 'xs',
	'show-clear'   => false,
	'show-caption' => false,
	'readonly'     => true,
);

if ( is_user_logged_in() ) {
	$user_id = get_current_user_id();
}

$page_no        = 1;
$sort_by        = 'date';
$posts_per_page = apply_filters( 'rrf_number_of_reviews_per_page', get_option( 'posts_per_page', 10 ) );

$review_args = array(
	'posts_per_page' => $posts_per_page,
	'orderby'        => $sort_by,
	'course_id'      => -1,
	'filterby'       => -1,
	'page'           => 1,
);

if ( isset( $_REQUEST['orderby'] ) ) {
	$review_args['orderby'] = sanitize_key( $_REQUEST['orderby'] );
}

if ( isset( $_REQUEST['course_id'] ) && -1 != sanitize_key( $_REQUEST['course_id'] ) ) {
	$review_args['course_id'] = sanitize_key( $_REQUEST['course_id'] );
}

if ( isset( $_REQUEST['filterby'] ) && -1 != sanitize_key( $_REQUEST['filterby'] ) ) {
	$review_args['filterby'] = sanitize_key( $_REQUEST['filterby'] );
}

if ( isset( $_REQUEST['pno'] ) ) {
	$review_args['page'] = sanitize_key( $_REQUEST['pno'] );
}

$rrf_courses = rrf_get_all_courses();

$rrf_all_reviews = rrf_get_all_the_reviews(
	$review_args
);

$all_reviews = $rrf_all_reviews['result'];

$max_pages = $rrf_all_reviews['max_pages'];

?>
<div class="filter-options">
	<div class="select">
		<select class="select-text filter_by_course" required>
			<option value="-1" selected><?php esc_html_e( 'All Courses', 'wdm_ld_course_review' ); ?></option>
			<?php foreach ( $rrf_courses as $rrf_course ) : ?>
			<option value="<?php echo esc_attr( $rrf_course->ID ); ?>"><?php echo esc_html( $rrf_course->post_title ); ?></option>
			<?php endforeach; ?>
		</select>
		<span class="select-highlight"></span>
		<span class="select-bar"></span>
		<label class="select-label"><?php esc_html_e( 'Filter by Course', 'wdm_ld_course_review' ); ?></label>
	</div> <!-- first .select closing -->
	<div class="select">
		<select class="select-text sort_results" required>
			<option value="date" selected><?php esc_html_e( 'Most Recent', 'wdm_ld_course_review' ); ?></option>
			<option value="meta_value_num"><?php esc_html_e( 'Top Ratings', 'wdm_ld_course_review' ); ?></option>
		</select>
		<span class="select-highlight"></span>
		<span class="select-bar"></span>
		<label class="select-label"><?php esc_html_e( 'Sort by', 'wdm_ld_course_review' ); ?></label>
	</div> <!-- second .select closing -->
	<div class="select">
		<select class="select-text filter_results" required>
			<option value="-1" selected><?php esc_html_e( 'All Stars', 'wdm_ld_course_review' ); ?></option>
			<option value="5"><?php esc_html_e( '5 star only', 'wdm_ld_course_review' ); ?></option>
			<option value="4"><?php esc_html_e( '4 star only', 'wdm_ld_course_review' ); ?></option>
			<option value="3"><?php esc_html_e( '3 star only', 'wdm_ld_course_review' ); ?></option>
			<option value="2"><?php esc_html_e( '2 star only', 'wdm_ld_course_review' ); ?></option>
			<option value="1"><?php esc_html_e( '1 star only', 'wdm_ld_course_review' ); ?></option>
		</select>
		<span class="select-highlight"></span>
		<span class="select-bar"></span>
		<label class="select-label"><?php esc_html_e( 'Filter by', 'wdm_ld_course_review' ); ?></label>
	</div> <!-- third .select closing. -->
</div> <!-- .filter-options closing -->
<div class="loader hide"><img src="<?php echo esc_url( RRF_PLUGIN_URL . '/public/images/loader.gif' ); ?>"></div>
<div id="course-reviews-section" class="course-reviews-section">
	<div id="inside-course-reviews-section" class="inside-course-reviews-section">
		<input type="hidden" value="<?php echo esc_attr( $max_pages ); ?>" class="max_page_no" />
			<?php

			if ( empty( $all_reviews ) ) {
				esc_html_e( 'No Reviews Found', 'wdm_ld_course_review' );
				return;
			}

			$running_course_id = 0;

			$bool = false;

			foreach ( $all_reviews as $course_review ) :

				$course_id = $course_review['course_id'];
				$review_id = $course_review['review_id'];

				if ( 0 != $running_course_id ) {
					$bool = true;
				}

				if ( $running_course_id != $course_id ) :

					$running_course_id = $course_id;
					$course_ratings    = rrf_get_course_rating_details( $running_course_id );

					$course_title = get_the_title( $running_course_id );

					if ( $bool ) :
						?>
	</div><!-- .review_listing closing -->
</div> <!-- .wdm_course_rating_reviews closing -->
						<?php
					endif;
					?>
	<div class="reviews-listing-wrap" id="reviews-listing-wrap">
	<div class="wdm_course_rating_reviews">
	<div class="review_listing">
					<?php
				endif;
				$review = get_post( $review_id );

				include \ns_wdm_ld_course_review\Review_Submission::get_template( 'single-review.php' );

	endforeach;
			?>
	<div class = "rrf_prev_next_links" style = "position: relative; left: 40%;">
		<a href="#" class="prev" ><?php esc_html_e( '<< Previous Page', 'wdm_ld_course_review' ); ?></a>
		<a href="#" class="next" ><?php esc_html_e( 'Next Page >>', 'wdm_ld_course_review' ); ?></a>
	</div><!-- .rrf_prev_next_links closing -->
</div> <!-- .inside-course-reviews-section closing -->
</div> <!-- .course-reviews-section closing -->
