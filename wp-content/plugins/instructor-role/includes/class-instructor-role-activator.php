<?php
/**
 * Fired during plugin activation
 *
 * @link       https://wisdmlabs.com
 * @since      1.0.0
 *
 * @package    Instructor_Role
 * @subpackage Instructor_Role/includes
 */

namespace InstructorRole\Includes;

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Instructor_Role
 * @subpackage Instructor_Role/includes
 * @author     WisdmLabs <support@wisdmlabs.com>
 */
class Instructor_Role_Activator {

	/**
	 * Activation Sequence
	 *
	 * Performs necessary actions such as adding instructor role and capabilities to admin.
	 *
	 * @since    3.5.0
	 *
	 * @param bool $network_wide    Whether to enable the plugin for all sites in the network or just the current site.
	 *                              Multisite only. Default false.
	 */
	public function activate( $network_wide ) {
		$this->add_instructor_role();
		if ( is_multisite() && $network_wide ) {
			global $wpdb;
			foreach ( $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" ) as $blog_id ) {
				switch_to_blog( $blog_id );
				$admin_role = get_role( 'administrator' );
				if ( null !== $admin_role ) {
					$admin_role->add_cap( 'instructor_reports' );
					$admin_role->add_cap( 'instructor_page' );
				}
				restore_current_blog();
			}
		} else {
			$admin_role = get_role( 'administrator' );
			if ( null !== $admin_role ) {
				$admin_role->add_cap( 'instructor_reports' );
				$admin_role->add_cap( 'instructor_page' );
			}
		}

		/**
		 * Fires once after the instructor role plugin is activated
		 *
		 * @since   3.6.1
		 *
		 * @param bool $network_wide    Whether to enable the plugin for all sites in the network or just the current site.
		 *                              Multisite only. Default false.
		 */
		do_action( 'ir_action_plugin_activated', $network_wide );
	}

