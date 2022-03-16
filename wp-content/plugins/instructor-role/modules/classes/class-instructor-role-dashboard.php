<?php
/**
 * Instructor Dashboard Module
 *
 * @since      4.0
 * @package    Instructor_Role
 * @subpackage Instructor_Role/modules/classes
 * @author     WisdmLabs <support@wisdmlabs.com>
 */

namespace InstructorRole\Modules\Classes;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Instructor_Role_Dashboard' ) ) {
	/**
	 * Class Instructor Role Dashboard Module
	 */
	class Instructor_Role_Dashboard {
		/**
		 * Singleton instance of this class
		 *
		 * @var object  $instance
		 *
		 * @since 4.0
		 */
		protected static $instance = null;

		/**
		 * Plugin Slug
		 *
		 * @var string  $plugin_slug
		 *
		 * @since 4.0
		 */
		protected $plugin_slug = '';

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->plugin_slug = INSTRUCTOR_ROLE_TXT_DOMAIN;
		}

		/**
		 * Get a singleton instance of this class
		 *
		 * @return object
		 * @since   4.0
		 */
		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Enqueue dashboard scripts
		 *
		 * @since 4.0
		 */
		public function enqueue_dashboard_scripts() {
			global $post;

			if ( ! wdm_is_instructor() ) {
				return;
			}

			wp_enqueue_style(
				'irb-styles',
				plugins_url( 'css/irb-admin.css', __DIR__ ),
				array(),
				INSTRUCTOR_ROLE_PLUGIN_VERSION
			);

			$screen = get_current_screen();

			if ( learndash_get_post_type_slug( 'course' ) === $screen->id ) {
				$this->add_course_page_scripts();
			}

		}

		/**
		 * Add course page specific scripts
		 *
		 * @since 4.0
		 */
		public function add_course_page_scripts() {

			wp_enqueue_script(
				'ir-dashboard-course-script',
				plugins_url( 'js/dashboard/ir-dashboard-course-script.js', __DIR__ ),
				array( 'jquery' ),
				filemtime( INSTRUCTOR_ROLE_ABSPATH . 'modules/js/dashboard/ir-dashboard-course-script.js' ),
				1
			);

			$settings = array(
				'course_page',
				'learndash_course_builder',
				'sfwd-courses-settings',
			);

			if ( learndash_get_total_post_count( learndash_get_post_type_slug( 'group' ) ) !== 0 ) {
				$settings[] = 'learndash_course_groups';
			}

			/**
			 * Filter the list of settings for progress bar.
			 *
			 * @since 4.0
			 *
			 * @param array $settings   Settings for progress bar.
			 */
			$settings      = apply_filters( 'ir_filter_progress_bar_settings', $settings );
			$settings_text = sprintf(
				// translators: Total settings count.
				__( '_count_ out of %d settings', 'wdm_instructor_role' ),
				count( $settings )
			);

			wp_localize_script(
				'ir-dashboard-course-script',
				'ir_dashboard_loc',
				array(
					'settings'      => $settings,
					'settings_text' => $settings_text,
					'step_width'    => floatval( 100 / ( count( $settings ) - 1 ) )
				)
			);

			// wp_enqueue_style(
			// 	'ir-dashboard-course-styles',
			// 	plugins_url( 'css/dashboard/ir-dashboard-course-styles.css', __DIR__ ),
			// 	array(),
			// 	filemtime( INSTRUCTOR_ROLE_ABSPATH . '/modules/css/dashboard/ir-dashboard-course-styles.css' )
			// );
		}

		/**
		 * Add save and continue section on course page
		 *
		 * @since 4.0
		 */
		public function add_save_and_continue_section() {
			if ( ! wdm_is_instructor() ) {
				return;
			}

			$screen = get_current_screen();

			$settings = array(
				'course_page',
				'learndash_course_builder',
				'sfwd-courses-settings',
			);

			if ( learndash_get_total_post_count( learndash_get_post_type_slug( 'group' ) ) !== 0 ) {
				$settings[] = 'learndash_course_groups';
			}

			/**
			 * Filter the list of settings for progress bar.
			 *
			 * @since 4.0
			 *
			 * @param array $settings   Settings for progress bar.
			 */
			$settings = apply_filters( 'ir_filter_progress_bar_settings', $settings );

			$current_setting = 'course_page';
			if ( ! empty( $_GET ) && isset( $_GET['currentTab'] ) ) {
				$current_setting = $_GET['currentTab'];
			}

			/**
			 * Filter the current setting for progress bar.
			 *
			 * @since 4.0
			 *
			 * @param string $current_setting   Currently active setting.
			 */
			$current_setting = apply_filters( 'ir_filter_progress_bar_current_setting', $current_setting );

			$current_setting_count = 0;
			if ( in_array( $current_setting, $settings ) ) {
				$current_setting_count = array_search( $current_setting, $settings );
			}
			$current_setting_count = intval( $current_setting_count ) + 1;

			if ( learndash_get_post_type_slug( 'course' ) === $screen->id ) {
				ir_get_template(
					INSTRUCTOR_ROLE_ABSPATH . '/modules/templates/dashboard/ir-course-footer.template.php',
					array(
						'settings_count'        => count( $settings ),
						'current_setting_count' => $current_setting_count,
						'step_width'			=>	floatval( 100 / ( count( $settings ) - 1 ) )
					)
				);
			}
		}

		/**
		 * Add instructor dashboard settings menu
		 *
		 * @since 4.0
		 */
		public function add_dashboard_settings_menu() {
			add_theme_page(
				__( 'Instructor Dashboard Settings', 'wdm_instructor_role' ),
				__( 'Instructor Dashboard Settings', 'wdm_instructor_role' ),
				'edit_theme_options',
				'ir-dashboard-settings',
				array(
					$this,
					'display_dashboard_settings_page',
				)
			);
		}

		/**
		 * Display dashboard settings page
		 *
		 * @since 4.0
		 */
		public function display_dashboard_settings_page() {
			// check user capabilities.
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$google_fonts = $this->get_google_fonts();
			ir_get_template(
				INSTRUCTOR_ROLE_ABSPATH . '/modules/templates/dashboard/ir-dashboard-settings-page.template.php',
				array(
					'ir_accent_primary_color'             => ir_get_settings( 'ir_accent_primary_color' ),
					'ir_accent_secondary_color'           => ir_get_settings( 'ir_accent_secondary_color' ),
					'ir_dashboard_header'                 => ir_get_settings( 'ir_dashboard_header' ),
					'ir_dashboard_logo'                   => ir_get_settings( 'ir_dashboard_logo' ),
					'ir_dashboard_image_background_color' => ir_get_settings( 'ir_dashboard_image_background_color' ),
					'ir_dashboard_text_title'             => ir_get_settings( 'ir_dashboard_text_title' ),
					'ir_dashboard_text_sub_title'         => ir_get_settings( 'ir_dashboard_text_sub_title' ),
					'ir_dashboard_title_font_family'      => ir_get_settings( 'ir_dashboard_title_font_family' ),
					'ir_dashboard_title_font_color'       => ir_get_settings( 'ir_dashboard_title_font_color' ),
					'ir_dashboard_sub_title_font_family'  => ir_get_settings( 'ir_dashboard_sub_title_font_family' ),
					'ir_dashboard_sub_title_font_color'   => ir_get_settings( 'ir_dashboard_sub_title_font_color' ),
					'ir_dashboard_text_background_color'  => ir_get_settings( 'ir_dashboard_text_background_color' ),
					'google_fonts'                        => $google_fonts,
				)
			);
		}

		/**
		 * Save dashboard settings page
		 *
		 * @since 4.0
		 */
		public function save_dashboard_settings_page() {
			// If not admin then return
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			// Verify nonce
			if ( ! array_key_exists( 'ir_dashboard_nonce', $_POST ) || ! wp_verify_nonce( $_POST['ir_dashboard_nonce'], 'ir_dashboard_settings' ) ) {
				return;
			}

			// Save dashboard settings.
			$ir_accent_primary_color             = isset( $_POST['ir_accent_primary_color'] ) ? $_POST['ir_accent_primary_color'] : '';
			$ir_accent_secondary_color           = isset( $_POST['ir_accent_secondary_color'] ) ? $_POST['ir_accent_secondary_color'] : '';
			$ir_dashboard_header                 = isset( $_POST['ir_dashboard_header'] ) ? $_POST['ir_dashboard_header'] : '';
			$ir_dashboard_logo                   = isset( $_POST['ir_dashboard_logo'] ) ? $_POST['ir_dashboard_logo'] : '';
			$ir_dashboard_image_background_color = isset( $_POST['ir_dashboard_image_background_color'] ) ? $_POST['ir_dashboard_image_background_color'] : '';
			$ir_dashboard_text_title             = isset( $_POST['ir_dashboard_text_title'] ) ? $_POST['ir_dashboard_text_title'] : '';
			$ir_dashboard_text_sub_title         = isset( $_POST['ir_dashboard_text_sub_title'] ) ? $_POST['ir_dashboard_text_sub_title'] : '';
			$ir_dashboard_title_font_family      = isset( $_POST['ir_dashboard_title_font_family'] ) ? $_POST['ir_dashboard_title_font_family'] : '';
			$ir_dashboard_title_font_color       = isset( $_POST['ir_dashboard_title_font_color'] ) ? $_POST['ir_dashboard_title_font_color'] : '';
			$ir_dashboard_sub_title_font_family  = isset( $_POST['ir_dashboard_sub_title_font_family'] ) ? $_POST['ir_dashboard_sub_title_font_family'] : '';
			$ir_dashboard_sub_title_font_color   = isset( $_POST['ir_dashboard_sub_title_font_color'] ) ? $_POST['ir_dashboard_sub_title_font_color'] : '';
			$ir_dashboard_text_background_color  = isset( $_POST['ir_dashboard_text_background_color'] ) ? $_POST['ir_dashboard_text_background_color'] : '';

			ir_set_settings( 'ir_accent_primary_color', $ir_accent_primary_color );
			ir_set_settings( 'ir_accent_secondary_color', $ir_accent_secondary_color );
			ir_set_settings( 'ir_dashboard_header', $ir_dashboard_header );
			ir_set_settings( 'ir_dashboard_logo', $ir_dashboard_logo );
			ir_set_settings( 'ir_dashboard_image_background_color', $ir_dashboard_image_background_color );
			ir_set_settings( 'ir_dashboard_text_title', $ir_dashboard_text_title );
			ir_set_settings( 'ir_dashboard_text_sub_title', $ir_dashboard_text_sub_title );
			ir_set_settings( 'ir_dashboard_title_font_family', $ir_dashboard_title_font_family );
			ir_set_settings( 'ir_dashboard_title_font_color', $ir_dashboard_title_font_color );
			ir_set_settings( 'ir_dashboard_sub_title_font_family', $ir_dashboard_sub_title_font_family );
			ir_set_settings( 'ir_dashboard_sub_title_font_color', $ir_dashboard_sub_title_font_color );
			ir_set_settings( 'ir_dashboard_text_background_color', $ir_dashboard_text_background_color );

		}

		/**
		 * Enqueue scripts for instructor dashboard settings
		 *
		 * @since 4.0
		 */
		public function enqueue_scripts() {
			$screen = get_current_screen();

			if ( ! empty( $screen ) && 'appearance_page_ir-dashboard-settings' === $screen->id ) {
				wp_enqueue_style(
					'ir-dashboard-settings-styles',
					plugins_url( 'css/dashboard/ir-dashboard-settings-styles.css', __DIR__ ),
					array(),
					filemtime( INSTRUCTOR_ROLE_ABSPATH . '/modules/css/dashboard/ir-dashboard-settings-styles.css' )
				);

				wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_script(
					'ir-dashboard-settings-script',
					plugins_url( 'js/dashboard/ir-dashboard-settings-script.js', __DIR__ ),
					array( 'jquery', 'wp-color-picker' ),
					filemtime( INSTRUCTOR_ROLE_ABSPATH . '/modules/js/dashboard/ir-dashboard-settings-script.js' ),
					1
				);
			}
		}

		/**
		 * Get google fonts in an array
		 *
		 * @since 4.0
		 */
		public function get_google_fonts() {
			$google_fonts = get_option( 'ir_google_fonts' );
			if ( false === $google_fonts ) {
				$google_fonts = array();
				$font_json    = file_get_contents( INSTRUCTOR_ROLE_ABSPATH . '/modules/media/google-fonts.json' );
				$font_decoded = json_decode( $font_json );
				$fonts        = $font_decoded->items;

				foreach ( $fonts as $key => $value ) {
					array_push( $google_fonts, $value->family );
				}
				update_option( 'ir_google_fonts', $google_fonts );
			}

			return apply_filters( 'ir_filter_google_fonts', $google_fonts );
		}

		/**
		 * Add instructor logo on dashboard.
		 *
		 * @since 4.0
		 */
		public function add_instructor_logo() {
			// Check if instructor.
			if ( ! wdm_is_instructor() ) {
				return;
			}

			// Get header settings type.
			$header_type                = ir_get_settings( 'ir_dashboard_header' );
			$dashboard_background_color = '';

			// If not set, return.
			if ( empty( $header_type ) || 'none' === $header_type ) {
				return;
			}

			if ( 'image' === $header_type ) {
				$ir_dashboard_logo          = ir_get_settings( 'ir_dashboard_logo' );
				$dashboard_background_color = ir_get_settings( 'ir_dashboard_image_background_color' );

				$logo_image = '<img src="' . esc_attr( wp_get_attachment_image_src( $ir_dashboard_logo )[0] ) . '" alt="" />';
				/**
				 * A url to logo on the instructor dashboard.
				 *
				 * @since 3.5.6
				 *
				 * @param string A URL to the instructor dashboard logo.
				 */

				$logo_url = apply_filters( 'ir_instructor_dashboard_logo_url', '' );

				if ( '' !== $logo_url ) {
					$logo_image = '<a href="' . esc_attr( $logo_url ) . '">' . $logo_image . '</a>';
				}
				$logo_li_tag = '<li id="ir-admin-logo-item"><div class="ir-admin-logo ir-admin-image">' . $logo_image . '</div></li>';
			} elseif ( 'text' === $header_type ) {
				$ir_dashboard_text_title     = ir_get_settings( 'ir_dashboard_text_title' );
				$ir_dashboard_text_sub_title = ir_get_settings( 'ir_dashboard_text_sub_title' );

				// $ir_dashboard_title_font_family = ir_get_settings( 'ir_dashboard_title_font_family' );
				$ir_dashboard_title_font_color = ir_get_settings( 'ir_dashboard_title_font_color' );

				/**
				 * Customize instructor logo title styles.
				 *
				 * @param string $styles    Styles applied to the logo title.
				 */
				$title_font_styles = apply_filters(
					'ir_filter_logo_title_styles',
					"font-family: 'Poppins', sans-serif;
					font-size: 26px;
					text-align: center;
					padding: 15px;
					color: {$ir_dashboard_title_font_color};"
				);
				// $ir_dashboard_sub_title_font_family = ir_get_settings( 'ir_dashboard_sub_title_font_family' );
				$ir_dashboard_sub_title_font_color = ir_get_settings( 'ir_dashboard_sub_title_font_color' );
				/**
				 * Customize instructor logo subtitle styles.
				 *
				 * @param string $styles    Styles applied to the logo subtitle.
				 */
				$subtitle_font_styles = apply_filters(
					'ir_filter_logo_title_styles',
					"font-family: 'Poppins', sans-serif;
					font-size: 12px;
					text-align: center;
					color: {$ir_dashboard_sub_title_font_color};"
				);

				$dashboard_background_color = ir_get_settings( 'ir_dashboard_text_background_color' );
			}

			?>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						<?php if ( 'image' === $header_type ) : ?>
							$('#adminmenu').prepend('<?php echo $logo_li_tag; ?>');
						<?php elseif ( 'text' === $header_type ) : ?>
							$('#adminmenu').prepend(`
								<li id='ir-admin-logo-item'>
								<div id='ir-admin-logo-text' class='ir-admin-logo'>
									<div class='ir-admin-menu-logo-title' style="<?php echo esc_attr( $title_font_styles ); ?>">
										<?php echo esc_html( $ir_dashboard_text_title ); ?>
									</div>
									<div class='ir-admin-menu-logo-subtitle' style="<?php echo esc_attr( $subtitle_font_styles ); ?>">
										<?php echo esc_html( $ir_dashboard_text_sub_title ); ?>
									</div>
								</div>
							</li>`);
						<?php endif; ?>

						$('.ir-admin-logo').css('background', "<?php echo esc_attr( $dashboard_background_color ); ?>");
					});
				</script>
			<?php
		}

		/**
		 * Add accent color styles to instructor dashboard.
		 *
		 * @since 4.0
		 */
		public function add_accent_color_styles() {
			// Check if instructor
			if ( is_user_logged_in() && wdm_is_instructor() ) {
				$accent_color   = ir_get_settings( 'ir_accent_primary_color' );
				$custom_styling = ir_get_template(
					INSTRUCTOR_ROLE_ABSPATH . '/modules/templates/dashboard/ir-accent-color-styles.template.php',
					array(
						'accent_color' => $accent_color,
					),
					1
				);
				wp_add_inline_style( 'irb-styles', $custom_styling );
			}
		}

		/**
		 * Exclude menu items from the list of post types to restrict access to.
		 *
		 * @since 4.0
		 *
		 * @param array $post_types     Array of post types to be excluded.
		 *
		 * @return array                Updated array of excluded post types.
		 */
		public function exclude_menu_items( $post_types ) {
			if ( wdm_is_instructor() ) {
				$post_types[] = 'nav_menu_item';
			}
			return $post_types;
		}

		/**
		 * Remove default admin bar for instructors
		 *
		 * @since 4.0
		 */
		public function remove_admin_bar() {
			if ( is_user_logged_in() && wdm_is_instructor() ) {
				remove_action( 'in_admin_header', 'wp_admin_bar_render', 0 );
				?>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						$('#adminmenu').prepend(`<?php echo $this->get_toggle_menu_button(); ?>`);
					});
				</script>
				<?php
			}
		}

		/**
		 * Add dashboard menu for instructors
		 *
		 * @since 4.0
		 */
		public function add_instructor_menu() {
			// Check if instructor
			if ( wdm_is_instructor() ) {
				$instructor_menu_slug = 'ir-instructor-menu';

				if ( has_nav_menu( $instructor_menu_slug ) ) {
					echo wp_nav_menu(
						array(
							'theme_location'  => $instructor_menu_slug,
							'menu_id'         => 'ir-primary-menu',
							'container_id'    => 'ir-primary-navigation',
							'container_class' => '',
							'echo'            => false,
						)
					);
				} else {
					/**
					 * Allow third party plugins to update default instructor menu.
					 *
					 * @since 3.3.0
					 */
					$template = apply_filters(
						'ir_filter_default_instructor_menu_path',
						INSTRUCTOR_ROLE_ABSPATH . '/modules/templates/settings/ir-default-instructor-menu.template.php'
					);
					include $template;
				}
			}
		}

		/**
		 * Get toggle menu button.
		 *
		 * @since 4.0
		 */
		public function get_toggle_menu_button() {
			$toggle_menu_button = '
			<li id="collapse-menu" class="hide-if-no-js">
				<button type="button" id="collapse-button" aria-label="' . esc_attr__( 'Collapse Main menu' ) . '" aria-expanded="true">
					<span class="irb-icon-side-bar-expand"></span>
				</button>
			</li>';

			/**
			 * Filter the toggle menu button HTML.
			 *
			 * @since 4.0
			 */
			$toggle_menu_button = apply_filters( 'ir_filter_toggle_menu_html', $toggle_menu_button );

			return $toggle_menu_button;
		}

		/**
		 * Instructor Dashboard footer text.
		 * 
		 * @since 4.0
		 */
		public function dashboard_footer_text() {
			return __return_empty_string();
		}
	}
}
