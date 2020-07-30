<?php
if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly
/**
 * Template Masked Image
 * 
 * Access original fields: $args['mod_settings']
 * @author Themify
 */
if (TFCache::start_cache($args['mod_name'], self::$post_id, array('ID' => $args['module_ID']))):
	$fields_default = array(
		'mod_title_image' => '',
		'style_image' => 'image-left',
		'url_image' => '',
		'mask_type' => 'image',
		'mask_image' => '',
		'mask_icon_data' => '',
		'mask_feather' => '',
		'mask_flip' => 'none',
		'transparency_effect' => '',
		'width_image' => '',
		'auto_fullwidth' => false,
		'height_image' => '',
		'image_size_image' => '',
		'title_image' => '',
		'link_image' => '#',
		'param_image' => '',
		'lightbox_width' => '',
		'lightbox_height' => '',
		'lightbox_size_unit_width' => 'pixels',
		'lightbox_size_unit_height' => 'pixels',
		'alt_image' => '',
		'caption_image' => '',
		'gutter_image' => '',
		'gutter_h_image' => '',
		'css_image' => '',
		'animation_effect' => ''
	);

    $fields_args = wp_parse_args( $args['mod_settings'], $fields_default );
    unset( $args['mod_settings'] );
    $container_class = apply_filters( 'themify_builder_module_classes', array(
		    'module', 'module-' . $args['mod_name'], $args['module_ID'], $fields_args['style_image'], $fields_args['css_image'], self::parse_animation_effect( $fields_args['animation_effect'], $fields_args ),
	    ), $args['mod_name'], $args['module_ID'], $fields_args );
    if(!empty($args['element_id'])){
	$container_class[] = 'tb_'.$args['element_id'];
    }
    if(!empty($fields_args['global_styles']) && Themify_Builder::$frontedit_active===false){
	$container_class[] = $fields_args['global_styles'];
    }
    if ( $fields_args['auto_fullwidth'] == '1' && ( $fields_args['style_image'] === 'image-top' || $fields_args['style_image'] === 'image-center' ) ) {
	    $container_class[]= 'auto_fullwidth';
    }
    $lightbox_size_unit_width = $fields_args['lightbox_size_unit_width'] === 'pixels' ? 'px' : '%';
    $lightbox_size_unit_height = $fields_args['lightbox_size_unit_height'] === 'pixels' ? 'px' : '%';

    $lightbox = $fields_args['param_image'] === 'lightbox';
    $newtab = ! $lightbox && $fields_args['param_image'] === 'newtab';
    $lightbox_data = ! empty( $fields_args['lightbox_width'] ) || ! empty( $fields_args['lightbox_height'] )
				    ? sprintf( ' data-zoom-config="%s|%s"', $fields_args['lightbox_width'] . $lightbox_size_unit_width, $fields_args['lightbox_height'] . $lightbox_size_unit_height)
				    : false;
    $image_alt = '' !== $fields_args['alt_image'] ? $fields_args['alt_image'] : wp_strip_all_tags( $fields_args['caption_image'] );
    $image_title = $fields_args['title_image'];
    if ( $image_alt === '' ) {
	    $image_alt = $image_title;
    }
    if ( Themify_Builder_Model::is_img_php_disabled() ) {
	    // get image preset
	    global $_wp_additional_image_sizes;
	    $preset = $fields_args['image_size_image'] !== '' ? $fields_args['image_size_image'] : themify_builder_get( 'setting-global_feature_size', 'image_global_size_field' );
	    if ( isset( $_wp_additional_image_sizes[ $preset ] ) && $fields_args['image_size_image'] !== '') {
		    $width_image = (int) $_wp_additional_image_sizes[$preset]['width'];
		    $height_image = (int) $_wp_additional_image_sizes[$preset]['height'];
	    } else {
		    $width_image = $fields_args['width_image'] !== '' ? $fields_args['width_image'] : get_option($preset . '_size_w');
		    $height_image = $fields_args['height_image'] !== '' ? $fields_args['height_image'] : get_option($preset . '_size_h');
	    }
	    $upload_dir = wp_upload_dir();
	    $base_url = $upload_dir['baseurl'];
	    $attachment_id = themify_get_attachment_id_from_url( $fields_args['url_image'], $base_url );
	    $class = $attachment_id ? 'wp-image-' . $attachment_id : '';
	    $image = '<img src="' . esc_url( $fields_args['url_image'] ) . '" alt="' . esc_attr( $image_alt ) . ( ! empty( $image_title ) ? ( '" title="' . esc_attr( $image_title ) ) : '' ) . '" width="' . $fields_args['width_image'] . '" height="' . $fields_args['height_image'] . '" class="' . $class . '">';
	    if ( ! empty( $attachment_id ) ) {
		    $image = wp_get_attachment_image( $attachment_id, $preset );
	    }
    } else {
	    $image = themify_get_image( 'src=' . esc_url($fields_args['url_image']) . '&w=' . $fields_args['width_image'] . '&h=' . $fields_args['height_image'] . '&alt=' . $image_alt . (!empty($image_title) ? ( '&title=' . $image_title ) : '' ) . '&ignore=true' );
    }

    $container_props = apply_filters('themify_builder_module_container_props', array(
	    'id' => $args['module_ID'],
	    'class' => implode(' ', $container_class),
	    'style' => 'visibility: hidden;'
    ), $fields_args, $args['mod_name'], $args['module_ID']);

