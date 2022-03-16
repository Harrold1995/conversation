<?php

namespace ACA\EC\Column\Event;

use AC;
use ACA\EC\Editing;
use ACA\EC\Filtering;
use ACP;

class Sticky extends AC\Column
	implements ACP\Filtering\Filterable, ACP\Editing\Editable, ACP\Search\Searchable {

	public function __construct() {
		$this->set_type( 'column-ec-event_sticky' )
		     ->set_label( __( 'Sticky in Month View', 'codepress-admin-columns' ) )
		     ->set_group( 'events_calendar' );
	}

	public function get_value( $post_id ) {
		$value = $this->get_raw_value( $post_id );

		return ac_helper()->icon->yes_or_no( -1 === $value );
	}

	public function get_raw_value( $post_id ) {
		return get_post_field( 'menu_order', $post_id );
	}

	public function filtering() {
		return new Filtering\Event\Sticky( $this );
	}

	public function editing() {
		return new Editing\Event\Sticky( $this );
	}

	public function search() {
		return new \ACA\EC\Search\Event\Sticky();
	}

}