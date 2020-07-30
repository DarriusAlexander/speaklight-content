<?php
global $product;
$rating_count = $product->get_rating_count();
if ($rating_count > 0) {
    ?>
    <div class="woocommerce-product-rating">
	<?php
	    echo woocommerce_template_loop_rating();
	    if (Tbp_Public::$is_archive === false && !is_product_category() && !is_product_tag()):
	?>
	<a href="#reviews" class="woocommerce-review-link" rel="nofollow">(<?php printf(__('%s customer review', 'themify'), $rating_count); ?>)</a>
	<?php endif; ?>
    </div>
    <?php
}
elseif(isset($args['mod_name']) && (Tbp_Utils::$isActive===true || Themify_Builder::$frontedit_active===true)){?>
	<div class="tbp_empty_module">
	    <?php echo Themify_Builder_Model::get_module_name($args['mod_name']);?>
	</div>
<?php } 
$args=null;