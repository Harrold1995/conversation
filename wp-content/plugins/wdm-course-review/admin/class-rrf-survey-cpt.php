<?php
/**
 * This file is used to include the class which registers Survey CPT in WordPress.
 *
 * @package RatingsReviewsFeedback\Admin\Survey
 */

namespace ns_wdm_ld_course_review{

	/**
	 * This will create custom post type called survey to handle user response of course.
	 */
	class RRF_SURVEY_CPT {
		/**
		 * CPT Slug
		 *
		 * @var string
		 */
		public $cpt = 'rff_survey';
		/**
		 * Fields shown in metabox.
		 *
		 * @var array
		 */
		public $meta_box_survey = array();
		/**
		 * Dunno.
		 *
		 * @var array
		 */
		public $meta_box_posts = array();
		/**
		 * Instance of this class.
		 *
		 * @since    1.0.0
		 *
		 * @var object
		 */
		protected static $instance = null;
		/**
		 * Constructor for the class.
		 * Used to initialize all the hooks in the class.
		 */
		public function __construct() {
			// registering cpt of course survey.
			\wdm_add_hook( 'init', 'create_post_type', $this, array( 'priority' => 11 ) );
			// adding meta box for survey details.
			\wdm_add_hook( 'add_meta_boxes', 'add_meta_boxes', $this );

			// adding ajax for filtering question.
			\wdm_add_hook( 'wp_ajax_filter_question', 'filter_question', $this );

			// adding ajax for filtering question.
			\wdm_add_hook( 'wp_ajax_filter_survey_report', 'filter_survey_report', $this );

			// for saving meta box values.
			\wdm_add_hook( 'save_post', 'save_meta_boxes', $this, array( 'num_args' => 3 ) );

			// adding menu on dashboard.
			\wdm_add_hook( 'admin_menu', 'real_admin_menu', $this );
		}
		/**
		 * Returns an instance of this class.
		 *
		 * @since     1.0.0
		 *
		 * @return object A single instance of this class.
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Adding menu items to admin must be done in admin_menu which gets executed BEFORE admin_init.
		 */
		public function real_admin_menu() {
			add_submenu_page(
				'edit.php?post_type=rff_survey',
				__( 'Survey Questions', 'wdm_ld_course_review' ),
				__( 'Survey Questions', 'wdm_ld_course_review' ),
				'manage_options',
				'edit.php?post_type=rff_question'
			);
			add_submenu_page(
				'edit.php?post_type=rff_survey',
				__( 'User Responses', 'wdm_ld_course_review' ),
				__( 'User Responses', 'wdm_ld_course_review' ),
				'manage_options',
				'rff_survey_report',
				array( &$this, 'rff_survey_report_callback' )
			);

			remove_submenu_page( 'edit.php?post_type=rff_survey', 'post-new.php?post_type=rff_survey' );

			wp_enqueue_script( 'rrf-survey-report-js', plugins_url( 'admin/js/rrf-survey-report.js', RRF_PLUGIN_FILE ), array( 'jquery' ), WDM_LD_COURSE_VERSION );
			wp_enqueue_script( 'rrf-survey-report-jquery.canvasjs.min', plugins_url( 'admin/js/jquery.canvasjs.min.js', RRF_PLUGIN_FILE ), array( 'jquery' ), WDM_LD_COURSE_VERSION );
			wp_enqueue_style( 'rff-survey-report-css', plugins_url( 'admin/css/rrf-survey-report.css', RRF_PLUGIN_FILE ), array(), filemtime( RRF_PLUGIN_PATH . 'admin/css/rrf-survey-report.css' ) );
			$ajax_nonce = wp_create_nonce( 'rrf-nonce-survey-report' );
				wp_localize_script(
					'rrf-survey-report-js',
					'filter_survey_report_ajax',
					array(
						'ajax_url'           => admin_url( 'admin-ajax.php' ),
						'action'             => 'filter_survey_report',
						'security'           => $ajax_nonce,
						'no_of_users_i18n'   => __( 'Number of Users', 'wdm_ld_course_review' ),
						'user_i18n'          => __( 'User Name', 'wdm_ld_course_review' ),
						'date_i18n'          => __( 'Date', 'wdm_ld_course_review' ),
						'question_i18n'      => __( 'Question', 'wdm_ld_course_review' ),
						'user_response_i18n' => __( 'User Response', 'wdm_ld_course_review' ),
						'options_i18n'       => __( 'Options', 'wdm_ld_course_review' ),
						'total_users_i18n'   => __( 'Total Users', 'wdm_ld_course_review' ),
					)
				);
			wp_enqueue_script( 'rrf-survey-report-js' );
		}

