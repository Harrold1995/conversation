<?php

namespace ACA\BP\Column\Group;

use AC;

class Id extends AC\Column {

	public function __construct() {
		$this->set_type( 'column-group_id' );
		$this->set_label( __( 'ID', 'codepress-admin-columns' ) );

	}

	public function get_raw_value( $group_id ) {
		return $group_id;
	}

}