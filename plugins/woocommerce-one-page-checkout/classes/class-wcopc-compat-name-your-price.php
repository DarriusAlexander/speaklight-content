<?php
/**
 * @package		WooCommerce One Page Checkout
 * @subpackage	Name Your Price Extension Compatibility
 * @category	Compatibility Class
 * @version 	1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class to hold Name Your Price compat functionality
 */
class WCOPC_Compat_Name_Your_Price {

	const PREFIX = '-opc-';

	public static function init() {

		if ( class_exists( 'WC_Name_Your_Price' ) ) {

			// Add NYP input to product_table and pricing_table templates.
			add_action( 'wcopc_before_add_to_cart_button', array( __CLASS__, 'opc_nyp_price_input' ) );

			// Filter the NYP prefix.
			add_filter( 'nyp_field_prefix', array( __CLASS__, 'nyp_cart_prefix' ), 10, 3 );

			// Maybe swap on single product pages.
			add_action( 'woocommerce_before_add_to_cart_form', array( __CLASS__, 'maybe_swap_nyp_price_input' ), 5 );

			// Swap on single product shortcodes.
			add_action( 'wcopc_single_add_to_cart', array( __CLASS__, 'swap_nyp_price_input' ), 5 );

			if ( isset( WC_Name_Your_Price()->display ) ) {
				// Load the NYP scripts with OPC scripts.
				add_action( 'wcopc_enqueue_scripts', array( WC_Name_Your_Price()->display, 'nyp_scripts' ) );
				add_action( 'wcopc_enqueue_scripts', array( WC_Name_Your_Price()->display, 'nyp_style' ) );
			}
		}
	}

	/**
	 * Maybe swap default price input with OPC function that adds prefix.
	 * @param	obj $product
	 * @return	void
	 * @access	public
	 * @since	1.5.0
	 */
	public static function maybe_swap_nyp_price_input(){
		if ( is_wcopc_checkout() ) {
			self::swap_nyp_price_input();
		}
	}

	/**
	 * Swap default price input with OPC function that adds prefix.
	 * @param	obj $product
	 * @return	void
	 * @access	public
	 * @since	1.6.0
	 */
	public static function swap_nyp_price_input(){
		remove_action( 'woocommerce_before_add_to_cart_button', array( WC_Name_Your_Price()->display, 'display_price_input' ), 9 );
		add_action( 'woocommerce_before_add_to_cart_button', array( __CLASS__, 'opc_nyp_price_input' ), 9 );

		// NYP 2.9.0 changes display hook to woocommerce_single_variation for variable products.
		if ( version_compare( WC_Name_Your_Price()->version, '2.9.0', '>=' ) ) {
			remove_action( 'woocommerce_before_variations_form', array( WC_Name_Your_Price()->display, 'move_display_for_variable_product' ) );
			add_action( 'woocommerce_before_variations_form', array( __CLASS__, 'move_display_for_variable_product' ) );
		}
	}

	/**
	 * Fix price input position on variable products - This shows them before Product Addons.
	 * @since 1.6.0
	 */
	public static function move_display_for_variable_product() {
		remove_action( 'woocommerce_before_add_to_cart_button', array( __CLASS__, 'opc_nyp_price_input' ), 9 );
		add_action( 'woocommerce_single_variation', array( __CLASS__, 'opc_nyp_price_input' ), 12 );
	}

	/**
	 * Display Price Input in OPC templates.
	 * @param	obj $product
	 * @return	void
	 * @access	public
	 * @since	1.5.0
	 */
	public static function opc_nyp_price_input( $product = false ){
		if ( ! is_a( $product, 'WC_Product' ) ) {
			global $product;
		}

		if ( ! is_a( $product, 'WC_Product' ) ) {
			return;
		}

		$nyp_id = WC_Name_Your_Price_Core_Compatibility::get_id( $product );
		$prefix = self::PREFIX . $nyp_id ; 

		WC_Name_Your_Price()->display->display_price_input( $nyp_id, $prefix );
	}

	/**
	 * Sets a unique prefix for unique NYP products in OPC templates. 
	 * The prefix is set and re-set globally before validating and adding to cart.
	 *
	 * @param  	string  $prefix
	 * @param  	int     $nyp_id
	 * @return  string
	 * @access  public
	 * @since	1.5.0
	 */
	public static function nyp_cart_prefix( $prefix, $nyp_id ) {

		if ( PP_One_Page_Checkout::is_any_form_of_opc_page() ) {
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX && isset( $_REQUEST['add_to_cart'] ) ) {
				$prefix = self::PREFIX . $_REQUEST['add_to_cart'];
			} elseif ( isset( $_REQUEST['add-to-cart'] ) ) {
				$prefix = self::PREFIX . $_REQUEST['add-to-cart'];
			}
		}

		return $prefix;
	}
}
add_action( 'init', 'WCOPC_Compat_Name_Your_Price::init' );
