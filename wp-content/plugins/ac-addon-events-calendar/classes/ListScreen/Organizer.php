<?php

namespace ACA\EC\ListScreen;

use ACP;

class Organizer extends ACP\ListScreen\Post {

	public function __construct() {
		parent::__construct( 'tribe_organizer' );

		$this->set_group( 'events-calendar' );
	}

	protected function register_column_types() {
		parent::register_column_types();

		$this->register_column_types_from_dir( 'ACA\EC\Column\Organizer' );
	}

}