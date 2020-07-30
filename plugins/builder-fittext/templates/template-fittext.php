<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
/**
 * Template FitText
 * 
 * Access original fields: $args['mod_settings']
 * @author Themify
 */
if (TFCache::start_cache($args['mod_name'], self::$post_id, array('ID' => $args['module_ID']))):
    $fields_default = array(
        'fittext_text' => '',
        'fittext_link' => '',
        'fittext_params' => '',
        'font_family' => '',
	'font_family_w'=>'400,700',
        'add_css_fittext' => '',
        'js_params' => array(),
        'animation_effect' => ''
    );

    $fields_args = wp_parse_args($args['mod_settings'], $fields_default);
    unset($args['mod_settings']);
    $fields_args['fittext_params']  = $fields_args['fittext_params'] !== '' ?array_values( explode( '|', $fields_args['fittext_params'] ) ):array();
    
    $fields_args['fittext_params'] = isset($fields_args['fittext_params'][0])?$fields_args['fittext_params'][0]:false;//convert old checkbox type to radio type
    $container_class = apply_filters('themify_builder_module_classes', array(
        'module', 'module-' . $args['mod_name'], $args['module_ID'], $fields_args['add_css_fittext'], self::parse_animation_effect($fields_args['animation_effect'], $fields_args)
                    ), $args['mod_name'], $args['module_ID'], $fields_args);
    if(!empty($args['element_id'])){
	$container_class[] = 'tb_'.$args['element_id'];
    }
    if(!empty($fields_args['global_styles']) && Themify_Builder::$frontedit_active===false){
	$container_class[] = $fields_args['global_styles'];
    }
    $link_target = $link_class = false;
    if ($fields_args['fittext_params']==='lightbox') {
        $link_class = 'themify_lightbox';
    } elseif ($fields_args['fittext_params']==='newtab') {
        $link_target = '_blank';
    }

    $container_props = apply_filters('themify_builder_module_container_props', array(
        'id' => $args['module_ID'],
        'class' => implode(' ', $container_class),
            ), $fields_args, $args['mod_name'], $args['module_ID']);
    
    $f=$fields_args['font_family'];
    if($f!=='' && $f!=='default'){
	$f.=':'.$fields_args['font_family_w'];
	$f = esc_attr($f);
    }
    else{
	$f=false;
    }
    ?>
    <!-- module fittext -->
    <div <?php echo self::get_element_attributes($container_props); ?><?php if($f):?> data-font-family="<?php echo $f; ?>"<?php endif;?>>
	<?php $container_props=$container_class=null;?>
        <?php do_action('themify_builder_before_template_content_render'); ?>

        <?php if ('' !== $fields_args['fittext_link']) : ?>
            <a href="<?php echo $fields_args['fittext_link']; ?>"<?php if ($link_class !== false): ?> class="<?php echo $link_class; ?>"<?php endif; ?><?php if ($link_target !== false): ?> target="<?php echo $link_target; ?>"<?php endif; ?>>
            <?php endif; ?>

            <span><?php echo $fields_args['fittext_text']; ?></span>

            <?php if ('' !== $fields_args['fittext_link']) : ?>
            </a>
        <?php endif; ?>

        <?php do_action('themify_builder_after_template_content_render'); ?>
    </div>
    <!-- /module fittext -->
<?php endif; ?>
<?php TFCache::end_cache(); ?>