<?php
global $product;
$isAvaialble=$product->is_purchasable() && $product->is_in_stock();
if($isAvaialble===true && !empty($args['label'])){
    global $label;
    $label=$args['label'];
    if(!function_exists('tb_pro_add_to_cart_text')){
	function tb_pro_add_to_cart_text($text){
	    remove_filter('woocommerce_product_single_add_to_cart_text','tb_pro_add_to_cart_text');
	    remove_filter('woocommerce_product_add_to_cart_text','tb_pro_add_to_cart_text');
	    global $label;
	    $text = $label;
	    $label=null;
	    return $text;
	}
    }
    add_filter('woocommerce_product_add_to_cart_text','tb_pro_add_to_cart_text');
    add_filter('woocommerce_product_single_add_to_cart_text','tb_pro_add_to_cart_text');
}
?>
<div class="tb_pro_loop_add_to_cart tb_pro_add_to_cart<?php if(!empty($args['fullwidth']) && 'no' !== $args['fullwidth']):?> buttons-fullwidth<?php endif;?>">
    <?php
    if($args['quantity']!=='yes' || $isAvaialble===false){
	woocommerce_template_loop_add_to_cart();
    }
    else{
	if(Tbp_Utils::isAjaxAddToCart()===true){
	    wp_enqueue_script('wc-add-to-cart');
	}
	$origVal=isset($_POST['quantity'])?$_POST['quantity']:false;
	$_POST['quantity'] =1;
	woocommerce_simple_add_to_cart();
	if($origVal!==false){
	    $_POST['quantity'] =$origVal;
	}
    }
    $args=null;
    ?>
</div>
