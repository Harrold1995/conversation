<?php
/**
 * Default Instructor Menu Template
 *
 * @since 3.1.0
 */
?>
<div id="ir-primary-navigation" class="menu-test-container ir-default-menu">
	<ul id="ir-primary-menu" class="menu">
		<li class="wdm-mob-menu wdm-admin-menu-show wdm-hidden"><span class="dashicons dashicons-menu-alt"></span></li>
		<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="<?php echo get_bloginfo( 'url' ); ?>"><?php echo get_bloginfo( 'name' ); ?></a></li>
		<?php if ( defined( 'WDM_LD_REPORTS_FILE' ) && get_option( 'ldrp_reporting_page' ) ) : ?>
			<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="<?php echo get_permalink( get_option( 'ldrp_reporting_page' ) ); ?>"><?php _e( 'Advanced Reports', 'instructor-role' ); ?></a></li>
		<?php endif; ?>
		<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="<?php echo wp_logout_url(); ?>"><?php _e( 'Logout', 'instructor-role' ); ?></a></li>
	</ul>
</div>
