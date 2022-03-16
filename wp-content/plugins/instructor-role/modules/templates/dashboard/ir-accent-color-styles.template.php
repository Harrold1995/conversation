<?php
/**
 * Accent color style template
 *
 * Dynamic styling for instructor dashboard for selected accent color.
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

?>

#adminmenu a:hover,#adminmenu li.menu-top>a:focus,#adminmenu .wp-submenu a:hover,#adminmenu .wp-submenu a:focus {
	color: <?php echo esc_attr( $accent_color ); ?>
}

input[type="text"]:focus,input[type="password"]:focus,input[type="color"]:focus,input[type="date"]:focus,input[type="datetime"]:focus,input[type="datetime-local"]:focus,input[type="email"]:focus,input[type="month"]:focus,input[type="number"]:focus,input[type="search"]:focus,input[type="tel"]:focus,input[type="time"]:focus,input[type="url"]:focus,input[type="week"]:focus,input[type="checkbox"]:focus,input[type="radio"]:focus,select:focus,textarea:focus {
	border-color: <?php echo esc_attr( $accent_color ); ?>;
}

.ld-node-header .toggle svg path,.ld-expand-collapse-all svg path {
	stroke: <?php echo esc_attr( $accent_color ); ?> !important
}

.ld-node-header h2 button svg,.ld-node-header h3 button svg {
	fill: <?php echo esc_attr( $accent_color ); ?> !important
}

.ld__builder--app input[type=submit].is-primary,.components-button.is-primary,#elementor-switch-mode-button {
	background-color: <?php echo esc_attr( $accent_color ); ?> !important;
}

.components-button.is-tertiary {
	color: <?php echo esc_attr( $accent_color ); ?> !important
}

.components-button.is-secondary:hover:not(:disabled),.components-button.is-tertiary:hover:not(:disabled) {
	color: <?php echo esc_attr( $accent_color ); ?>;
}

#poststuff #learndash-course-display-content-settings .inside .ld-radio-input-wrapper .ld-radio-input:checked+.ld-radio-input__label:before,#poststuff #learndash-course-access-settings .inside .ld-radio-input-wrapper .ld-radio-input:checked+.ld-radio-input__label:before,#poststuff #learndash-course-navigation-settings .inside .ld-radio-input-wrapper .ld-radio-input:checked+.ld-radio-input__label:before,#poststuff #sfwd-courses .inside .ld-radio-input-wrapper .ld-radio-input:checked+.ld-radio-input__label:before,#poststuff #learndash-course-grid-meta-box .inside .ld-radio-input-wrapper .ld-radio-input:checked+.ld-radio-input__label:before,#poststuff #learndash-quiz-access-settings .inside .ld-radio-input-wrapper .ld-radio-input:checked+.ld-radio-input__label:before,#poststuff #learndash-quiz-progress-settings .inside .ld-radio-input-wrapper .ld-radio-input:checked+.ld-radio-input__label:before,#poststuff #learndash-quiz-display-content-settings .inside .ld-radio-input-wrapper .ld-radio-input:checked+.ld-radio-input__label:before,#poststuff #learndash-quiz-results-options .inside .ld-radio-input-wrapper .ld-radio-input:checked+.ld-radio-input__label:before,#poststuff #learndash-quiz-admin-data-handling-settings .inside .ld-radio-input-wrapper .ld-radio-input:checked+.ld-radio-input__label:before {
	border: 5px solid <?php echo esc_attr( $accent_color ); ?>
}
#sfwd-header .ld-global-header .ld-tab-buttons .is-primary {
	color: <?php echo esc_attr( $accent_color ); ?> !important;
}

#side-sortables #sfwd-course-lessons .inside .ld__builder-sidebar-header a,#side-sortables #sfwd-course-topics .inside .ld__builder-sidebar-header a,#side-sortables #sfwd-course-quizzes .inside .ld__builder-sidebar-header a,#side-sortables #sfwd-quiz-questions .inside .ld__builder-sidebar-header a {
	color: <?php echo esc_attr( $accent_color ); ?>
}

#side-sortables #sfwd-course-lessons .inside .ld__builder-sidebar-refresh,#side-sortables #sfwd-course-topics .inside .ld__builder-sidebar-refresh,#side-sortables #sfwd-course-quizzes .inside .ld__builder-sidebar-refresh,#side-sortables #sfwd-quiz-questions .inside .ld__builder-sidebar-refresh {
	color: <?php echo esc_attr( $accent_color ); ?>
}

#learndash_course_builder .inside .ld__builder--app .ld__builder--footer .ld__builder--final-quizzes .ld__builder--new-entities a svg,#learndash_quiz_builder .inside .ld__builder--app .ld__builder--footer .ld__builder--final-quizzes .ld__builder--new-entities a svg {
	fill: <?php echo esc_attr( $accent_color ); ?>
}
#learndash_course_builder .inside .ld__builder--app .ld__builder--content .ld__builder--new-entities a svg,#learndash_quiz_builder .inside .ld__builder--app .ld__builder--content .ld__builder--new-entities a svg {
	fill: <?php echo esc_attr( $accent_color ); ?>
}
.irb-btn {
	background-color: <?php echo esc_attr( $accent_color ); ?> !important;
}

.nav-tab-wrapper .nav-tab.nav-tab-active {
	color: <?php echo esc_attr( $accent_color ); ?>;
}

.folded #adminmenu li.menu-top .wp-submenu>li.wp-submenu-head {
	background-color: <?php echo esc_attr( $accent_color ); ?>
}

.irb-icon-add:before {
	color: <?php echo esc_attr( $accent_color ); ?>
}

.irb-icon-play:before {
	color: <?php echo esc_attr( $accent_color ); ?>
}

#wdm_report_tbl a,#reports_table_div a,.ir-payout-transactions-container a,.ir-assignments-table a {
	color: <?php echo esc_attr( $accent_color ); ?>
}

#learndash_course_builder .inside .ld__builder--app .ld__builder--content .ld__builder--new-entities a,#learndash_quiz_builder .inside .ld__builder--app .ld__builder--content .ld__builder--new-entities a {
	color: <?php echo esc_attr( $accent_color ); ?>    
}

#learndash_course_builder .inside .ld__builder--app .ld__builder--footer .ld__builder--final-quizzes .ld__builder--new-entities a,#learndash_quiz_builder .inside .ld__builder--app .ld__builder--footer .ld__builder--final-quizzes .ld__builder--new-entities a {
	color: <?php echo esc_attr( $accent_color ); ?>
}

a{
	color: <?php echo esc_attr( $accent_color ); ?>;
}

body .global-new-entity-button{
	background: <?php echo esc_attr( $accent_color ); ?>;
}

.wp-core-ui .button, .wp-core-ui .button-secondary{
	color: <?php echo esc_attr( $accent_color ); ?>;
	border-color: <?php echo esc_attr( $accent_color ); ?>;
}

#adminmenu li.menu-top:hover, #adminmenu li.opensub>a.menu-top, #adminmenu li>a.menu-top:focus {
	color: <?php echo esc_attr( $accent_color ); ?>;
}
