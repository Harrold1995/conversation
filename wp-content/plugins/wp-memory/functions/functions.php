<?php
/**
 * @ Author: Bill Minozzi
 * @ Copyright: 2020 www.BillMinozzi.com
 * @ Modified time: 2021-03-03 08:58:41
 */
if (!defined('ABSPATH')) {
    die('We\'re sorry, but you can not directly access this file.');
}
$wpmemory_now = strtotime("now");
$wpmemory_after = strtotime("now") + (3600);
function wpmemory_enqueue_scripts()
{
    wp_register_script('wpmemory-fix-config-manager', WPMEMORYURL . 'dashboard/fixconfig/wp-memory-fix-config-manager.js', array('jquery'), WPMEMORYVERSION, true);
    wp_enqueue_script('wpmemory-fix-config-manager');
    wp_enqueue_style('bill-help-wpmemory', WPMEMORYURL . 'dashboard/fixconfig/help.css');
}
add_action('admin_init', 'wpmemory_enqueue_scripts');
if (!function_exists('ini_get')) {
	function wpmemory_general_admin_notice2()
	{
		if (is_admin()) {
			echo '<div class="notice notice-warning is-dismissible">
				 <p>Your server doesn\'t have a PHP function ini_get.</p>
				 <p>Please, talk with your hosting company.</p>
			 </div>';
		}
	}
	add_action('admin_notices', 'wpmemory_general_admin_notice');
}
function wpmemory_memory_test()
{
    global $wpmemory_memory, $wpmemory_usage_content, $wpmemory_label, $wpmemory_status, $wpmemory_description, $wpmemory_actions;
    $result = array(
        'badge' => array(
            'label' => $wpmemory_label,
            'color' => $wpmemory_memory['color'],
        ),
        'test' => 'wpmemory_test',
        // status: Section the result should be displayed in. Possible values are good, recommended, or critical.
        'status' => $wpmemory_status,
        'label' => __('Memory Usage', 'wpmemory'),
        'description' => $wpmemory_description . '  ' . $wpmemory_usage_content,
        'actions' => $wpmemory_actions
    );
    return $result;
}
function wpmemory_add_debug_info($debug_info)
{
    global $wpmemory_usage_content;
    $debug_info['wpmemory'] = array(
        'label' => __('Memory Usage', 'wpmemory'),
        'fields' => array(
            'memory' => array(
                'label' => __('Memory Usage information', 'wpmemory'),
                'value' => strip_tags($wpmemory_usage_content),
                'private' => true
            )
        )
    );
    return $debug_info;
}
function wpmemory_activation()
{
    global $wp_version;
    if (version_compare(PHP_VERSION, '5.3', '<')) {
        deactivate_plugins(plugin_basename(__FILE__));
        load_plugin_textdomain('wpmemory', false, dirname(plugin_basename(__FILE__)) . '/language/');
        $plugin_data    = get_plugin_data(__FILE__);
        $plugin_version = $plugin_data['Version'];
        $plugin_name    = $plugin_data['Name'];
        wp_die('<h1>' . __('Could not activate plugin: PHP version error', 'wpmemory') . '</h1><h2>PLUGIN: <i>' . $plugin_name . ' ' . $plugin_version . '</i></h2><p><strong>' . __('You are using PHP version', 'wpmemory') . ' ' . PHP_VERSION . '</strong>. ' . __('This plugin has been tested with PHP versions 5.3 and greater.', 'wpmemory') . '</p><p>' . __('WordPress itself <a href="https://wordpress.org/about/requirements/" target="_blank">recommends using PHP version 7 or greater</a>. Please upgrade your PHP version or contact your Server administrator.', 'wpmemory') . '</p>', __('Could not activate plugin: PHP version error', 'wpmemory'), array(
            'back_link' => true
        ));
    }
    if (version_compare($wp_version, '5.2') < 0) {
        deactivate_plugins(plugin_basename(__FILE__));
        load_plugin_textdomain('wpmemory', false, dirname(plugin_basename(__FILE__)) . '/language/');
        $plugin_data    = get_plugin_data(__FILE__);
        $plugin_version = $plugin_data['Version'];
        $plugin_name    = $plugin_data['Name'];
        wp_die('<h1>' . __('Could not activate plugin: WordPress need be 5.2 or bigger.', 'wpmemory') . '</h1><h2>PLUGIN: <i>' . $plugin_name . ' ' . $plugin_version . '</i></h2><p><strong>' . __('Please, Update WordPress to Version 5.2 or bigger to use this plugin.', 'wpmemory') . '</strong>', array(
            'back_link' => true
        ));
    }
}
function wp_memory_activ_message()
{
    if (get_transient('wpmemory-admin-notice')) {
        echo '<div class="updated"><p>';
        $bd_msg = '<h2>';
        $bd_msg .= __('WP Memory  Plugin was activated!', "wpmemory");
        $bd_msg .= '</h2>';
        $bd_msg .= '<h3>';
        $bd_msg .= __('For details and help, take a look at WP Memory  at your left menu, Tools', "wpmemory");
        $bd_msg .= '<br />';
        $bd_msg .= ' <a class="button button-primary" href="admin.php?page=wp_memory_admin_page">';
        $bd_msg .= __('or click here', "wpmemory");
        $bd_msg .= '</a>';
        echo $bd_msg;
        echo "</p></h3></div>";
        delete_transient('wpmemory-admin-notice');
    }
}
function wpmemory_admin_notice_activation_hook()
{
    /* Create transient data */
    set_transient('wpmemory-admin-notice', true, 5);
}
function wp_memory_init()
{
    add_management_page(
        'WP Memory',
        'WP Memory',
        'manage_options',
        'wp_memory_admin_page', // slug
        'wp_memory_admin_page'
    );
}
function wpmemory_check_memory()
{
    global $wpmemory_memory;
    $wpmemory_memory['limit'] = (int) ini_get('memory_limit');
    $wpmemory_memory['usage'] = function_exists('memory_get_usage') ? round(memory_get_usage() / 1024 / 1024, 0) : 0;
    if (!defined("WP_MEMORY_LIMIT")) {
        $wpmemory_memory['msg_type'] = 'notok';
        return;
    }
    $wpmemory_memory['wp_limit'] =  trim(WP_MEMORY_LIMIT);
    if ($wpmemory_memory['wp_limit'] > 9999999)
        $wpmemory_memory['wp_limit'] = ($wpmemory_memory['wp_limit'] / 1024) / 1024;
    if (!is_numeric($wpmemory_memory['usage'])) {
        $wpmemory_memory['msg_type'] = 'notok';
        return;
    }
    if (!is_numeric($wpmemory_memory['limit'])) {
        $wpmemory_memory['msg_type'] = 'notok';
        return;
    }
    
    if ($wpmemory_memory['limit'] > 9999999)
       $wpmemory_memory['limit'] = ($wpmemory_memory['limit'] / 1024) / 1024;	


    if ($wpmemory_memory['usage'] < 1) {
        $wpmemory_memory['msg_type'] = 'notok';
        return;
    }
    $wplimit = $wpmemory_memory['wp_limit'];
    $wplimit = substr($wplimit, 0, strlen($wplimit) - 1);
    $wpmemory_memory['wp_limit'] = $wplimit;
    $wpmemory_memory['percent'] = $wpmemory_memory['usage'] / $wpmemory_memory['wp_limit'];
    $wpmemory_memory['color'] = 'font-weight:normal;';
    if ($wpmemory_memory['percent'] > .7) $wpmemory_memory['color'] = 'font-weight:bold;color:#E66F00';
    if ($wpmemory_memory['percent'] > .85) $wpmemory_memory['color'] = 'font-weight:bold;color:red';
    $wpmemory_memory['msg_type'] = 'ok';
    return $wpmemory_memory;
}
function wp_memory_admin_page()
{
            require_once WPMEMORYPATH . "/dashboard/dashboard_container.php";
}
function wp_memory_plugin_settings_link($links)
{
    $settings_link = '<a href="admin.php?page=wp_memory_admin_page">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}

function wpmemory_add_memory_test($tests)
{
    $tests['direct']['wpmemory_plugin'] = array(
        'label' => __('WP Memory Test', 'wpmemory'),
        'test' => 'wpmemory_memory_test'
    );
    return $tests;
}
function wpmemory_check($code)
{
  $code = trim($code);
  if(empty($code))
    return false;
  $code = stripNonAlphaNumeric($code);
  $size = strlen($code);
  if (($size != 17) and ($size != 6)  and ($size != 7)  and ($size != 8))
    return false;
  if ($size == 6 or $size == 7 or $size == 8) {
    if (!is_numeric($code))
      return false;
    if ($code < 290000)
      return false;
  }
  /*
  if (($size == 17)) {
    if (is_numeric($code))
      return false;
    if (!ctype_alnum($code))
      return false;
    if (!preg_match('#[0-9]#', $code)) {
      return false;
    }
    if ($code != strtoupper($code))
      return false;
  }
  */
  return true;
}
function wpmemory_updated_message()
{
    echo '<div class="notice notice-success is-dismissible">';
    echo '<br /><b>';
    echo __('Database Updated!', 'wpmemory');
    echo '<br /><br /></div>';
}


function wpmemory_update()
{
    global $wpmemory_checkversion;


    $wpmemory_termina = get_transient('wpmemory_termina');


      //  if (empty($wpmemory_checkversion) or $wpmemory_termina !== false)
        if (empty($wpmemory_checkversion) or  trim($wpmemory_checkversion) == ''  or $wpmemory_termina !== false) {
            return;
        }


    ob_start();
    $domain_name = get_site_url();
    $urlParts = parse_url($domain_name);
    $domain_name = preg_replace('/^www\./', '', $urlParts['host']);
    $myarray = array(
        'domain_name' => $domain_name,
        'wpmemory_checkversion' => $wpmemory_checkversion,
        'wpmemory_version' => WPMEMORYVERSION
    );
    $url = "https://wpmemory.com/api/httpapi.php";
    $response = wp_remote_post($url, array(
        'method' => 'POST',
        'timeout' => 5,
        'redirection' => 5,
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => array(),
        'body' => $myarray,
        'cookies' => array()
    ));
    if (is_wp_error($response)) {
        set_transient('wpmemory_termina', DAY_IN_SECONDS, DAY_IN_SECONDS);
        ob_end_clean();
        return;
    }
    $r = trim($response['body']);
    $r = json_decode($r, true);
    $q = count($r);
    if ($q == 1) {
        $botip = trim($r[0]['ip']);
        if ($botip == '-9') {
            update_option('wpmemory_checkversion', '');
        }
        else
           set_transient('wpmemory_termina', DAY_IN_SECONDS, (30 * DAY_IN_SECONDS)  );
    }
    else
    {
        set_transient('wpmemory_termina', DAY_IN_SECONDS, DAY_IN_SECONDS);
    }
    ob_end_clean();
}