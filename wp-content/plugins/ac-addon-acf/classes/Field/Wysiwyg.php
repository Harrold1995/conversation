<?php

namespace ACA\ACF\Field;

use AC;
use ACA\ACF\Editing;
use ACA\ACF\Field;
use ACA\ACF\Filtering;
use ACP;

class Wysiwyg extends Field {

	public function get_dependent_settings() {
		return [
			new AC\Settings\Column\WordLimit( $this->column ),
		];
	}

	public function editing() {
		return new Editing\Wysiwyg( $this->column, $this->column->get_option( 'editable_type' ) );
	}

	public function filtering() {
		return new Filtering( $this->column );
	}

	public function sorting() {
		return ( new ACP\Sorting\Model\MetaFactory() )->create( $this->get_meta_type(), $this->get_meta_key() );
	}

	public function search() {
		return new ACP\Search\Comparison\Meta\Text( $this->get_meta_key(), $this->get_meta_type() );
	}

}