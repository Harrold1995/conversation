<?php

namespace ACA\BP\Editing\Storage;

use ACP;
use BP_XProfile_ProfileData;

class Profile implements ACP\Editing\Storage {

	/**
	 * @var string
	 */
	private $field;

	public function __construct( $field ) {
		$this->field = $field;
	}

	public function update( $id, $value ) {
		return xprofile_set_field_data( $this->field, $id, $value );
	}

	public function get( $id ) {
		return maybe_unserialize( BP_XProfile_ProfileData::get_value_byid( $this->field, $id ) );
	}

}