		/**
		 * Function to render submenu page
		 */
		public function rff_survey_report_callback() {
			$args         = array(
				'order'          => 'ASC',
				'post_type'      => 'sfwd-courses',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
			);
			$courses      = get_posts( $args );
			$course_label = learndash_get_custom_label( 'course' );
			?>
				<div class="rrf_survey_report_title">
					<h3><?php esc_html_e( 'Survey Results', 'wdm_ld_course_review' ); ?></h3>
				</div>
				<div class="rrf_survey_report_main">
					<div class="rrf_survey_report_main_one">
						<div class="rrf_survey_course_details_filter">
							<div class="rrf_survey_course_details_form">
								<form action='#' method='post'>
									<div class="rrf_survey_course_details_form_fields">
										<div class="rrf_survey_course_details_form_fields_course">
											<label style="margin-right: 7px;"><?php echo esc_html( $course_label ); ?></label>
											<select name="rrf_survey_course_filter" class="rrf_survey_course_filter">
												<?php /* translators: %s: Course Label */ ?>
												<option value=""><?php echo esc_html( sprintf( __( 'Select %s', 'wdm_ld_course_review' ), $course_label ) ); ?></option>
												<?php
												foreach ( $courses as $course ) {
													?>
														<option value="<?php echo esc_attr( $course->ID ); ?>"><?php echo esc_html( $course->post_title ); ?></option>
													<?php
												}
												?>
											</select>
										</div>
										<div class="rrf_survey_course_details_form_fields_from">
											<label style="margin-right: 20px;"><?php esc_html_e( 'From', 'wdm_ld_course_review' ); ?></label>
											<input type="date" name="rrf_survey_date_from_filter" class="rrf_survey_date_from_filter">
										</div>
										<div class="rrf_survey_course_details_form_fields_to">
											<label style="margin-right: 36px;"><?php esc_html_e( 'To', 'wdm_ld_course_review' ); ?></label>
											<input type="date" name="rrf_survey_date_to_filter" class="rrf_survey_date_to_filter">
										</div>
										<div class="rrf_get_report_button_div">
											<button class="rrf_get_report_button button-primary"><?php esc_html_e( 'Get Report', 'wdm_ld_course_review' ); ?></button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
					<div class="rrf_actual_user_response_report">
						<div class="rrf_after_filter_details">
							<div class="rrf_after_filter_details_course">
								<strong><?php echo esc_html( $course_label ); ?> : </strong>
								<span class="rrf_after_filter_details_course_name"></span>
							</div>
							<div>
								<strong><?php esc_html_e( 'Survey : ', 'wdm_ld_course_review' ); ?></strong>
								<span class="rrf_after_filter_details_survey_name"></span>
							</div>
						</div>
						<div class="rrf_actual_chart_based_report">
							<div class="question_handling_section">
								<div class="rrf_after_filter_details_question_prev"><button class="rrf_after_filter_details_question_prev_button button" data-pre-number="0" disabled="disabled"><?php esc_html_e( 'Previous', 'wdm_ld_course_review' ); ?></button></div>
								<div class="rrf_after_filter_details_question_details" data-current-question-number="1" data-total-question="0"></div>
								<div class="rrf_after_filter_details_question_next"><button class="rrf_after_filter_details_question_next_button button" data-next-number="2"><?php esc_html_e( 'Next', 'wdm_ld_course_review' ); ?></button></div>
							</div>
							<div id="rrf_actual_pie_chart" style="height: 370px; width: 100%;">
							</div>
						</div>
					<div class="user_based_report"><strong><?php esc_html_e( 'Summarized Report', 'wdm_ld_course_review' ); ?></strong></div>
					<div class="rrf_actual_user_based_report"></div>
					</div>
					<div class="user_based_report"><strong><?php esc_html_e( 'Detailed Report', 'wdm_ld_course_review' ); ?></strong></div>
					<div class="rrf_detailed_report"></div>
				</div>
			<?php
		}

