<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
/**
 * Template Product Reviews
 * 
 * Access original fields: $args['mod_settings']
 * @author Themify
 */
if (themify_is_woocommerce_active()):
    $fields_default = array(
	'description' => 'yes',
	'additionaly'=>'yes',
	'reviews'=>'yes',
	'css' => '',
	'animation_effect' => ''
    );
    $fields_args = wp_parse_args($args['mod_settings'], $fields_default);
    unset($args['mod_settings']);
    $elementId=$args['element_id'];
    
    $mod_name=$args['mod_name'];
    $element_id =!empty($args['element_id'])?'tb_' . $args['element_id']:$args['module_ID'];
    $builder_id=$args['builder_id'];
    $container_class = apply_filters('themify_builder_module_classes', array(
	'module',
	'module-' . $mod_name,
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
    <!-- Product Reviews module -->
    <div <?php echo self::get_element_attributes(self::sticky_element_props($container_props, $fields_args)); ?>>
	<?php
	$container_props=$container_class=null;
	global $woocommerce;
	do_action('themify_builder_background_styling',$builder_id,array('styling'=>$fields_args,'mod_name'=>$mod_name),$element_id,'module');
	$the_query = Tbp_Utils::get_wc_actual_query();
	if ($the_query===null ||$the_query->have_posts()) {
	    if($the_query!==null){
		$the_query->the_post();
	    }
	    TB_Product_Reviews_Module::$elId=$elementId;
	    TB_Product_Reviews_Module::$hasDescription=$fields_args['description']==='yes';
	    TB_Product_Reviews_Module::$hasAdditionaly=$fields_args['additionaly']==='yes';
	    TB_Product_Reviews_Module::$hasReviews=$fields_args['reviews']==='yes';
	    if(Themify_Builder::$frontedit_active===true){
		global $withcomments;
		$withcomments = true; 
	    }
	    add_filter( 'woocommerce_product_tabs', array('TB_Product_Reviews_Module','getTabs') );
	    ob_start();
		woocommerce_output_product_data_tabs();
		remove_filter( 'woocommerce_product_tabs', array('TB_Product_Reviews_Module','getTabs') );
		$output = ob_get_clean();
	    ?>
	    <div class="product<?php echo true === TB_Product_Reviews_Module::$singleTab ? ' tbp_single_tab' : ''; ?>">
		<?php echo $output;$output=null; ?>
	    </div>
	    <?php
	    if($the_query!==null){
		wp_reset_postdata();
	    }
	}
	?>
    </div>
    <!-- /Product Reviews module -->
<?php endif; ?>
