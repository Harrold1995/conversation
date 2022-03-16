<?php

namespace ACA\BP\Editing\Profile;

use ACA\BP\Editing\Storage\Profile;
use ACP\Editing;

class MultiChoices extends Editing\Service\Basic {

	public function __construct( $field, $options ) {
		parent::__construct( new Editing\View\CheckboxList( $options ), new Profile( $field ) );
	}

	public function get_value( $id ) {
		$value = parent::get_value( $id );

		if ( ! is_array( $value ) ) {
			return $value;
		}

		return array_values( $value );
	}

}