		/**
		 * Ajax callback to filter survey data
		 */
		public function filter_survey_report() {
			$custom_table = RRF_CUSTOM_DB_TABLE::get_instance();
			if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( sanitize_key( $_POST['security'] ), 'rrf-nonce-survey-report' ) ) {
				echo wp_json_encode( array( 'success' => false ) );
				die();
			}

			$course_id = filter_input( INPUT_POST, 'course_id' );
			$from_date = filter_input( INPUT_POST, 'from_date' );
			$to_date   = filter_input( INPUT_POST, 'to_date' );
			$survey_id = get_post_meta( $course_id, 'assigned_survey', true );

			if ( $survey_id ) {
				if ( empty( $from_date ) && empty( $to_date ) ) {
					$user_survey_details = $custom_table->rrf_get_data_by_post_survey( $course_id, $survey_id );
				} elseif ( $from_date ) {
					$user_survey_details = $custom_table->rrf_get_data_by_post_survey_from_date( $course_id, $survey_id, $from_date );
				} elseif ( ! empty( $from_date ) && ! empty( $to_date ) ) {
					$user_survey_details = $custom_table->rrf_get_data_by_post_survey_from_and_to_date( $course_id, $survey_id, $from_date, $to_date );
				}
				if ( $user_survey_details ) {
					foreach ( $user_survey_details as $user_survey ) {
						$user_survey->user_name     = get_userdata( $user_survey->user_id )->display_name;
						$user_survey->user_response = unserialize( $user_survey->user_response );
					}
				}
				$course_details                  = get_post( $course_id );
				$course_name                     = $course_details->post_title;
				$survey_details                  = get_post( $survey_id );
				$survey_name                     = $survey_details->post_title;
				$survey_assigned_question        = unserialize( get_post_meta( $survey_id, 'assigned_question', true ) );
				$survey_assigned_question_option = array();
				if ( isset( $survey_assigned_question ) ) {
					foreach ( $survey_assigned_question as $question ) {
						$question_details        = get_post( $question );
						$question_option         = unserialize( get_post_meta( $question, 'question_option', true ) );
						$question_option_details = array(
							'question_id'     => $question,
							'question_name'   => $question_details->post_title,
							'question_option' => $question_option,
						);
						array_push( $survey_assigned_question_option, $question_option_details );
					}
				}
				wp_send_json_success(
					array(
						'course_id'          => $course_id,
						'course_name'        => $course_name,
						'survey_id'          => $survey_id,
						'survey_name'        => $survey_name,
						'question_data'      => $survey_assigned_question_option,
						'user_response_data' => $user_survey_details,
					)
				);
			} else {
				echo wp_json_encode( array() );
				die();
			}
		}

