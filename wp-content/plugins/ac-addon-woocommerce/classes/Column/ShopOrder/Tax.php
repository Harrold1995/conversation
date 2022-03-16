<?php

namespace ACA\WC\Column\ShopOrder;

use AC;
use ACA\WC\Search;

/**
 * @since 3.6
 */
class Tax extends AC\Column {

	public function __construct() {
		$this->set_type( 'column-wc-order_tax' )
		     ->set_label( __( 'Tax', 'codepress-admin-columns' ) )
		     ->set_group( 'woocommerce' );
	}

	public function get_value( $id ) {
		$taxes = wc_get_order( $id )->get_tax_totals();

		if ( empty( $taxes ) ) {
			return $this->get_empty_char();
		}

		$result = [];

		foreach ( $taxes as $tax ) {
			$result[] = sprintf( '<small><strong>%s: </strong></small> %s', $tax->label, $tax->formatted_amount );
		}

		return implode( '<br>', $result );
	}

}