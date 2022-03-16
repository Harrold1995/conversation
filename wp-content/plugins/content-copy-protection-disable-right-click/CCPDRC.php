<?php
/**
 * Plugin Name: Content Copy Protection & Disable Right Click
 * Plugin URI: https://wordpress.org/plugins/content-copy-protection-disable-right-click/
 * Text Domain: content-copy-protection-disable-right-click
 * Domain Path: /languages
 * Description: This plugin provides a quick and easy way to disable right click, disable cut, copy, paste, disable view source and disable image drag & drop.
 * Version: 1.0.3
 * Author: Subodh Ghulaxe
 * Author URI: http://www.subodhghulaxe.com
 */

// Avoid direct calls to this file where wp core files not present
if (!function_exists('add_action')) {
  header('Status: 403 Forbidden');
  header('HTTP/1.1 403 Forbidden');
  exit();
}

if (!class_exists('CCPDRC')) {
  /**
   * Content Copy Protection & Disable Right Click class
   *
   * @package Content Copy Protection & Disable Right Click
   * @since 1.0.0
   */
  class CCPDRC {

    /**
		 * Instance of CCPDRC class
		 *
		 * @since 1.0.0
		 * @access private
		 * @var object
		 */
		private static $instance = false;

		/**
		 * Plugin settings
		 *
		 * @since 1.0.0
		 * @access public
		 * @var array
		 */
		public $ccpdrc_settings = array();

		public $default_settings = array(
			"right_click" => "1",
			"right_click_message" => "Right click is disabled!",
			"cut_copy_paste" => "1",
			"cut_copy_paste_message" => "Cut/Copy/Paste is disabled!",
			"view_source" => "1",
			"view_source_message" => "View source is disabled!",
			"image_drag_drop" => "1",
			"image_drag_drop_message" => "Image drag and drop is disabled!",
			"content_selection" => "1",
			"content_selection_message" => "Content selection is disabled!",
		);

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
		
		function __construct() {
			$this->constants();
			$this->text_domain();
			$this->ccpdrc_settings = get_option('ccpdrc_settings', array());

			add_action( 'init', array( &$this, 'init' ));
			add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_scripts' ) );
		}

    /**
		 * Define plugin constants
		 *
		 * @since 1.0.0
		 */
		public function constants() {
			defined("CCPDRC_PLUGIN_NAME") || define( 'CCPDRC_PLUGIN_NAME', 'Content Copy Protection & Disable Right Click' );
			defined("CCPDRC_BASEDIR") || define( 'CCPDRC_BASEDIR', dirname( plugin_basename(__FILE__) ) );
			defined("CCPDRC_ASSETS_URL") || define( 'CCPDRC_ASSETS_URL', plugins_url('assets/',__FILE__) );
		}

    /**
		 * Load plugin text domain
		 *
		 * @since 1.0.0
		 */
		public function text_domain() {
			load_plugin_textdomain( 'content-copy-protection-disable-right-click', false, CCPDRC_BASEDIR . '/languages' );
		}

    /**
		 * Runs after WordPress has finished loading but before any headers are sent.
		 *
		 * @since 1.0.0
		 */
		public function init() {
			// Add settings link in plugin listing page.
			add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( &$this, 'add_action_links' ) );

			// Add donate link in plugin listing page.
			add_filter( 'plugin_row_meta', array( &$this, 'donate_link' ), 10, 2 );
		}

		/**
		 * Enqueue script and style
		 * 
		 * @since 1.0.0
		 * @return array
		 */
		public function enqueue_scripts() {
			wp_enqueue_style( 'ccpdrc-style', CCPDRC_ASSETS_URL . 'style.css', array(), '1.0.3' );
    	wp_enqueue_script( 'ccpdrc-script', CCPDRC_ASSETS_URL . 'script.js', array(), '1.0.3', true );
			wp_localize_script( 'ccpdrc-script', 'ccpdrc_settings', $this->ccpdrc_settings );
		}

    /**
		 * Add settings link to plugin action links in /wp-admin/plugins.php
		 * 
		 * @since 1.0.0
		 * @param  array $links
		 * @return array
		 */
		public function add_action_links ( $links ) {
			$mylinks = array(
				'<a href="' . admin_url( 'options-general.php?page=ccpdrc_options' ) . '">'.__( 'Settings', 'content-copy-protection-disable-right-click' ).'</a>',
			);

			return array_merge( $mylinks, $links );
		}

		/**
		 * Called on register_activation_hook
		 *
		 * @since 1.0.0
		 */
    public static function activate() {
			$ccpdrc_instance = CCPDRC::get_instance();
			// Save default settings on activate
			update_option('ccpdrc_settings', array_merge(
				$ccpdrc_instance->default_settings, 
				$ccpdrc_instance->ccpdrc_settings
			));
    }

		/**
		 * Add donate link to plugin description in /wp-admin/plugins.php
		 * 
		 * @since 1.0.0
		 * @param  array $plugin_meta
		 * @param  string $plugin_file
		 * @return array
		 */
		public function donate_link( $plugin_meta, $plugin_file ) {
			if ( plugin_basename( __FILE__ ) == $plugin_file )
				$plugin_meta[] = sprintf(
					'&hearts; <a href="%s" target="_blank">%s</a>',
					'https://www.patreon.com/subodhghulaxe',
					__( 'Donate', 'content-copy-protection-disable-right-click' )
			);
			
			return $plugin_meta;
		}

  } // end class CCPDRC

  add_action('plugins_loaded', array('CCPDRC', 'get_instance'));
	register_activation_hook(__FILE__, array('CCPDRC', 'activate'));

  include_once('admin/CCPDRC_Admin.php');
}
