<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
/**
 * Template Product Image
 * 
 * Access original fields: $args['mod_settings']
 * @author Themify
 */
if (themify_is_woocommerce_active()):
    $fields_default = array(
	'thumb_image' => 'thumb_img_bottom',
	'image_w' => '',
	'image_h' => '',
	'auto_fullwidth' => false,
	'appearance_image' => '',
	'sale_b' => 'on',
	'badge_pos' => 'left',
	'link' => 'permalink',
	'open_link' => 'regular',
	'fallback_s' => 'no',
	'fallback_i' => '',
	'zoom' => 'yes',
	'css' => '',
	'animation_effect' => ''
    );
    if (isset($args['mod_settings']['appearance_image'])) {
	$args['mod_settings']['appearance_image'] = self::get_checkbox_data($args['mod_settings']['appearance_image']);
    }
    $fields_args = wp_parse_args($args['mod_settings'], $fields_default);
    unset($args['mod_settings']);
    $mod_name=$args['mod_name'];
    $element_id =!empty($args['element_id'])?'tb_' . $args['element_id']:$args['module_ID'];
    $builder_id=$args['builder_id'];
    $container_class = apply_filters('themify_builder_module_classes', array(
	'module',
	'module-' . $mod_name,
	$element_id,
	$fields_args['css'],
	$fields_args['appearance_image'],
	$fields_args['thumb_image'],
	self::parse_animation_effect($fields_args['animation_effect'], $fields_args)
	    ), $mod_name, $element_id, $fields_args);

    if (Tbp_Utils::$isLoop !== true) {
	if ($fields_args['auto_fullwidth'] == '1') {
	    $container_class[] = ' auto_fullwidth';
	}
	$container_class[] = $fields_args['appearance_image'];
    }
    if(!empty($fields_args['global_styles']) && Themify_Builder::$frontedit_active===false){
	$container_class[] = $fields_args['global_styles'];
    }
	if('no' === $fields_args['zoom']){
		$container_class[] = 'tbp_disable_wc_zoom';
	}
    $container_props = apply_filters('themify_builder_module_container_props', array(
	'class' => implode(' ', $container_class),
	    ), $fields_args, $mod_name, $element_id);

    $args = null;
    ?>
    <!-- Product Image module -->
    <div <?php echo self::get_element_attributes(self::sticky_element_props($container_props, $fields_args)); ?>>
	<?php
	$container_props = $container_class = null;
	do_action('themify_builder_background_styling',$builder_id,array('styling'=>$fields_args,'mod_name'=>$mod_name),$element_id,'module');
	$the_query = Tbp_Utils::get_wc_actual_query();
	if ($the_query === null || $the_query->have_posts()) {
	    if ($the_query !== null) {
		$the_query->the_post();
	    }
	    if (Tbp_Utils::$isLoop === true) {
		self::retrieve_template('wc/loop/image.php', $fields_args);
	    } else {
		self::retrieve_template('wc/single/image.php', $fields_args);
	    }
	    if ($the_query !== null) {
		wp_reset_postdata();
	    }
	}
	?>
    </div>
    <!-- /Product Image module -->
<?php endif; ?>
