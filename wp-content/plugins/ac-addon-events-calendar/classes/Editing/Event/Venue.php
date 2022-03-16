<?php

namespace ACA\EC\Editing\Event;

use AC;
use ACP;
use ACP\Editing\Storage;

class Venue extends ACP\Editing\Service\BasicStorage implements ACP\Editing\PaginatedOptions {

	public function __construct() {
		parent::__construct( new Storage\Post\Meta( '_EventVenueID' ) );
	}

	public function get_view( $context ) {
		$view = new ACP\Editing\View\AjaxSelect();

		return $view->set_clear_button( true );
	}

	public function get_value( $id ) {
		$post = get_post( parent::get_value( $id ) );

		return $post
			? [ $post->ID => $post->post_title ]
			: false;
	}

	public function get_paginated_options( $s, $paged, $id = null ) {
		$entities = new ACP\Helper\Select\Entities\Post( [
			's'         => $s,
			'paged'     => $paged,
			'post_type' => 'tribe_venue',
		] );

		return new AC\Helper\Select\Options\Paginated(
			$entities,
			new ACP\Helper\Select\Formatter\PostTitle( $entities )
		);
	}

}