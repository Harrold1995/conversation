<?php
if (!current_user_can('manage_options')) {
  wp_die('You do not have permissions to access this page.');
}
?>

<div>
    <div class="ccpdrc-col-7">
      <div class="ccpdrc-row">

        <!-- Setting Item -->
        <div class="ccpdrc-col-12">
          <div>
            <label class="ccpdrc-setting-label" for="ccpdrc_disable_content_selection">
              <?php echo __('Disable Content Selection', 'content-copy-protection-disable-right-click'); ?>
            </label>
            <span class="ccpdrc-setting-item">
              <label class="ccpdrc-switch">
                <input type="checkbox" name="ccpdrc_settings[content_selection]" value="1" id="ccpdrc_disable_content_selection" <?php if (isset($ccpdrc_settings['content_selection']) && $ccpdrc_settings['content_selection'] == 1) echo 'checked="checked"'; ?>>
                <span class="ccpdrc-slider ccpdrc-round"></span>
              </label>
            </span>
          </div>
          
          <div class="ccpdrc-m-t-7">
            <label class="ccpdrc-setting-label" for="ccpdrc_disable_content_selection_message">
              <?php echo __('Content Selection Disabled Message', 'content-copy-protection-disable-right-click'); ?>
            </label>
            <span class="ccpdrc-setting-item">
              <input type="text" name="ccpdrc_settings[content_selection_message]" value="<?php if (isset($ccpdrc_settings['content_selection_message'])) echo $ccpdrc_settings['content_selection_message']; ?>" id="ccpdrc_disable_content_selection_message">
              <small>
                <?php echo __('Keep empty if you don\'t want to show message on content selection.', 'content-copy-protection-disable-right-click'); ?>
              </small>
            </span>
          </div>
        </div>
        <!-- / Setting Item -->

      </div>
    </div>

</div>
