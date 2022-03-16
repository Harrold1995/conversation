<?php

namespace ACA\BP\Sorting\User;

use ACP\Sorting\AbstractModel;

class ActivityUpdates extends AbstractModel {

	/**
	 * @var string
	 */
	private $activity_type;

	public function __construct( $activity_type ) {
		parent::__construct();

		$this->activity_type = (string) $activity_type;
	}

	public function get_sorting_vars() {
		return [
			'ids' => $this->get_sorted_ids(),
		];
	}

	private function get_sorted_ids() {
		global $wpdb, $bp;

		$where = '';

		if ( $this->activity_type ) {
			$where = $wpdb->prepare( 'WHERE type = %s', $this->activity_type );
		}

		$sql = "SELECT user_id, COUNT(user_id) as activities
				FROM {$bp->activity->table_name}
				{$where}
				GROUP BY user_id
				ORDER BY activities " . $this->get_order();

		return (array) $wpdb->get_col( $sql );
	}

}