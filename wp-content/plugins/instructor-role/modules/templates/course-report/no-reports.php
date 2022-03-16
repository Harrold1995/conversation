<?php
/**
 * No Course Reports Found Template
 *
 * @var $icon_path  string  Path of the no report image displayed on the page.
 *
 * @since 3.3.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

?>
<div class="ir-no-enrollments">
	<p class="no-reports-message">
		<?php echo sprintf( esc_html__( 'No users enrolled in this %s yet to show reports', 'wdm_instructor_role' ), \LearnDash_Custom_Label::label_to_lower( 'course' ) ); ?>
	</p>
</div>
