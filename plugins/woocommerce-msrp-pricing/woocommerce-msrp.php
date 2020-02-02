<?php
/*
Plugin Name: WooCommerce MSRP Pricing
Plugin URI: https://www.woothemes.com/products/msrp-pricing/
Description: A WooCommerce extension that lets you flag Manufacturer Suggested Retail Prices against products, and display them on the front end.
Author: Lee Willis
Version: 2.9.5
Woo: 18727:b9133a56078a1ffa217e74136769022b
WC requires at least: 2.6.0
WC tested up to: 3.2.0
Author URI: http://plugins.leewillis.co.uk/
License: GPLv3
*/

/**
 * Required functions
 */
if ( ! function_exists( 'woothemes_queue_update' ) ) {
	require_once( 'woo-includes/woo-functions.php' );
}

/**
 * Plugin updates
 */
woothemes_queue_update( plugin_basename( __FILE__ ), 'b9133a56078a1ffa217e74136769022b', '18727' );

if ( is_woocommerce_active() ) {
	if ( is_admin() ) {
		require_once( 'woocommerce-msrp-admin.php' );
	}
	require_once( 'woocommerce-msrp-frontend.php' );
}

register_activation_hook( __FILE__, 'woocommerce_msrp_activate' );

/**
 * Add default option settings on plugin activation
 */
function woocommerce_msrp_activate() {
	add_option( 'woocommerce_msrp_status', 'always', '', true );
	add_option( 'woocommerce_msrp_description', 'MSRP', '', true );
}

/**
 * Support for import / export in WooCommerce 3.1+
 */
require_once( 'woocommerce-msrp-import-export.php' );
global $woocommerce_msrp_import_export;
$woocommerce_msrp_import_export = new WoocommerceMsrpImportExport();
