<?php

namespace ACA\EC\Editing\Event;

use AC\Helper\Select\Option;
use AC\Request;
use AC\Type\ToggleOptions;
use ACP;
use RuntimeException;

class Sticky implements ACP\Editing\Service {

	public function get_view( $context ) {
		return new ACP\Editing\View\Toggle(
			new ToggleOptions(
				new Option( '0' ),
				new Option( '-1' )
			)
		);
	}

	public function get_value( $id ) {
		return get_post_field( 'menu_order', $id );
	}

	public function update( Request $request ) {
		$result = wp_update_post( [
			'ID'         => $request->get( 'id' ),
			'menu_order' => $request->get( 'value' ),
		] );

		if ( is_wp_error( $result ) ) {
			throw new RuntimeException( $result->get_error_message() );
		}

		return false !== $result;
	}

}