<?php

namespace ACA\BP\Sorting;

use ACA\BP\Column;
use ACP\Sorting\AbstractModel;
use ACP\Sorting\Type\CastType;
use ACP\Sorting\Type\DataType;

class Profile extends AbstractModel {

	/**
	 * @var Column\Profile
	 */
	protected $column;

	protected $datatype;

	public function __construct( Column\Profile $column, DataType $data_type = null ) {
		parent::__construct( $data_type );

		$this->column = $column;
	}

	public function get_sorting_vars() {
		return [
			'ids' => $this->get_sorted_ids(),
		];
	}

	private function get_sorted_ids() {
		global $wpdb, $bp;

		$cast_type = CastType::create_from_data_type( $this->data_type )->get_value();
		$join_type = $this->show_empty ? 'LEFT JOIN' : 'INNER JOIN';

		$sql = $wpdb->prepare( "
					SELECT DISTINCT u.ID
					FROM {$wpdb->users} AS u
					{$join_type} {$bp->profile->table_name_data} as bpd ON ( u.ID = bpd.user_id AND bpd.field_id = %d )
					ORDER BY CAST(bpd.value as " . esc_sql( $cast_type ) . ") " . $wpdb->_real_escape( $this->get_order() ), $this->column->get_buddypress_field_id() );

		return (array) $wpdb->get_col( $sql );
	}

}