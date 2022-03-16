<?php /*
Plugin Name: WP Memory
Plugin URI: http://wpmemory.com
Description: Check For High Memory Usage, include result in the Site Health Page and Give Suggestions.
Version: 2.31
Author: Bill Minozzi
Domain Path: /language
Author URI: http://billminozzi.com
Text Domain: wpmemory
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
if (!defined('ABSPATH')) {
    die('We\'re sorry, but you can not directly access this file.');
}
$wpmemory_php_memory_limit = (int) get_option('wpmemory_php_memory_limit', '0');
if ($wpmemory_php_memory_limit > 0 and $wpmemory_php_memory_limit <= 1024) {
    @ini_set('memory_limit', $wpmemory_php_memory_limit . 'M');
}
define('WPMEMORYURL', plugin_dir_url(__file__));
$wpmemory_request_url = trim(esc_url($_SERVER['REQUEST_URI']));

$plugin = plugin_basename(__FILE__);
define('WPMEMORYPATH', plugin_dir_path(__file__));
define('WPMEMORYDOMAIN', get_site_url());
define('WPMEMORYIMAGES', plugin_dir_url(__file__) . 'images');
define('WPMEMORYPAGE', trim(sanitize_text_field($GLOBALS['pagenow'])));
define('WPMEMORYHOMEURL', admin_url());
$wpmemory_request_url = esc_url($_SERVER['REQUEST_URI']);

function wpmemory_add_admstylesheet()
{
    global $wpmemory_request_url;

    $pos = strpos($wpmemory_request_url, 'page=wp_memory_admin_page');
    $pos2 = strpos($wpmemory_request_url, 'wp-admin/index.php');
    if ($pos !== false or $pos2 !== false)
    {
        wp_enqueue_script('jquery');
        wp_enqueue_script('wpmah-flot', WPMEMORYURL .
            'js/jquery.flot.min.js', array('jquery'));
        wp_enqueue_script('wpmflotpie', WPMEMORYURL .
            'js/jquery.flot.pie.js', array('jquery')); 
        wp_enqueue_script('wpmcircle', WPMEMORYURL .
            'js/radialIndicator.js', array('jquery'));

        wp_register_script("wpmemory-cookies", WPMEMORYURL . 'js/c_o_o_k_i_e.js', array('jquery'), WPMEMORYVERSION, true);
        wp_enqueue_script('wpmemory-cookies');

        wp_register_script("wpmemory-dismiss", WPMEMORYURL . 'js/dismiss.js', array('jquery'), WPMEMORYVERSION, true);
        wp_enqueue_script('wpmemory-dismiss');
    }

    wp_register_style('wpmemory ', plugin_dir_url(__FILE__) . '/css/wpmemory.css');
    wp_enqueue_style('wpmemory ');
}
if (is_admin()) {
    add_action('admin_init', 'wpmemory_add_admstylesheet');
}
$wpmemory_plugin_data = get_file_data(__FILE__, array('Version' => 'Version'), false);
$wp_memory_plugin_version = $wpmemory_plugin_data['Version'];
define('WPMEMORYVERSION', $wp_memory_plugin_version);
$wpmemory_checkversion = trim(sanitize_text_field(get_option('wpmemory_checkversion', '')));
add_filter("plugin_action_links_$plugin", 'wp_memory_plugin_settings_link');
$wpmemory_memory['limit'] = (int) ini_get('memory_limit');
$wpmemory_memory['usage'] = function_exists('memory_get_usage') ? round(memory_get_usage() / 1024 / 1024, 0) : 0;
if (!is_numeric($wpmemory_memory['usage']) or $wpmemory_memory['usage'] < 1) {
    $wpmemory_memory['usage'] = 1;
}
$wpmemory_mb = 'MB';
if (defined("WP_MEMORY_LIMIT")) {
    $wpmemory_memory['wp_limit'] = trim(WP_MEMORY_LIMIT);
    $wpmemory_memory['wp_limit'] = substr($wpmemory_memory['wp_limit'], 0, strlen($wpmemory_memory['wp_limit']) - 1);
} else {
    $wpmemory_memory['wp_limit'] = 40;
}
if (!is_numeric($wpmemory_memory['wp_limit'])) {
    $wpmemory_memory['wp_limit'] = 40;
}
$perc = $wpmemory_memory['usage'] / $wpmemory_memory['wp_limit'];
// $perc = 100;
if ($perc > .7) {
    $wpmemory_memory['color'] = 'red';
} else {
    $wpmemory_memory['color'] = 'green';
}
$wpmemory_usage_content = __('Current memory WordPress Limit', 'wpmemory') . ': ' . $wpmemory_memory['wp_limit'] . $wpmemory_mb . '&nbsp;&nbsp;&nbsp;  |&nbsp;&nbsp;&nbsp;';
$wpmemory_usage_content .= '<span style="color:' . $wpmemory_memory['color'] . ';">';
$wpmemory_usage_content .= 'Your usage now: ' . $wpmemory_memory['usage'] .
    'MB &nbsp;&nbsp;&nbsp;';
$wpmemory_usage_content .= '</span>';
$wpmemory_usage_content .= '<br />';
$wpmemory_usage_content .= '</strong>';
if ($perc > .7) {
    $wpmemory_label = 'Critical';
    $wpmemory_status = 'critical';
    $wpmemory_description = $wpmemory_usage_content . sprintf('<p>%s</p>', __('Run your site with High Memory Usage, can result in behaving slowly, or pages fail to load, you get random white screens of death or 500 internal server error. Basically, the more content, features and plugins you add to your site, the bigger your memory limit has to be. Increase the WP Memory Limit is a standard practice in WordPress. You can manually increase memory limit in WordPress by editing the wp-config.php file. You can find instructions in the official WordPress documentation (Increasing memory allocated to PHP). Just click the link below: ', 'wpmemory'));
    $wpmemory_actions = sprintf('<p><a href="%s">%s</a></p>', 'https://codex.wordpress.org/Editing_wp-config.php', __('WordPress Help Page', 'wpmemory'));
} else {
    $wpmemory_label = 'Performance';
    $wpmemory_status = 'good';
    $wpmemory_description = __('Pass', 'wpmemory') . '.';
    $wpmemory_actions =     '';
}
require_once WPMEMORYPATH . "functions/functions.php";
if (!empty(trim($wpmemory_checkversion)) or trim($wpmemory_checkversion) == '' ){

    wpmemory_update();
}
add_filter('site_status_tests', 'wpmemory_add_memory_test');
register_activation_hook(__FILE__, 'wpmemory_activation');
add_filter('debug_information', 'wpmemory_add_debug_info');
register_activation_hook(__FILE__, 'wpmemory_admin_notice_activation_hook');
add_action('admin_notices', 'wp_memory_activ_message');
add_action('admin_menu', 'wp_memory_init');


if (!function_exists('wp_get_current_user')) {
	require_once(ABSPATH . "wp-includes/pluggable.php");
}


function wpmemory_install_required_extensions()
{
 global $plugin_required;
 if(empty($plugin_required))
    return;

    echo '<div class="notice notice-warning is-dismissible">';
    echo '<br /><b>';
    echo __('Message from WP Memory', 'wpmemory');
    echo ':</b><br />';
    echo __('To Install the extension:', 'wpmemory');
    echo ' '.$plugin_required;
    echo '<br />';
    echo ' <a class="button button-primary" href="plugins.php?page=tgmpa-install-plugins">';
    echo __('click here', "wpmemory");
    echo '</a>';
    echo '<br /><br /></div>';
}

/* =============================== */
function wpmemory_new_more_plugins()
{
	//wpmemory_show_logo();
	$plugins_to_install = array();
	$plugins_to_install[0]["Name"] = "Anti Hacker Plugin";
	$plugins_to_install[0]["Description"] = "Firewall, Scanner, Login Protect, block user enumeration and TOR, disable Json WordPress Rest API, xml-rpc (xmlrpc) & Pingback and more security tools...";
	$plugins_to_install[0]["image"] = "https://ps.w.org/antihacker/assets/icon-256x256.gif?rev=2524575";
	$plugins_to_install[0]["slug"] = "antihacker";
	$plugins_to_install[1]["Name"] = "Stop Bad Bots";
	$plugins_to_install[1]["Description"] = "Stop Bad Bots, Block SPAM bots, Crawlers and spiders also from botnets. Save bandwidth, avoid server overload and content steal. Blocks also by IP.";
	$plugins_to_install[1]["image"] = "https://ps.w.org/stopbadbots/assets/icon-256x256.gif?rev=2524815";
	$plugins_to_install[1]["slug"] = "stopbadbots";
	$plugins_to_install[2]["Name"] = "WP Tools";
	$plugins_to_install[2]["Description"] = "More than 35 useful tools! It is a swiss army knife, to take your site to the next level.";
	$plugins_to_install[2]["image"] = "https://ps.w.org/wptools/assets/icon-256x256.gif?rev=2526088";
	$plugins_to_install[2]["slug"] = "wptools";
	$plugins_to_install[3]["Name"] = "reCAPTCHA For All";
	$plugins_to_install[3]["Description"] = "Protect ALL Pages of your site against bots (spam, hackers, fake users and other types of automated abuse)
	with invisible reCaptcha V3 (Google). You can also block visitors from China.";
	$plugins_to_install[3]["image"] = "https://ps.w.org/recaptcha-for-all/assets/icon-256x256.gif?rev=2544899";
	$plugins_to_install[3]["slug"] = "recaptcha-for-all";
	$plugins_to_install[4]["Name"] = "WP Memory";
	$plugins_to_install[4]["Description"] = "Check High Memory Usage, Memory Limit, PHP Memory, show result in Site Health Page and fix php low memory limit.";
	$plugins_to_install[4]["image"] = "https://ps.w.org/wp-memory/assets/icon-256x256.gif?rev=2525936";
	$plugins_to_install[4]["slug"] = "wp-memory";
	$plugins_to_install[5]["Name"] = "Truth Social";
	$plugins_to_install[5]["Description"] = "Tools and feeds for Truth Social new social media platform and Twitter.";
	$plugins_to_install[5]["image"] = "https://ps.w.org/toolstruthsocial/assets/icon-256x256.png?rev=2629666";
	$plugins_to_install[5]["slug"] = "toolstruthsocial";
?>
	<div style="padding-right:20px;">
		<!-- <h1>Useful FREE Plugins of the same author</h1> -->
		<div id="bill-wrap-install" class="bill-wrap-install" style="display:none">
			<h3>Please wait</h3>
			<big>
				<h4>
					Installing plugin <div id="billpluginslug">...</div>
				</h4>
			</big>
			<img src="/wp-admin/images/wpspin_light-2x.gif" id="billimagewaitfbl" style="display:none;margin-left:0px;margin-top:0px;" />
			<br />
		</div>
		<table style="margin-right:20px; border-spacing: 0 25px; " class="widefat" cellspacing="0" id="wpmemory-more-plugins-table">
			<tbody class="wpmemory-more-plugins-body">
				<?php
				$counter = 0;
				$total = count($plugins_to_install);
				for ($i = 0; $i < $total; $i++) {
					if ($counter % 2 == 0) {
						echo '<tr style="background:#f6f6f1;">';
					}
					++$counter;
					if ($counter % 2 == 1)
						echo '<td style="max-width:140px; max-height:140px; padding-left: 40px;" >';
					else
						echo '<td style="max-width:140px; max-height:140px;" >';
					echo '<img style="width:100px;" src="' . esc_url($plugins_to_install[$i]["image"]) . '">';
					echo '</td>';
					echo '<td style="width:40%;">';
					echo '<h3>' . esc_attr($plugins_to_install[$i]["Name"]) . '</h3>';
					echo esc_attr($plugins_to_install[$i]["Description"]);
					echo '<br>';
					echo '</td>';
					echo '<td style="max-width:140px; max-height:140px;" >';
					if (wpmemory_plugin_installed($plugins_to_install[$i]["slug"]))
						echo '<a href="#" class="button activate-now">Installed</a>';
					else
						echo '<a href="#" id="' . esc_attr($plugins_to_install[$i]["slug"]) . '"class="button button-primary wpm-bill-install-now">Install</a>';
					echo '</td>';
					if ($counter % 2 == 1) {
						echo '<td style="width; 100px; border-left: 1px solid gray;">';
						echo '</td>';
					}
					if ($counter % 2 == 0) {
						echo '</tr>';
					}
				}
				?>
			</tbody>
		</table>
	</div>
<?php
}
function wpmemory_plugin_installed($slug)
{
	$all_plugins = get_plugins();
	foreach ($all_plugins as $key => $value) {
		$plugin_file = $key;
		$slash_position = strpos($plugin_file, '/');
		$folder = substr($plugin_file, 0, $slash_position);
		// match FOLDER against SLUG
		if ($slug == $folder) {
			return true;
		}
	}
	return false;
}
function wpmemory_load_upsell()
{
	Global $wpmemory_checkversion;

	wp_enqueue_style('wpmemory-more2', WPMEMORYURL . 'includes/more/more2.css');
	wp_register_script('wpmemory-more2-js', WPMEMORYURL . 'includes/more/more2.js', array('jquery'));
	wp_enqueue_script('wpmemory-more2-js');
	$wpmemory_bill_go_pro_hide = trim(get_option('bill_go_pro_hide'));


	if (!empty($wpmemory_checkversion))
	   return;


    // $wpmemory_bill_go_pro_hide = '';
    // Debug ...
    $wtime = strtotime('-08 days');
    // update_option('wpmemory_bill_go_pro_hide', $wtime);
    if(empty ($wpmemory_bill_go_pro_hide)) {
        $wtime = strtotime('-05 days');
        update_option('bill_go_pro_hide', $wtime);
        $wpmemory_bill_go_pro_hide =  $wtime;
    }
    $now = time();
    $delta = $now - $wpmemory_bill_go_pro_hide;

	// debug
	// $delta = time();
    /*
    if ($delta > (3600 * 24 * 6)) {
	   $list = 'enqueued';
	   if( !wp_script_is( 'bill-css-vendor-fix', $list ) ) {
		require_once(WPMEMORYPATH . 'includes/vendor/vendor.php');
		wp_enqueue_style('bill-css-vendor-fix', WPMEMORYURL . 'includes/vendor/vendor_fix.css');

		wp_register_script("bill-js-vendor", WPMEMORYURL . 'includes/vendor/vendor.js', array('jquery'), WPMEMORYVERSION, true);
		wp_enqueue_script('bill-js-vendor');

	   }
    }
	*/

	wp_register_script("bill-js-vendor-sidebar", WPMEMORYURL . 'includes/vendor/vendor-sidebar.js', array('jquery'), WPMEMORYVERSION, true);
	wp_enqueue_script('bill-js-vendor-sidebar');

	wp_enqueue_style('bill-css-vendor-wpm', WPMEMORYURL . 'includes/vendor/vendor.css');
	// var_dump(__LINE__);
}

