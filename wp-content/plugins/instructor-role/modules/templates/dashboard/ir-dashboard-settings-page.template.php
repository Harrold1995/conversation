<?php
/**
 * Instructor Dashboard Settings Page Template
 *
 * @since 4.0
 *
 * @var string   $ir_accent_primary_color
 * @var string   $ir_accent_secondary_color
 * @var string   $ir_dashboard_logo
 * @var string   $ir_dashboard_header
 * @var string   $ir_dashboard_image_background_color
 * @var string   $ir_dashboard_text_title
 * @var string   $ir_dashboard_text_sub_title
 * @var string   $ir_dashboard_title_font_family
 * @var string   $ir_dashboard_title_font_color
 * @var string   $ir_dashboard_sub_title_font_family
 * @var string   $ir_dashboard_sub_title_font_color
 * @var string   $ir_dashboard_text_background_color
 * @var array    $google_fonts
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

?>
<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<form class="ir-dashboard-settings-form" action="themes.php?page=ir-dashboard-settings" method="post">
		<table>
			<tbody>
				<tr>
					<th>
						<?php esc_html_e( 'Accent Color', 'wdm_instructor_role' ); ?>
					</th>
					<td>
						<input class="ir-color-picker" type="text" name="ir_accent_primary_color" id="ir_accent_primary_color" value="<?php echo esc_attr( $ir_accent_primary_color ); ?>" data-default-color="#4553e6"/>
					</td>
				</tr>
				<tr>
					<th>
						<h2><?php esc_html_e( 'Header Settings', 'wdm_instructor_role' ); ?></h2>
					</th>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Type', 'wdm_instructor_role' ); ?></th>
					<td>
						<select name="ir_dashboard_header" id="ir_dashboard_header">
							<option value="-1" <?php selected( '-1', $ir_dashboard_header ); ?>><?php esc_html_e( 'None', 'wdm_instructor_role' ); ?></option>
							<option value="image" <?php selected( 'image', $ir_dashboard_header ); ?>><?php esc_html_e( 'Image', 'wdm_instructor_role' ); ?></option>
							<option value="text" <?php selected( 'text', $ir_dashboard_header ); ?>><?php esc_html_e( 'Text', 'wdm_instructor_role' ); ?></option>
						</select>
					</td>
				</tr>
				<tr class="ir-dashboard-image-field" <?php echo ( 'image' !== $ir_dashboard_header ) ? "style='display: none;'" : ''; ?> >
					<th><?php esc_html_e( 'Image Upload', 'wdm_instructor_role' ); ?></th>
					<td>
						<div id="ir-dashboard-image-container">
							<?php if ( $dashboard_logo = wp_get_attachment_image_src( $ir_dashboard_logo ) ) : ?>
								<button class="ir_upload_image button">
									<img
										class="ir-dashboard-logo-img"
										src="<?php echo esc_url( $dashboard_logo[0] ); ?>"
										alt="<?php esc_html_e( 'Instructor Dashboard Logo', 'wdm_instructor_role' ); ?>"
									/>
								</button>
								<button class="ir_remove_image button">
									<?php esc_html_e( 'Remove', 'wdm_instructor_role' ); ?>
								</button>
							<?php else : ?>
								<button class="ir_upload_image button">
									<?php esc_html_e( 'Upload', 'wdm_ld_group' ); ?>
								</button>
								<button class="ir_remove_image button ir-hide">
									<?php esc_html_e( 'Remove', 'wdm_ld_group' ); ?>
								</button>
							<?php endif; ?>
							<input type="hidden" name="ir_dashboard_logo" id="ir_dashboard_logo" value="<?php echo esc_attr( $ir_dashboard_logo ); ?>"/>
						</div>
						<span class="dashicons dashicons-info"></span>
						<em><?php esc_html_e( 'We recommended a square image of size 110px', 'wdm_instructor_role' ); ?></em>
					</td>
				</tr>
				<tr class="ir-dashboard-image-field" <?php echo ( 'image' !== $ir_dashboard_header ) ? "style='display: none;'" : ''; ?> >
					<th><?php esc_html_e( 'Background Color', 'wdm_instructor_role' ); ?></th>
					<td>
						<input class="ir-color-picker" type="text" name="ir_dashboard_image_background_color" id="ir_dashboard_image_background_color" value="<?php echo esc_attr( $ir_dashboard_image_background_color ); ?>"/>
					</td>
				</tr>
				<tr class="ir-dashboard-text-field" <?php echo ( 'text' !== $ir_dashboard_header ) ? "style='display: none;'" : ''; ?> >
					<th><?php esc_html_e( 'Title', 'wdm_instructor_role' ); ?></th>
					<td>
						<input type="text" name="ir_dashboard_text_title" id="ir_dashboard_text_title" value="<?php echo esc_attr( $ir_dashboard_text_title ); ?>" />
					</td>
				</tr>
				<tr class="ir-dashboard-text-field" <?php echo ( 'text' !== $ir_dashboard_header ) ? "style='display: none;'" : ''; ?> >
					<th><?php esc_html_e( 'Sub Title', 'wdm_instructor_role' ); ?></th>
					<td>
						<input type="text" name="ir_dashboard_text_sub_title" id="ir_dashboard_text_sub_title" value="<?php echo esc_attr( $ir_dashboard_text_sub_title ); ?>"/>
					</td>
				</tr>
				<tr class="ir-dashboard-text-field" <?php echo ( 'text' !== $ir_dashboard_header ) ? "style='display: none;'" : ''; ?> >
					<th><h3><?php esc_html_e( 'Title Font Settings', 'wdm_instructor_role' ); ?></h3></th>
				</tr>
				<tr class="ir-dashboard-text-field" <?php echo ( 'text' !== $ir_dashboard_header ) ? "style='display: none;'" : ''; ?> >
					<!-- <th><?php esc_html_e( 'Font Family', 'wdm_instructor_role' ); ?></th>
					<td>
						<select name="ir_dashboard_title_font_family" id="ir_dashboard_title_font_family">
						<?php foreach ( $google_fonts as $key => $value ) : ?>
							<option value='<?php echo esc_attr( $value ); ?>' <?php selected( $ir_dashboard_title_font_family, $value ); ?>><?php echo esc_attr( $value ); ?></option>
						<?php endforeach; ?>
						</select>
					</td> -->
				</tr>
				<tr class="ir-dashboard-text-field" <?php echo ( 'text' !== $ir_dashboard_header ) ? "style='display: none;'" : ''; ?> >
					<th><?php esc_html_e( 'Font Color', 'wdm_instructor_role' ); ?></th>
					<td>
						<input class="ir-color-picker" type="text" name="ir_dashboard_title_font_color" id="ir_dashboard_title_font_color" value="<?php echo esc_attr( $ir_dashboard_title_font_color ); ?>"/>
					</td>
				</tr>
				<tr class="ir-dashboard-text-field" <?php echo ( 'text' !== $ir_dashboard_header ) ? "style='display: none;'" : ''; ?> >
					<th><h3><?php esc_html_e( 'Sub Title Font Settings', 'wdm_instructor_role' ); ?></h3></th>
				</tr>
				<tr class="ir-dashboard-text-field" <?php echo ( 'text' !== $ir_dashboard_header ) ? "style='display: none;'" : ''; ?> >
					<!-- <th><?php esc_html_e( 'Font Family', 'wdm_instructor_role' ); ?></th>
					<td>
						<select name="ir_dashboard_sub_title_font_family" id="ir_dashboard_sub_title_font_family">
							<?php foreach ( $google_fonts as $key => $value ) : ?>
								<option value='<?php echo esc_attr( $value ); ?>' <?php selected( $ir_dashboard_sub_title_font_family, $value ); ?>><?php echo esc_attr( $value ); ?></option>
							<?php endforeach; ?>
						</select>
					</td> -->
				</tr>
				<tr class="ir-dashboard-text-field" <?php echo ( 'text' !== $ir_dashboard_header ) ? "style='display: none;'" : ''; ?> >
					<th><?php esc_html_e( 'Font Color', 'wdm_instructor_role' ); ?></th>
					<td>
						<input class="ir-color-picker" type="text" name="ir_dashboard_sub_title_font_color" id="ir_dashboard_sub_title_font_color" value="<?php echo esc_attr( $ir_dashboard_sub_title_font_color ); ?>"/>
					</td>
				</tr>
				<tr class="ir-dashboard-text-field" <?php echo ( 'text' !== $ir_dashboard_header ) ? "style='display: none;'" : ''; ?> >
					<th><?php esc_html_e( 'Background Color', 'wdm_instructor_role' ); ?></th>
					<td>
						<input class="ir-color-picker" type="text" name="ir_dashboard_text_background_color" id="ir_dashboard_text_background_color" value="<?php echo esc_attr( $ir_dashboard_text_background_color ); ?>" data-default-color="#00ACD3" />
					</td>
				</tr>
			</tbody>
		</table>
		<?php wp_nonce_field( 'ir_dashboard_settings', 'ir_dashboard_nonce' ); ?>
		<?php submit_button( __( 'Save Settings', 'wdm_instructor_role' ) ); ?>
	</form>
</div>
