<?php

namespace ACA\WC\Column\Product;

use AC;
use ACA\WC\Editing;
use ACA\WC\Filtering;
use ACA\WC\Search;
use ACP;

/**
 * @since 3.0
 */
class SoldIndividually extends AC\Column\Meta
	implements ACP\Editing\Editable, ACP\Filtering\Filterable, ACP\Sorting\Sortable, ACP\Search\Searchable {

	public function __construct() {
		$this->set_type( 'column-wc-product_sold_individually' );
		$this->set_label( __( 'Sold Individually', 'woocommerce' ) );
		$this->set_group( 'woocommerce' );
	}

	public function get_meta_key() {
		return '_sold_individually';
	}

	public function get_value( $id ) {
		if ( ! $this->get_raw_value( $id ) ) {
			return $this->get_empty_char();
		}

		return ac_helper()->icon->yes( false, false, 'blue' );
	}

	public function get_raw_value( $id ) {
		return wc_get_product( $id )->is_sold_individually();
	}

	public function sorting() {
		return new ACP\Sorting\Model\Post\Meta( $this->get_meta_key() );
	}

	public function editing() {
		return new Editing\Product\SoldIndividually( );
	}

	public function filtering() {
		return new Filtering\Product\SoldIndividually( $this );
	}

	public function search() {
		return new Search\Product\SoldIndividually();
	}

}