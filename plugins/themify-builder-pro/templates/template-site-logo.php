<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
/**
 * Template Site Logo
 * 
 * Access original fields: $args['mod_settings']
 * @author Themify
 */
if (TFCache::start_cache($args['mod_name'], self::$post_id, array('ID' => $args['element_id']))):
    $fields_default = array(
	'display' => 'text',
	'url_image' => '',
	'width_image' => 100,
	'height_image' => 100,
	'link' => 'siteurl',
	'custom_url' => '',
	'html_tag' => '',
	'css' => '',
	'animation_effect' => '',
    );
    $fields_args = wp_parse_args($args['mod_settings'], $fields_default);
    unset($args['mod_settings']);
    $mod_name = $args['mod_name'];
    $element_id =!empty($args['element_id'])?'tb_' . $args['element_id']:$args['module_ID'];
    $builder_id = $args['builder_id'];
    $container_class = apply_filters('themify_builder_module_classes', array(
	'module',
	'module-' . $mod_name,
	$element_id,
	$fields_args['css'],
	self::parse_animation_effect($fields_args['animation_effect'], $fields_args)
	    ), $mod_name, $element_id, $fields_args);
    
    if (!empty($fields_args['global_styles']) && Themify_Builder::$frontedit_active === false) {
	$container_class[] = $fields_args['global_styles'];
    }
    $container_props = apply_filters('themify_builder_module_container_props', array(
	'class' => implode(' ', $container_class),
	    ), $fields_args, $mod_name, $element_id);
    $args = null;
    if ('image' === $fields_args['display'] && '' !== $fields_args['url_image']) {
	
	$width_image = $fields_args['width_image'] !== '' ? $fields_args['width_image'] : get_option($preset . '_size_w');
	$height_image = $fields_args['height_image'] !== '' ? $fields_args['height_image'] : get_option($preset . '_size_h');
	if (Themify_Builder_Model::is_img_php_disabled()) {
	    $upload_dir = wp_upload_dir();
	    $attachment_id = themify_get_attachment_id_from_url($fields_args['url_image'], $upload_dir['baseurl']);
	    $image =!empty($attachment_id)?wp_get_attachment_image($attachment_id, $preset):apply_filters('themify_image_make_responsive_image', '<img src="' . esc_url($fields_args['url_image']) . '" alt="" width="' . $width_image. '" height="' . $height_image. '"/>');
	} else {
	    $image = themify_get_image('src=' . esc_url($fields_args['url_image']) . '&w=' . $width_image . '&h=' . $height_image. '&alt=&ignore=true');
	}
    } else {
	$image = get_bloginfo('name');
    }

    $url = 'siteurl' === $fields_args['link'] ? site_url() : ( 'custom' === $fields_args['link'] && '' !== $fields_args['custom_url'] ? esc_url($fields_args['custom_url']) : false);
    ?>
    <!-- Site Logo module -->
    <div <?php echo self::get_element_attributes(self::sticky_element_props($container_props, $fields_args));?>>
        <div class="site-logo-inner">
	    <?php $container_props = $container_class = null; 
		do_action('themify_builder_background_styling',$builder_id,array('styling'=>$fields_args,'mod_name'=>$mod_name),$element_id,'module');
	    ?>
	    <?php if (!empty($fields_args['html_tag'])): ?>
		<<?php echo $fields_args['html_tag'] ?>>
	    <?php endif; ?>

	    <?php if ($url !== false): ?>
		<a href="<?php echo $url ?>">
		<?php endif; ?>

		<?php echo $image; ?>
		<?php if ($url !== false): ?>
		</a>
	    <?php endif; ?>
	    <?php if (!empty($fields_args['html_tag'])): ?>
		</<?php echo $fields_args['html_tag'] ?>>
	    <?php endif; ?>
        </div>
    </div>
    <!-- /Site Logo module -->
<?php endif; ?>
<?php TFCache::end_cache(); ?>
