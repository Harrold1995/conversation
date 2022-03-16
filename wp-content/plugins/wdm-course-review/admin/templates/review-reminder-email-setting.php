<?php
/**
 * Loading email template of course review reminder email setting.
 *
 * @package Reviews.
 */

wp_enqueue_script( 'jquery-ui-datepicker', array( 'jquery' ), WDM_LD_COURSE_VERSION );
wp_enqueue_style( 'rrf-jquery-ui-css', plugins_url( 'admin/css/jquery-ui.css', RRF_PLUGIN_FILE ), array(), WDM_LD_COURSE_VERSION );

$enable_reminder    = get_option( 'rrf_enable_review_reminder_email' );
$reminder_type      = get_option( 'rrf_review_reminder_type' );
$periodic_frequency = get_option( 'rrf_periodic_frequency' );
$day_of_week        = get_option( 'rrf_day_of_week' );
$day_of_month       = get_option( 'rrf_day_of_month', 1 );
$days_after_cc      = get_option( 'rrf_days_after_cc', 1 );
$no_of_reminders    = get_option( 'rrf_no_of_reminders' );

$email_subject = get_option( 'rrf_review_reminder_email_subject', RRF_DEFAULT_REVIEW_PROMPT_SUBJECT );
$email_body    = get_option( 'rrf_review_reminder_email_body', RRF_DEFAULT_REVIEW_PROMPT_BODY );
$email_subject = stripslashes( $email_subject );
?>
<style type="text/css">
	.form-table td .description {
	font-size: 12px;
	font-style: italic;
}
</style>
<script>
jQuery(document).ready(function($){
	$('#rrf_day_of_month').datepicker({dateFormat: 'dd'});

	$('#rrf_review_reminder_type').change(function(){
		if($(this).val() == 'once') {
			$('#rrf_days_after_cc').parents('tr').removeAttr('style');
			$('#rrf_periodic_frequency').parents('tr').css('display','none');
			$('#rrf_day_of_week').parents('tr').css('display','none');
			$('#rrf_day_of_month').parents('tr').css('display','none');
			$('#rrf_no_of_reminders').parents('tr').css('display','none');
		}
		if($(this).val() == 'periodic') {
			$('#rrf_periodic_frequency').parents('tr').removeAttr('style');
			$('#rrf_days_after_cc').parents('tr').css('display','none');
			$('#rrf_periodic_frequency').change();
		}
		if(($(this).val() == '')) {
			$('#rrf_periodic_frequency').parents('tr').css('display','none');
			$('#rrf_days_after_cc').parents('tr').css('display','none');
			$('#rrf_day_of_week').parents('tr').css('display','none');
			$('#rrf_day_of_month').parents('tr').css('display','none');
			$('#rrf_no_of_reminders').parents('tr').css('display','none');
		}
	});

	$('#rrf_periodic_frequency').change(function(){
		if($(this).val() == 'daily')
		{
			$('#rrf_day_of_week').parents('tr').css('display','none');
			$('#rrf_day_of_month').parents('tr').css('display','none');
			$('#rrf_no_of_reminders').parents('tr').removeAttr('style');
		}
		if($(this).val() == 'weekly')
		{
			$('#rrf_day_of_week').parents('tr').removeAttr('style');
			$('#rrf_day_of_month').parents('tr').css('display','none');
			$('#rrf_no_of_reminders').parents('tr').removeAttr('style');
		}
		if($(this).val() == 'monthly')
		{
			$('#rrf_day_of_month').parents('tr').removeAttr('style');
			$('#rrf_day_of_week').parents('tr').css('display','none');
			$('#rrf_no_of_reminders').parents('tr').removeAttr('style');
		}
		if(($(this).val() == ''))
		{
			$('#rrf_day_of_week').parents('tr').css('display','none');
			$('#rrf_day_of_month').parents('tr').css('display','none');
			$('#rrf_no_of_reminders').parents('tr').css('display','none');
		}
	});

	$('#rrf_periodic_frequency').change();
	$('#rrf_review_reminder_type').change();

	});
</script>

<style>
.ui-datepicker-header, table.ui-datepicker-calendar > thead tr
{
	display:none
}
</style>

<div>
<form method="post">

