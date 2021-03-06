<?php
/**
 * Add admin Social Groups settings page in Dashboard->BuddyBoss->Settings
 *
 * @package BuddyBoss\Core
 *
 * @since BuddyBoss 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Main Social Groups Settings class.
 *
 * @since BuddyBoss 1.0.0
 */

class BP_Admin_Setting_Groups extends BP_Admin_Setting_tab {

	/**
	 * Initialize class.
	 */
	public function initialize() {
		$this->tab_label = __( 'Groups', 'buddyboss' );
		$this->tab_name  = 'bp-groups';
		$this->tab_order = 20;

		$this->active_tab = bp_core_get_admin_active_tab();

		// Group Avatar.
		add_filter( 'bp_attachment_avatar_script_data', 'bb_admin_setting_profile_group_add_script_data', 10, 2 );

		// Group Cover.
		add_filter( 'bp_attachments_cover_image_upload_dir', 'bb_default_custom_profile_group_cover_image_upload_dir', 10, 1 );
		add_filter( 'bb_attachments_get_attachment_dir', 'bb_attachments_get_profile_group_attachment_dir', 10, 4 );
		add_filter( 'bb_attachments_get_attachment_sub_dir', 'bb_attachments_get_profile_group_attachment_sub_dir', 10, 4 );
	}

	/**
	 * Save options.
	 */
	public function settings_save() {
		$group_avatar_type_before_saving = bb_get_default_group_avatar_type();
		$group_cover_type_before_saving  = bb_get_default_group_cover_type();

		parent::settings_save();

		$group_avatar_type_after_saving = bb_get_default_group_avatar_type();
		$group_cover_type_after_saving  = bb_get_default_group_cover_type();
		$bb_default_custom_group_avatar = filter_input( INPUT_POST, 'bp-default-custom-group-avatar', FILTER_SANITIZE_STRING );
		$bb_default_custom_group_cover  = filter_input( INPUT_POST, 'bp-default-custom-group-cover', FILTER_SANITIZE_STRING );

		/**
		 * Validate custom option for group avatar and cover.
		 *
		 * @since BuddyBoss 1.8.6
		 */
		if ( ! isset( $bb_default_custom_group_avatar ) || ( isset( $bb_default_custom_group_avatar ) && empty( $bb_default_custom_group_avatar ) && 'custom' === $group_avatar_type_after_saving ) ) {

			if ( 'custom' === $group_avatar_type_before_saving ) {
				$group_avatar_type_before_saving = 'buddyboss';
			}

			bp_update_option( 'bp-default-group-avatar-type', $group_avatar_type_before_saving );
		}

		if ( ! isset( $bb_default_custom_group_cover ) || ( isset( $bb_default_custom_group_cover ) && empty( $bb_default_custom_group_cover ) && 'custom' === $group_cover_type_after_saving ) ) {

			if ( 'custom' === $group_cover_type_before_saving ) {
				$group_cover_type_before_saving = 'buddyboss';
			}

			bp_update_option( 'bp-default-group-cover-type', $group_cover_type_before_saving );
		}
	}

	/**
	 * Check if groups are enabled.
	 *
	 * @return bool
	 */
	public function is_active() {
		return bp_is_active( 'groups' );
	}

