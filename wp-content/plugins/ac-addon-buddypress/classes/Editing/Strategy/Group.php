<?php

namespace ACA\BP\Editing\Strategy;

use ACP;

class Group implements ACP\Editing\Strategy {

	/**
	 * @param int|object $entry_id
	 *
	 * @return bool|int
	 */
	public function user_has_write_permission( $entry_id ) {
		if ( is_object( $entry_id ) ) {
			$entry_id = $entry_id->id;
		}

		if ( ! current_user_can( 'bp_moderate', $entry_id ) ) {
			return false;
		}

		return $entry_id;
	}

}