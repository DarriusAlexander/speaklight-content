<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
/**
 * Template Related Products
 * 
 * Access original fields: $args['mod_settings']
 * @author Themify
 */
if (themify_is_woocommerce_active()):
    $fields_default = array(
	'heading' => '',
	'layout' => 'grid3',
	'limit' => '',
	'css' => '',
	'animation_effect' => ''
    );
    $fields_args = wp_parse_args($args['mod_settings'], $fields_default);
    unset($args['mod_settings']);
    $mod_name=$args['mod_name'];
    $element_id =!empty($args['element_id'])?'tb_' . $args['element_id']:$args['module_ID'];
    $builder_id=$args['builder_id'];
    $container_class = apply_filters('themify_builder_module_classes', array(
	'module',
	'module-' . $mod_name,
	'woocommerce',
	$element_id,
	$fields_args['css'],
	self::parse_animation_effect($fields_args['animation_effect'], $fields_args)
	    ), $mod_name, $element_id, $fields_args);
 
    if(!empty($fields_args['global_styles']) && Themify_Builder::$frontedit_active===false){
	$container_class[] = $fields_args['global_styles'];
    }
    $container_props = apply_filters('themify_builder_module_container_props', array(
	'class' => implode(' ', $container_class),
	    ), $fields_args, $mod_name, $element_id);
    $args=null;
    ?>
    <!-- Related Products module -->
    <div <?php echo self::get_element_attributes(self::sticky_element_props($container_props, $fields_args)); ?>>
	<?php
	$container_props=$container_class=null; 
	do_action('themify_builder_background_styling',$builder_id,array('styling'=>$fields_args,'mod_name'=>$mod_name),$element_id,'module');
	global $product;
	$the_query = Tbp_Utils::get_wc_actual_query();
	if ($the_query===null || $the_query->have_posts()) :
	    if($the_query!==null){
		$the_query->the_post();
	    }
	    global $themify, $product;
	    $col = 3;
	    if ($fields_args['layout'] === 'grid2') {
		$col = 2;
	    } elseif ($fields_args['layout'] === 'grid4') {
		$col = 4;
	    }
	    
	    $attr = array(
		'posts_per_page' => empty($fields_args['limit']) ? $col : $fields_args['limit'],
		'columns' => $col,
		'orderby' => 'rand',
		'order' => 'desc'
	    );
	    // Get visible related products then sort them at random.
	    $related_products = array_filter(array_map('wc_get_product', wc_get_related_products($product->get_id(), $attr['posts_per_page'], $product->get_upsell_ids())), 'wc_products_array_filter_visible');
	    // Handle orderby.
	    $related_products = wc_products_array_orderby($related_products, $attr['orderby'], $attr['order']);
	    if (!empty($related_products)) :
		$isLoop=$ThemifyBuilder->in_the_loop===true;
		$ThemifyBuilder->in_the_loop = true;
		wc_set_loop_prop('name', 'related');
		wc_set_loop_prop('columns', apply_filters('woocommerce_related_products_columns', $attr['columns']));
	    ?>
	        <section class="related tbp_posts_wrap">
		    <?php if ($fields_args['heading'] !== ''): ?>
			<h2><?php echo $fields_args['heading']; ?></h2>
		    <?php endif; ?>
		    <?php woocommerce_product_loop_start(); ?>

		    <?php foreach ($related_products as $rel) : ?>

			<?php
			$post_object = get_post($rel->get_id());

			setup_postdata($GLOBALS['post'] = &$post_object);

			wc_get_template_part('content', 'product');
			?>

		    <?php endforeach; ?>

		    <?php 
			woocommerce_product_loop_end(); 
			$ThemifyBuilder->in_the_loop = $isLoop;
		    ?>

	        </section>
	    <?php endif;
	    if($the_query!==null){
		wp_reset_postdata();
	    }
	endif;
	?>
	<?php if(empty($related_products) && (Tbp_Utils::$isActive===true || Themify_Builder::$frontedit_active===true)):?>
	    <div class="tbp_empty_module">
		<?php echo Themify_Builder_Model::get_module_name($mod_name);?>
	    </div>
	<?php endif; ?>
    </div>
    <!-- /Related Products module -->
<?php endif; ?>
