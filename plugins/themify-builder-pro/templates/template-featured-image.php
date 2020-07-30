<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
/**
 * Template Featured Image
 * 
 * Access original fields: $args['mod_settings']
 * @author Themify
 */
$fields_default = array(
    'image_w' => '',
    'image_h' => '',
    'auto_fullwidth' => false,
    'appearance_image' => '',
    'link' => 'permalink',
    'custom_link' => '',
    'open_link' => 'regular',
    'lightbox' => '',
    'lightbox_w' => '',
    'lightbox_h' => '',
    'lightbox_w_unit' => '%',
    'lightbox_h_unit' => '%',
    'fallback_s' => 'no',
    'fallback_i' => '',
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
    'module-image',
    'module-' .$mod_name,
    $element_id,
    $fields_args['css'],
    $fields_args['appearance_image'],
    self::parse_animation_effect($fields_args['animation_effect'], $fields_args)
    ), $mod_name, $element_id, $fields_args );

if ($fields_args['auto_fullwidth']=='1') {
    $container_class[]=' auto_fullwidth';
} 
if(!empty($fields_args['global_styles']) && Themify_Builder::$frontedit_active===false){
    $container_class[] = $fields_args['global_styles'];
}
$container_props = apply_filters('themify_builder_module_container_props', array(
    'class' =>  implode(' ', $container_class),
), $fields_args, $mod_name, $element_id);
$args=null;
?>
<!-- Featured Image module -->
<div <?php echo self::get_element_attributes(self::sticky_element_props($container_props,$fields_args)); ?>>
    <div class="image-wrap" itemprop="image">
       <?php 
        $container_props=$container_class=null;
	do_action('themify_builder_background_styling',$builder_id,array('styling'=>$fields_args,'mod_name'=>$mod_name),$element_id,'module');
	$the_query = Tbp_Utils::get_actual_query();
	$hasFallback='yes' === $fields_args['fallback_s'] && '' !== $fields_args['fallback_i'];
	if ($the_query===null || $the_query->have_posts() || $hasFallback===true) {
	    if($the_query!==null){
		$the_query->the_post();
	    }
	    $hasImage = has_post_thumbnail();
	    if($hasImage===true || $hasFallback===true){
		$hasLink=$fields_args['link']!=='none';
		$post_title = esc_attr(get_the_title());
		$img='';
		$width = '' !== $fields_args['image_w'] ? 'w=' . esc_attr( $fields_args['image_w'] ) . '&' : '';
		$height = '' !== $fields_args['image_h'] ? 'h=' . esc_attr( $fields_args['image_h'] ) . '&' : '';
		if($hasLink===true){
		    $link_attr=Tbp_Utils::getLinkParams($fields_args,($hasImage===false && $hasFallback===true && 'media' === $fields_args['link']?esc_url($fields_args['fallback_i']):''));
		    $hasLink=isset($link_attr['href']);
		}
		?>
		<?php if ($hasLink===true):?>
		    <a <?php echo self::get_element_attributes($link_attr); ?>> 
		<?php endif;?>
			
		<?php if($hasImage===true):?>
			
		    <?php if(Themify_Builder_Model::is_img_php_disabled()):?>
			<?php 
			$attachment_id = get_post_thumbnail_id();
			$img= !empty($attachment_id)?wp_get_attachment_image($attachment_id,array($fields_args['image_w'],$fields_args['image_h'])):apply_filters('themify_image_make_responsive_image','<img itemprop="imageObject" src="' . esc_url(get_the_post_thumbnail_url()) . '" alt="'.$post_title.'" width="' . $fields_args['image_w'] . '" height="' . $fields_args['image_h'] . '" />');
			?>
		    <?php else:?>
			<?php $img= themify_get_image($width . $height . 'alt='.$post_title.'&ignore=true');?>
		    <?php endif;?>  
			
		<?php elseif($hasFallback===true):?>
			<?php $img=themify_get_image($width . $height . 'src=' . esc_url($fields_args['fallback_i']) . '&alt='.$post_title.'&ignore=true');?>
		<?php endif;?>
			
		<?php if($img!==''):?>
		    <?php echo $img?>
		<?php elseif(Themify_Builder::$frontedit_active===true):?>
			<img itemprop="imageObject" alt="<?php echo $post_title?>" src="<?php echo THEMIFY_BUILDER_URI?>/img/image-placeholder.jpg"/>
		<?php endif;?>
			
		<?php if ($hasLink===true):?>
		    </a>
		<?php endif;?>
	
	    <?php
	    }
	    if($the_query!==null){
		wp_reset_postdata();
	    }
	}
	?>
	</div>
    </div>
<!-- /Featured Image module -->
