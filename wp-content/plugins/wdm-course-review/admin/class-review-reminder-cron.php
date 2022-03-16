<?php
/**
 * This class contains the logic to send Review Reminder mail to the concerned student using cron.
 *
 * @package Reviews
 * @version 2.0.0
 */

namespace ns_wdm_ld_course_review;

if ( ! class_exists( 'Review_Reminder_Cron' ) ) {
	/**
	 * This class is used to send Review Reminder mail to the concerned student using cron.
	 */
	class Review_Reminder_Cron {
		/**
		 * This property contains the singleton instance of the class.
		 *
		 * @var Class object
		 */
		protected static $instance = null;

		/**
		 * This property contains the concerened User's data.
		 *
		 * @var string
		 */
		protected $cron_hook = 'rrf_review_reminder_cron_scheduler_hook';


		/**
		 * This method is used to add all action/filter hooks.
		 */
		public function __construct() {
			add_action( 'learndash_course_completed', array( $this, 'save_users_eligible_for_reminder' ), 5, 1 );
			add_action( 'wdm_on_review_setting_update', array( $this, 'configure_review_reminder_cron' ), 10 );
			add_action( $this->cron_hook, array( $this, 'rrf_review_reminder' ), 10 );
			add_action( 'wdm_student_rated_course_successfully', array( $this, 'rrf_remove_user_course_from_reminder_list' ), 10, 3 );
			add_action( 'learndash_update_course_access', array( $this, 'rrf_remove_user_course_from_reminder_list_on_removal' ), 5, 4 );
		}

		/**
		 * This method is used to check if user is removed from a course.
		 * Called when user is enrolled/removed into/from a Course.
		 *
		 * @param  int   $user_id            User ID.
		 * @param  int   $course_id          Course ID.
		 * @param  array $course_access_list Course Access List.
		 * @param  bool  $remove             To Remove.
		 */
		public function rrf_remove_user_course_from_reminder_list_on_removal( $user_id, $course_id, $course_access_list, $remove ) {

			if ( $remove ) {

				$users_review_reminder_list = get_option( 'rrf_users_review_reminder_list', 'not_set' );

				if ( 'not_set' != $users_review_reminder_list ) {//phpcs:ignore

					if ( ! empty( $users_review_reminder_list[ $user_id ][ $course_id ] ) ) {
						unset( $users_review_reminder_list[ $user_id ][ $course_id ] );
					}
				}

				update_option( 'rrf_users_review_reminder_list', $users_review_reminder_list );
			}

		}

		/**
		 * Callback function for the hook 'wdm_student_rated_course_successfully'.
		 * Removes concerned course from the User's reminder course list on successful rating.
		 *
		 * @param int $course_id Course ID.
		 * @param int $rid       Review ID.
		 * @param int $user_id   User ID.
		 */
		public function rrf_remove_user_course_from_reminder_list( $course_id, $rid, $user_id ) {

			$users_review_reminder_list = get_option( 'rrf_users_review_reminder_list', 'not_set' );

			if ( 'not_set' != $users_review_reminder_list ) {//phpcs:ignore

				if ( ! empty( $users_review_reminder_list[ $user_id ][ $course_id ] ) ) {
					unset( $users_review_reminder_list[ $user_id ][ $course_id ] );
				}
			}

			update_option( 'rrf_users_review_reminder_list', $users_review_reminder_list );

		}

		/**
		 * This function is used to fetch the instance of this class.
		 *
		 * @return object returns class instance.]
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 *  This method is used to schedule the event to send reminder emails.
		 */
		public function configure_review_reminder_cron() {
			$enable_reminders = rrf_check_if_post_set( $_POST, 'rrf_enable_review_reminder_email' );//phpcs:ignore

			if ( ! empty( $enable_reminders ) ) {
				$event_exists = wp_get_schedule( $this->cron_hook );
				if ( false === $event_exists ) {
					wp_schedule_event( time() + 90, 'daily', $this->cron_hook );
				} else {
					do_action( $this->cron_hook );
				}
			} else {
				wp_clear_scheduled_hook( $this->cron_hook );
			}
		}

		/**
		 * This method is used to check if user has met the review criteria.
		 *
		 * @param array $data User's course completion data.
		 */
		public function save_users_eligible_for_reminder( $data ) {
			$user_id               = intval( $data['user']->ID );
			$course_id             = intval( $data['course']->ID );
			$course_completed_time = $data['course_completed'];
			$updated_list          = array();

			$criteria_met = rrf_can_user_post_reviews( $user_id, $course_id );
			if ( ! $criteria_met ) {
				return;
			}
			$user_rated = rrf_get_user_course_review_id( $user_id, $course_id );
			if ( ! empty( $user_rated ) ) {
				return;
			}

			$users_review_reminder_list = get_option( 'rrf_users_review_reminder_list', 'not_set' );

			if ( 'not_set' == $users_review_reminder_list ) {//phpcs:ignore
				$updated_list[ $user_id ] = array(
					$course_id => array(
						'completion_time' => $course_completed_time,
						'reminders_sent'  => 0,
					),
				);
			} else {
				$updated_list = $users_review_reminder_list;
				if ( array_key_exists( $user_id, $updated_list ) ) {
					$updated_list[ $user_id ][ $course_id ] = array(
						'completion_time' => $course_completed_time,
						'reminders_sent'  => 0,
					);
				} else {
					$updated_list[ $user_id ] = array(
						$course_id => array(
							'completion_time' => $course_completed_time,
							'reminders_sent'  => 0,
						),
					);
				}
			}

			update_option( 'rrf_users_review_reminder_list', $updated_list );
		}

		/**
		 * Function sending one shot reminder to Users yet to post a review, x days after Course Completion
		 *
		 * @param int $days_after_cc Days post course completion.
		 */
		public function rrf_one_shot_review_reminder( $days_after_cc ) {
			$users_review_reminder_list = get_option( 'rrf_users_review_reminder_list', 'not_set' );
			if ( 'not_set' == $users_review_reminder_list || empty( $users_review_reminder_list ) ) { //phpcs:ignore
				return;
			}
			$current_time = time();

			foreach ( $users_review_reminder_list as $user_id => $course_data ) {
				foreach ( $course_data as $cid => $c_data ) {
					$reminders_sent = intval( $c_data['reminders_sent'] );
					$cc_time        = $c_data['completion_time'];
					if ( 0 == $reminders_sent && round( ( $current_time - $cc_time ) / 60 / 60 / 24 ) >= $days_after_cc ) { //phpcs:ignore
						$mail_sent = $this->rrf_send_review_reminder_email( $user_id, $cid );
						if ( $mail_sent ) {
							$users_review_reminder_list[ $user_id ][ $cid ]['reminders_sent'] = 1;
						}
					}
				}
			}

			update_option( 'rrf_users_review_reminder_list', $users_review_reminder_list );
		}

		/**
		 * Function sending periodic reminder to Users yet to post a review, till max emails have been sent.
		 *
		 * @param int $max_emails Max number of mails to be sent.
		 */
		public function rrf_periodic_review_reminder( $max_emails ) {
			$users_review_reminder_list = get_option( 'rrf_users_review_reminder_list', 'not_set' );
			if ( 'not_set' == $users_review_reminder_list || empty( $users_review_reminder_list ) ) { //phpcs:ignore
				return;
			}

			foreach ( $users_review_reminder_list as $user_id => $course_data ) {
				foreach ( $course_data as $cid => $c_data ) {
					$reminders_sent = intval( $c_data['reminders_sent'] );
					$reminders_sent = apply_filters( 'rrf_periodic_review_reminders_sent', $reminders_sent, $user_id, $cid );
					if ( $reminders_sent < $max_emails || 0 == $max_emails ) { //phpcs:ignore
						$mail_sent = $this->rrf_send_review_reminder_email( $user_id, $cid );
						if ( $mail_sent ) {
							$users_review_reminder_list[ $user_id ][ $cid ]['reminders_sent'] = ++$reminders_sent;
						}
					}
				}
			}

			update_option( 'rrf_users_review_reminder_list', $users_review_reminder_list );
		}

		/**
		 * Callback function for the Cron hook, logic checking whether to run the Cron or not.
		 */
		public function rrf_review_reminder() {
			$reminder_type = get_option( 'rrf_review_reminder_type' );
			if ( empty( $reminder_type ) ) {
				return;
			}
			if ( 'once' == $reminder_type ) { //phpcs:ignore
				$days_after_cc = intval( get_option( 'rrf_days_after_cc' ), 0 );
				$this->rrf_one_shot_review_reminder( $days_after_cc );
			} elseif ( 'periodic' == $reminder_type ) { //phpcs:ignore
				$email_frequency = get_option( 'rrf_periodic_frequency' );
				if ( empty( $email_frequency ) ) {
					return;
				}
				if ( 'weekly' == $email_frequency ) { //phpcs:ignore
					$day_of_week = get_option( 'rrf_day_of_week' );
					$today       = gmdate( 'w' );
					if ( $day_of_week != $today ) { //phpcs:ignore
						return;
					}
				}
				if ( 'monthly' == $email_frequency ) { //phpcs:ignore
					$day_of_month = get_option( 'rrf_day_of_month' );
					$today        = gmdate( 'd' );
					if ( $day_of_month != $today ) { //phpcs:ignore
						return;
					}
				}
				$max_emails = intval( get_option( 'rrf_no_of_reminders' ) );
				$this->rrf_periodic_review_reminder( $max_emails );
			}
		}

		/**
		 * Sending review reminder email to the user.
		 *
		 * @param int $user_id   User ID.
		 * @param int $course_id Course ID.
		 */
		public function rrf_send_review_reminder_email( $user_id, $course_id ) {

			$user_id   = intval( $user_id );
			$course_id = intval( $course_id );

			$user_rated = rrf_get_user_course_review_id( $user_id, $course_id );

			if ( ! empty( $user_rated ) ) {
				return 0;
			}

			$course           = get_post( $course_id );
			$course_permalink = get_post_permalink( $course );
			$course_link      = '<a href="' . esc_url( $course_permalink ) . '">' . esc_html( $course->post_title ) . '</a>';
			// Author and User details.
			$author_id  = $course->post_author;
			$author_obj = get_userdata( $author_id );
			$user_info  = get_userdata( $user_id );

			$find          = array(
				// user shortcode.
				'[user_first_name]',
				'[user_last_name]',
				'[user_display_name]',
				'[user_email_id]',
				'[user_id]',
				// author shortcode.
				'[author_first_name]',
				'[author_last_name]',
				'[author_display_name]',
				'[author_email_id]',
				'[author_id]',
				// course shortcode.
				'[course_title]', // Course title.
				'[course_link]', // Course link (i.e course URL).
				'[course_id]', // Course ID.
			);
			$replace       = array(
				// user shortcode.
					$user_info->first_name, // [user_first_name].
				$user_info->last_name, // [user_last_name].
				$user_info->display_name, // [user_display_name].
				$user_info->user_email, // [user_email_id].
				$user_info->ID, // [user_id].
			// author shortcode.
				$author_obj->first_name, // [author_first_name].
				$author_obj->last_name, // [author_last_name].
				$author_obj->display_name, // [author_display_name].
				$author_obj->user_email, // [user_email_id].
				$author_obj->ID, // [author_id].
			// course shortcode.
				$course->post_title,
				$course_link,
				$course_id,
			);
			$email_subject = get_option( 'rrf_review_reminder_email_subject', RRF_DEFAULT_REVIEW_PROMPT_SUBJECT );
			$email_body    = get_option( 'rrf_review_reminder_email_body', RRF_DEFAULT_REVIEW_PROMPT_BODY );

			$email_body    = stripslashes( $email_body );
			$email_body    = str_replace( $find, $replace, $email_body );
			$email_subject = stripslashes( $email_subject );
			$email_subject = str_replace( $find, $replace, $email_subject );

			$headers[] = "From: {$author_obj->display_name} <{$author_obj->user_email}>";
			$headers[] = "Reply-To: {$author_obj->display_name} <{$author_obj->user_email}>";
			$headers[] = 'Content-Type: text/html; charset=UTF-8';

			$email_to = array( $user_info->user_email );

			$email_to = apply_filters( 'wdm_recipient_of_review_prompt_mail', $email_to, $user_id, $course, $review );

			$mail_sent = wp_mail( $email_to, $email_subject, nl2br( $email_body ), $headers );

			return $mail_sent;
		}
	}
}
Review_Reminder_Cron::get_instance();