		/**
		 * Registering survey CPT.
		 */
		public function create_post_type() {
			$labels = array(
				'name'               => _x( 'Course Survey', 'wdm_ld_course_review' ),
				'singular_name'      => _x( 'Course Survey', 'wdm_ld_course_review' ),
				'add_new'            => _x( 'Add New', 'wdm_ld_course_review' ),
				'add_new_item'       => __( 'Add New Survey', 'wdm_ld_course_review' ),
				'edit_item'          => __( 'Edit Survey', 'wdm_ld_course_review' ),
				'new_item'           => __( 'New Survey', 'wdm_ld_course_review' ),
				'all_items'          => __( 'All Surveys', 'wdm_ld_course_review' ),
				'view_item'          => __( 'View Survey', 'wdm_ld_course_review' ),
				'search_items'       => __( 'Search Surveys', 'wdm_ld_course_review' ),
				'not_found'          => __( 'No surveys found', 'wdm_ld_course_review' ),
				'not_found_in_trash' => __( 'No surveys found in the Trash', 'wdm_ld_course_review' ),
				'menu_name'          => 'Course Survey',
			);

			$args = array(
				'labels'              => $labels,
				'label'               => __( 'Course Survey', 'wdm_ld_course_review' ),
				'description'         => __( 'Course Surveys', 'wdm_ld_course_review' ),
				'menu_position'       => 25,
				'supports'            => array( 'title', 'editor', 'author' ),
				'exclude_from_search' => true,
				'show_in_nav_menus'   => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'can_export'          => true,
				'show_in_admin_bar'   => false,
				'has_archive'         => false,
				'menu_icon'           => 'dashicons-text-page',
			);
			register_post_type( $this->cpt, $args );
		}

		/**
		 * Adding question to the survey details metabox on survey page.
		 */
		public function add_meta_boxes() {
			add_meta_box( 'rrf_survey_question_add', __( 'Add Question', 'wdm_ld_course_review' ), array( $this, 'show_surevy_add_question' ), $this->cpt, 'normal', 'high', array( 'type' => 'meta_box_survey_add_question' ) );
			add_meta_box( 'rrf_add_courses_to_survey', __( 'Survey Courses', 'wdm_ld_course_review' ), array( $this, 'assign_courses_to_survey' ), $this->cpt, 'normal', 'high', null );
			add_action( 'admin_print_scripts', array( $this, 'load_style_n_script' ) );
		}

		/**
		 * Loading css to hide add new button.
		 */
		public function load_style_n_script() {
			global $post;
			if ( isset( $post ) && $post->post_type === $this->cpt ) {
				wp_enqueue_script( 'jquery-ui-core' );
				wp_enqueue_script( 'jquery-ui-draggable' );
				wp_enqueue_script( 'jquery-ui-droppable' );
				wp_enqueue_script( 'rrf-survey-custom-js', plugins_url( 'admin/js/rrf-survey-add-question.js', RRF_PLUGIN_FILE ), array( 'jquery' ), WDM_LD_COURSE_VERSION );
				wp_enqueue_script( 'rrf-survey-select2-min-js', plugins_url( 'admin/js/select2.full.min.js', RRF_PLUGIN_FILE ), array( 'jquery' ), WDM_LD_COURSE_VERSION );
				wp_enqueue_style( 'rff-survey-select2-css', plugins_url( 'admin/css/select2.min.css', RRF_PLUGIN_FILE ), array(), filemtime( RRF_PLUGIN_PATH . 'admin/css/select2.min.css' ) );
				wp_enqueue_style( 'rff-survey-css-survey', plugins_url( 'admin/css/rff-survey.css', RRF_PLUGIN_FILE ), array(), filemtime( RRF_PLUGIN_PATH . 'admin/css/rff-survey.css' ) );
				$ajax_nonce = wp_create_nonce( 'rrf-nonce-search-questions' );
				wp_localize_script(
					'rrf-survey-custom-js',
					'filter_question_ajax',
					array(
						'ajax_url'    => admin_url( 'admin-ajax.php' ),
						'action'      => 'filter_question',
						'security'    => $ajax_nonce,
						'placeholder' => __( 'Select Courses', 'wdm_ld_course_review' ),
					)
				);
				wp_enqueue_script( 'rrf-survey-custom-js' );
			}
		}

