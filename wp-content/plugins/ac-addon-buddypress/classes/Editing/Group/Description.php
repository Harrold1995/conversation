<?php

namespace ACA\BP\Editing\Group;

use ACA\BP\Editing\Storage\Group;
use ACP;

class Description extends ACP\Editing\Service\BasicStorage {

	public function __construct() {
		parent::__construct( new Group( 'description' ) );
	}

	public function get_view( $context ) {
		return ( new ACP\Editing\View\TextArea() )->set_clear_button( true );
	}

}