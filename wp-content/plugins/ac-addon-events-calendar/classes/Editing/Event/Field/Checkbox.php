<?php

namespace ACA\EC\Editing\Event\Field;

use AC\Request;
use ACA\EC\Column\Event\Field;
use ACP;
use ACP\Editing;
use ACP\Editing\Storage;

/**
 * @property  Field $column
 * @since 1.1.2
 */
class Checkbox extends ACP\Editing\Service\Basic {

	public function __construct( $meta_key, array $options ) {
		parent::__construct(
			( new Editing\View\CheckboxList( $options ) )->set_clear_button( true ),
			new Storage\Post\Meta( $meta_key )
		);
	}

	public function get_value( $id ) {
		$value = parent::get_value( $id );

		return explode( '|', $value );
	}

	public function update( Request $request ) {
		$value = $request->get( 'value' );

		if( is_array( $value ) ) {
			$value = implode( '|', $value );
		}

		$request->get_parameters()->set( 'value', $value );

		parent::update( $request );
	}

}