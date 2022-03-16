<?php

namespace ACA\WC\Editing\ShopCoupon;

use AC\Request;
use WC_Coupon;

class ExcludeProductCategories extends ProductCategories {

	public function update( Request $request ) {
		$coupon = new WC_Coupon( $request->get( 'id' ) );
		$coupon->set_excluded_product_categories( $request->get( 'value' ) );

		return $coupon->save() > 0;
	}

}