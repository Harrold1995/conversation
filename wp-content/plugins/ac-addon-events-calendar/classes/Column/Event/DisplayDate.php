<?php

namespace ACA\EC\Column\Event;

use AC;
use ACA\EC\Editing;
use ACA\EC\Filtering;
use ACA\EC\Settings;
use ACP;
use ACP\Search;
use ACP\Sorting\Type\DataType;

class DisplayDate extends AC\Column\Meta
	implements ACP\Filtering\Filterable, ACP\Sorting\Sortable, ACP\Search\Searchable, ACP\Editing\Editable {

	public function __construct() {
		$this->set_type( 'column-ec-event_display_date' )
		     ->set_label( __( 'Date', 'codepress-admin-columns' ) )
		     ->set_group( 'events_calendar' );
	}

	public function get_meta_key() {
		return $this->get_setting( 'event_date' )->get_value();
	}

	public function get_value( $id ) {
		return $this->get_formatted_value( $this->get_raw_value( $id ) );
	}

	public function filtering() {
		return new Filtering\Event\Date( $this );
	}

	public function register_settings() {
		$this->add_setting( new Settings\EventDates( $this ) );
		$this->add_setting( new AC\Settings\Column\Date( $this ) );
	}

	public function sorting() {
		return new ACP\Sorting\Model\Post\Meta( $this->get_meta_key(), new DataType( DataType::DATETIME ) );
	}

	public function search() {
		return new Search\Comparison\Meta\DateTime\ISO( $this->get_meta_key(), $this->get_meta_type() );
	}

	public function editing() {
		switch ( $this->get_meta_key() ) {
			case '_EventStartDate':
				return new Editing\Event\StartDate( $this );
			case '_EventEndDate':
				return new Editing\Event\EndDate( $this );
			default:
				return false;
		}
	}

}