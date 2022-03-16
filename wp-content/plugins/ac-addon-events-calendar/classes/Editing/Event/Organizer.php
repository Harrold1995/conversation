<?php

namespace ACA\EC\Editing\Event;

use AC;
use AC\Request;
use ACP;

class Organizer implements ACP\Editing\PaginatedOptions, ACP\Editing\Service {

	public function get_view( $context ) {
		return ( new ACP\Editing\View\AjaxSelect() )
			->set_multiple( true )
			->set_clear_button( true );
	}

	private function get_meta_key() {
		return '_EventOrganizerID';
	}

	public function get_value( $id ) {
		$values = [];
		$ids = get_post_meta( $id, $this->get_meta_key(), false );

		if ( ! $ids ) {
			return $values;
		}

		foreach ( array_filter( $ids ) as $_id ) {
			$values[ $_id ] = html_entity_decode( get_the_title( $_id ) );
		}

		return $values;
	}

	public function get_paginated_options( $s, $paged, $id = null ) {
		$entities = new ACP\Helper\Select\Entities\Post( [
			's'         => $s,
			'paged'     => $paged,
			'post_type' => 'tribe_organizer',
		] );

		return new AC\Helper\Select\Options\Paginated(
			$entities,
			new ACP\Helper\Select\Formatter\PostTitle( $entities )
		);
	}

	public function update( Request $request ) {
		$id = $request->get( 'id' );
		delete_post_meta( $id, $this->get_meta_key() );

		$values = (array) $request->get( 'value', [] );

		foreach ( $values as $value ) {
			add_post_meta( $id, $this->get_meta_key(), $value );
		}

		return true;
	}

}