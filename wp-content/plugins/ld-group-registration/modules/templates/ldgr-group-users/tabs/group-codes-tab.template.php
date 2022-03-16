<?php
/**
 * Group Codes Tab contents display template
 *
 * @since      4.1.0
 * @package    Ld_Group_Registration
 * @subpackage Ld_Group_Registration/modules/templates/ldgr-group-users/tabs
 * @author     WisdmLabs <support@wisdmlabs.com>
 */
?>
<div id="tab-<?php echo esc_attr( $content['id'] ); ?>" class="tab-content ldgr-group-code-tab">
	<div class="ldgr-group-code-content">
		<div class="ldgr-group-code-items">

			<span class="ldgr-cngc-btn ldgr-btn"><?php echo sprintf( esc_html__( 'Create New %s Code', 'wdm_ld_group' ), \LearnDash_Custom_Label::get_label( 'group' ) ); ?></span>

			<?php if ( ! empty( $content['data']['group_codes_data'] ) ) : ?>
				<?php foreach ( $content['data']['group_codes_data'] as $group_code ) : ?>
					<?php
					ldgr_get_template(
						WDM_LDGR_PLUGIN_DIR . '/modules/templates/group-code-screens/ldgr-view-group-code-single.template.php',
						array(
							'group_code' => $group_code,
						)
					);
					?>
				<?php endforeach; ?>
			<?php else : ?>
				<span class="ldgr-no-group-codes">
					<?php esc_html_e( 'No group codes added yet', 'wdm_ld_group' ); ?>
				</span>
			<?php endif; ?>

		</div>
	</div>

	<?php
	// Add create group code template.
	ldgr_get_template(
		WDM_LDGR_PLUGIN_DIR . '/modules/templates/group-code-screens/ldgr-create-group-code-screen.template.php',
		array(
			'group_id'     => $content['data']['group_id'],
			'is_unlimited' => $content['data']['is_unlimited'],
		)
	);
	?>

	<?php
	// Add edit group code template.
	ldgr_get_template(
		WDM_LDGR_PLUGIN_DIR . '/modules/templates/group-code-screens/ldgr-edit-group-code-screen.template.php',
		array(
			'group_id'     => $content['data']['group_id'],
			'is_unlimited' => $content['data']['is_unlimited'],
		)
	);
	?>

	<div class="ldgr-black-screen">
		<span style="margin-bottom:10px;"><?php esc_html_e( 'Loading...', 'wdm_ld_group' ); ?></span>
		<span class="dashicons dashicons-update spin"></span>
	</div>
</div>
