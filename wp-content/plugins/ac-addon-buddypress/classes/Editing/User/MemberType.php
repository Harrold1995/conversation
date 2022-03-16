<?php

namespace ACA\BP\Editing\User;

use AC\Request;
use ACP\Editing\Service;
use ACP\Editing\View;

class MemberType implements Service {

	/**
	 * @var array
	 */
	private $options;

	public function __construct( array $options ) {
		$this->options = $options;
	}

	public function get_view( $context ) {
		return new View\Select( $this->options );
	}

	public function get_value( $id ) {
		return bp_get_member_type( $id );
	}

	public function update( Request $request ) {
		return bp_set_member_type( $request->get( 'id' ), $request->get( 'value' ) );
	}

}