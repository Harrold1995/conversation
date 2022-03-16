<?php
/**
 * Loading email template of course reviews.
 *
 * @package Reviews.
 */

$enable_prompt = get_option( 'rrf_enable_review_prompt_email' );
$email_subject = get_option( 'rrf_review_prompt_email_subject', RRF_DEFAULT_REVIEW_PROMPT_SUBJECT );
$email_body    = get_option( 'rrf_review_prompt_email_body', RRF_DEFAULT_REVIEW_PROMPT_BODY );
$email_subject = stripslashes( $email_subject );
?>
<style type="text/css">
	.form-table td .description {
	font-size: 12px;
	font-style: italic;
}
</style>
<div>


<form method="post">

<table class="form-table">
	<tbody>

	<tr>
	<th scope="row"><label for="rrf_enable_review_prompt_email"><?php esc_html_e( 'Enable Review Prompt emails', 'wdm_ld_course_review' ); ?></label></th>
	<td>
	<input type="checkbox" name="rrf_enable_review_prompt_email" id="rrf_enable_review_prompt_email" aria-describedby="wdm-review-setting" value="1" 
	<?php
	checked( $enable_prompt );
	?>
	/>		 
	<p class="description" id="wdm-review-prompt"><?php esc_html_e( 'Send Review Prompt email to students when they are eligible to submit the review', 'wdm_ld_course_review' ); ?></p>
	</td>
	</tr>

	<tr>
	<th scope="row"><label for="rrf_review_prompt_email_subject"><?php esc_html_e( 'Subject', 'wdm_ld_course_review' ); ?></label></th>
	<td>
	<input name="rrf_review_prompt_email_subject" type="text" id="rrf_review_prompt_email_subject" aria-describedby="review-email-subject" value="<?php echo esc_html( $email_subject ); ?>" class="regular-text ltr">
	<p class="description" id="review-email-subject"><?php esc_html_e( 'This subject will be used while sending email to the author.', 'wdm_ld_course_review' ); ?></p>
	</td>
	</tr>

	<tr>
	<th scope="row"><label for="rrf_review_prompt_email_body"><?php esc_html_e( 'Body', 'wdm_ld_course_review' ); ?></label></th>
	<td>
	<?php

	$editor_settings = array(
		'textarea_rows' => 100,
		'editor_height' => 200,
	);
	wp_editor(
		( wp_unslash( $email_body ) ),
		'rrf_review_prompt_email_body',
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
		do_action( 'rrf_review_prompt_email_template' );
	?>
</tbody>
</table>
<?php wp_nonce_field( 'rrf_review_prompt_email_template_action', 'rrf_review_prompt_email_template_nonce' ); ?>
<p class="submit">
<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
</p>
</form>

</div>

