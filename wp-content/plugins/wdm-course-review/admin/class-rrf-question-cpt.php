<?php
/**
 * This file is used to include the class which registers Survey Question CPT in WordPress.
 *
 * @package RatingsReviewsFeedback\Admin\Question
 */

namespace ns_wdm_ld_course_review{

	/**
	 * This will create custom post type called question to add in survey of course.
	 */
	class RRF_QUESTION_CPT {
		/**
		 * CPT Slug
		 *
		 * @var string
		 */
		public $cpt = 'rff_question';
		/**
		 * Fields shown in metabox.
		 *
		 * @var array
		 */
		public $meta_box_question = array();
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

			// adding meta box for question details.
			\wdm_add_hook( 'add_meta_boxes', 'add_meta_boxes', $this );

			// for saving meta box values.
			\wdm_add_hook( 'save_post', 'save_meta_boxes', $this, array( 'num_args' => 3 ) );

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
		 * Registering question CPT.
		 */
		public function create_post_type() {
			$labels = array(
				'name'               => _x( 'Survey Question', 'wdm_ld_course_review' ),
				'singular_name'      => _x( 'Survey Questions', 'wdm_ld_course_review' ),
				'add_new'            => _x( 'Add New', 'wdm_ld_course_review' ),
				'add_new_item'       => __( 'Add New Question', 'wdm_ld_course_review' ),
				'edit_item'          => __( 'Edit Question', 'wdm_ld_course_review' ),
				'new_item'           => __( 'New Question', 'wdm_ld_course_review' ),
				'all_items'          => __( 'All Questions', 'wdm_ld_course_review' ),
				'view_item'          => __( 'View Question', 'wdm_ld_course_review' ),
				'search_items'       => __( 'Search Questions', 'wdm_ld_course_review' ),
				'not_found'          => __( 'No questions found', 'wdm_ld_course_review' ),
				'not_found_in_trash' => __( 'No questions found in the Trash', 'wdm_ld_course_review' ),
				'menu_name'          => __( 'Survey Questions', 'wdm_ld_course_review' ),
			);

			$args = array(
				'labels'              => $labels,
				'label'               => __( 'Survey Questions', 'wdm_ld_course_review' ),
				'description'         => __( 'Survey Questions', 'wdm_ld_course_review' ),
				'menu_position'       => 25,
				'supports'            => array( 'title', 'author' ),
				'exclude_from_search' => false,
				'show_in_nav_menus'   => false,
				'show_ui'             => true,
				'show_in_menu'        => false,
				'show_in_admin_bar'   => false,
				'has_archive'         => false,
				'menu_icon'           => 'dashicons-welcome-write-blog',
			);
			register_post_type( $this->cpt, $args );
		}

		/**
		 * Adding question options details metabox on survey question page.
		 */
		public function add_meta_boxes() {
			add_meta_box( 'rrf_question_option', __( 'Question Options', 'wdm_ld_course_review' ), array( &$this, 'show_question_option' ), $this->cpt, 'normal', 'high', array( 'type' => 'meta_box_question' ) );
			add_action( 'admin_print_scripts', array( $this, 'load_style_n_script' ) );
		}

		/**
		 * Loading css to hide add new button.
		 */
		public function load_style_n_script() {
			global $post;
			if ( isset( $post ) && $post->post_type === $this->cpt ) {
				wp_enqueue_script( 'rrf-survey-addel', plugins_url( 'admin/js/addel.jquery.min.js', RRF_PLUGIN_FILE ), array( 'jquery' ), WDM_LD_COURSE_VERSION );
				wp_enqueue_script( 'rrf-survey-js', plugins_url( 'admin/js/rrf-survey.js', RRF_PLUGIN_FILE ), array( 'jquery' ), WDM_LD_COURSE_VERSION );
				wp_enqueue_style( 'rff-survey-css', plugins_url( 'admin/css/rff-survey.css', RRF_PLUGIN_FILE ), array(), filemtime( RRF_PLUGIN_PATH . 'admin/css/rff-survey.css' ) );
			}
		}

		/**
		 * Callback function to show question option.
		 *
		 * @param object $post [post object].
		 */
		public function show_question_option( $post ) {
			$question_option       = get_post_meta( $post->ID, 'question_option', true );
			$question_option_final = ( ! empty( $question_option ) ) ? unserialize( $question_option ) : array( '' );
			?>
				<div class="rff-survey-question-option-main">
					<div class="addel">
						<?php
						foreach ( $question_option_final as $option_value ) {
							?>
						<div class="addel-target">
							<div class="rff-survey-question-option-all">
								<div class="rff-survey-question-option-input">
									<input type="text" placeholder="<?php esc_attr_e( 'Add Your Option', 'wdm_ld_course_review' ); ?>" name="question_option[]" value="<?php echo esc_attr( $option_value ); ?>">
								</div>
								<div class="rff-survey-question-option-delete">
									<div class="addel-delete"><?php esc_html_e( 'Delete Option', 'wdm_ld_course_review' ); ?></div>
								</div>
							</div>
						</div>
							<?php
						}
						?>
						<div class="rff-survey-question-option-add">
							<div class="addel-add button-primary">
								<?php esc_html_e( 'Add New Option', 'wdm_ld_course_review' ); ?>
							</div>
						</div>
					</div>
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
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			} // do nothing special if autosaving
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				return;
			} // do nothing special if ajax

			if ( ! isset( $_POST['_wpnonce'] ) || $post->post_type != $this->cpt ) {//phpcs:ignore
				return;
			}

			if ( $post->post_type === $this->cpt && current_user_can( 'manage_options' ) ) {
				$question_option = filter_input( INPUT_POST, 'question_option', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
				if ( empty( $question_option ) ) {
					delete_post_meta( $post_id, 'question_option' );
				} elseif ( count( $question_option ) === 1 && $question_option[0] == '' ) {//phpcs:ignore
					delete_post_meta( $post_id, 'question_option' );
				} else {
					$question_option_value = serialize( $question_option );
					update_post_meta( $post_id, 'question_option', $question_option_value );
				}
			}
		}
	}
	RRF_QUESTION_CPT::get_instance();
}