	/**
	 * Register setting fields.
	 */
	public function register_fields() {
		// Group Avatar.
		$is_disabled_avatar  = bp_disable_group_avatar_uploads();
		$default_avatar_type = bb_get_default_group_avatar_type();

		// Group Cover.
		$is_disabled_cover  = bp_disable_group_cover_image_uploads();
		$default_cover_type = bb_get_default_group_cover_type();

		// Group Settings.
		$this->add_section( 'bp_groups', esc_html__( 'Group Settings', 'buddyboss' ), '', 'bp_group_setting_tutorial' );

		// Allow subscriptions setting.
		$this->add_field( 'bp_restrict_group_creation', esc_html__( 'Group Creation', 'buddyboss' ), 'bp_admin_setting_callback_group_creation', 'intval' );

		// Allow Group Message.
		if ( bp_is_active( 'groups' ) && bp_is_active( 'messages' ) ) {
			$this->add_field( 'bp-disable-group-messages', esc_html__( 'Group Messages', 'buddyboss' ), 'bp_admin_setting_callback_group_messages', 'intval' );
		}

		// Group avatar and cover.
		$this->add_section( 'bp_groups_avatar_settings', esc_html__( 'Group Images', 'buddyboss' ), '', 'bp_group_avatar_tutorial' );

		// Allow group avatars.
		$this->add_field( 'bp-disable-group-avatar-uploads', esc_html__( 'Group Avatars', 'buddyboss' ), 'bp_admin_setting_callback_group_avatar_uploads', 'intval' );

		$args          = array();
		$args['class'] = 'group-avatar-options avatar-options default-group-avatar-type' . ( $is_disabled_avatar ? ' bp-hide' : '' );
		$this->add_field( 'bp-default-group-avatar-type', esc_html__( 'Default Group Avatar', 'buddyboss' ), 'bp_admin_setting_callback_default_group_avatar_type', 'string', $args );

		$args          = array();
		$args['class'] = 'group-avatar-options avatar-options default-group-avatar-custom' . ( ! $is_disabled_avatar && 'custom' === $default_avatar_type ? '' : ' bp-hide' );
		$this->add_field( 'bp-default-custom-group-avatar', esc_html__( 'Upload Custom Avatar', 'buddyboss' ), 'bp_admin_setting_callback_default_group_custom_avatar', 'string', $args );

		// Allow group cover photos.
		if ( bp_is_active( 'groups', 'cover_image' ) ) {
			$this->add_field( 'bp-disable-group-cover-image-uploads', esc_html__( 'Group Cover Images', 'buddyboss' ), 'bp_admin_setting_callback_group_cover_image_uploads', 'intval' );

			$args          = array();
			$args['class'] = 'group-cover-options avatar-options default-group-cover-type' . ( $is_disabled_cover ? ' bp-hide' : '' );
			$this->add_field( 'bp-default-group-cover-type', esc_html__( 'Default Group Cover Image', 'buddyboss' ), 'bp_admin_setting_callback_default_group_cover_type', 'string', $args );

			$args          = array();
			$args['class'] = 'group-cover-options avatar-options default-group-cover-custom' . ( ! $is_disabled_cover && 'custom' === $default_cover_type ? '' : ' bp-hide' );
			$this->add_field( 'bp-default-custom-group-cover', esc_html__( 'Upload Custom Cover Image', 'buddyboss' ), 'bp_admin_setting_callback_default_group_custom_cover', 'string', $args );

			$args          = array();
			$args['class'] = 'group-cover-options preview-avatar-cover-image' . ( $is_disabled_cover ? ' bp-hide' : '' );
			$this->add_field( 'bp-preview-group-avatar-cover', esc_html__( 'Preview Cover Image', 'buddyboss' ), 'bp_admin_setting_callback_preview_group_avatar_cover', 'string', $args );
		}

		// Register Group Types sections.
		$this->add_section( 'bp_groups_types', __( 'Group Types', 'buddyboss' ), '', 'bp_group_types_tutorial' );

		// enable or disable group types.
		$this->add_field( 'bp-disable-group-type-creation', __( 'Group Types', 'buddyboss' ), 'bp_admin_setting_callback_group_type_creation', 'intval' );

		// enable or disable group automatically approve memberships.
		$this->add_field( 'bp-enable-group-auto-join', __( 'Auto Membership Approval', 'buddyboss' ), 'bp_admin_setting_callback_group_auto_join', 'intval' );

		// Register Group Hierarchies sections.
		$this->add_section( 'bp_groups_hierarchies', __( 'Group Hierarchies', 'buddyboss' ), '', 'bp_group_hierarchies_tutorial' );

		// enable or disable group hierarchies.
		$type          = array();
		$type['class'] = 'bp-enable-group-hierarchies';
		$this->add_field( 'bp-enable-group-hierarchies', __( 'Hierarchies', 'buddyboss' ), 'bp_admin_setting_callback_group_hierarchies', 'intval', $type );

		// Hide subgroups from the main Groups Directory.
		$type          = array();
		$type['class'] = 'bp-enable-group-hide-subgroups';
		$this->add_field( 'bp-enable-group-hide-subgroups', __( 'Hide Subgroups', 'buddyboss' ), 'bp_admin_setting_callback_group_hide_subgroups', 'intval', $type );

		// enable or disable restrict invites to members who already in specific parent group.
		$type          = array();
		$type['class'] = 'bp-enable-group-restrict-invites';
		$this->add_field( 'bp-enable-group-restrict-invites', __( 'Restrict Invitations', 'buddyboss' ), 'bp_admin_setting_callback_group_restrict_invites', 'intval', $type );

		// Section for group list.
		$this->add_section( 'bp_group_list_settings', __( 'Group Directories', 'buddyboss' ), '', 'bp_group_directories_tutorial' );

		// Admin Settings for Settings > Groups > Group Directories > Enabled Views.
		$this->add_field(
			'bp-group-layout-format',
			__( 'Enabled View(s)', 'buddyboss' ),
			'bp_admin_setting_callback_group_layout_type_format'
		);

		// Admin Settings for Settings > Groups > Group Directories > Default View.
		$args          = array();
		$args['class'] = 'group-default-layout group-layout-options';
		$this->add_field( 'bp-group-layout-default-format', __( 'Default View', 'buddyboss' ), 'bp_admin_setting_group_layout_default_option', 'radio', $args );

		/**
		 * Fires to register Groups tab settings fields and section.
		 *
		 * @since BuddyBoss 1.2.6
		 *
		 * @param Object $this BP_Admin_Setting_Groups.
		 */
		do_action( 'bp_admin_setting_groups_register_fields', $this );
	}

