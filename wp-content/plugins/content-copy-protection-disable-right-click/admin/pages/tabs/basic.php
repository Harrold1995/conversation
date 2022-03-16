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
            <label class="ccpdrc-setting-label" for="ccpdrc_disable_right_click">
              <?php echo __('Disable Right Click', 'content-copy-protection-disable-right-click'); ?>
            </label>
            <span class="ccpdrc-setting-item">
              <label class="ccpdrc-switch">
                <input type="checkbox" name="ccpdrc_settings[right_click]" value="1" id="ccpdrc_disable_right_click" <?php if (isset($ccpdrc_settings['right_click']) && $ccpdrc_settings['right_click'] == 1) echo 'checked="checked"'; ?>>
                <span class="ccpdrc-slider ccpdrc-round"></span>
              </label>
            </span>
          </div>
          
          <div class="ccpdrc-m-t-7">
            <label class="ccpdrc-setting-label" for="ccpdrc_disable_right_click_message">
              <?php echo __('Right Click Disabled Message', 'content-copy-protection-disable-right-click'); ?>
            </label>
            <span class="ccpdrc-setting-item">
              <input type="text" name="ccpdrc_settings[right_click_message]" value="<?php if (isset($ccpdrc_settings['right_click_message'])) echo $ccpdrc_settings['right_click_message']; ?>" id="ccpdrc_disable_right_click_message">
              <small>
                <?php echo __('Keep empty if you don\'t want to show message on right click.', 'content-copy-protection-disable-right-click'); ?>
              </small>
            </span>
          </div>
        </div>
        <!-- / Setting Item -->

        <!-- Setting Item -->
        <div class="ccpdrc-col-12">
          <div>
            <label class="ccpdrc-setting-label" for="ccpdrc_disable_cut_copy_paste">
              <?php echo __('Disable Cut/Copy/Paste', 'content-copy-protection-disable-right-click'); ?>
            </label>
            <span class="ccpdrc-setting-item">
              <label class="ccpdrc-switch">
                <input type="checkbox" name="ccpdrc_settings[cut_copy_paste]" value="1" id="ccpdrc_disable_cut_copy_paste" <?php if (isset($ccpdrc_settings['cut_copy_paste']) && $ccpdrc_settings['cut_copy_paste'] == 1) echo 'checked="checked"'; ?>>
                <span class="ccpdrc-slider ccpdrc-round"></span>
              </label>
            </span>
          </div>
          
          <div class="ccpdrc-m-t-7">
            <label class="ccpdrc-setting-label" for="ccpdrc_disable_cut_copy_paste_message">
              <?php echo __('Cut/Copy/Paste Message', 'content-copy-protection-disable-right-click'); ?>
            </label>
            <span class="ccpdrc-setting-item">
              <input type="text" name="ccpdrc_settings[cut_copy_paste_message]" value="<?php if (isset($ccpdrc_settings['cut_copy_paste_message'])) echo $ccpdrc_settings['cut_copy_paste_message']; ?>" id="ccpdrc_disable_cut_copy_paste_message">
              <small>
                <?php echo __('Keep empty if you don\'t want to show message on cut/copy/paste.', 'content-copy-protection-disable-right-click'); ?>
              </small>
            </span>
          </div>
        </div>
        <!-- / Setting Item -->

        <!-- Setting Item -->
        <div class="ccpdrc-col-12">
          <div>
            <label class="ccpdrc-setting-label" for="ccpdrc_disable_image_drag_drop">
              <?php echo __('Disable Image Drag & Drop', 'content-copy-protection-disable-right-click'); ?>
            </label>
            <span class="ccpdrc-setting-item">
              <label class="ccpdrc-switch">
                <input type="checkbox" name="ccpdrc_settings[image_drag_drop]" value="1" id="ccpdrc_disable_image_drag_drop" <?php if (isset($ccpdrc_settings['image_drag_drop']) && $ccpdrc_settings['image_drag_drop'] == 1) echo 'checked="checked"'; ?>>
                <span class="ccpdrc-slider ccpdrc-round"></span>
              </label>
            </span>
          </div>
          
          <div class="ccpdrc-m-t-7">
            <label class="ccpdrc-setting-label" for="ccpdrc_disable_image_drag_drop_message">
              <?php echo __('Image Drag & Drop Disabled Message', 'content-copy-protection-disable-right-click'); ?>
            </label>
            <span class="ccpdrc-setting-item">
              <input type="text" name="ccpdrc_settings[image_drag_drop_message]" value="<?php if (isset($ccpdrc_settings['image_drag_drop_message'])) echo $ccpdrc_settings['image_drag_drop_message']; ?>" id="ccpdrc_disable_image_drag_drop_message">
              <small>
                <?php echo __('Keep empty if you don\'t want to show message on image drag & drop.', 'content-copy-protection-disable-right-click'); ?>
              </small>
            </span>
          </div>
        </div>
        <!-- / Setting Item -->

        <!-- Setting Item -->
        <div class="ccpdrc-col-12">
          <div>
            <label class="ccpdrc-setting-label" for="ccpdrc_disable_view_source">
              <?php echo __('Disable View Source', 'content-copy-protection-disable-right-click'); ?>
            </label>
            <span class="ccpdrc-setting-item">
              <label class="ccpdrc-switch">
                <input type="checkbox" name="ccpdrc_settings[view_source]" value="1" id="ccpdrc_disable_view_source" <?php if (isset($ccpdrc_settings['view_source']) && $ccpdrc_settings['view_source'] == 1) echo 'checked="checked"'; ?>>
                <span class="ccpdrc-slider ccpdrc-round"></span>
              </label>
            </span>
          </div>
          
          <div class="ccpdrc-m-t-7">
            <label class="ccpdrc-setting-label" for="ccpdrc_disable_view_source_message">
              <?php echo __('View Source Disabled Message', 'content-copy-protection-disable-right-click'); ?>
            </label>
            <span class="ccpdrc-setting-item">
              <input type="text" name="ccpdrc_settings[view_source_message]" value="<?php if (isset($ccpdrc_settings['view_source_message'])) echo $ccpdrc_settings['view_source_message']; ?>" id="ccpdrc_disable_view_source_message">
              <small>
                <?php echo __('Keep empty if you don\'t want to show message on view source.', 'content-copy-protection-disable-right-click'); ?>
              </small>
            </span>
          </div>
        </div>
        <!-- / Setting Item -->

      </div>
    </div>

</div>


