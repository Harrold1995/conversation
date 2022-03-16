<?php
/**
 * LDGR Group Users [wdm_group_users] shortcode display template
 *
 * @since      4.0
 * @package    Ld_Group_Registration
 * @subpackage Ld_Group_Registration/modules/templates/ldgr-group-users
 * @author     WisdmLabs <support@wisdmlabs.com>
 */

?>
<!-- <form id='wdm_search_submit' method='post'> -->
	<?php if ( ! $group_selected ) : ?>
		<div class="wdm-select-wrapper">
			<?php $instance->show_group_select_wrapper( $user_id, $group_ids, $sub_group_instance ); ?>
			<?php do_action( 'wdm_after_select_product', $group_id, $group_limit ); ?>
		</div>
	<?php else : ?>
		<div class='wdm-notification-messages'>
			<?php $instance->show_notification_messages(); ?>
		</div>
		<?php if ( ! $need_to_restrict ) : ?>
			<?php $instance->show_group_registrations_tabs( $group_id, $need_to_restrict ); ?>
		<?php else : ?>
            <?php $instance->show_subscription_errors( $need_to_restrict, $subscription_id, $sub_current_status ); ?>
		<?php endif; ?>
	<?php endif; ?>
<!-- </form> -->
