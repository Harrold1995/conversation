<?php
/**
 * This class contains add shortcode to display survey details.
 *
 * @version 2.0.0
 * @package RatingsReviewsFeedback\Public\SurveyShortcode
 */

namespace ns_wdm_ld_course_review {
	if ( ! class_exists( 'Survey_Shortcode' ) ) {
		/**
		 * This class is used for loading the review template for course single page and the shortcode for the same.
		 *
		 * @SuppressWarnings(PHPMD.ShortVariable)
		 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
		 */
		class Survey_Shortcode {
			/**
			 * This property contains the singleton instance of the class.
			 *
			 * @var Class Object.
			 */
			protected static $instance = null;
			/**
			 * This method is used to add all action/filter hooks.
			 */
			public function __construct() {
				add_shortcode( 'rrf_survey_details', array( $this, 'rrf_survey_details_callback' ) );
				// filter to add feedback button.
				add_filter( 'ld_after_course_status_template_container', array( $this, 'show_attempt_survey_button' ), 99, 4 );

				// adding ajax for filtering question.
				\wdm_add_hook( 'wp_ajax_save_user_survey_response', 'save_user_survey_response', $this );
			}

			/**
			 * This function is used to fetch the instance of this class.
			 *
			 * @return Object returns class instance.]
			 */
			public static function get_instance() {
				if ( is_null( self::$instance ) ) {
					self::$instance = new self();
				}
				return self::$instance;
			}

			/**
			 * This method is used to render survey shortcode.
			 *
			 * @param  array $atts .
			 */
			public function rrf_survey_details_callback( $atts ) {
				$ajax_nonce = wp_create_nonce( 'rrf-nonce-save-survey-data' );
				rrf_load_jquery_modal_lib();
				wp_enqueue_style( 'survey-pop-up-css', plugins_url( 'public/css/survey-pop-up.css', RRF_PLUGIN_FILE ), array(), filemtime( RRF_PLUGIN_PATH . 'public/css/survey-pop-up.css' ) );
				wp_enqueue_script( 'survey-pop-up-js', plugins_url( 'public/js/survey-pop-up.js', RRF_PLUGIN_FILE ), array( 'jquery' ), WDM_LD_COURSE_VERSION, true );
				wp_localize_script(
					'survey-pop-up-js',
					'save_survey_response_ajax',
					array(
						'ajax_url' => admin_url( 'admin-ajax.php' ),
						'action'   => 'save_user_survey_response',
						'nonce'    => $ajax_nonce,
					)
				);
				wp_enqueue_script( 'survey-pop-up-js' );

				$atts                     = shortcode_atts(
					array(
						'post_id'         => 0,
						'survey_id'       => 0,
						'button_text'     => 'Submit',
						'button_bg_color' => '',
						'button_color'    => '',
						'redirect_url'    => get_site_url(),
					),
					$atts,
					'rrf_survey_details'
				);
				$survey_id                = $atts['survey_id'];
				$survey_details           = get_post( $survey_id );
				$post_id                  = $atts['post_id'];
				$survey_questions_details = get_post_meta( $survey_id, 'assigned_question', true );
				$assigned_question_final  = ( unserialize( $survey_questions_details ) !== null ) ? unserialize( $survey_questions_details ) : array();
				$total_questions          = count( $assigned_question_final );
				?>
					<div id="survey_pop_main_div" data-total-question="<?php echo esc_attr( $total_questions ); ?>" data-current-question-number="1">
						<div class="survey_pop_title_description_div">
							<div class="survey_title"><?php echo esc_attr( $survey_details->post_title ); ?></div>
							<div class="survey_description"><?php echo esc_html( $survey_details->post_content ); ?></div>
						</div>
						<div class="easy-navigation">
							<?php
							$iterator = 0;
							$width    = floor( 500 / $total_questions ) - ( 2 * $total_questions ); // 500 is max-width of each question element. and 2px margin is added after every element.
							while ( $iterator < $total_questions ) {
								$class = 'breadcrumb index-' . ( $iterator + 1 );
								if ( 0 === $iterator ) {
									$class .= ' attempting';
								}
								$iterator++;
								echo "<span class='" . esc_attr( $class ) . "' style='width: " . esc_attr( $width ) . "px;'></span>";
							}
							?>
						</div>
						<div class="survey_question_main_div">
							<form action="" class="survey_question_form" method='post'>
								<input type="hidden" name="post_id" value="<?php echo esc_attr( $post_id ); ?>">
								<input type="hidden" name="survey_id" value="<?php echo esc_attr( $survey_id ); ?>">
								<input type="hidden" name="user_id" value="<?php echo esc_attr( get_current_user_id() ); ?>">
								<?php
								foreach ( $assigned_question_final as $key => $question ) {
									$question_details = get_post( $question );
									$question_option  = get_post_meta( $question, 'question_option', true );
									if ( ! empty( $question_option ) ) {
										$question_option_final = unserialize( $question_option );
										?>
										<div class="survey_question_details_div survey_question_number_<?php echo esc_attr( $key + 1 ); ?>">
											<div class="survey_question_title">
												<b><?php echo esc_html( $key + 1 ); ?> . </b><?php echo esc_html( $question_details->post_title ); ?>
											</div>
											<div class="survey_question_options">
												<?php
												foreach ( $question_option_final as $option ) {
													?>
														<div class="survey_question_options_sub">
															<input class="survey_question_option_input" type="radio" name="<?php echo esc_attr( $question_details->ID ); ?>" id="survey_question_option_input-<?php echo esc_attr( $option ); ?>" value="<?php echo esc_attr( $option ); ?>">
															<label class="survey_question_option_label" for="survey_question_option_input-<?php echo esc_attr( $option ); ?>"><?php echo esc_html( $option ); ?></label>
														</div>
													<?php
												}
												?>
											</div>
										</div>
										<?php
									}
								}
								?>
								<div class="survey_pre_next_question">
									<div class="survey_pre_question_div">
										<button class="survey_pre_question_button" data-pre-number="0"><?php esc_html_e( 'Prev', 'wdm_ld_course_review' ); ?></button>
									</div>
									<div class="survey_submit_button">
										<button type="submit"><?php esc_html_e( 'Submit', 'wdm_ld_course_review' ); ?></button>
									</div>
									<div class="survey_next_question_div">
										<button class="survey_next_question_button" data-next-number="2"><?php esc_html_e( 'Next', 'wdm_ld_course_review' ); ?></button>
									</div>
								</div>
							</form>
						</div>
					</div>
					<div class="open_survey_pop_up_div">
						<button id="open_survey_pop_up" style="background: <?php echo esc_attr( $atts['button_bg_color'] ); ?>;color: <?php echo esc_attr( $atts['button_color'] ); ?>;">
							<?php echo esc_html( $atts['button_text'] ); ?>
						</button>
					</div>
					<div id="survey_thank_you_pop_up">
						<h2><?php esc_html_e( 'Thank you for taking the time to complete this survey', 'wdm_ld_course_review' ); ?></h2>
					</div>
				<?php
			}

			/**
			 * Ajax function to filter question.
			 */
			public function save_user_survey_response() {
				$result = array(
					'success' => false,
				);

				$security = filter_input( INPUT_POST, 'security' );
				if ( ! isset( $security ) || ! wp_verify_nonce( $security, 'rrf-nonce-save-survey-data' ) ) {
					wp_send_json_error();
				}

				/**
				 * Filters the user reponse on survey.
				 *
				 * @param int    $user_id       User ID.
				 * @param int    $survey_id     Survey ID.
				 * @param int    $post_id       Post ID.
				 * @param string $user_response User Response.
				 */
				apply_filters(
					'rff_user_survey_response',
					array(
						'user_id'       => $user_id,
						'survey_id'     => $survey_id,
						'post_id'       => $post_id,
						'user_response' => $user_response,
					)
				);

				$user_id             = filter_input( INPUT_POST, 'user_id', FILTER_VALIDATE_INT );
				$survey_id           = filter_input( INPUT_POST, 'survey_id', FILTER_VALIDATE_INT );
				$post_id             = filter_input( INPUT_POST, 'post_id', FILTER_VALIDATE_INT );
				$user_response       = filter_input( INPUT_POST, 'user_response', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
				$custom_table        = RRF_CUSTOM_DB_TABLE::get_instance();
				$user_survey_details = $custom_table->rrf_get_data_custom_db_table( $post_id, $user_id, $survey_id );
				/**
				* Check if user already attemped survey or not
				*/

				if ( count( $user_survey_details ) === 0 ) {
					$custom_table->rrf_add_data_custom_db_table(
						array(
							'post_id'       => $post_id,
							'user_id'       => $user_id,
							'survey_id'     => $survey_id,
							'user_response' => serialize( $user_response ),
							'date_time'     => gmdate( 'Y-m-d H:i:s' ),
						)
					);
					/**
					 * Action after save data of survey
					 *
					 * @param int $user_id   User ID.
					 * @param int $survey_id Survey ID.
					 * @param int $post_id   Post ID.
					 */
					do_action( 'rrf_survey_submitted_by_user', $user_id, $survey_id, $post_id );
				}
				$result['success'] = true;
				wp_send_json_success( $result );
			}

			/**
			 * To show the survey button on course single page after user completes the course.
			 *
			 * @param string $content       [default content].
			 * @param string $course_status [course status].
			 * @param int    $course_id     [course id].
			 * @param int    $user_id       [user id].
			 *
			 * @return string $content       [modified content]
			 */
			public function show_attempt_survey_button( $content, $course_status, $course_id, $user_id ) {
				global $wpdb;
				$course_status = $course_status;
				$survey_id     = get_post_meta( $course_id, 'assigned_survey', true );
				if ( learndash_course_completed( $user_id, $course_id ) && $survey_id ) {
					$user_survey_details = $wpdb->get_results(
						$wpdb->prepare(
							"
								SELECT *
								FROM {$wpdb->prefix}rrf_survey
								WHERE post_id = %d
								AND user_id = %d
								AND survey_id = %d
							",
							$course_id,
							$user_id,
							$survey_id
						)
					);

					/**
					 * Check if user already attemped survey or not
					 */

					if ( null !== get_post( $survey_id ) && 'publish' == get_post_status( $survey_id ) && count( $user_survey_details ) === 0 ) {
						echo do_shortcode( '[rrf_survey_details post_id=' . $course_id . ' survey_id=' . $survey_id . ' button_text="'. __( 'Submit Survey', 'wdm_ld_course_review' ) .'" button_color="" button_bg_color=""]' );
					}
				}
				return $content;
			}

		}
	}
	Survey_Shortcode::get_instance();
}
