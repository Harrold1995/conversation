<?php

namespace ACA\EC\ListScreen;

use ACA\EC\API;
use ACA\EC\Column;
use ACA\EC\Export\Strategy;
use ACP;

class Event extends ACP\ListScreen\Post {

	public function __construct() {
		parent::__construct( 'tribe_events' );

		$this->set_group( 'events-calendar' );
	}

	protected function register_column_types() {
		parent::register_column_types();

		$this->register_column_types_from_dir( 'ACA\EC\Column\Event' );

		if ( API::is_pro() ) {
			$fields = API::get_additional_fields();

			foreach ( $fields as $field ) {
				$column = $this->get_column_by_field_type( $field['type'] );

				if ( ! $column ) {
					continue;
				}

				$column->set_label( $field['label'] )
				       ->set_type( 'column' . $field['name'] );

				$this->register_column_type( $column );
			}

		}

	}

	/**
	 * @param string $type
	 *
	 * @return false|Column\Event\Field
	 */
	public function get_column_by_field_type( $type ) {
		$class_name = 'ACA\EC\Column\Event\Field\\' . ucfirst( $type );

		if ( ! class_exists( $class_name ) ) {
			return false;
		}

		return new $class_name;
	}

	public function export() {
		return new Strategy\Event( $this );
	}

}