<table class="form-table">
	<tbody>

	<tr>
	<th scope="row"><label for="rrf_enable_review_reminder_email"><?php esc_html_e( 'Enable Review Reminder emails', 'wdm_ld_course_review' ); ?></label></th>
	<td>
	<input type="checkbox" name="rrf_enable_review_reminder_email" id="rrf_enable_review_reminder_email" aria-describedby="wdm-review-setting" value="1" 
	<?php
	checked( $enable_reminder );
	?>
	/>		 
	<p class="description" id="wdm-review-prompt"><?php esc_html_e( 'Send Review Reminder email to students when they are eligible to submit the review', 'wdm_ld_course_review' ); ?></p>
	</td>
	</tr>

	<tr> 
	<th scope="row"><label for="rrf_review_reminder_type"><?php esc_html_e( 'Review Reminder email type', 'wdm_ld_course_review' ); ?></label></th>
	<td>
	<select name="rrf_review_reminder_type" id="rrf_review_reminder_type" aria-describedby="wdm-review-setting">
	<option value = ''><?php esc_html_e( 'Select Reminder Type', 'wdm_ld_course_review' ); ?></option>
	<option value="once" <?php selected( $reminder_type, 'once' ); ?>>Once</option>
	<option value="periodic" <?php selected( $reminder_type, 'periodic' ); ?>>Periodic</option>
	</select>
	</td>
	</tr>

	<tr style = "display:none">
	<th scope="row"><label for="rrf_days_after_cc"><?php esc_html_e( 'How many days post course completion', 'wdm_ld_course_review' ); ?></label></th>
	<td>
	<input type = 'text' name="rrf_days_after_cc" id="rrf_days_after_cc" aria-describedby="wdm-review-setting" value="<?php echo esc_html( $days_after_cc ); ?>" class="regular-text ltr">
	<p class="description" id="wdm-days-after-cc"><?php esc_html_e( 'Email will be sent x days after course completion.', 'wdm_ld_course_review' ); ?></p>
	</td>
	</tr>

	<tr style = "display:none">
	<th scope="row"><label for="rrf_periodic_frequency"><?php esc_html_e( 'Periodic Frequency', 'wdm_ld_course_review' ); ?></label></th>
	<td>
	<select name="rrf_periodic_frequency" id="rrf_periodic_frequency" aria-describedby="wdm-review-setting">
	<option value = ''><?php esc_html_e( 'Select Email Frequency', 'wdm_ld_course_review' ); ?></option>
	<option value="daily" <?php selected( $periodic_frequency, 'daily' ); ?>>Daily</option>
	<option value="weekly" <?php selected( $periodic_frequency, 'weekly' ); ?>>Weekly</option>
	<option value="monthly" <?php selected( $periodic_frequency, 'monthly' ); ?>>Monthly</option>
	</select>
	</td>
	</tr>

	<tr style = "display:none"> 
	<th scope="row"><label for="rrf_day_of_week"><?php esc_html_e( 'Day of week', 'wdm_ld_course_review' ); ?></label></th>
	<td>
	<select name="rrf_day_of_week" id="rrf_day_of_week" aria-describedby="wdm-review-setting">
	<option><?php esc_html_e( 'Select Day of week', 'wdm_ld_course_review' ); ?></option>
	<option value="0" <?php selected( $day_of_week, '0' ); ?>>Sunday</option>
	<option value="1" <?php selected( $day_of_week, '1' ); ?>>Monday</option>
	<option value="2" <?php selected( $day_of_week, '2' ); ?>>Tuesday</option>
	<option value="3" <?php selected( $day_of_week, '3' ); ?>>Wednesday</option>
	<option value="4" <?php selected( $day_of_week, '4' ); ?>>Thursday</option>
	<option value="5" <?php selected( $day_of_week, '5' ); ?>>Friday</option>
	<option value="6" <?php selected( $day_of_week, '6' ); ?>>Saturday</option>
	</select>
	</td>
	</tr>

	<tr style = "display:none"> 
	<th scope="row"><label for="rrf_day_of_month"><?php esc_html_e( 'Select date of month.', 'wdm_ld_course_review' ); ?></label></th>
	<td>
	<input type = 'text' name="rrf_day_of_month" id="rrf_day_of_month" aria-describedby="wdm-review-setting" value="<?php echo esc_html( $day_of_month ); ?>" class="regular-text ltr">
	<p class="description" id="wdm-day-of-month"><?php esc_html_e( 'Email will be sent on which date of the month.', 'wdm_ld_course_review' ); ?></p>
	</td>
	</tr>

	<tr style = "display:none"> 
	<th scope="row"><label for="rrf_no_of_reminders"><?php esc_html_e( 'Number of reminder emails to be sent', 'wdm_ld_course_review' ); ?></label></th>
	<td>
	<input type = 'text' name="rrf_no_of_reminders" id="rrf_no_of_reminders" aria-describedby="wdm-review-setting" value="<?php echo esc_html( $no_of_reminders ); ?>" class="regular-text ltr">
	<p class="description" id="wdm-day-of-month"><?php esc_html_e( 'x reminder emails will be sent. 0 for no limit.', 'wdm_ld_course_review' ); ?></p>
	</td>
	</tr>

	<tr>
	<th scope="row"><label for="rrf_review_reminder_email_subject"><?php esc_html_e( 'Subject', 'wdm_ld_course_review' ); ?></label></th>
	<td>
	<input name="rrf_review_reminder_email_subject" type="text" id="rrf_review_reminder_email_subject" aria-describedby="review-email-subject" value="<?php echo esc_html( $email_subject ); ?>" class="regular-text ltr">
	<p class="description" id="review-email-subject"><?php esc_html_e( 'This subject will be used while sending email to the author.', 'wdm_ld_course_review' ); ?></p>
	</td>
	</tr>

	<tr>
	<th scope="row"><label for="rrf_review_reminder_email_body"><?php esc_html_e( 'Body', 'wdm_ld_course_review' ); ?></label></th>
	<td>
	<?php

	$editor_settings = array(
		'textarea_rows' => 100,
		'editor_height' => 200,
	);
	wp_editor(
		( wp_unslash( $email_body ) ),
		'rrf_review_reminder_email_body',
		$editor_settings
	);

	?>
	</td>
	</tr>
	<tr>
	<th scope="row"><label for="wdm_shortcode"><?php esc_html_e( 'Available shortcodes', 'wdm_ld_course_review' ); ?></label></th>
	<td>
		<table>
			<tr>
				<td>
		<b><?php esc_html_e( 'User shortcodes', 'wdm_ld_course_review' ); ?></b>
		<div>
		<span>
		<ol>
			<li>[user_first_name]   : <?php esc_html_e( 'User first name', 'wdm_ld_course_review' ); ?></li>
			<li>[user_last_name]   : <?php esc_html_e( 'User last name', 'wdm_ld_course_review' ); ?></li>
			<li>[user_display_name] : <?php esc_html_e( 'User display name', 'wdm_ld_course_review' ); ?></li>
			<li>[user_email_id]     : <?php esc_html_e( 'User email ID', 'wdm_ld_course_review' ); ?></li>
			<li>[user_id]           : <?php esc_html_e( 'User ID', 'wdm_ld_course_review' ); ?></li>
		</ol>
		</span>
	</div>
				</td>
				<td>
