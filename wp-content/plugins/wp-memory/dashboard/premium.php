<?php
/**
 * @ Author: Bill Minozzi
 * @ Copyright: 2020 www.BillMinozzi.com
 * @ Modified time: 2021-03-02 17:19:27
 */
if (!defined('ABSPATH')) {
    die('We\'re sorry, but you can not directly access this file.');
}
global $wpmemory_memory;
global $wpmemory_checkversion;
if (!function_exists('ini_set')) {
    function wpmemory_general_admin_notice1()
    {
        if (is_admin()) {
            echo '<div class="notice notice-warning is-dismissible">
				 <p>Your server doesn\'t have a PHP function ini_set.</p>
				 <p>Please, talk with your hosting company before to buy premium.</p>
			 </div>';
        }
    }
    add_action('admin_notices', 'wpmemory_general_admin_notice');
}
if (isset($_GET['page']) && $_GET['page'] == 'wp_memory_admin_page') {
    if (isset($_POST['process']) && $_POST['process'] == 'wp_memory_admin_page') {
        //get limit
        if (isset($_POST['pcode'])) {
            $wpmemory_pcode = sanitize_text_field($_POST['pcode']);
            // var_dump($wpmemory_pcode);
            
           // if (wpmemory_check($wpmemory_pcode)) {
                if (!update_option('wpmemory_checkversion', $wpmemory_pcode))
                    add_option('wpmemory_checkversion', $wpmemory_pcode);
                wpmemory_updated_message();
           // }
            
        }
    }
}
//display form
echo '<div class="wrap-wpmemory ">' . "\n";
echo '<h2 class="title">Premium</h2>' . "\n";
echo '<p class="description">Go Premium and remove all plugin restrictions.</p>' . "\n";
echo '<br />';

 if (empty($wpmemory_checkversion)) {
    
echo '<a href="http://siterightaway.net/wp-memory-premium-plugin/">Click Here to Go Premium</a>';
echo '<br />';
echo '<br />';

?>
    Paste below the Item Purchase Code received by email from us when you bought the premium version.
    You don't need reinstall the plugin.
    <br />
    After that, click UPDATE Button.
    <br />
    <br />
    <form class="wpmemory -form" method="post" action="admin.php?page=wp_memory_admin_page&tab=premium">
        <input type="hidden" name="process" value="wp_memory_admin_page" />
        <label for="pcode">Purchase Code:</label>
        <input type="text" id="pcode" name="pcode"><br><br>
    <?php
    echo '<br />';
    echo '<br />';
    echo '<input class="wpmemory -submit button-primary" type="submit" value="Update" />';
    echo '</form>' . "\n";

} else
    echo '<h2> Premium Version activated!</h2>';
//echo $wpmemory_checkversion;
//wpmemory_update();

echo '<div class="main-notice">';
echo '</div>' . "\n";
echo '</div>';
function stripNonAlphaNumeric($string)
{
    return preg_replace("/[^a-z0-9]/i", "", $string);
}