<?php
/**
 * @author William Sergio Minossi
 * @copyright 2016
 */
// If uninstall is not called from WordPress, exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

$wpmemory_option_name[] ='wpmemory_checkversion';

$wnum = count($wpmemory_option_name);
for ($i = 0; $i < $wnum; $i++)
{
 delete_option( $wpmemory_option_name[$i] );
 // For site options in Multisite
 delete_site_option($wpmemory_option_name[$i] );    
}
// Drop a custom db table
global $wpdb;
//$current_table = $wpdb->prefix . 'ah_stats';
//$wpdb->query( "DROP TABLE IF EXISTS $current_table" );
?>