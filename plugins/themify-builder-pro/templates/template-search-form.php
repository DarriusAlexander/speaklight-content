<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
/**
 * Template Search Form
 * 
 * Access original fields: $args['mod_settings']
 * @author Themify
 */
if (TFCache::start_cache($args['mod_name'], self::$post_id, array('ID' => $args['element_id']))):
    $fields_default = array(
	'placeholder' => '',
	'button' => 'yes',
	'icon' => 'icon',
	'button_icon'=>'no',
	'button_t' => '',
	'css' => '',
	'animation_effect' => '',
	'post_type' => 'any'
    );
    $fields_args = wp_parse_args($args['mod_settings'], $fields_default);
    unset($args['mod_settings']);
    $mod_name=$args['mod_name'];
    $element_id =!empty($args['element_id'])?'tb_' . $args['element_id']:$args['module_ID'];
    $builder_id=$args['builder_id'];
    $container_class = apply_filters( 'themify_builder_module_classes', array(
	'module',
	'module-' . $mod_name,
	$element_id,
	$fields_args['css'],
	self::parse_animation_effect($fields_args['animation_effect'], $fields_args)
    ), $mod_name, $element_id, $fields_args );

    if($fields_args['button_icon']==='yes'){
	$container_class[] = 'tb_search_overlay';
    }
    if(!empty($fields_args['global_styles']) && Themify_Builder::$frontedit_active===false){
	$container_class[] = $fields_args['global_styles'];
    }
    $container_props = apply_filters( 'themify_builder_module_container_props', array(
	'class' => implode( ' ', $container_class ),
    ), $fields_args, $mod_name, $element_id);
    $args=null;
    ?>
    <!-- Search Form module -->
    <div <?php echo self::get_element_attributes(self::sticky_element_props($container_props,$fields_args)); ?>>
	<?php $container_props=$container_class=null; 
	    do_action('themify_builder_background_styling',$builder_id,array('styling'=>$fields_args,'mod_name'=>$mod_name),$element_id,'module');
	    do_action( 'pre_get_search_form' );
	?>
	<form role="search" method="get" class="searchform" action="<?php echo  esc_url( home_url( '/' ) ); ?>">
	    <?php if ( $fields_args['button_icon'] === 'yes' ): ?>
		    <span class="tbp_icon_search overlay"><?php echo $fields_args['icon'] === ''; ?></span>
	    <?php endif; ?>
	    <input type="text" name="s" title="<?php esc_attr_e( $fields_args['placeholder'] ); ?>" placeholder="<?php esc_attr_e( $fields_args['placeholder'] ); ?>" value="<?php echo get_search_query(); ?>" />
	    <?php if(!empty($fields_args['post_type']) && 'any' !== $fields_args['post_type']): ?>
	    <input type="hidden" name="post_type" value="<?php echo $fields_args['post_type']; ?>" />
	<?php endif; ?>
		<?php if ( $fields_args['button'] === 'yes' ): ?>
		    <button type="submit" class="module-buttons">
		    <?php echo $fields_args['icon'] === 'text' && $fields_args['button_t'] !== '' ? esc_attr( $fields_args['button_t'] ) : '<span class="tbp_icon_search"></span>'; ?>
		</button>
	    <?php endif; ?>
	</form>
    </div>
    <!-- /Search Form module -->
<?php endif; ?>
<?php TFCache::end_cache(); ?>
