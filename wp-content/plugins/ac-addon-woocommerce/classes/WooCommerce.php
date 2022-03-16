<?php

namespace ACA\WC;

use AC;
use AC\Registrable;
use ACA\WC\ListScreen\ShopCoupon;
use ACA\WC\ListScreen\ShopOrder;
use ACA\WC\QuickAdd;
use ACP;

final class WooCommerce extends AC\Plugin {

	public function __construct( $file ) {
		parent::__construct( $file, 'aca_wc_version' );
	}

	/**
	 * Register hooks
	 */
	public function register() {
		add_action( 'ac/list_screen_groups', [ $this, 'register_list_screen_groups' ] );
		add_action( 'ac/column_groups', [ $this, 'register_column_groups' ] );
		add_action( 'ac/list_screens', [ $this, 'register_list_screens' ] );
		add_action( 'ac/column_types', [ $this, 'register_columns' ] );
		add_filter( 'acp/quick_add/enable', [ $this, 'disable_quick_add' ], 10, 2 );
		add_action( 'init', [ $this, 'install' ] );

		ACP\QuickAdd\Model\Factory::add_factory( new QuickAdd\Factory );

		$modules = [
			new TableScreen( $this->get_location() ),
			new Subscriptions(),
			new Rounding(),
			new Admin(),
		];

		foreach ( $modules as $module ) {
			if ( $module instanceof Registrable ) {
				$module->register();
			}
		}

		// Variation List Table
		if ( $this->use_product_variations() ) {
			new PostType\ProductVariation( $this->get_location() );
		}
	}

	public function disable_quick_add( $enabled, AC\ListScreen $list_screen ) {
		switch ( true ) {
			case ( $list_screen instanceof ShopOrder ) :
				$enabled = false;
				break;
		}

		return $enabled;
	}

	/**
	 * @return bool
	 */
	private function use_product_variations() {
		return apply_filters( 'acp/wc/show_product_variations', true ) && $this->is_wc_version_gte( '3.3' );
	}

	public function install() {
		parent::install();

		if ( $this->is_version_gte( '3.3' ) ) {
			$update = new Update\V3300();

			if ( ! $update->is_applied() ) {
				$update->run();
			}
		}
	}

	public function register_list_screens() {
		$list_screens = [
			new ListScreen\ShopOrder,
			new ListScreen\ShopCoupon,
			new ListScreen\Product,
			new ListScreen\ProductCategory,
		];

		if ( $this->use_product_variations() ) {
			$list_screens[] = new ListScreen\ProductVariation;
		}

		foreach ( $list_screens as $list_screen ) {
			AC\ListScreenTypes::instance()->register_list_screen( $list_screen );
		}
	}

	public function register_columns( AC\ListScreen $list_screen ) {
		if ( $list_screen instanceof AC\ListScreen\User ) {
			$list_screen->register_column_types_from_dir( __NAMESPACE__ . '\Column\User' );
		}

		if ( $list_screen instanceof AC\ListScreen\Comment ) {
			$list_screen->register_column_types_from_dir( __NAMESPACE__ . '\Column\Comment' );
		}
	}

	public function register_list_screen_groups( AC\Groups $groups ) {
		$groups->register_group( 'woocommerce', __( 'WooCommerce', 'codepress-admin-columns' ), 7 );
	}

	public function register_column_groups( AC\Groups $groups ) {
		$groups->register_group( 'woocommerce', __( 'WooCommerce', 'codepress-admin-columns' ), 15 );
	}

	/**
	 * @param string $version
	 *
	 * @return bool
	 */
	public function is_wc_version_gte( $version ) {
		return version_compare( WC()->version, $version, '>=' );
	}

}