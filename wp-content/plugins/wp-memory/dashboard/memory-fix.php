<?php
/**
 * @ Author: Bill Minozzi
 * @ Copyright: 2020 www.BillMinozzi.com
 * @ Modified time: 2021-03-02 12:42:23
 */
if (!defined('ABSPATH')) {
    die('We\'re sorry, but you can not directly access this file.');
} 
global $wpmemory_memory;
global $wpmemory_checkversion;
//display form
echo '<div class="wrap-wpmemory ">' . "\n";
echo '<h2 class="title">WordPress Memory Limit</h2>' . "\n";
echo '<p class="description">WordPress Memory Limit is the maximum amount of memory that can be consumed by PHP.<br />WP_MEMORY_LIMIT option allows you to specify that in your wp-config.php file on your root folder.</p>' . "\n";
$mb = 'MB';
echo '<hr>';
echo 'Total Current WordPress Memory Limit: ' . $wpmemory_memory['wp_limit'] . $mb;
echo '<hr>';
echo '<br />';
echo '<a href="http://wpmemory.com/fix-low-memory-limit/">Click Here to Learn More</a>';
echo '<br />';
echo '<br />';
echo ' This Amount need be minor than PHP Memory Limit.';
echo '<br />';
echo ' We can update it for you. Just select below and click FIX IT NOW.';
echo ' (We will make one backup and update the file wp-config.php)';
echo '<br />';
if (empty($wpmemory_checkversion)) {
    echo '<b>Free Version max memory upgrade is 128M';
    echo '<br />';
    echo 'Go Premium and you can setup up the limit up to 1024M. Just click the premium Tab above.</b>';
}
echo '<form class="wpmemory -form" method="post" action="admin.php?page=wp_memory_admin_page&tab=wpmemory">' . "\n";
echo '<input type="hidden" name="process" value="wp_memory_admin_page"/>' . "\n";
echo '<br />' . "\n";
//echo '</form>' . "\n";
$wpmeml = $wpmemory_memory['wp_limit'];
?>
<label for="wpmemorylimit">Update the WordPress Memory Limit to:</label>
<select name="wp_memory_select" id="wp_memory_select">
    <option value="64" <?php echo ($wpmeml == '64') ? ' selected="selected"' : ''; ?>>64 MB</option>
    <option value="96" <?php echo ($wpmeml == '96') ? ' selected="selected"' : ''; ?>>96 MB</option>
    <option value="128" <?php echo ($wpmeml == '128') ? ' selected="selected"' : ''; ?>>128 MB</option>
    <?php
    if (!empty($wpmemory_checkversion)) { ?>
        <option value="256" <?php echo ($wpmeml == '256') ? ' selected="selected"' : ''; ?>>256 MB</option>
        <option value="512" <?php echo ($wpmeml == '512') ? ' selected="selected"' : ''; ?>>512 MB</option>
        <option value="1024" <?php echo ($wpmeml == '1024') ? ' selected="selected"' : ''; ?>>1024 MB</option>
    <?php } ?>
</select>
<br />
<?php
echo '<br />';
echo '<a href="#" id="themefix-wpconfig-button" class="button button-primary">Fix it Now!</a>';
echo '</form>' . "\n";
echo '<div class="main-notice">';
echo '</div>' . "\n";
echo '</div>';
// usar outra coisa?
// colocar um cookie?


$verticalmenu_urlkey = urlencode(substr(NONCE_KEY, 0, 10));
$verticalmenu_mypath = WPMEMORYURL . 'dashboard/fixconfig/fixconfig.php';
//$verticalmenu_myrestore = WPMEMORYURL . 'public/restore-config.php?key=' . $verticalmenu_urlkey;
$verticalmenu_myrestore = WPMEMORYURL . 'public/restore-config.php';
$verticalmenu_email = get_bloginfo('admin_email');
global $wpmemory_memory;
?>
<!-- ///////////// Fix Config /////////////////  -->
<div id="themefix-wpconfig" style="display: none;">
    <div class="themefix-message-wrap" style="">
        <div class="themefix-message" style="">
            If your server allow us, we can try to fix your file wp-config.php to release more memory.
            <br />
            <strong>Please, copy the url blue below to safe place before to proceed.</strong>
            <br />
            Use the url only to undo this operation if you've problem accessing your site after the update.
            <br />
            <br />
            After Copy the URL, click UPDATE to proceed or Cancel to abort.
            <br /> <br />
            <textarea rows="3" id="restore_wpconfig" name="restore_wpconfig" style="width:100%; color: blue;"><?php echo $verticalmenu_myrestore; ?></textarea>
            <textarea rows="6" id="feedback_wpconfig" name="feedback_wpconfig" style="width:100%; font-weight: bold;"></textarea>
            <br /><br />
            <img alt="aux" src="/wp-admin/images/wpspin_light-2x.gif" id="wpmemory-imagewait20" />
            <input type="hidden" id="email" name="email" value="<?php echo $verticalmenu_email; ?>" />
            <input type="hidden" id="url_config" name="url_config" value="<?php echo $verticalmenu_mypath; ?>" />
            <input type="hidden" id="verticalmenu_urlkey" name="server_memory" value="<?php echo $verticalmenu_urlkey; ?>" />
            <input type="hidden" id="server_memory" name="server_memory" value="<?php echo (int) ini_get('memory_limit') ?>" />
            <a href="#" id="button-close-wpconfig" class="button button-primary button-close-wpconfig"><?php _e("Update", "wpmemory"); ?></a>
            <a href="#" id="button-cancell-wpconfig" class="button button-primary button-cancell-wpconfig"><?php _e("Cancel", "wpmemory"); ?></a>
            <br /><br />
        </div>
    </div>
</div>
<!-- ///////////// End Fix config /////////////////  -->