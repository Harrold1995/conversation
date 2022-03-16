<?php
/**
 * Instructor Commission Settings Template
 *
 * @var array $instructors      List of all instructors data.
 * @since 3.5.5
 */

defined( 'ABSPATH' ) || exit;
?>

<br/>
<div id="reports_table_div" style="padding-right: 5px">
	<div class="CL"></div>
		<?php esc_html_e( 'Search', 'wdm_instructor_role' ); ?>
		<input id="filter" type="text">
		<select name="change-page-size" id="change-page-size">
			<option value="5">
				<?php esc_html_e( '5 per page', 'wdm_instructor_role' ); ?>
			</option>
			<option value="10">
				<?php esc_html_e( '10 per page', 'wdm_instructor_role' ); ?>
			</option>
			<option value="20">
				<?php esc_html_e( '20 per page', 'wdm_instructor_role' ); ?>
			</option>
			<option value="50">
				<?php esc_html_e( '50 per page', 'wdm_instructor_role' ); ?>
			</option>
	</select>
	<br><br>
	<!--Table shows Name, Email, etc-->
	<table class="footable" data-filter="#filter"  id="wdm_report_tbl" data-page-size="5" >
		<thead>
			<tr>
				<th data-sort-initial="descending" data-class="expand">
					<?php esc_html_e( 'Name', 'wdm_instructor_role' ); ?>
				</th>
				<th>
					<?php esc_html_e( 'User email', 'wdm_instructor_role' ); ?>
				</th>
				<th>
					<?php esc_html_e( 'Commission %', 'wdm_instructor_role' ); ?>
				</th>
				<th data-hide="phone" >
					<?php esc_html_e( 'Update', 'wdm_instructor_role' ); ?>
				</th>
			</tr>
		</thead>
		<tbody>
			<?php
			if ( ! empty( $instructors ) ) {
				foreach ( $instructors as $instructor ) {
					$commission_percent = get_user_meta( $instructor->ID, 'wdm_commission_percentage', true );
					if ( '' === $commission_percent ) {
						$commission_percent = 0;
					}
					?>
					<tr>
						<td>
						<?php echo esc_html( $instructor->display_name ); ?>
					</td>
						<td>
						<?php echo esc_html( $instructor->user_email ); ?>
					</td>
						<td>
							<input
								name="commission_input"
								size="5"
								value="<?php echo esc_attr( $commission_percent ); ?>"
								min="0"
								max="100"
								type="number"
								id="input_<?php echo esc_attr( $instructor->ID ); ?>"
							/>
						</td>
						<td>
							<a
								name="update_<?php echo esc_attr( $instructor->ID ); ?>"
								class="update_commission button button-primary"
								href="#">
								<?php esc_html_e( 'Update', 'wdm_instructor_role' ); ?>
							</a>
							<img
								class="wdm_ajax_loader"
								src="<?php echo esc_attr( plugins_url( 'media/ajax-loader.gif', __DIR__ ) ); ?>"
								style="display:none;"
							/>
						</td>
					</tr>
					<?php
				}
			} else {
				?>
				<tr>
					<td colspan="4">
						<?php esc_html_e( 'No instructor found', 'wdm_instructor_role' ); ?>
					</td>
				</tr>
				<?php
			}
			?>
		</tbody>
		<tfoot class="hide-if-no-paging">
			<tr>
				<td colspan="4" style="border-radius: 0 0 6px 6px;">
					<div class="pagination pagination-centered hide-if-no-paging"></div>
				</td>
			</tr>
		</tfoot>
	</table>
</div>
<br/>
<div id="update_commission_message"></div>
