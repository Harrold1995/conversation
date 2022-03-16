<?php

namespace ACA\BP\Editing\User;

use AC\Helper\Select\Option;
use AC\Request;
use AC\Type\ToggleOptions;
use ACP\Editing\Service;
use ACP\Editing\View\Toggle;

class Status implements Service {

	public function get_view( $context ) {
		$options = new ToggleOptions(
			new Option( 0, __( 'Active', 'buddypress' ) ),
			new Option( 1, __( 'Spammer', 'buddypress' ) ),
		);

		return new Toggle( $options );
	}

	public function get_value( $id ) {
		return ac_helper()->user->get_user_field( 'user_status', $id );
	}

	public function update( Request $request ) {
		global $wpdb;

		$result = $wpdb->update(
			$wpdb->users,
			[ 'user_status' => (string) $request->get( 'value', '' ) ],
			[ 'ID' => (int) $request->get( 'id' ) ]
		);

		clean_user_cache( $request->get( 'id' ) );

		return $result;
	}

}