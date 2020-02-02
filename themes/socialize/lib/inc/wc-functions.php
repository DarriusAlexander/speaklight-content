<?php

/*--------------------------------------------------------------
Load custom WooCommerce stylesheet
--------------------------------------------------------------*/

if ( ! function_exists( 'ghostpool_wc_enqueue_styles' ) ) {	
	function ghostpool_wc_enqueue_styles() {
		wp_enqueue_style( 'gp-woocommerce', get_template_directory_uri() . '/lib/css/woocommerce.css' );
	}
}
add_action( 'wp_enqueue_scripts', 'ghostpool_wc_enqueue_styles' );

/**
 * Disable activation redirect
 *
 */
if ( ! function_exists( 'ghostpool_wc_disable_redirect' ) ) {
	function ghostpool_wc_disable_redirect() {
		return true;
	}
}
add_filter( 'woocommerce_prevent_automatic_wizard_redirect', 'ghostpool_wc_disable_redirect' );

/*--------------------------------------------------------------
Set WooCommerce defaults
--------------------------------------------------------------*/

if ( is_admin() && get_option( 'ghostpool_wc_defaults' ) !== '1' ) {
	function ghostpool_woocommerce_defaults() {		
		update_option( 'shop_catalog_image_size', array( 'width' => 454, 'height' => 550, 'crop' => 1 ) );
		update_option( 'shop_thumbnail_image_size', array( 'width' => 180, 'height' => 180, 'crop' => 1 ) ); 
		update_option( 'shop_single_image_size', array( 'width' => 500, 'height' => 700, 'crop' => 1 ) );
	}	
	add_action( 'init', 'ghostpool_woocommerce_defaults', 1 );	
	update_option( 'ghostpool_wc_defaults', '1' );												
}
	

/*--------------------------------------------------------------
Pagination
--------------------------------------------------------------*/

remove_action( 'woocommerce_pagination', 'woocommerce_pagination', 10 );
if ( ! function_exists( 'woocommerce_pagination' ) ) {
	function woocommerce_pagination() {
		global $wp_query;
		echo ghostpool_pagination( $wp_query->max_num_pages );
	}
}	
add_action( 'woocommerce_pagination', 'woocommerce_pagination', 10 );


/*--------------------------------------------------------------
Dropdown Cart
--------------------------------------------------------------*/

// Normal Drop Down Cart
if ( ! function_exists( 'ghostpool_dropdown_cart' ) ) {														
	function ghostpool_dropdown_cart() {
		if ( ! is_cart() ) { ?>	
			<div id="gp-dropdowncart" class="gp-nav">
				<ul class="menu">
					<li class="gp-standard-menu gp-dropdowncart-menu">
						<a href="<?php echo wc_get_cart_url(); ?>" id="gp-cart-button" title="<?php esc_html_e( 'View your shopping cart', 'socialize' ); ?>">
							<span id="gp-cart-counter"><?php echo sprintf( _n( '%d', '%d', WC()->cart->get_cart_contents_count(), 'socialize' ), WC()->cart->get_cart_contents_count() ); ?></span>
						</a>
						<ul class="sub-menu">
							<?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>		
						</ul>
					</li>
				</ul>		
			</div>
	<?php }
	}
}

// Ajaxify Cart Button
if ( ! function_exists( 'ghostpool_woocommerce_add_to_cart_fragment' ) ) {
	function ghostpool_woocommerce_add_to_cart_fragment( $fragments ) {
		ob_start(); ?>
			<span id="gp-cart-counter"><?php echo sprintf( _n( '%d', '%d', WC()->cart->get_cart_contents_count(), 'socialize' ), WC()->cart->get_cart_contents_count() ); ?></span>
		<?php $fragments['#gp-cart-button #gp-cart-counter'] = ob_get_clean();
		return $fragments;

	}
}
add_filter( 'woocommerce_add_to_cart_fragments', 'ghostpool_woocommerce_add_to_cart_fragment' );

/**
 * Load content hooks
 *
 * @since Socialize 2.9
 */
require_once( get_template_directory() . '/lib/inc/wc-content-hooks.php' );

?>