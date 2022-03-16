<?php

namespace ACA\BP\Editing\Group;

use ACA\BP\Editing\Storage\Group;
use ACP;

class NameOnly extends ACP\Editing\Service\BasicStorage {

	public function __construct() {
		parent::__construct( new Group( 'name' ) );
	}

	public function get_view( $context ) {
		return new ACP\Editing\View\Text();
	}

}