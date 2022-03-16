<?php

/**
 * @ Author: Bill Minozzi
 * @ Copyright: 2020 www.BillMinozzi.com
 * @ Modified time: 2021-03-02 12:33:13
 */
if (!defined('ABSPATH')) {
    die('We\'re sorry, but you can not directly access this file.');
}
global $wpmemory_memory;
if (!function_exists('ini_set')) {
    function wpmemory_general_admin_notice1()
    {
        if (is_admin()) {
            echo '<div class="notice notice-warning is-dismissible">
				 <p>Your server doesn\'t have a PHP function ini_set.</p>
				 <p>Please, talk with your hosting company.</p>
			 </div>';
        }
    }
    add_action('admin_notices', 'wpmemory_general_admin_notice');
}
if (isset($_GET['page']) && $_GET['page'] == 'wp_memory_admin_page') {
    if (isset($_POST['process']) && $_POST['process'] == 'wp_memory_admin_page') {
        //get limit
        if (isset($_POST['wp_memory_select'])) {
            $wp_php_memory_limit = sanitize_text_field($_POST['wp_memory_select']);
            //update options
            if (is_numeric($wp_php_memory_limit))
                $wp_php_memory_limit = (string) $wp_php_memory_limit;
            if (!update_option('wpmemory_php_memory_limit', $wp_php_memory_limit))
                add_option('wpmemory_php_memory_limit', $wp_php_memory_limit);
            $wpmemory_memory['limit'] = $wp_php_memory_limit;
            wpmemory_updated_message();
        }
    }
}
//display form
echo '<div class="wrap-wpmemory ">' . "\n";
echo '<h2 class="title">PHP Memory Limit</h2>' . "\n";
echo '<p class="description">The PHP "Memory Limit" is the Total Php Server Memory and is usually defined on your php.ini file.' . "\n";
$mb = 'MB';
echo '<hr>';
echo 'Total Current PHP Memory Limit: ' . $wpmemory_memory['limit'] .
    'MB';
echo '<hr>';
echo '<br />';
echo 'The PHP memory limit needs be bigger than WordPress Memory Limit and not bigger than your Hardware Memory.';
echo '<br />';
echo '<br />';
// echo 'The Total Php Server Memory is the PHP "Memory Limit" usually defined on your php.ini file.';
echo '<a href="http://wpmemory.com/php-memory-limit/">Click Here to learn more</a>';
echo '<br />';
echo '<br />';
if ($wpmemory_memory['limit'] < 1128) {
    echo ' We can update it without touch your php.ini file. Just choose the amount below and click UPDATE.';
    echo '<form class="wpmemory -form" method="post" action="admin.php?page=wp_memory_admin_page&tab=phpmemory">' . "\n";
    echo '<input type="hidden" name="process" value="wp_memory_admin_page"/>' . "\n";
    echo '<br />' . "\n";
    $wpmeml = $wpmemory_memory['limit'];
?>
    <label for="wpmemorylimit">Update the PHP memory limit to:</label>
    <select name="wp_memory_select" id="wp_memory_select">
        <option value="64" <?php echo ($wpmeml == '64') ? ' selected="selected"' : ''; ?>>64 MB</option>
        <option value="96" <?php echo ($wpmeml == '96') ? ' selected="selected"' : ''; ?>>96 MB</option>
        <option value="128" <?php echo ($wpmeml == '128') ? ' selected="selected"' : ''; ?>>128 MB</option>
        <option value="256" <?php echo ($wpmeml == '256') ? ' selected="selected"' : ''; ?>>256 MB</option>
        <option value="512" <?php echo ($wpmeml == '512') ? ' selected="selected"' : ''; ?>>512 MB</option>
        <option value="1024" <?php echo ($wpmeml == '1024') ? ' selected="selected"' : ''; ?>>1024 MB</option>
    </select>
    <br />
<?php
    echo '<br />';
    echo '<br />';
    echo '<input class="wpmemory -submit button-primary" type="submit" value="Update" />';
    echo '</form>' . "\n";
}
echo '<div class="main-notice">';
echo '</div>' . "\n";
echo '</div>';