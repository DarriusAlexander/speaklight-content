<?php global $product; 
      $isExist=isset($args['mod_name']) && (Tbp_Utils::$isActive===true || Themify_Builder::$frontedit_active===true)?false:null;
?>
<div class="product_meta">
    <?php do_action('woocommerce_product_meta_start'); ?>
    <?php if ($args['enable_sku'] === 'yes' && wc_product_sku_enabled() && ( ($sku = $product->get_sku()) || $product->is_type('variable') )) : ?>
        <span class="sku_wrapper">
	    <?php if ($args['sku'] !== '' && $sku): ?>
		<?php echo $args['sku']; ?>: 
	    <?php endif; ?>
	    <?php
		if($isExist===false){
		    $isExist=true;
		}
		echo $sku;
	    ?>
        </span>
    <?php endif; ?>
    <?php if ($args['enable_cat'] === 'yes'): ?>

	<?php 
	    $output = wc_get_product_category_list($product->get_id(), ', ', '<span class="posted_in">' . ($args['cat']!==''?$args['cat']. ': ':''), '</span>');
	    echo $output;
	    if($isExist===false){
		$isExist = !empty($output);
	    }
	    $output=null;
	?>

    <?php endif; ?>
    <?php if ($args['enable_tag'] === 'yes'): ?>

	<?php $output= wc_get_product_tag_list($product->get_id(), ', ', '<span class="tagged_as">' .($args['tag']!==''?$args['tag']. ': ':''), '</span>');
	    echo $output;
	    if($isExist===false){
		$isExist = !empty($output);
	    }
	    $output=null;
	?>

    <?php endif; ?>
    <?php do_action('woocommerce_product_meta_end'); ?>

</div>
<?php if($isExist===false):?>
    <div class="tbp_empty_module">
	<?php echo Themify_Builder_Model::get_module_name($args['mod_name']);?>
    </div>
<?php endif; $args=null;?>