<?php

namespace ACA\BP\Column\Group;

use AC;
use ACA\BP\Editing;
use ACP;

class NameOnly extends AC\Column
	implements ACP\Editing\Editable {

	public function __construct() {
		$this->set_type( 'column-group_name' );
		$this->set_label( __( 'Name Only', 'codepress-admin-columns' ) );

	}

	public function editing() {
		return new Editing\Group\NameOnly();
	}

}