		/**
		 * Callback function to show question option.
		 *
		 * @param object $post [post object].
		 * @return void
		 */
		public function assign_courses_to_survey( $post ) {
			$assigned_courses       = get_post_meta( $post->ID, 'assigned_courses', true );
			$assigned_courses_final = unserialize( $assigned_courses ) ? unserialize( $assigned_courses ) : array();
			$args                   = array(
				'order'          => 'ASC',
				'post_type'      => 'sfwd-courses',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
			);
			$courses                = get_posts( $args );
			if ( empty( $courses ) ) {
				return;
			}
			?>
				<div>
					<div>
						<select name="assigned_courses[]" multiple="multiple" id="assign_surevey_select" style="width: 75%">
							<?php
							foreach ( $courses as $course ) {
								$is_already_assigned_course = get_post_meta( $course->ID, 'assigned_survey', true );
								//if ( $is_already_assigned_course ) {
								    
								    if ( in_array( $course->ID, $assigned_courses_final ) ) {
								        //Skip
								    } else {
									?>
											<option value="<?php echo esc_attr( $course->ID ); ?>" <?php echo 'selected="selected"';?>>
											<?php
												echo esc_html( $course->post_title );
											?>
											</option>
									<?php
								    }

								if ( in_array( $course->ID, $assigned_courses_final ) ) {
									?>
										<option value="<?php echo esc_attr( $course->ID ); ?>" selected="selected"><?php echo esc_html( $course->post_title ); ?></option>
									<?php
								}
							}
							?>
						</select>
					</div>
				</div>
			<?php
		}

		/**
		 * Callback function to show question option.
		 *
		 * @param object $post [post object].
		 * @return void
		 */
		public function show_surevy_add_question( $post ) {
			$assigned_question       = get_post_meta( $post->ID, 'assigned_question', true );
			$assigned_question_final = unserialize( $assigned_question ) ? unserialize( $assigned_question ) : array();
			$unassigned_post_args    = array(
				'order'          => 'ASC',
				'post_type'      => 'rff_question',
				'post_status'    => 'any',
				'posts_per_page' => 10,
				'post__not_in'   => $assigned_question_final,
			);
			$unassigned_questions    = get_posts( $unassigned_post_args );
			$assigned_questions      = array();
			if ( count( $assigned_question_final ) ) {
				$assigned_post_args = array(
					'order'          => 'ASC',
					'post_type'      => 'rff_question',
					'post_status'    => 'any',
					'posts_per_page' => -1,
					'post__in'       => $assigned_question_final,
				);
				$assigned_questions = get_posts( $assigned_post_args );
			}
			?>
				<div class="filter-search-section">
					<div class="search_label"><?php esc_html_e( 'Search Questions', 'wdm_ld_course_review' ); ?></div>
					<div class="question_search_class"><input type="search" placeholder="<?php esc_attr_e( 'Search Questions', 'wdm_ld_course_review' ); ?>" id="question_search_input" onkeydown="return (event.keyCode!=13);"></div>
					<div class="question-search-loader-gif"><img src="<?php echo esc_url( plugins_url( 'admin/images/loader.gif', RRF_PLUGIN_FILE ) ); ?>" alt="loader"></div>
				</div>
				<div class="rrf_survey_question_main">
					<div class="rrf_survey_unassigned_question">
						<h2><?php esc_html_e( 'Unassigned Questions', 'wdm_ld_course_review' ); ?></h2>
						<div id="rrf_survey_unassigned_question_section">
							<?php
							if ( ! empty( $unassigned_questions ) ) {
								foreach ( $unassigned_questions as $unassigned ) {
									?>
										<div class="rrf_survey_question_div" data-post-id="<?php echo esc_attr( $unassigned->ID ); ?> ">
											<div class="rrf_survey_question_div_drag_drop_icon"></div>
											<div class="rrf_survey_question_div_title">
												<?php echo esc_html( $unassigned->post_title ); ?>
											</div>
										</div>
									<?php
								}
							}
							?>
						</div>
					</div>
					<div class="rrf_survey_direction_section">
						<img class="rff_question_move_left" src="<?php echo esc_url( plugins_url( 'admin/images/arrow-left.png', RRF_PLUGIN_FILE ) ); ?>" alt="arrow-left">
						<img class="rff_question_move_right" src="<?php echo esc_url( plugins_url( 'admin/images/arrow-right.png', RRF_PLUGIN_FILE ) ); ?>" alt="arrow-right">
					</div>
					<div class="rrf_survey_assigned_question">
						<h2><?php esc_html_e( 'Assigned Questions', 'wdm_ld_course_review' ); ?></h2>
						<div id="rrf_survey_assigned_question_section">
							<?php
							if ( ! empty( $assigned_questions ) ) {
								foreach ( $assigned_questions as $assigned ) {
									?>
										<div class="rrf_survey_question_div rrf_survey_question_div_assigned" data-post-id="<?php echo esc_attr( $assigned->ID ); ?>">
											<div class="rrf_survey_question_div_drag_drop_icon"></div>
											<div class="rrf_survey_question_div_title">
												<?php echo esc_html( $assigned->post_title ); ?>
											</div>
											<input type="hidden" name="assigned_question[]" value="<?php echo esc_attr( $assigned->ID ); ?>">
										</div>
									<?php
								}
							}
							?>
						</div>
					</div>
				</div>
				<div class="question_filter_section">
					<div class="decrease_page_count button"><?php esc_html_e( 'Previous', 'wdm_ld_course_review' ); ?></div>
					<div class="page_number_class">
						<input type="text" name="page_number" id="page_number_input" value="1" onkeydown="return (event.keyCode!=13);" readonly="readonly">
					</div>
					<div class="increase_page_count button"><?php esc_html_e( 'Next', 'wdm_ld_course_review' ); ?></div>
				</div>
				<div class="create-question">
					<a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=rff_question' ) ); ?>"><?php esc_html_e( 'Create New Question', 'wdm_ld_course_review' ); ?></a>
				</div>
			<?php
		}

