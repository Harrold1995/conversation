<?php
/**
 * Template : Instructor Overview Template
 *
 * @param array $course_list        List of instructor course ids
 * @param string $ajax_loader       Ajax Loader path
 * @param object $instance          Instance of Instructor_Role_Overview class.
 * @since 3.1.0
 */

?>
<div class="irb-container">
	<div class="irb-overview-wrap">
		<div class="irb-overview">
			<h1><?php esc_html_e( 'Instructor Overview', 'wdm_instructor_role' ); ?></h1>
			<div class="irb-tiles-wrap">
				<div class="irb-tile">
					<div class="irb-tile-header">
						<span class="irb-header-icon">
							<i class="irb-icon-courses"></i>
						</span>
						<span class="irb-header-text"><?php echo esc_attr( $instance->courses_label ); ?></span>
					</div>	
					<div class="irb-tile-content">
						<span class="irb-tile-value"><?php echo esc_attr( $instance->course_count ); ?></span>
					</div>
				</div>

				<div class="irb-tile">
					<div class="irb-tile-header">
						<span class="irb-header-icon">
							<i class="irb-icon-student"></i>
						</span>
						<span class="irb-header-text"><?php esc_html_e( 'Students', 'wdm_instructor_role' ); ?></span>
					</div>	
					<div class="irb-tile-content">
						<span class="irb-tile-value"><?php echo esc_attr( $instance->student_count ); ?></span>
					</div>
				</div>
				<?php if ( ! empty( $instance->addon_info ) && array_key_exists( 'products', $instance->addon_info ) ) : ?>
					<div class="irb-tile">
						<div class="irb-tile-header">
							<span class="irb-header-icon">
								<i class="irb-icon-product"></i>
							</span>
							<span class="irb-header-text"><?php esc_html_e( 'Products', 'wdm_instructor_role' ); ?></span>
						</div>	
						<div class="irb-tile-content">
							<span class="irb-tile-value"><?php echo esc_attr( $instance->addon_info['products'] ); ?></span>
						</div>
					</div>
				<?php endif; ?>
			</div>
			<div class="irb-tiles-wrap irb-charts">
				<?php if ( ! ir_admin_settings_check( 'instructor_commission' ) ) : ?>
					<div class="irb-tile irb-medium">
						<div class="irb-tile-header">
							<span class="irb-header-text irb-bold"><?php esc_html_e( 'Earnings', 'wdm_instructor_role' ); ?></span>
						</div>	
						<div class="irb-tile-content">
							<div id="ir-earnings-pie-chart-div"></div>
						</div>
					</div>
				<?php endif; ?>
				<div class="irb-tile irb-medium">
					<div class="irb-tile-header">
						<span class="irb-header-text irb-bold"><?php esc_html_e( 'Course Reports', 'wdm_instructor_role' ); ?></span>
					</div>	
					<div class="irb-tile-content">
						<div class="ir-ajax-overlay" style="display: none;">
							<img src="<?php echo esc_attr( $ajax_icon ); ?>" alt="Loading...">
						</div>
						<?php if ( ! empty( $course_list ) ) : ?>
							<div class="ir-instructor-course-select-wrap">
								<select name="sel-instructor-courses" id="instructor-courses-select">
									<?php foreach ( $course_list as $key => $course_id ) : ?>
										<option value="<?php echo esc_attr( $course_id ); ?>" <?php echo ! ( $key ) ? 'selected' : ''; ?>>
											<?php echo esc_html( get_the_title( $course_id ) ); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
							<div id="ir-course-pie-chart-div"></div>
						<?php else : ?>
							<?php echo sprintf( esc_html__( 'There are no %s to show reports', 'wdm_instructor_role' ), \LearnDash_Custom_Label::get_label( 'courses' ) ); ?>
						<?php endif; ?>
					</div>
				</div>
				<!-- <?php if ( ! ir_admin_settings_check( 'instructor_commission' ) ) : ?>
					<div class="ir-earnings ir-chart ir-theme-color">
						<div class="ir-chart-title"><?php esc_html_e( 'Earnings', 'wdm_instructor_role' ); ?></div>
						<div id="ir-earnings-pie-chart-div"></div>
					</div>
				<?php endif; ?>
				<div class="ir-course-reports ir-chart">
					<div class="ir-chart-title"><?php esc_html_e( 'Course Reports', 'wdm_instructor_role' ); ?></div>
					<div class="ir-ajax-overlay" style="display: none;">
						<img src="<?php echo esc_attr( $ajax_icon ); ?>" alt="Loading...">
					</div>
					<div class="ir-instructor-course-select-wrap">
						<select name="sel-instructor-courses" id="instructor-courses-select">
						<?php if ( ! empty( $course_list ) ) : ?>
							<?php foreach ( $course_list as $key => $course_id ) : ?>
								<option value="<?php echo esc_attr( $course_id ); ?>" <?php echo ! ( $key ) ? 'selected' : ''; ?>>
									<?php echo esc_html( get_the_title( $course_id ) ); ?>
								</option>
							<?php endforeach; ?>
						<?php else : ?>
							<?php echo sprintf( esc_html__( 'No %s created', 'wdm_instructor_role' ), \LearnDash_Custom_Label::get_label( 'course' ) ); ?>
						<?php endif; ?>
						</select>
					</div>
					<div id="ir-course-pie-chart-div"></div>
				</div> -->
			</div>
			<div class="irb-tiles-wrap irb-sub">
				<div class="irb-tile irb-large">
					<div class="irb-tile-header">
						<span class="irb-header-text irb-bold"><?php esc_html_e( 'Submissions', 'wdm_instructor_role' ); ?></span>
					</div>	
					<div class="irb-tile-content">
						<?php $instance->generateSubmissionReports(); ?>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
	
