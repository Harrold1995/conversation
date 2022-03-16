<?php

// Avoid direct calls to this file where wp core files not present
if (!function_exists ('add_action')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}

if ( !class_exists( 'CCPDRC_Admin' ) ) {

  /**
	 * Content Copy Protection & Disable Right Click Admin class
	 *
	 * @package Content Copy Protection & Disable Right Click
	 * @since 1.0.0
	 */
	class CCPDRC_Admin {

    /**
		 * Instance of CCPDRC_Admin class
		 *
		 * @since 1.0.0
		 * @access private
		 * @var object
		 */
		private static $instance = false;

		/**
		 * Return unique instance of this class
		 *
		 * @since 1.0.0
		 * @return object
		 */
		public static function get_instance() {
			if ( ! self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

    public function __construct() {
			if (is_admin()) {
				add_action( 'admin_menu', array( &$this, 'register_admin_menu' ) );
			}
      add_action( 'admin_enqueue_scripts', array( &$this,'admin_scripts' ) );
			add_action( 'wp_ajax_ccpdrc_save_settings', array( &$this,'save_settings' ) );
		}

    /**
		 * Include style in WordPress admin
		 * 
		 * @since 1.0.0
		 */
		function admin_scripts() {
			wp_enqueue_style('ccpdrc-admin-style', CCPDRC_ASSETS_URL.'admin.css');
			wp_enqueue_script('ccpdrc-admin-script', CCPDRC_ASSETS_URL.'admin.js');
		}

    /**
		 * Add submenu in WordPress admin settings menu
		 * 
		 * @since 1.0.0
		 */
		public function register_admin_menu() {
			add_options_page( CCPDRC_PLUGIN_NAME.' Settings', __( 'Disable Right Click', 'content-copy-protection-disable-right-click' ), 'manage_options', 'ccpdrc_options', array( &$this, 'options' ) );
		}

    /**
		 * Load the setting page
		 * 
		 * @since 1.0.0
		 */
		public function options() {
			include_once('pages/options.php');
		}

		/**
		 * Save plugin settings
		 * 
		 * @since 1.0.0
		 * @return string (json)
		 */
		public function save_settings() {
			$response = array();
			$error = "";

			// Check for request security
			if (!check_ajax_referer( 'ccpdrc-save-settings', 'security' )) {
				$error = __( "Error! Security Check Failed! Please refresh page and save settings again.", 'content-copy-protection-disable-right-click' );
			}

			// Check user capabilities
			if (!current_user_can('manage_options'))
				return;

			// Securing inputs
			$ccpdrc_settings = array(
				"right_click" => isset($_POST['ccpdrc_settings']['right_click']) ? sanitize_text_field($_POST['ccpdrc_settings']['right_click']) : '',
				"right_click_message" => isset($_POST['ccpdrc_settings']['right_click_message']) ? sanitize_text_field($_POST['ccpdrc_settings']['right_click_message']) : '',
				"cut_copy_paste" => isset($_POST['ccpdrc_settings']['cut_copy_paste']) ? sanitize_text_field($_POST['ccpdrc_settings']['cut_copy_paste']) : '',
				"cut_copy_paste_message" => isset($_POST['ccpdrc_settings']['cut_copy_paste_message']) ? sanitize_text_field($_POST['ccpdrc_settings']['cut_copy_paste_message']) : '',
				"view_source" => isset($_POST['ccpdrc_settings']['view_source']) ? sanitize_text_field($_POST['ccpdrc_settings']['view_source']) : '',
				"view_source_message" => isset($_POST['ccpdrc_settings']['view_source_message']) ? sanitize_text_field($_POST['ccpdrc_settings']['view_source_message']) : '',
				"image_drag_drop" => isset($_POST['ccpdrc_settings']['image_drag_drop']) ? sanitize_text_field($_POST['ccpdrc_settings']['image_drag_drop']) : '',
				"image_drag_drop_message" => isset($_POST['ccpdrc_settings']['image_drag_drop_message']) ? sanitize_text_field($_POST['ccpdrc_settings']['image_drag_drop_message']) : '',
				"content_selection" => isset($_POST['ccpdrc_settings']['content_selection']) ? sanitize_text_field($_POST['ccpdrc_settings']['content_selection']) : '',
				"content_selection_message" => isset($_POST['ccpdrc_settings']['content_selection_message']) ? sanitize_text_field($_POST['ccpdrc_settings']['content_selection_message']) : '',
			);

      // Save setting in WordPress options
      if (empty($error)) {
				update_option('ccpdrc_settings', $ccpdrc_settings);
        $response['status'] = 'success';
        $response['message'] = __( 'Settings updated successfully.', 'content-copy-protection-disable-right-click' );
      } else {
        $response['status'] = 'error';
        $response['message'] = $error;
      }

			echo json_encode($response);
			exit();
		}

  } // end class CCPDRC_Admin

	add_action( 'plugins_loaded', array( 'CCPDRC_Admin', 'get_instance' ) );

} // end class_exists