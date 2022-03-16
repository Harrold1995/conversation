<?php
/**
 * LDGR Group Users add single user row template.
 *
 * @since      4.2.0
 * @package    Ld_Group_Registration
 * @subpackage Ld_Group_Registration/modules/templates/ldgr-group-users
 * @author     WisdmLabs <support@wisdmlabs.com>
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

?>
<div class="ldgr-add-user">
	<div class="ldgr-field">
		<label><?php esc_html_e( 'first name', 'wdm_ld_group' ); ?></label>
		<input type="text" class="ldgr-textbox" name='wdm_members_fname[]' />
		<span class="ldgr-field-error"></span>
	</div>
	<div class="ldgr-field">
		<label><?php esc_html_e( 'last name', 'wdm_ld_group' ); ?></label>
		<input type="text" class="ldgr-textbox" name='wdm_members_lname[]' />
		<span class="ldgr-field-error"></span>
	</div>
	<div class="ldgr-field">
		<label><?php esc_html_e( 'email', 'wdm_ld_group' ); ?></label>
		<input type="text" class="ldgr-textbox" name='wdm_members_email[]' />
		<span class="ldgr-field-error"></span>
	</div>
	<div class="ldgr-field remove-user">
		<i class="ldgr-icon-Trash"></i>
		<span class="ldgr-rm-usr"><?php esc_html_e( 'Remove User', 'wdm_ld_group' ); ?></span>
	</div>
</div>
