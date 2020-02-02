<?php

/**
 * Remove default WooCommerce content wrappers
 *
 * @since Socialize 2.9
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

/**
 * Opening WooCommerce content wrappers 
 *
 * @since Socialize 2.9
 */
if ( ! function_exists( 'ghostpool_wc_wrapper_start' ) ) {
	function ghostpool_wc_wrapper_start() {
  
		if ( $GLOBALS['ghostpool_page_header'] == 'gp-fullwidth-page-header' OR $GLOBALS['ghostpool_page_header'] == 'gp-full-page-page-header' ) { ghostpool_page_header( get_the_ID() ); }

		echo '<div id="gp-content-wrapper" class="gp-container">';
		
			if ( $GLOBALS['ghostpool_page_header'] == 'gp-large-page-header' ) { ghostpool_page_header( get_the_ID() ); }
			
			echo '<div id="gp-inner-container">
			
				<div id="gp-left-column">
		
					<div id="gp-content">';
						
	}
}
add_action( 'woocommerce_before_main_content', 'ghostpool_wc_wrapper_start', 10 );

/**
 * Closing WooCommerce content wrappers 
 *
 * @since Socialize 2.9
 */
if ( ! function_exists( 'ghostpool_wc_wrapper_end' ) ) {
	function ghostpool_wc_wrapper_end() {
			
				echo '</div>';

				get_sidebar( 'left' );
	
			echo '</div>';
	
			get_sidebar( 'right' );

		echo '</div>';

		 echo '<div class="gp-clear"></div>';

	echo '</div>';	

	}
}
add_action( 'woocommerce_after_main_content', 'ghostpool_wc_wrapper_end', 10 );

?>