<?php
/*
Plugin Name:    Admin Columns Pro - BuddyPress
Version:        1.6
Description:    BuddyPress add-on for Admin Columns Pro
Author:         AdminColumns.com
Author URI:     https://www.admincolumns.com
Plugin URI:     https://www.admincolumns.com
Text Domain:    codepress-admin-columns
Requires PHP:   5.6.20
*/

use ACA\BP\BuddyPress;
use ACA\BP\Dependencies;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! is_admin() ) {
	return;
}

require_once __DIR__ . '/classes/Dependencies.php';

add_action( 'after_setup_theme', function () {
	$dependencies = new Dependencies( plugin_basename( __FILE__ ), '1.6' );
	$dependencies->requires_acp( '5.6' );
	$dependencies->requires_php( '5.6.20' );

	if ( ! class_exists( 'BuddyPress', false ) ) {
		$dependencies->add_missing_plugin( __( 'BuddyPress', 'buddypress' ), $dependencies->get_search_url( 'BuddyPress' ) );
	}

	if ( $dependencies->has_missing() ) {
		return;
	}

	$class_map = __DIR__ . '/config/autoload-classmap.php';

	if ( is_readable( $class_map ) ) {
		AC\Autoloader::instance()->register_class_map( require $class_map );
	} else {
		AC\Autoloader::instance()->register_prefix( 'ACA\BP', __DIR__ . '/classes' );
	}

	$addon = new BuddyPress( __FILE__ );
	$addon->register();
} );