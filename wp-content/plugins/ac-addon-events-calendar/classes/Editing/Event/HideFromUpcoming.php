<?php

namespace ACA\EC\Editing\Event;

use AC\Helper\Select\Option;
use AC\Request;
use AC\Type\ToggleOptions;
use ACP;

class HideFromUpcoming implements ACP\Editing\Service {

	/**
	 * @var ACP\Editing\Storage
	 */
	private $storage;

	public function __construct() {
		$this->storage = new ACP\Editing\Storage\Post\Meta( '_EventHideFromUpcoming' );
	}

	public function get_view( $context ) {
		return new ACP\Editing\View\Toggle(
			new ToggleOptions(
				new Option( '' ),
				new Option( 'yes' )
			)
		);
	}

	public function get_value( $id ) {
		return $this->storage->get( $id );
	}

	public function update( Request $request ) {
		$value = $request->get( 'value' );

		return $value
			? $this->storage->update( $request->get( 'id' ), $value )
			: delete_post_meta( $request->get( 'id' ), '_EventHideFromUpcoming' );
	}

}