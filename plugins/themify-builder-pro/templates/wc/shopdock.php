<?php
/**
 * Template for cart icon
 * @package Themify Builder Pro
 * @since 1.0.0
 */
?>
<div class="tbp_shopdock">
	<?php
	// check whether cart is not empty
	if ( !empty( WC()->cart->get_cart() )):
		?>
        <div class="tbp_cart_wrap">
            <div class="tbp_cart_list">
				<?php get_template_part( 'includes/loop-product', 'cart' ); ?>
            </div>
            <!-- /cart-list -->
            <div class="tbp_cart_total_checkout_wrap">
                <p class="tbp_cart_total">
                    <span class="tbp_cart_amount"><?php echo WC()->cart->get_cart_subtotal(); ?></span>
                    <a class="tbp_view_cart" href="<?php echo esc_url( wc_get_cart_url() ) ?>">
			<?php _e( 'view cart', 'themify' ) ?>
                    </a>
                </p>

                <p class="tbp_checkout_button">
                    <button type="submit" onClick="document.location.href = '<?php echo esc_url( wc_get_checkout_url() ); ?>'; return false;"><?php _e( 'Checkout', 'themify' ) ?></button>
                </p>
                <!-- /checkout-botton -->
            </div>

        </div>
        <!-- /#cart-wrap -->
	<?php else: ?>
		<?php
		$shop_permalink = $page_id = version_compare(WOOCOMMERCE_VERSION, '3.0.0', '>=') ? wc_get_page_id('shop') : woocommerce_get_page_id('shop');
		?>
		<span class="tbp_empty_shopdock">
			<?php printf( __( 'Your cart is empty. Go to <a href="%s">Shop</a>', 'tbp' ), get_permalink( $shop_permalink ) ); ?>
		</span>
	<?php endif; // cart whether is not empty?>
</div>
