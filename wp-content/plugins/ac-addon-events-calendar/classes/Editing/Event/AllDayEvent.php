<?php

namespace ACA\EC\Editing\Event;

use AC\Helper\Select\Option;
use AC\Request;
use AC\Type\ToggleOptions;
use ACP;

class AllDayEvent implements ACP\Editing\Service {

	public function get_view( $context ) {
		$options = new ToggleOptions(
			new Option( '0' ),
			new Option( 'yes' )
		);

		return new ACP\Editing\View\Toggle( $options );
	}

	private function get_meta_key() {
		return '_EventAllDay';
	}

	public function get_value( $id ) {
		return get_post_meta( $id, $this->get_meta_key(), true );
	}

	public function update( Request $request ) {
		$value = $request->get( 'value' );
		$id = $request->get( 'id' );

		if ( '0' === $value ) {
			return false !== delete_post_meta( $id, $this->get_meta_key() );
		}

		return update_post_meta( $id, $this->get_meta_key(), $value );
	}

}