if (!empty( $fields_args['mask_image'] ) ) {
    $mask = $fields_args['mask_image'];
    // icon mask
    if ( $fields_args['mask_type'] === 'icon' && ! empty( $fields_args['mask_icon_data'] ) ) {
	    $mask = 'data:image/svg+xml;charset=UTF-8,' . $fields_args['mask_icon_data'];
    }
}
else{
    $mask=null;
}
?>
<!-- module masked image -->
<div class="tb_slider_loader"></div>
<div <?php echo self::get_element_attributes( $container_props ); ?>>
	<?php $container_props=$container_class=null;?>
	<?php if ($fields_args['mod_title_image'] !== ''): ?>
	    <?php echo $fields_args['before_title'] . apply_filters( 'themify_builder_module_title', $fields_args['mod_title_image'], $fields_args ) . $fields_args['after_title']; ?>
	<?php endif; ?>

	<?php do_action('themify_builder_before_template_content_render'); ?>
	<?php if($mask!==null):?>
	    <div class="bmi-image-wrap">
		    <div class="bmi-image">
			    <a href="<?php echo esc_url( $fields_args['link_image'] ); ?>"
			       <?php if ( $lightbox ) : ?>class="lightbox-builder themify_lightbox"<?php echo $lightbox_data; ?><?php endif; ?>
			       <?php if ( $newtab ): ?> rel="noopener" target="_blank"<?php endif; ?>>
				    <?php echo $image; ?>
				    <canvas data-mask="<?php echo esc_attr( $mask ); ?>" data-gutter="<?php echo $fields_args['gutter_image']; ?>" data-gutter-h="<?php echo $fields_args['gutter_h_image']; ?>" data-transparency="<?php echo $fields_args['transparency_effect']; ?>" data-mask-flip="<?php echo $fields_args['mask_flip']; ?>" data-feather="<?php echo $fields_args['mask_feather']; ?>"></canvas>
			    </a>
		    </div>
	    </div>
	<?php endif;?>
	<?php if ( ! empty( $fields_args['caption_image'] ) || ! empty( $fields_args['title_image'] ) ) : ?>
	<div class="bmi-text-wrap"><div class="bmi-text"><?php if ( ! empty( $fields_args['title_image'] ) ) : ?><h3 class="image-title"><?php echo $fields_args['title_image']; ?></h3><?php endif; ?><?php if ( ! empty( $fields_args['caption_image'] ) ) : ?><div class="image-caption"><?php echo $fields_args['caption_image']; ?></div><?php endif; ?></div></div>
	<?php endif; ?>

	<?php do_action('themify_builder_after_template_content_render'); ?>

</div><!-- /module masked image -->
<?php endif; ?>
<?php TFCache::end_cache(); ?>