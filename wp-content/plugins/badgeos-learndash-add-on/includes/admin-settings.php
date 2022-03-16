<?php
/**
 * BadgeOS LearnDash Settings
 */

if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Class BadgeOS_ld_Admin_Settings
 */
class BadgeOS_ld_Admin_Settings {

    public $page_tab;

    public function __construct() {

        add_filter( 'admin_footer_text', [ $this, 'remove_footer_admin' ] );
        add_action('admin_init', array($this,'badgeos_learndash_setting_register'));
        add_action( 'badgeos_settings_main_tab_header', array($this,'badgeos_learndash_settings_tab_header'),10, 1);
        add_action( 'badgeos_settings_main_tab_content', array($this, 'badgeos_learndash_settings_tab_content'),10, 1);

        //	enqueue admin scripts
		add_action( 'admin_enqueue_scripts', array($this, 'badgeos_learndasg_admin_enqueue_scripts') );
    }

    /**
     * Register the JavaScript and CSS for the admin area.
     *
     * @since    1.1
     */
    public function badgeos_learndasg_admin_enqueue_scripts() {

        /**
         * Load admin styles
         */
        wp_enqueue_style('badgeos-learndash-admin', plugin_dir_url(__FILE__) . 'assets/css/badgeos-learndash-admin-style.css', array(), '1.1', 'all');

        /**
         * Load admin scripts
         */
        wp_enqueue_script('badgeos-learndash-admin', plugin_dir_url(__FILE__) . 'assets/js/badgeos-learndash-admin-script.js', array('jquery'), '1.1', true);

    }

    /**
     * Add footer branding
     *
     * @param $footer_text
     * @return mixed
     */
    function remove_footer_admin ( $footer_text ) {
        if( isset( $_GET['page'] ) && ( $_GET['page'] == 'badgeos_learndash_settings' ) ) {
            _e('Fueled by <a href="http://www.wordpress.org" target="_blank">WordPress</a> | developed and designed by <a href="https://wooninjas.com" target="_blank">The WooNinjas</a></p>', 'badgeos-learndash' );
        } else {
            return $footer_text;
        }
    }

    /*
     *  register settings for BadgeOS LearnDash addon    
    */
    
    function badgeos_learndash_setting_register()
    {
         register_setting( 'wn_bos_ld_options_group', 'wn_bos_ld_options', array($this, 'badgeos_learndash_settings_validate') );
    }
    
    /*
     *  validate registered settings for BadgeOS LearnDash addon    
    */

    function badgeos_learndash_settings_validate($input='')
    {
        $bdos_ld_original_settings=array();
        $bdos_ld_saved_settings = get_option('wn_bos_ld_options');

        $bdos_ld_original_settings['quiz_points_as_badgeos_points'] = isset( $_POST['quiz_points_as_badgeos_points'] ) ?  $_POST['quiz_points_as_badgeos_points'] : $bdos_ld_saved_settings['quiz_points_as_badgeos_points'];

        $bdos_ld_original_settings['badgeos_learndash_quiz_score_multiplier'] = isset( $_POST['badgeos_learndash_quiz_score_multiplier'] ) ?  (int) $_POST['badgeos_learndash_quiz_score_multiplier'] : $bdos_ld_saved_settings['badgeos_learndash_quiz_score_multiplier'];

        $bdos_ld_original_settings['bos_ld_quiz_point_type'] = isset( $_POST['bos_ld_quiz_point_type'] ) ? absint($_POST['bos_ld_quiz_point_type']) : $bdos_ld_saved_settings['bos_ld_quiz_point_type'];

        $bdos_ld_original_settings['badgeos_learndash_quiz_multi_time_point_award'] = $_POST['badgeos_learndash_quiz_multi_time_point_award']=='on' ? 1 : 0;
        
        return $bdos_ld_original_settings;
    }

    /*
     *  add heading of main tab for BadgeOS LearnDash addon    
    */

    function badgeos_learndash_settings_tab_header($setting_page_tab)
    {
         ?>
         <a href="admin.php?page=badgeos_settings&bos_s_tab=bos_ld_settings" class="nav-tab <?php echo $setting_page_tab == 'bos_ld_settings'? 'nav-tab-active' : ''; ?>">
                <i class="fa fa-shield" aria-hidden="true"></i>
                <?php _e( 'LearnDash', 'badgeos' ); ?>
        </a>
         <?php
    }

    /*
     *  add content od main tab for BadgeOS LearnDash addon    
    */

    function badgeos_learndash_settings_tab_content($setting_page_tab)
    {
        if( $setting_page_tab == 'bos_ld_settings' ) {
            include_once( 'admin-templates/general.php' );
        }
    }
}

$GLOBALS['badgeos_learndash_options'] = new BadgeOS_ld_Admin_Settings();