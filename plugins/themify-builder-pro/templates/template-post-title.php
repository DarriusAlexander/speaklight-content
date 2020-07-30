<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
/**
 * Template Post Title
 * 
 * Access original fields: $args['mod_settings']
 * @author Themify
 */
$fields_default = array(
    'link' => 'permalink',
    'open_link' => 'regular',
    'lightbox_w_unit' => '%',
    'lightbox_h_unit' => '%',
    'html_tag' => 'h2',
    'no_follow' => 'no',
    'css' => '',
    'animation_effect' => ''
);
$fields_args = wp_parse_args($args['mod_settings'], $fields_default);
unset($args['mod_settings']);
$mod_name=$args['mod_name'];
$builder_id = $args['builder_id'];
$element_id =!empty($args['element_id'])?'tb_' . $args['element_id']:$args['module_ID'];
$container_class = apply_filters( 'themify_builder_module_classes', array(
    'module',
    'module-' . $mod_name,
    $element_id,
    $fields_args['css'],
    self::parse_animation_effect($fields_args['animation_effect'], $fields_args)
), $mod_name, $element_id, $fields_args );
if(!empty($fields_args['global_styles']) && Themify_Builder::$frontedit_active===false){
    $container_class[] = $fields_args['global_styles'];
}
$container_props = apply_filters( 'themify_builder_module_container_props', array(
    'class' => implode( ' ', $container_class ),
), $fields_args, $mod_name, $element_id );
$args=null;
?>
<!-- Post Title module -->
<div <?php echo self::get_element_attributes(self::sticky_element_props($container_props,$fields_args));?>>
   <?php $container_props=$container_class=null; 
	do_action('themify_builder_background_styling',$builder_id,array('styling'=>$fields_args,'mod_name'=>$mod_name),$element_id,'module');
	$the_query = Tbp_Utils::get_actual_query();
	if ( $the_query===null || $the_query->have_posts() ){
	    if($the_query!==null){
		$the_query->the_post();
	    }
	    themify_before_post_title(); 
	    self::retrieve_template('partials/title.php', $fields_args);
	    themify_after_post_title(); 
	    if($the_query!==null){
		wp_reset_postdata();
	    }
	}
    ?>
</div>
<!-- /Post Title module -->