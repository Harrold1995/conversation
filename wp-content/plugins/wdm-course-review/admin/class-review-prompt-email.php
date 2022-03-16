<?php
/**
 * This class contains the logic to send Review Prompt mail to the concerned student.
 *
 * @package Reviews
 * @version 2.0.0
 */

namespace ns_wdm_ld_course_review;

if ( ! class_exists( 'Review_Prompt_Email' ) ) {
	/**
	 * This class is used to send Review Prompt mail to the concerned student when (S)he meets the Review criteria.
	 */
	class Review_Prompt_Email {

		/**
		 * This property contains the singleton instance of the class.
		 *
		 * @var Class object
		 */
		protected static $instance = null;

		/**
		 * This property contains the concerened User's data.
		 *
		 * @var Wp_User Object
		 */
		protected $user_info = '';

		/**
		 * This property contains id of the Course, User can submit the review on.
		 *
		 * @var int
		 */
		protected $course_id = '';

		/**
		 * This method is used to add all action/filter hooks.
		 */
		public function __construct() {
			add_action( 'learndash_course_completed', array( $this, 'check_if_review_prompt_mail_criteria_met' ), 5, 1 );
			add_action( 'learndash_lesson_completed', array( $this, 'check_if_review_prompt_mail_criteria_met' ), 5, 1 );
			add_action( 'learndash_topic_completed', array( $this, 'check_if_review_prompt_mail_criteria_met' ), 5, 1 );
			add_action( 'learndash_quiz_completed', array( $this, 'handle_review_criteria_separately_on_quiz' ), 5, 2 );
			add_action( 'learndash_update_course_access', array( $this, 'handle_review_criteria_separately_on_enrollment' ), 5, 4 );
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
		 * This method is used to check if user has met the review criteria upon enrollment.
		 * Called when user is enrolled/removed into/from a Course.
		 *
		 * @param  int   $user_id            User ID.
		 * @param  int   $course_id          Course ID.
		 * @param  array $course_access_list Course Access List.
		 * @param  bool  $remove             Is Removed.
		 */
		public function handle_review_criteria_separately_on_enrollment( $user_id, $course_id, $course_access_list, $remove ) {

			if ( ! $remove ) {
				$user_rated = rrf_get_user_course_review_id( $user_id, $course_id );
				if ( ! empty( $user_rated ) ) {
					return; // not required, just to be on the safer side when User Re-enrolls.
				}

				$criteria_met = \rrf_can_user_post_reviews( $user_id, $course_id );
				if ( $criteria_met ) {
					$this->user_info = get_user_by( 'id', $user_id );

					$this->course_id = intval( $course_id );
					$this->rrf_send_review_prompt_mail();
				}
			}
		}


		/**
		 * This method is used to check if user has met the review criteria on quiz submission.
		 * Called when user completes any Quiz.
		 *
		 * @param Array  $quizdata   Quiz Data.
		 * @param Object $user_info User Info.
		 */
		public function handle_review_criteria_separately_on_quiz( $quizdata, $user_info ) {
			$user_id     = intval( $user_info->ID );
			$course_id   = intval( $quizdata['course']->ID );
			$course_args     = array(
				'course_id'     => $course_id,
				'user_id'       => $user_id,
				'post_id'       => $course_id,
				'activity_type' => 'course',
			);
			$course_activity = learndash_get_user_activity($course_args);
			if ( ! empty( $course_activity ) ) {
				$course_comp = $course_activity->activity_id;
				$meta_key = 'rrf_review_prompt_mail_sent_for_' . $course_id . "_" . $course_comp;
			} else {
				$meta_key = 'rrf_review_prompt_mail_sent_for_' . $course_id;
			}
			$prompt_sent = get_user_meta( $user_id, $meta_key , true );
			$user_rated  = rrf_get_user_course_review_id( $user_id, $course_id );
			if ( ! empty( $prompt_sent ) || ! empty( $user_rated ) ) {
				return;
			}

			$criteria_met = \rrf_can_user_post_reviews( $user_id, $course_id );
			if ( $criteria_met ) {
				$this->user_info = $user_info;
				$this->course_id = intval( $course_id );
				$this->rrf_send_review_prompt_mail();
			}
		}

		/**
		 * This method is used to check if user has met the review criteria.
		 * Called when user completes any topic, lesson or the course.
		 *
		 * @param Array $data User and its course data.
		 */
		public function check_if_review_prompt_mail_criteria_met( $data ) {
			$user_id     = intval( $data['user']->ID );
			$course_id   = intval( $data['course']->ID );
			$course_args     = array(
				'course_id'     => $course_id,
				'user_id'       => $user_id,
				'post_id'       => $course_id,
				'activity_type' => 'course',
			);
			$course_activity = learndash_get_user_activity($course_args);
			if ( ! empty( $course_activity ) ) {
				$course_comp = $course_activity->activity_id;
				$meta_key = 'rrf_review_prompt_mail_sent_for_' . $course_id . "_" . $course_comp;
			} else {
				$meta_key = 'rrf_review_prompt_mail_sent_for_' . $course_id;
			}
			$prompt_sent = get_user_meta( $user_id, $meta_key , true );
			$user_rated  = rrf_get_user_course_review_id( $user_id, $course_id );
			if ( ! empty( $prompt_sent ) || ! empty( $user_rated ) ) {
				return;
			}

			$criteria_met = \rrf_can_user_post_reviews( $user_id, $course_id );
			if ( $criteria_met ) {
				$this->user_info = $data['user'];
				$this->course_id = intval( $course_id );
				$this->rrf_send_review_prompt_mail();
			}
		}

		/**
		 * Sending review prompt email as the criteria is just met.
		 */
		public function rrf_send_review_prompt_mail() {
			$is_email_enabled = get_option( 'rrf_enable_review_prompt_email', 'not_set' );
			if ( 'not_set' === $is_email_enabled || empty( $is_email_enabled ) ) {
				return;
			}

			$course           = get_post( $this->course_id );
			$course_permalink = get_post_permalink( $course );
			$course_link      = '<a href="' . esc_url( $course_permalink ) . '">' . esc_html( $course->post_title ) . '</a>';
			// Author and User details.
			$author_id  = $course->post_author;
			$author_obj = get_userdata( $author_id );
			$user_info  = $this->user_info;

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
				$this->course_id,
			);
			$email_subject = get_option( 'rrf_review_prompt_email_subject', RRF_DEFAULT_REVIEW_PROMPT_SUBJECT );
			$email_body    = get_option( 'rrf_review_prompt_email_body', RRF_DEFAULT_REVIEW_PROMPT_BODY );

			$email_body    = stripslashes( $email_body );
			$email_body    = str_replace( $find, $replace, $email_body );
			$email_subject = stripslashes( $email_subject );
			$email_subject = str_replace( $find, $replace, $email_subject );

			if ( current_user_can('administrator') ) {
				$headers[] = "From: {$author_obj->display_name} <{".get_option( 'admin_email' )."}>";
				$headers[] = "Reply-To: {$author_obj->display_name} <{".get_option( 'admin_email' )."}>";
			} else {
				$headers[] = "From: {$author_obj->display_name} <{$author_obj->user_email}>";
				$headers[] = "Reply-To: {$author_obj->display_name} <{$author_obj->user_email}>";
			}
			$headers[] = 'Content-Type: text/html; charset=UTF-8';

			$email_to = array( $user_info->user_email );

			$email_to = apply_filters( 'rrf_recipient_of_review_prompt_mail', $email_to, $user_id, $course, $review );

			$mail_sent = wp_mail( $email_to, $email_subject, nl2br( $email_body ), $headers );

			if ( $mail_sent ) {
				$course_args     = array(
					'course_id'     => $this->course_id,
					'user_id'       => $user_info->ID,
					'post_id'       => $this->course_id,
					'activity_type' => 'course',
				);
				$course_activity = learndash_get_user_activity($course_args);
				if ( ! empty( $course_activity ) ) {
					$course_comp = $course_activity->activity_id;
					$meta_key = 'rrf_review_prompt_mail_sent_for_' . $this->course_id . "_" . $course_comp;
				} else {
					$meta_key = 'rrf_review_prompt_mail_sent_for_' . $this->course_id;
				}
				update_user_meta( $user_info->ID, $meta_key, 'yes' ); // prompt sent once.
			}
		}
	}
}
	Review_Prompt_Email::get_instance();