		/**
		 * Saving meta box values.
		 *
		 * @param int    $post_id [post id].
		 * @param object $post    [post object].
		 */
		public function save_meta_boxes( $post_id, $post ) {
			if ( ! isset( $_POST['_wpnonce'] ) ) {//phpcs:ignore
				return;
			}
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			} // do nothing special if autosaving.
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				return;
			} // do nothing special if ajax.

			if ( $post->post_type === $this->cpt && current_user_can( 'manage_options' ) ) {
				$old_assigned_courses = unserialize( get_post_meta( $post_id, 'assigned_courses', true ) );
				$assigned_courses     = filter_input( INPUT_POST, 'assigned_courses', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
				if ( ! is_null( $old_assigned_courses ) ) {
					foreach ( $old_assigned_courses as $old_course ) {
						if ( ! in_array( $old_course, $assigned_courses ) ) {//phpcs:ignore
							delete_post_meta( $old_course, 'assigned_survey', $post_id );
						}
					}
				}

				if ( ! is_null( $assigned_courses ) ) {
					foreach ( $assigned_courses as $course ) {
						update_post_meta( $course, 'assigned_survey', $post_id );
					}
				}
				update_post_meta( $post_id, 'assigned_courses', serialize( $assigned_courses ) );
				$assigned_question = filter_input( INPUT_POST, 'assigned_question', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
				update_post_meta( $post_id, 'assigned_question', serialize( $assigned_question ) );
			}
		}

		/**
		 * Ajax function to filter question.
		 */
		public function filter_question() {
			$security = filter_input( INPUT_POST, 'security' );
			if ( ! isset( $security ) || ! wp_verify_nonce( $security, 'rrf-nonce-search-questions' ) ) {
				wp_send_json_error();
			}
			$page_limit  = filter_input( INPUT_POST, 'limit', FILTER_VALIDATE_INT );
			$page_number = filter_input( INPUT_POST, 'filter_page_number', FILTER_VALIDATE_INT );
			$exclude     = filter_input( INPUT_POST, 'exclude', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
			$search_text = filter_input( INPUT_POST, 'search_text', FILTER_SANITIZE_STRING );
			$args        = array(
				'order'          => 'ASC',
				'post_type'      => 'rff_question',
				'post_status'    => 'any',
				'posts_per_page' => $page_limit,
				'post__not_in'   => $exclude,
				'paged'          => $page_number,
				's'              => $search_text,
			);
			$posts       = get_posts( $args );
			wp_send_json_success( $posts );
		}
	}
	RRF_SURVEY_CPT::get_instance();
}
