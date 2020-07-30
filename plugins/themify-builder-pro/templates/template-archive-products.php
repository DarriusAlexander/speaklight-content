<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
/**
 * Template Archive Products
 * 
 * Access original fields: $args['mod_settings']
 * @author Themify
 */
if (themify_is_woocommerce_active()):
    $fields_default = array(
	'layout_product' => 'grid3',
	'masonry' => 'no',
	'sort' => 'no',
	'per_page' => get_option('posts_per_page'),
	'pagination' => 'yes',
	'pagination_option' => 'numbers',
	'next_link' => '',
	'prev_link' => '',
	'no_found'=>'',
	'offset'=>'',
	'archive_products' => array(),
	'css' => '',
	'animation_effect' => ''
    );
    if (isset($args['mod_settings']['archive_products']['image']['val']['appearance_image'])) {
	$args['mod_settings']['archive_products']['image']['val']['appearance_image'] = self::get_checkbox_data($args['mod_settings']['archive_products']['image']['val']['appearance_image']);
    }
    $fields_args = wp_parse_args($args['mod_settings'], $fields_default);
    unset($args['mod_settings']);
    $mod_name=$args['mod_name'];
    $element_id =!empty($args['element_id'])?'tb_' . $args['element_id']:'';
    $builder_id=$args['builder_id'];
    $container_class = apply_filters('themify_builder_module_classes', array(
	'module',
	'woocommerce',
	'module-image',
	'module-' . $mod_name,
	$element_id,
	$fields_args['css'],
	isset($fields_args['archive_products']['image']['val']['appearance_image']) ? $fields_args['archive_products']['image']['val']['appearance_image'] : '',
	self::parse_animation_effect($fields_args['animation_effect'], $fields_args)
	    ), $mod_name,$element_id,$fields_args);
    
    $masonry_class = $fields_args['masonry'] === 'yes' && in_array($fields_args['layout_product'], array('grid2', 'grid3', 'grid4', 'grid2_thumb'), true) ? 'tbp_masonry' : '';
   	if(isset($_POST['pageId']) && Themify_Builder::$frontedit_active===true){
		Tbp_Utils::get_wc_actual_query();
	}
    $per_page = (int)$fields_args['per_page'];
    $paged =  $fields_args['pagination'] === 'yes' || $fields_args['pagination'] === 'on'? self::get_paged_query() : 1;
    $query_args = array(
	'post_type' => 'product',
	'post_status' => 'publish',
	'ptb_disable'=>true,
	'tbp_aap' => true, // flag the query
	'posts_per_page' => $per_page,
	'paged' => $paged,
	'offset' => ( ( $paged - 1 ) * $per_page )
    );
    if($fields_args['sort'] === 'yes'){
		$ordering_args = WC()->query->get_catalog_ordering_args();
		$query_args['orderby'] = $ordering_args['orderby'];
		$query_args['order'] = $ordering_args['order'];
		if ( $ordering_args['meta_key'] ) {
			$query_args['meta_key'] = $ordering_args['meta_key'];
		}
    }else{
		$query_args['orderby'] = 'ID';
		$query_args['order'] = 'DESC';
    }
     if($fields_args['offset']!==''){
	$query_args['offset']+=(int)$fields_args['offset'];
    }
    $isProductArchive = Tbp_Public::$is_archive === true && (is_product_category() || is_product_tag());
    if ($isProductArchive === true) {
	$obj = get_queried_object();
	$query_args['tax_query'] = array(
	    array(
		'taxonomy' => is_product_category() ? 'product_cat' : 'product_tag',
		'field' => 'term_id',
		'terms' => $obj->term_id,
		'operator' => 'IN'
	    )
	);
    }
    if(isset($fields_args['builder_content']) && Tbp_Utils::$isLoop===true){
	$fields_args['builder_id']=$args['builder_id'];
	unset($fields_args['archive_products']);
	$isAPP=true;
	if(is_string($fields_args['builder_content'])){
	    $fields_args['builder_content']= json_decode($fields_args['builder_content'],true);
	}
	if (!empty($args['element_id'])) {
	    $container_class[] = 'themify_builder_content-' . $args['element_id'];
	}
    }
    else{
	$isAPP=null;
    }
    if(Tbp_Public::$isTemplatePage===true){
	 $query_args['ignore_sticky_posts']=true;
    }
    $container_props = apply_filters('themify_builder_module_container_props', array(
	'class' => implode(' ', $container_class),
    ), $fields_args, $mod_name,$element_id);
    
    $the_query = new WP_Query($query_args);
    $query_args=$args=NULL;
    ?>
    <!-- <?php echo $mod_name?> module -->
    <div <?php echo self::get_element_attributes(self::sticky_element_props($container_props, $fields_args)); ?>>
	<?php
	do_action('themify_builder_background_styling',$builder_id,array('styling'=>$fields_args,'mod_name'=>$mod_name),$element_id,'module');
	$container_props=$container_class=null;
	if ($the_query->have_posts()) :
		if($fields_args['sort'] === 'yes'){
			$inBuilder = (isset($_POST['pageId']) && Themify_Builder::$frontedit_active===true) || (isset($_POST['pageId']) && true === $isAPP);
			if(true == $inBuilder){
				global $wp_query;
				$main_query = clone $wp_query;
				$the_query->set( 'wc_query', 'product_query');
				$wp_query = $the_query;
			}
			woocommerce_catalog_ordering();
			if(true == $inBuilder){
				$wp_query = $main_query;
				$main_query = null;
			}
		}
        Tbp_Utils::disable_ptb_loop();
	    if ($isProductArchive === false && is_shop() && function_exists('wc_print_notices')) {
		wc_print_notices();
	    }
	    $isLoop=$ThemifyBuilder->in_the_loop===true;
	    $ThemifyBuilder->in_the_loop = true;
	    ?>
	    <ul class="builder-posts-wrap clearfix products loops-wrapper tbp_posts_wrap <?php echo $fields_args['layout_product'] . ' ' . $masonry_class; ?>">
		<?php
		while ($the_query->have_posts()) :
			$the_query->the_post();
		    ?>
		    <li id="post-<?php the_ID(); ?>" <?php wc_product_class('post clearfix'); ?>>
			<?php
			    if($isAPP===true){
				self::retrieve_template('partials/advanched-archive.php', $fields_args);
			    }
			    else{
				self::retrieve_template('wc/loop/simple-archive.php', $fields_args);
			    }
			?>
		    </li>
		    <?php
		endwhile;
		?>
	    </ul>
    <?php
	    wp_reset_postdata();
	    $ThemifyBuilder->in_the_loop = $isLoop;
	    if ($fields_args['pagination'] === 'yes') {
		    self::retrieve_template('partials/pagination.php', array(
			    'pagination_option'=>$fields_args['pagination_option'],
			    'next_link'=>$fields_args['next_link'],
			    'prev_link'=>$fields_args['prev_link'],
			    'query'=>$the_query
		    ));
	    }
        ?>
	<?php else:?>
	    <?php echo $fields_args['no_found'];?>
	<?php endif; ?>
        <!-- /<?php echo $mod_name?> module -->
    </div>
<?php endif; ?>