<b><?php esc_html_e( 'Author shortcodes', 'wdm_ld_course_review' ); ?></b>
	<div>
		<span>
		<ol>
			<li>[author_first_name]   : <?php esc_html_e( 'Author first name', 'wdm_ld_course_review' ); ?></li>
			<li>[author_last_name]   : <?php esc_html_e( 'Author last name', 'wdm_ld_course_review' ); ?></li>
			<li>[author_display_name] : <?php esc_html_e( 'Author display name', 'wdm_ld_course_review' ); ?></li>
			<li>[author_email_id]     : <?php esc_html_e( 'Author email ID', 'wdm_ld_course_review' ); ?></li>
			<li>[author_id]           : <?php esc_html_e( 'Author ID', 'wdm_ld_course_review' ); ?></li>
		</ol>
		</span>
	</div>
				</td>
			</tr>

			<tr>
				<td>
<b><?php esc_html_e( 'Course shortcodes', 'wdm_ld_course_review' ); ?></b>
		<div>
		<span>
		<ol>
			<li>[course_title]   : <?php esc_html_e( 'Course title', 'wdm_ld_course_review' ); ?></li>
			<li>[course_link]   : <?php esc_html_e( 'Course link', 'wdm_ld_course_review' ); ?></li>
			<li>[course_id] : <?php esc_html_e( 'Course ID', 'wdm_ld_course_review' ); ?></li>
		</ol>
		</span>
		</div>
			</td>
			</tr>
		</table>


	</td>
	</tr>

	<?php
		do_action( 'rrf_review_reminder_email_template' );
	?>
</tbody>
</table>
<?php wp_nonce_field( 'rrf_review_reminder_email_template_action', 'rrf_review_reminder_email_template_nonce' ); ?>
<p class="submit">
<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
</p>
</form>

</div>

