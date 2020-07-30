<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
/**
 * Template Add To Cart
 * 
 * Access original fields: $args['mod_settings']
 * @author Themify
 */
if (themify_is_woocommerce_active()):
    $fields_default = array(
	'quantity' => 'yes',
	'label' =>'',
	'label_variable' =>'',
	'label_outofstock' =>'',
	'fullwidth' => '',
	'css' => '',
	'animation_effect' => ''
    );
    $fields_args = wp_parse_args($args['mod_settings'], $fields_default);
    unset($args['mod_settings']);
    $element_id =!empty($args['element_id'])?'tb_' . $args['element_id']:$args['module_ID'];
    $builder_id=$args['builder_id'];
    $mod_name=$args['mod_name'];
    $container_class = apply_filters('themify_builder_module_classes', array(
	'module',
	'module-' . $mod_name,
	$element_id,
	$fields_args['css'],
	self::parse_animation_effect($fields_args['animation_effect'], $fields_args)
	    ), $mod_name,$element_id, $fields_args);

    if(!empty($fields_args['fullwidth']) && 'no' !== $fields_args['fullwidth']){
	$container_class[] = 'buttons-fullwidth';
    }
    if(!empty($fields_args['global_styles']) && Themify_Builder::$frontedit_active===false){
	$container_class[] = $fields_args['global_styles'];
    }
    $container_props = apply_filters('themify_builder_module_container_props', array(
	'class' => implode(' ', $container_class),
	    ), $fields_args, $mod_name,$element_id);
    $args=null;

	/* this module is used on both single and archive product pages */
	$is_single = is_product();

    ?>
    <!-- Add To Cart module -->
    <div <?php echo self::get_element_attributes(self::sticky_element_props($container_props, $fields_args)); ?>>
	<?php
	$container_props=$container_class=null;
	do_action('themify_builder_background_styling',$builder_id,array('styling'=>$fields_args,'mod_name'=>$mod_name),$element_id,'module');
	$the_query = Tbp_Utils::get_wc_actual_query();
	if ($the_query===null || $the_query->have_posts()){
	    if($the_query!==null){
			$the_query->the_post();
	    }

		global $product;

		if ( $product->get_stock_status() === 'outofstock' ) {
			$label = $fields_args['label_outofstock'];
		} else if ( $product->get_type() === 'variable' ) {
			$label = $fields_args['label_variable'];
		} else {
			$label = $fields_args['label'];
		}

	    if ( ! empty( $label ) ) {
			TB_Add_To_Cart_Module::$cartText = $label;
			if ( $is_single ) {
				add_filter( 'woocommerce_product_single_add_to_cart_text', array( 'TB_Add_To_Cart_Module', 'changeCartText' ) );
			} else {
				add_filter( 'woocommerce_product_add_to_cart_text', array( 'TB_Add_To_Cart_Module', 'changeCartText' ) );
			}
	    }
	    if ( $fields_args['quantity'] !== 'yes' ) {
			add_filter( 'wc_get_template', array( 'TB_Add_To_Cart_Module', 'filterQuantityInput' ), 10, 5 );
		} else {
			// on archive pages WC by default doesn't show Quantity field
			if ( ! $is_single && $product->get_type() === 'simple' ) { ?>
				<div class="quantity">
					<input type="number" class="input-text qty text filled" step="1" min="1" max="" name="quantity" value="1" title="Qty" size="4" inputmode="numeric" onchange="this.parentElement.nextElementSibling.setAttribute( 'data-quantity', this.value );">
				</div>
			<?php }
		}

		if ( $is_single ) {
			woocommerce_template_single_add_to_cart();
		} else {
			woocommerce_template_loop_add_to_cart();
		}

	    if ( $the_query !== null ) {
			wp_reset_postdata();
	    }

		// reset filters
		remove_filter( 'woocommerce_product_single_add_to_cart_text', array( 'TB_Add_To_Cart_Module', 'changeCartText' ) );
		remove_filter( 'woocommerce_product_add_to_cart_text', array( 'TB_Add_To_Cart_Module', 'changeCartText' ) );
		remove_filter( 'wc_get_template', array( 'TB_Add_To_Cart_Module', 'filterQuantityInput' ) );
	}
	?>
    </div>
    <!-- /Add To Cart module -->
<?php endif; ?>