if (!function_exists('wp_get_current_user')) {
	require_once(ABSPATH . "wp-includes/pluggable.php");
}
if (is_admin() or is_super_admin()) {
	//if (empty($wpmemory_checkversion))
	  add_action('admin_enqueue_scripts', 'wpmemory_load_upsell');
	//add_action('init', 'wpmemory_load_upsell');
	add_action('wp_ajax_wpmemory_install_plugin', 'wpmemory_install_plugin');
}

function wpmemory_install_plugin()
{
	if (isset($_POST['slug'])) {
		$slug = sanitize_text_field($_POST['slug']);
	} else {
		echo 'Fail error (-5)';
		wp_die();
	}
	$plugin['source'] = 'repo'; // $_GET['plugin_source']; // Plugin source.
	require_once ABSPATH . 'wp-admin/includes/plugin-install.php'; // Need for plugins_api.
	require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php'; // Need for upgrade classes.
	// get plugin information
	$api = plugins_api('plugin_information', array('slug' => $slug, 'fields' => array('sections' => false)));
	if (is_wp_error($api)) {
		echo 'Fail error (-1)';
		wp_die();
		// proceed
	} else {
		// Set plugin source to WordPress API link if available.
		if (isset($api->download_link)) {
			$plugin['source'] = $api->download_link;
			$source =  $api->download_link;
		} else {
			echo 'Fail error (-2)';
			wp_die();
		}
		$nonce = 'install-plugin_' . $api->slug;
		/*
        $type = 'web';
        $url = $source;
        $title = 'wptools';
        */
		$plugin = $slug;
		// verbose...
		//    $upgrader = new Plugin_Upgrader($skin = new Plugin_Installer_Skin(compact('type', 'title', 'url', 'nonce', 'plugin', 'api')));
		class wpmemory_QuietSkin extends \WP_Upgrader_Skin
		{
			public function feedback($string, ...$args)
			{ /* no output */
			}
			public function header()
			{ /* no output */
			}
			public function footer()
			{ /* no output */
			}
		}
		$skin = new wpmemory_QuietSkin(array('api' => $api));
		$upgrader = new Plugin_Upgrader($skin);
		// var_dump($upgrader);
		try {
			$upgrader->install($source);
			//	get all plugins
			$all_plugins = get_plugins();
			// scan existing plugins
			foreach ($all_plugins as $key => $value) {
				// get full path to plugin MAIN file
				// folder and filename
				$plugin_file = $key;
				$slash_position = strpos($plugin_file, '/');
				$folder = substr($plugin_file, 0, $slash_position);
				// match FOLDER against SLUG
				// if matched then ACTIVATE it
				if ($slug == $folder) {
					// Activate
					$result = activate_plugin(ABSPATH . 'wp-content/plugins/' . $plugin_file);
					if (is_wp_error($result)) {
						// Process Error
						echo 'Fail error (-3)';
						wp_die();
					}
				} // if matched
			}
		} catch (Exception $e) {
			echo 'Fail error (-4)';
			wp_die();
		}
	} // activation
	echo 'OK';
	wp_die();
}


