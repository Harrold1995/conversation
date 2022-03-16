<?php

namespace ACA\WC\Editing\Product;

use AC;
use AC\Request;
use ACA\WC\Helper\Select;
use ACP;

class Upsells implements ACP\Editing\PaginatedOptions, ACP\Editing\Service {

	public function get_view( $context ) {
		return ( new ACP\Editing\View\AjaxSelect() )->set_multiple( true )->set_clear_button( true );
	}

	public function get_value( $id ) {
		$product = wc_get_product( $id );

		return ac_addon_wc_helper()->get_editable_posts_values( (array) $product->get_upsell_ids() );
	}

	public function update( Request $request ) {
		$product = wc_get_product( $request->get( 'id' ) );
		$product->set_upsell_ids( (array) $request->get( 'value' ) );

		return $product->save() > 0;
	}

	public function get_paginated_options( $s, $paged, $id = null ) {
		$entities = new Select\Entities\Product( compact( 's', 'paged' ) );

		return new AC\Helper\Select\Options\Paginated(
			$entities,
			new Select\Formatter\ProductTitleAndSKU( $entities )
		);
	}

}