<?php

namespace ACA\BP\Editing\Ajax\EditableRows;

use ACP\Editing\Ajax\EditableRows;
use BP_Groups_Group;

class Groups extends EditableRows {

	public function register() {
		if ( ! $this->is_request() ) {
			return;
		}

		add_filter( 'bp_groups_admin_load', [ $this, 'send_editable_rows' ], 10, 2 );
	}

	public function send_editable_rows() {
		$editable_rows = [];

		$ids = BP_Groups_Group::get_group_type_ids();

		foreach ( $ids['all'] as $id ) {
			if ( $this->strategy->user_has_write_permission( $id ) ) {
				$editable_rows[] = $id;
			}
		}

		$this->success( $editable_rows );
	}

}