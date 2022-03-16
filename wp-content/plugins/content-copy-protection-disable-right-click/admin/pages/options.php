<?php
if (!current_user_can('manage_options')) {
  wp_die('You do not have permissions to access this page.');
}
$ccpdrc_settings = get_option('ccpdrc_settings', array());
?>
<div class="wrap about-wrap ccpdrc-wrap">
  <h1><?php echo __(CCPDRC_PLUGIN_NAME, 'content-copy-protection-disable-right-click'); ?></h1>
  <div class="about-text"><?php echo __('Manage settings here.', 'content-copy-protection-disable-right-click'); ?></div>

  <h2 class="nav-tab-wrapper">
    <a class="nav-tab" data-tab="ccpdrc-basic" href="#ccpdrc-basic" id="ccpdrc-basic-tab">
        <?php echo __('Basic', 'content-copy-protection-disable-right-click'); ?>
    </a>
    <a class="nav-tab" data-tab="ccpdrc-advanced" href="#ccpdrc-advanced" id="ccpdrc-advanced-tab">
        <?php echo __('Advanced', 'content-copy-protection-disable-right-click'); ?>
    </a>
    <a class="nav-tab" data-tab="ccpdrc-help" href="#ccpdrc-help" id="ccpdrc-help-tab">
        <?php echo __('Help', 'content-copy-protection-disable-right-click'); ?>
    </a>
  </h2>

  <form id="ccpdrc-settings-form" method="post">
    <input type="hidden" name="action" value="ccpdrc_save_settings">
    <input type="hidden" name="security" value="<?php echo wp_create_nonce("ccpdrc-save-settings"); ?>">

    <div id="ccpdrc-basic" class="ccpdrc-tabs">
      <?php include_once('tabs/basic.php'); ?>
    </div>

    <div id="ccpdrc-advanced" class="ccpdrc-tabs">
      <?php include_once('tabs/advanced.php'); ?>
    </div>

    <div id="ccpdrc-help" class="ccpdrc-tabs">
      <?php include_once('tabs/help.php'); ?>
    </div>
  </form>

  <div class="ccpdrc-save-settings-container">
    <input type="submit" value="<?php echo __('Save Settings', 'content-copy-protection-disable-right-click'); ?>" class="button button-large button-primary ccpdrc-button" id="ccpdrc-save-settings" name="save_settings">
    <div id="ccpdrc-error-message"></div>
  </div>

</div>
