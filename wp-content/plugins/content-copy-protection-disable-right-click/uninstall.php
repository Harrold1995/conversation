<?php
// If uninstall not called from WordPress then exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) 
  exit();

$option_name = 'ccpdrc_settings';

delete_option( $option_name );

// For site options in multisite
delete_site_option( $option_name );