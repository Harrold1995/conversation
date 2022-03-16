<?php
/**
 * This file is used to include the class which create custom table Survey data WordPress.
 *
 * @package RatingsReviewsFeedback\Admin\SurveyCustomTable
 */

namespace ns_wdm_ld_course_review{

	/**
	 * This will create custom databse table called rrf_survey.
	 */
	class RRF_CUSTOM_DB_TABLE {
		/**
		 * Table Name
		 *
		 * @var string
		 */
		public $custom_table_name = 'rrf_survey';
		/**
		 * Instance of this class.
		 *
		 * @since    1.0.0
		 *
		 * @var object
		 */
		protected static $instance = null;
		/**
		 * Constructor for the class.
		 * Used to initialize all the hooks in the class.
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'rrf_create_custom_db_table' ) );
		}
		/**
		 * Returns an instance of this class.
		 *
		 * @since     1.0.0
		 *
		 * @return object A single instance of this class.
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Creating custom table.
		 */
		public function rrf_create_custom_db_table() {
			global $wpdb;
			$charset_collate = $wpdb->get_charset_collate();
			$table_name      = $wpdb->prefix . $this->custom_table_name;
			$sql             = "CREATE TABLE $table_name (
                            id BIGINT(20) NOT NULL AUTO_INCREMENT,
                            post_id BIGINT(20) NOT NULL,
                            user_id BIGINT(20) NOT NULL,
                            survey_id BIGINT(20) NOT NULL,
                            user_response TEXT NOT NULL,
                            date_time DATETIME NOT NULL,
                            PRIMARY KEY  (id)
						) $charset_collate;";
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( $sql );
		}

		/**
		 * Add data custom table.
		 *
		 * @param Array $data Data to Insert.
		 */
		public function rrf_add_data_custom_db_table( $data ) {
			global $wpdb;
			$wpdb->insert(
				$wpdb->prefix . 'rrf_survey',
				$data
			);
		}

		/**
		 * Update data custom table.
		 *
		 * @param string $user_response User Response.
		 * @param int    $post_id       Post ID.
		 * @param int    $user_id       User ID.
		 * @param int    $survey_id     Survey ID.
		 */
		public function rrf_update_data_custom_db_table( $user_response, $post_id, $user_id, $survey_id ) {
			global $wpdb;
			$wpdb->query(
				$wpdb->prepare(
					"
						UPDATE {$wpdb->prefix}rrf_survey
						SET user_response = %s
						WHERE post_id = %d
						AND user_id = %d
						AND survey_id = %d
					",
					$user_response,
					$post_id,
					$user_id,
					$survey_id
				)
			);
		}

		/**
		 * Delete data custom table.
		 *
		 * @param int $post_id   Post ID.
		 * @param int $user_id   User ID.
		 * @param int $survey_id Survey ID.
		 */
		public function rrf_delete_data_custom_db_table( $post_id, $user_id, $survey_id ) {
			global $wpdb;
			$wpdb->query(
				$wpdb->prepare(
					"
						DELETE *
						FROM {$wpdb->prefix}rrf_survey
						WHERE post_id = %d
						AND user_id = %d
						AND survey_id = %d
					",
					$post_id,
					$user_id,
					$survey_id
				)
			);
		}

		/**
		 * Get data custom table.
		 *
		 * @param int $post_id   Post ID.
		 * @param int $user_id   User ID.
		 * @param int $survey_id Survey ID.
		 */
		public function rrf_get_data_custom_db_table( $post_id, $user_id, $survey_id ) {
			global $wpdb;
			return $wpdb->get_results(
				$wpdb->prepare(
					"
						SELECT *
						FROM {$wpdb->prefix}rrf_survey
						WHERE post_id = %d
						AND user_id = %d
						AND survey_id = %d
					",
					$post_id,
					$user_id,
					$survey_id
				)
			);
		}

		/**
		 * Get data by post and survey.
		 *
		 * @param int $post_id   Post ID.
		 * @param int $survey_id Survey ID.
		 */
		public function rrf_get_data_by_post_survey( $post_id, $survey_id ) {
			global $wpdb;
			return $wpdb->get_results(
				$wpdb->prepare(
					"
						SELECT *
						FROM {$wpdb->prefix}rrf_survey
						WHERE post_id = %d
						AND survey_id = %d
					",
					$post_id,
					$survey_id
				)
			);
		}

		/**
		 * Get data by post and survey and from date.
		 *
		 * @param int    $post_id    Post ID.
		 * @param int    $survey_id  Survey ID.
		 * @param string $from_date From Date.
		 */
		public function rrf_get_data_by_post_survey_from_date( $post_id, $survey_id, $from_date ) {
			global $wpdb;
			return $wpdb->get_results(
				$wpdb->prepare(
					"
						SELECT *
						FROM {$wpdb->prefix}rrf_survey
						WHERE post_id = %d
						AND survey_id = %d
						AND date_time >= %s
					",
					$post_id,
					$survey_id,
					$from_date
				)
			);
		}

		/**
		 * Get data by post and survey and from date and to date.
		 *
		 * @param int  $post_id   Post ID.
		 * @param int  $survey_id Survey ID.
		 * @param date $from_date From Date.
		 * @param date $to_date   To Date.
		 */
		public function rrf_get_data_by_post_survey_from_and_to_date( $post_id, $survey_id, $from_date, $to_date ) {
			global $wpdb;
			return $wpdb->get_results(
				$wpdb->prepare(
					"
						SELECT *
						FROM {$wpdb->prefix}rrf_survey
						WHERE post_id = %d
						AND survey_id = %d
						AND date_time BETWEEN %s AND %s
					",
					$post_id,
					$survey_id,
					$from_date,
					$to_date
				)
			);
		}
	}
	RRF_CUSTOM_DB_TABLE::get_instance();
}