add_filter('plugin_row_meta', 'wpmemory_custom_plugin_row_meta', 10, 2);
function wpmemory_custom_plugin_row_meta($links, $file)
{
    global $wpmemory_checkversion;
    if (strpos($file, 'wpmemory.php') !== false) {
        $new_links = array();
        
        if (empty($wpmemory_checkversion)) {
            $new_links['Pro'] = '<a href="https://wpmemory.com/premium/" target="_blank"><b><font color="#FF6600">Go Pro</font></b></a>';
        }
        else
        {
            if (is_multisite()) {
                 $url = esc_url(WPMEMORYHOMEURL)."plugin-install.php?s=sminozzi&tab=search&type=author";
                 }
                 else {
                   $url =  esc_url(WPMEMORYHOMEURL).'admin.php?page=wp_memory_admin_page&tab=tools';
                 }

            $new_links['Other'] = '<a href="'.$url.'" target="_blank"><b><font color="#FF6600">Click To see more plugins from same author</font></b></a>';
        }

        $links = array_merge($links, $new_links);
    }
    return $links;
}

function wpmemory_bill_go_pro_hide()
{
    // $today = date('Ymd', strtotime('+06 days'));
    $today = time();
    if (!update_option('bill_go_pro_hide', $today))
        add_option('bill_go_pro_hide', $today);
    wp_die();
}
add_action('wp_ajax_wpmemory_bill_go_pro_hide', 'wpmemory_bill_go_pro_hide');
