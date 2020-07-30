<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
/**
 * Template Post Meta
 * 
 * Access original fields: $args['mod_settings']
 * @author Themify
 */
$fields_default = array(
    'tab_content_post_meta' => array(),
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
<!-- Post Meta module -->
<div <?php echo self::get_element_attributes(self::sticky_element_props($container_props, $fields_args)); ?>>
    <?php
    $container_props=$container_class=null;
    do_action('themify_builder_background_styling',$builder_id,array('styling'=>$fields_args,'mod_name'=>$mod_name),$element_id,'module');
    $the_query = Tbp_Utils::get_actual_query();
    if ($the_query===null || $the_query->have_posts()){
	if($the_query!==null){
	    $the_query->the_post();
	}
	self::retrieve_template('partials/meta.php', array('meta' => $fields_args['tab_content_post_meta'],'mod_name'=>$mod_name));
	if($the_query!==null){
	    wp_reset_postdata();
	}
    }
    ?>
</div>
<!-- /Post Meta module -->
