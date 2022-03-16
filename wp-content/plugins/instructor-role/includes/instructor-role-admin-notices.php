<?php
/**
 * Handling admin notices for the plugin
 *
 * @link       https://wisdmlabs.com
 * @since      3.5.8
 *
 * @package    Instructor_Role
 * @subpackage Instructor_Role/includes
 */

namespace InstructorRole\Includes;

/**
 * Handling admin notices for the plugin
 *
 *
 * @since      3.5.8
 * @package    Instructor_Role
 * @subpackage Instructor_Role/includes
 * @author     WisdmLabs <support@wisdmlabs.com>
 */
class Instructor_Role_Admin_Notices {
    /**
     * Constructor.
     *
     * @since 3.5.8
     */
    public function __construct() {
        add_action( 'admin_init', array( $this, 'show_admin_notice' ) );
    }

    /**
     * Show admin notices.
     *
     * @since 3.5.8
     * @access public
     */
    public function show_admin_notice() {
        if ( isset( $_GET['ir_dismiss_survey_notice'] ) && ! empty( $_GET['ir_dismiss_survey_notice'] ) ) {
            update_option( 'ir_dismiss_survey_notice', 'yes', false );
        }

        if ( current_user_can( 'manage_options' ) && ! get_option( 'ir_dismiss_survey_notice', false ) ) {
            add_action('admin_notices', array($this, 'display_admin_survey_notice'));
        }

        // Add buddypress admin notice
        add_action( 'admin_notices', array( $this, 'display_buddypress_activation_notice' ) );
	    add_action( 'network_admin_notices', array( $this, 'display_buddypress_activation_notice' ) );
    }

    /**
     * Display admin survey notice.
     *
     * @since 3.5.8
     */
    public function display_admin_survey_notice() {
        $survey_url = esc_url( 'https://surveys.hotjar.com/04c60174-7b10-425c-a49a-a179be675e03' );

        $err_inst_msg = sprintf(
            /* translators:  %1$s: Logo Link, %2$s: Survey Link, %3$s: Dismiss Link. */
            __( '<div style="display:inline-block;width:10%%;text-align:left;"><img src="%1$s" style="object-fit: contain;max-height:55px;margin-left:10px; margin-top:10px;"/></div><div style="display:inline-block;vertical-align: top;margin: 12px 20px;"><div>We would love to hear from you what should be the next feature for the Instructor Role plugin.</div><div> This survey is just 3 questions long and your feedback will be very helpful to us</div><a href="%2$s" target="_blank">Take the Survey</a> | <a href="%3$s">Dismiss</a></div>', 'wdm_instructor_role' ),
            plugins_url( 'modules/images/wisdmlabs_logo.png', __DIR__ ),
            $survey_url,
            add_query_arg( array( 'ir_dismiss_survey_notice' => 1 ) )
        );
        echo '<div class="notice is-dismissable" style="background-color: #fefece; font-weight: 500;">' . $err_inst_msg . '</div>';// 
    }

    public function display_buddypress_activation_notice() {
        $ir_admin_settings = get_option( '_wdmir_admin_settings', array() );
        if ( ! current_user_can( 'activate_plugins' ) || empty( $ir_admin_settings ) || empty( $ir_admin_settings['ir_student_communication_check'])) {
			return;
		}


		// Display BuddyPress deactivation message
		if ( ! function_exists( 'bp_is_active' ) ) {
			$bp_plugins_url = is_network_admin() ? network_admin_url( 'plugins.php' ) : admin_url( 'plugins.php' );
			$link_plugins   = sprintf( "<a href='%s'>%s</a>", $bp_plugins_url, __( 'activate', 'wdm_instructor_role' ) );
			?>

			<div id="message" class="error notice">
				<p><strong><?php esc_html_e( 'Instructor Role: Student Teacher Communication is disabled.', 'wdm_instructor_role' ); ?></strong></p>
				<p><?php printf( esc_html__( 'The Student Teacher Communication feature can\'t work without the BuddyPress plugin. Please %s BuddyPress to re-enable the module.', 'wdm_instructor_role' ), $link_plugins ); ?></p>
			</div>
			<?php
		}

        // Display BuddyPress messages component deactivation message
        if ( function_exists( 'bp_is_active' ) && ! bp_is_active( 'messages' ) ) {
            $bp_plugins_url = bp_get_admin_url( add_query_arg( array( 'page' => 'bp-components' ), 'admin.php' ) );
			$link_plugins   = sprintf( "<a href='%s'>%s</a>", $bp_plugins_url, __( 'activate', 'wdm_instructor_role' ) );
			?>

			<div id="message" class="error notice">
				<p><strong><?php esc_html_e( 'Instructor Role: Student Teacher Communication is disabled.', 'wdm_instructor_role' ); ?></strong></p>
				<p><?php printf( esc_html__( 'The Student Teacher Communication feature can\'t work without the Private Messaging component in BuddyPress plugin. Please %s BuddyPress Private Messaging to re-enable the module.', 'wdm_instructor_role' ), $link_plugins ); ?></p>
			</div>
			<?php
        }
    }
}

new Instructor_Role_Admin_Notices();