	/**
	 * Admin Activation Sequence
	 *
	 * Check for plugin dependencies on plugin activation.
	 *
	 * @since    3.5.0
	 */
	public function admin_activate() {
		if ( is_multisite() ) {
			if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
				require_once ABSPATH . '/wp-admin/includes/plugin.php';
			}

			// Check if plugin is active in network or subsite.
			if ( ! is_plugin_active_for_network( 'sfwd-lms/sfwd_lms.php' ) && ! in_array( 'sfwd-lms/sfwd_lms.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
				deactivate_plugins( plugin_basename( INSTRUCTOR_ROLE_BASE ) );
				unset( $_GET['activate'] );
				add_action( 'admin_notices', array( $this, 'handle_admin_notices' ) );
			}
		} elseif ( ! class_exists( 'SFWD_LMS' ) || ! in_array( 'sfwd-lms/sfwd_lms.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			deactivate_plugins( plugin_basename( INSTRUCTOR_ROLE_BASE ) );
			unset( $_GET['activate'] );
			add_action( 'admin_notices', array( $this, 'handle_admin_notices' ) );
		}
	}

	/**
	 * Handle admin notices
	 */
	public function handle_admin_notices() {
		if ( ! class_exists( 'SFWD_LMS' ) ) {
			?>
			<div class='error'><p>
				<?php
				echo esc_html( __( "LearnDash LMS plugin is not active. In order to make the 'Instructor Role' plugin work, you need to install and activate LearnDash LMS first.", 'wdm_instructor_role' ) );
				?>
			</p></div>

			<?php
		}
	}

	/**
	 * Add the instructor role
	 *
	 * @since   1.0
	 */
	public function add_instructor_role() {

		$instructor_caps = array(
			'wpProQuiz_show'               => true, // true allows this capability
			'wpProQuiz_add_quiz'           => true,
			'wpProQuiz_edit_quiz'          => true, // Use false to explicitly deny
			'wpProQuiz_delete_quiz'        => true,
			'wpProQuiz_show_statistics'    => true,
			'wpProQuiz_import'             => true,
			'wpProQuiz_export'             => true,
			'read_course'                  => true,
			'publish_courses'              => true,
			'edit_courses'                 => true,
			'delete_courses'               => true,
			'edit_course'                  => true,
			'delete_course'                => true,
			'edit_published_courses'       => true,
			'delete_published_courses'     => true,
			'edit_assignment'              => true,
			'edit_assignments'             => true,
			'publish_assignments'          => true,
			'read_assignment'              => true,
			'delete_assignment'            => true,
			'edit_published_assignments'   => true,
			'delete_published_assignments' => true,
			// 'propanel_widgets'                 => true,
			'read'                         => true,
			'edit_others_assignments'      => true,
			'instructor_reports'           => true, // very important, custom for course report submenu page
			'instructor_page'              => true, // very important, for showing instructor submenu page. added in 2.4.0 v
			'manage_categories'            => true,
			'wpProQuiz_toplist_edit'       => true, // to show leaderboard of quiz
			'upload_files'                 => true, // to upload files
			'delete_essays'                => true,  // added v 2.4.0 for essay
			'delete_others_essays'         => true,
			'delete_private_essays'        => true,
			'delete_published_essays'      => true,
			'edit_essays'                  => true,
			'edit_others_essays'           => true,
			'edit_private_essays'          => true,
			'edit_published_essays'        => true,
			'publish_essays'               => true,
			'read_essays'                  => true,
			'read_private_essays'          => true,
			'edit_posts'                   => true,
			'publish_posts'                => true,
			'edit_published_posts'         => true,
			'delete_posts'                 => true,
			'delete_published_posts'       => true,
			'view_h5p_contents'            => true,
			'edit_h5p_contents'            => true,
			'unfiltered_html'              => true,
			'delete_product'               => true,
			'delete_products'              => true,
			'delete_published_products'    => true,
			'edit_product'                 => true,
			'edit_products'                => true,
			'edit_published_products'      => true,
			'publish_products'             => true,
			'read_product'                 => true,
			'assign_product_terms'         => true,
		);

		// Add instructor caps in options
		update_option( 'ir_instructor_caps', $instructor_caps );

		add_role(
			'wdm_instructor',
			__( 'Instructor', 'wdm_instructor_role' ),
			$instructor_caps
		);
	}


	/**
	 * Handle upgrade notices if any
	 *
	 * @param array $data
	 * @param object $response
	 *
	 * @since 3.6.2
	 */
	public function handle_update_notices( $data, $response ) {
		$this->new_version    = $response->new_version;
		$this->upgrade_notice = $this->get_upgrade_notice( $response->new_version );

		$cur_ver_parts = explode( '.', INSTRUCTOR_ROLE_PLUGIN_VERSION );
		$new_ver_parts = explode( '.', $this->new_version );

		// If user has already moved to the minor version, we don't need to flag up anything.
		if ( version_compare( $cur_ver_parts[0] . '.' . $cur_ver_parts[1], $new_ver_parts[0] . '.' . $new_ver_parts[1], '=' ) ) {
			return;
		}

		/**
		 * Plugin update message.
		 *
		 * @since 3.6.2
		 *
		 * @param string $this->upgrade_notice.
		 */
		echo wp_kses_post( apply_filters( 'ir_in_plugin_update_message', $this->upgrade_notice ? '</p>' . $this->upgrade_notice . '<p class="dummy">' : '' ) );
	}

	/**
	 * Get the upgrade notice from wisdmlabs.com.
	 *
	 * @since 3.6.2
	 *
	 * @param  string   Plugin version.
	 *
	 * @return string   Upgrade notice section.
	 */
	protected function get_upgrade_notice( $version ) {
		$transient_name = 'ir_upgrade_notice_' . $version;
		$upgrade_notice = get_transient( $transient_name );

		if ( false === $upgrade_notice ) {
			$response = wp_safe_remote_get( 'https://wisdmlabs.com/releases/ir/readme.txt' );

			if ( ! is_wp_error( $response ) && ! empty( $response['body'] ) ) {
				$upgrade_notice = $this->parse_update_notice( $response['body'], $version );
				set_transient( $transient_name, $upgrade_notice, DAY_IN_SECONDS );
			}
		}
		return $upgrade_notice;
	}

	/**
	 * Parse update notice from readme file. Code Adopted from WooCommerce.
	 *
	 * @since 3.6.2
	 *
	 * @param  string $content Instructor Role readme file content.
	 * @param  string $new_version Plugin new version.
	 *
	 * @return string
	 */
	private function parse_update_notice( $content, $new_version ) {
		$version_parts     = explode( '.', $new_version );
		$check_for_notices = array(
			$version_parts[0] . '.0', // Major.
			$version_parts[0] . '.0.0', // Major.
			$version_parts[0] . '.' . $version_parts[1], // Minor.
			$version_parts[0] . '.' . $version_parts[1] . '.' . $version_parts[2], // Patch.
		);
		$notice_regexp     = '~==\s*Upgrade Notice\s*==\s*=\s*(.*)\s*=(.*)(=\s*' . preg_quote( $new_version ) . '\s*=|$)~Uis';
		$upgrade_notice    = '';

		foreach ( $check_for_notices as $check_version ) {
			if ( version_compare( INSTRUCTOR_ROLE_PLUGIN_VERSION, $check_version, '>' ) ) {
				continue;
			}

			$matches = null;
			if ( preg_match( $notice_regexp, $content, $matches ) ) {
				$notices = (array) preg_split( '~[\r\n]+~', trim( $matches[2] ) );

				if ( version_compare( trim( $matches[1] ), $check_version, '=' ) ) {
					$upgrade_notice .= '<div class="ir_plugin_upgrade_notice">';

					foreach ( $notices as $index => $line ) {
						unset( $index );
						$upgrade_notice .= preg_replace( '~\[([^\]]*)\]\(([^\)]*)\)~', '<a href="${2}">${1}</a>', $line );
					}

					$upgrade_notice .= '</div>';
				}
				break;
			}
		}
		return wp_kses_post( $upgrade_notice );
	}

	/**
	 * Enqueue styles needed to display plugin update section.
	 *
	 * @return 4.0
	 */
	public function enqueue_plugin_update_css() {
		$current_screen = get_current_screen();

		if ( 'plugins' === $current_screen->id ) {
			wp_enqueue_style(
				'ir-upgrade-styles',
				plugins_url( 'modules/css/ir-upgrade-notice.css', __DIR__ ),
				array(),
				filemtime( INSTRUCTOR_ROLE_ABSPATH . '/modules/css/ir-upgrade-notice.css' )
			);
		}
	}
}