	/**
	 * Setup default custom avatar upload directory.
	 *
	 * @since BuddyBoss 1.8.6
	 *
	 * @param array $upload_dir The original Uploads dir.
	 * @return array Array containing the path, URL, and other helpful settings.
	 */
	public function bb_group_default_custom_group_avatar_upload_dir( $upload_dir = array() ) {
		$bp_params = array();

		if ( isset( $_POST['bp_params'] ) && ! empty( $_POST['bp_params'] ) ) {
			$bp_params = array_map( 'sanitize_text_field', $_POST['bp_params'] );
		}

		if ( ! is_admin() || empty( $bp_params ) || ! isset( $bp_params['object'] ) || ! isset( $bp_params['item_id'] ) ) {
			return $upload_dir;
		}

		$item_id = $bp_params['item_id'];
		$object  = $bp_params['object'];

		if ( ! is_admin() || ( 0 < $item_id && 'group' === $object ) || ( 'group' !== $object ) ) {
			return $upload_dir;
		}

		$directory = 'group-avatars';

		$path      = bp_core_avatar_upload_path() . '/' . $directory . '/custom';
		$newbdir   = $path;
		$newurl    = bp_core_avatar_url() . '/' . $directory . '/custom';
		$newburl   = $newurl;
		$newsubdir = '/' . $directory . '/custom';

		/**
		 * Filters default custom avatar upload directory.
		 *
		 * @since BuddyBoss 1.8.6
		 *
		 * @param array $value Array containing the path, URL, and other helpful settings.
		 */
		return apply_filters(
			'bb_group_default_custom_group_avatar_upload_dir',
			array(
				'path'    => $path,
				'url'     => $newurl,
				'subdir'  => $newsubdir,
				'basedir' => $newbdir,
				'baseurl' => $newburl,
				'error'   => false,
			),
			$upload_dir
		);
	}
}

return new BP_Admin_Setting_Groups();
