<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://themify.me/
 * @since      1.0.0
 *
 * @package    Tbp
 * @subpackage Tbp/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Tbp
 * @subpackage Tbp/includes
 * @author     Themify <themify@themify.me>
 */
class Tbp_Utils {
	
	public static $isLoop=false;
	
	public static $isActive=null;
	/**
	 * Get attachment image url by post_name.
	 * 
	 * @since 1.0.0
	 * @access public
	 * @param string $slug 
	 * @return string
	 */
	public static function get_attachment_url($slug) {
	$attachment_id = self::get_post_type_query('ID', 'attachment', $slug, null);
	return $attachment_id ? wp_get_attachment_url($attachment_id) : '';
	}

	/**
	 * Custom query post type using $wpdb object.
	 * 
	 * @since 1.0.0
	 * @access public
	 * @param string $field 
	 * @param string $post_type 
	 * @param string $slug 
	 * @param string $post_status 
	 * @return string|int|boolean
	 */
	public static function get_post_type_query($field, $post_type, $slug, $post_status = 'publish') {
	global $wpdb;
	$post_status_query = is_null($post_status) ? '' : " AND post_status='" . $post_status . "'";
	$sql = $wpdb->prepare("SELECT " . $field . " FROM $wpdb->posts WHERE post_type='%s'" . $post_status_query . " AND post_name='%s' LIMIT 1", $post_type, $slug);
	return $wpdb->get_var($sql);
	}

	public static function move_array_index(&$array, $a, $b) {
	$out = array_splice($array, $a, 1);
	array_splice($array, $b, 0, $out);
	}

	/**
	 * Get attachment image by post_name.
	 * 
	 * @since 1.0.0
	 * @access public
	 * @param string $slug 
	 * @param string $size 
	 * @param boolean $icon 
	 * @param string $attr 
	 * @return string
	 */
	public static function get_attachment_image($slug, $size = 'thumbnail', $icon = false, $attr = '') {
	$attachment_id = self::get_post_type_query('ID', 'attachment', $slug, null);
	return $attachment_id ? wp_get_attachment_image($attachment_id, $size, $icon, $attr) : '';
	}

	/**
	 * Check TF Theme exists
	 * 
	 * @since 1.0.0
	 * @access public
	 * @return boolean
	 */
	public static function theme_exists() {
	global $wpdb;
	$count = $wpdb->get_var($wpdb->prepare("SELECT 1 FROM $wpdb->posts WHERE post_type='%s' AND post_status='%s' LIMIT 1", array(Tbp_Themes::$post_type, 'publish')));
	return $count > 0;
	}

	/**
	 * Set active theme.
	 * 
	 * @since 1.0.0
	 * @access public
	 * @param int $id 
	 * @param boolean $set
	 */
	public static function set_active_theme($id, $set = true) {
	update_option('tbp_active_theme', $id);
	}

	public static function get_active_theme() {
	$post_id = get_option('tbp_active_theme');
	return get_post($post_id);
	}

	/**
	 * Get post_meta values by fields.
	 * 
	 * @since 1.0.0
	 * @access public
	 * @param array $fields 
	 * @param array $post_id 
	 * @return array
	 */
	public static function get_field_exist_values($fields, $post_id) {
	$keys = array_keys($fields);
	$return = array();
	foreach ($keys as $key) {
	    $value = get_post_meta($post_id, $key, true);
	    if (!empty($value))
		$return[$key] = $value;
	}
	return $return;
	}

	public static function get_template_conditions($post_id, $data = array()) {
	$condition = !empty($data) ? $data : get_post_meta($post_id, 'tbp_template_conditions', true);

	$records = array();
	if (!empty($condition)) {
	    foreach ($condition as $c) {
		$new_arr = array();
		$new_arr['type'] = isset($c['include']) ? ($c['include'] === 'ex' ? 'exclude' : $c['include']) : 'include';
		if (isset($c['general'])) {
		    $new_arr['general'] = $c['general'];
		}
		if (isset($c['query'])) {
		    $new_arr['query'] = $c['query'];
		}
		$new_arr['detail'] = !empty($c['detail']) && $c['detail']!=='all'?array_map('strval', array_keys($c['detail'])):'all';
		$records[] = $new_arr;
	    }
	}

	return $records;
	}

	public static function get_taxonomies() {
	$taxonomies = get_taxonomies(array('public' => true));
	$exclude_tax = array('post_format', 'product_shipping_class');

	// Exclude unnecessary taxonomies
	foreach ($exclude_tax as $tax) {
	    if (isset($taxonomies[$tax]))
		unset($taxonomies[$tax]);
	}
	return array_map('get_taxonomy', $taxonomies);
	}

	public static function get_public_post_types($args = array()) {
	$post_type_args = array(
	    'show_in_nav_menus' => true,
	);

	if (!empty($args['post_type'])) {
	    $post_type_args['name'] = $args['post_type'];
	}

	$_post_types = get_post_types($post_type_args, 'objects');

	$post_types = array();

	foreach ($_post_types as $post_type => $object) {
	    $post_types[$post_type] = $object->labels->singular_name;
	}

	return $post_types;
	}

	public static function theme_post_exists($title) {
	global $wpdb;
	$count = $wpdb->get_var($wpdb->prepare("SELECT 1 FROM $wpdb->posts WHERE post_type='".Tbp_Themes::$post_type."' AND post_status='publish' AND post_title='%s' LIMIT 1", array($title)));
	return $count > 0;
	}

	/**
	 * Get all related template and template parts ids based on specific theme.
	 * 
	 * @since 1.0.0
	 * @param int $theme_id 
	 * @param string $theme_slug 
	 * @return array
	 */
	public static function get_template_related_post_ids($theme_id, $theme_slug) {
	$return = array($theme_id);
	// Get all template data
	$args = array(
	    'post_type' => Tbp_Templates::$post_type,
	    'posts_per_page' => -1,
	    'ignore_sticky_posts'=>true,
	    'no_found_rows'=>true,
	    'fields' => 'ids',
	    'ptb_disable'=>true,
	    'meta_query' => array(
		array(
		    'key' => 'tbp_associated_theme',
		    'value' => $theme_slug
		)
	    )
	);
	$query = new WP_Query($args);
	$data = $query->get_posts();

	if ($data) {
	    $return = array_merge($return, $data);
	}

	// Include the post thumbnail attachment post type
	//if ( has_post_thumbnail( $theme_id ) ) 
	//	array_push( $return, get_post_thumbnail_id( $theme_id ) );

	return $return;
	}

	public static function get_actual_viewing_post() {
		if ( self::$isLoop === true ) {
			return null;
		}
		global $post;
		$temp_post = $post;

		wp_reset_postdata();
		$return_post = $post;

		$post = $temp_post;

		return ! empty( $return_post ) ? $return_post : null;
	}

	public static function get_actual_viewing_post_id() {
		$post = self::get_actual_viewing_post();
		if ( $post ) {
			return $post->ID;
		}

		return null;
	}

	public static function get_actual_query() {
	if(self::$isLoop ===true){
	    return null;
	}
	static $res=null;
	if($res!==null){
	    $res->rewind_posts();
	    return $res;
	}
	$args = array(
	    'order' => 'DESC',
	    'orderby' => 'ID',
	    'post_type'=>'any',
	    'ptb_disable'=>true,
	    'posts_per_page' => 1,
	    'no_found_rows' => true,
	    'post_status'=> !empty($_GET['preview']) ? 'any' : 'publish'
	);
	$cache=false;
	if(Themify_Builder::$frontedit_active===true || Tbp_Public::$isTemplatePage===true){
	    if($res===null){
			if(isset($_POST['pageId'])){
				$id=(int)$_POST['pageId'];
				$type=$_POST['type'];
				$isTerm=isset($_POST['terms'],$_POST['term_type']);
				$vals=array('post_type','order','orderby','offset');
				foreach($vals as $v){
					if(!empty($_POST[$v])){
						$args[$v]=$_POST[$v];
					}
				}
				$vals=null;
				if(isset($args['offset'])){
					$args['offset']=(int)$args['offset'];
				}
				if($isTerm===false && $type==='category'){
					$args['cat']=$id;
					$args['post_type']='post';
				}
				elseif($isTerm===false && $type==='tag'){
					$args['tag']=$id;
					$args['post_type']='post';
				}
				elseif($type==='archive'){
				    $args['post_type']=$_POST['pageId'];
				}
				elseif($type==='404'){
				    $args['post_type']='page';
				    $args['p']=$id;
				}
				elseif($type==='search'){
					$args['s']=$_POST['pageId'];
				}
				elseif($type==='author'){
					$args['author']=$id;
				}
				elseif(taxonomy_exists($type)){
					if($isTerm===true){
						$args['ignore_sticky_posts']=true;
						if ( $_POST['term_type'] === 'post_slug'  ) {
							if($_POST['slug']!==''){
								$args['post__in'] = Themify_Builder_Model::parse_slug_to_ids( $_POST['slug'], $args['post_type'] );
							}
						} else {
							Themify_Builder_Model::parseTermsQuery($args,$_POST['terms'],$type );
						}
					}
					else{
						$tax = get_taxonomy($type);
						if(!empty($tax)){
							$args['tax_query']=array(
								array(
								'taxonomy'=>$type,
								'field'=>'id',
								'terms'=>array($id)
								)
							);
							$args['post_type']=$tax->object_type;
						}
						$tax=null;
					}
				}
				else{
					$args['p']=$id;
					if(post_type_exists($type)){
					    $args['post_type']=$type;
					}
				}
				if($args['orderby']==='id'){
					$args['orderby']='ID';
				}
				elseif( ! empty( $args['meta_key'] ) && ($args['orderby']==='meta_value' || $args['orderby']==='meta_value_num')) {
					$args[ 'meta_key' ] = $args['meta_key'];
				}
				query_posts($args);
				global $wp_query;
				$res=$wp_query;
				Tbp_Public::set_rules();
				return $res;
			}
			elseif(isset($_POST['tb_post_id']) && is_numeric($_POST['tb_post_id']) && get_post_status ( $_POST['tb_post_id'] ) ){// id can be generated element id
				$id=(int)$_POST['tb_post_id'];
			}
			elseif(Tbp_Public::$isTemplatePage===true){
				$id=current(Tbp_Public::get_location());
				$cache=true;	    
			}
			if(isset($id)){    
				$post_type=get_post_type($id);
				if($post_type===Tbp_Templates::$post_type){
				$condition=self::get_template_conditions($id);
				$post_type=self::get_post_type(get_post_meta($id, 'tbp_template_type', true), $condition[0]); 
				$args['post_type']=$post_type==='any'?'post':$post_type;
				}
				else{
				$args['p'] = $id;
				}
			}
			else{
				$args['post_type']='post';
			}
	    }
	}
	else{
		$original_post = self::get_actual_viewing_post();
		if ( $original_post ) {
			$args['p'] = $original_post->ID;
			$args['post_type'] = $original_post->post_type;
		}
	    $args['post_status'] = 'attachment' === get_post_type($args['p']) ? 'inherit' : $args['post_status'];
	}
	if($args['orderby']==='id'){
	    $args['orderby']='ID';
	}
	elseif( ! empty( $args['meta_key'] ) && ($args['orderby']==='meta_value' || $args['orderby']==='meta_value_num')) {
	    $args[ 'meta_key' ] = $args['meta_key'];
	}
	$data= new WP_Query($args);
	if($cache===true){
	    $res=$data;
	}
	return $data;
	}
	
	
	public static function get_wc_actual_query() {
	if(self::$isLoop ===true){
	    return null;
	}
	static $res=null;
	if($res!==null){
	    $res->rewind_posts();
	    return $res;
	}
	$args=array(
		'post_type' => 'product',
		'nopaging' => true,
		'ptb_disable'=>true,
		'order' => 'DESC',
		'orderby' => 'ID',
		'posts_per_page' => 1,
		'no_found_rows' => true,
		'post_status'=>'publish',
		'ignore_sticky_posts' => true
	);
	if(Themify_Builder::$frontedit_active===true || Tbp_Public::$isTemplatePage===true){
	    if($res===null){
		$cache=Tbp_Public::$isTemplatePage===true;
		$args['tax_query']=array(
		    array(
			'taxonomy' => 'product_type',
			'field'    => 'slug',
			'terms'    => 'simple'
		    ),
		    array(
			'taxonomy' => 'product_visibility',
			'field'    => 'name',
			'operator' => 'NOT IN',
			'terms'    => array('exclude-from-catalog','outofstock','exclude-from-search','featured')
		    )
		);
		if(Themify_Builder::$frontedit_active===true){

		    if(isset($_POST['pageId'])){
			$id=(int)$_POST['pageId'];
			$type=$_POST['type'];
			if(taxonomy_exists($type)){
			    $args['tax_query']=array(
				'taxonomy'=>$type,
				'field'=>'id',
				'terms'=>array($id)
			    );
			}
			else{
			    $args['p']=$id;
			}
			query_posts($args);
			global $wp_query;
			$res=$wp_query;
			Tbp_Public::set_rules();
			return $res;
		    }
		    elseif(isset($_POST['tb_post_id']) && is_numeric($_POST['tb_post_id']) && get_post_status ( $_POST['tb_post_id'] )){    
			$id=(int)$_POST['tb_post_id'];
			$post_type=get_post_type($id);
			if($post_type!==Tbp_Templates::$post_type){
			    $args['p'] =$id;
			}
		    }
		}
	    }
	}
	else{
	    $cache=false;
	    $args['p'] = self::get_actual_viewing_post_id();
	}
	$data= new WP_Query($args);
	if($cache===true){
	    $res=$data;
	}
	return $data;
	}
	
	
	
	public static function get_post_type($location,$condition){
	$general=isset($condition['general'])?$condition['general']:'general';
	$query=isset($condition['query'])?$condition['query']:'';
	if($location!=='header' && $location!=='footer'){
	   $query=$general;
	}
	else{
	    if($general==='general'){
		return 'any';
	    }
	    $location=$general;
	}
	if($query==='is_404'  || $query==='page' || $location==='page' || $query==='child_of' || ($query==='is_front' && $location==='single')){
	    return array('page');
	}
	if($query==='all' || $query==='is_date' || $query==='is_author' || $query==='is_search'){
	    return 'any';
	}
	if($location==='product_single' || $location==='product_archive' || $query==='product_cat' || $query==='product_tag' || $query==='product'){
	    return array('product');
	}
	if($query==='post' || $query==='category' || $query==='post_tag' || ($query==='is_front' && $location==='archive')){
	    return array('post');
	}
	if($query==='is_attachment'){
	    return array('attachment');
	}
	if($location==='single' || $location==='archive'){
	    if($location==='archive' && strpos($query,'all_')===0){
		return array(str_replace('all_','',$query));
	    }
	    if(taxonomy_exists($query)){
		$tax = get_taxonomy($query);
		return $tax->object_type;
	    }
	}
	return array($query);
	}

	/**
	 * Insert an attachment from an URL address.
	 *
	 * @param  String $url
	 * @param  Int    $parent_post_id
	 * @return Int    Attachment ID
	 */
	public static function insert_attachment_from_url($url, $parent_post_id = null) {

	if (!class_exists('WP_Http'))
	    include_once( ABSPATH . WPINC . '/class-http.php' );

	$http = new WP_Http();
	$response = $http->request($url);
	if ($response['response']['code'] != 200) {
	    return false;
	}

	$upload = wp_upload_bits(basename($url), null, $response['body']);
	if (!empty($upload['error'])) {
	    return false;
	}

	$file_path = $upload['file'];
	$file_name = basename($file_path);
	$file_type = wp_check_filetype($file_name, null);
	$attachment_title = sanitize_file_name(pathinfo($file_name, PATHINFO_FILENAME));
	$wp_upload_dir = wp_upload_dir();

	$post_info = array(
	    'guid' => $wp_upload_dir['url'] . '/' . $file_name,
	    'post_mime_type' => $file_type['type'],
	    'post_title' => $attachment_title,
	    'post_content' => '',
	    'post_status' => 'inherit',
	);

	// Create the attachment
	$attach_id = wp_insert_attachment($post_info, $file_path, $parent_post_id);

	// Include image.php
	require_once( ABSPATH . 'wp-admin/includes/image.php' );

	// Define attachment metadata
	$attach_data = wp_generate_attachment_metadata($attach_id, $file_path);

	// Assign metadata to attachment
	wp_update_attachment_metadata($attach_id, $attach_data);

	return $attach_id;
	}

	public static function isAjax() {
	return defined('DOING_AJAX') && DOING_AJAX;
	}

	public static function get_exist_themes() {
	global $wpdb;
	return $wpdb->get_results("SELECT post_name, post_title FROM $wpdb->posts WHERE post_type='".Tbp_Themes::$post_type."' AND post_status='publish' LIMIT 100");
	}

	public static function isRest() {
	return (defined('REST_REQUEST') && REST_REQUEST)|| strpos($_SERVER[ 'REQUEST_URI' ], '/wp-json/') !== false;
	}

	/**
	* Manipulate post count in wp admin list table.
	* 
	* @since 1.0.0
	* @access public
	* @param string $what 
	* @param array $views 
	* @return array
	*/
   public static function manipulate_views_count( $what, $views ) {
	   global $wp_query;

	   $total = $wp_query->post_count;
	   $publish = $wp_query->post_count;

	   $views['all'] = preg_replace( '/\(.+\)/U', '('.$total.')', $views['all'] ); 
	   if ( isset( $views['publish'] ) ) {
		   $views['publish'] = preg_replace( '/\(.+\)/U', '('.$publish.')', $views['publish'] );  
	   }

	   return $views;
   }
   
   
   public static function getProductImageUrl(array $params){
	   
   }
   
   public static function getLightBoxParams(array $args){
	
	$lightbox_settings = array();
	if( isset($args['lightbox_w']) && '' !== $args['lightbox_w'] ) {
	    $lightbox_settings[] = $args['lightbox_w'] . $args['lightbox_w_unit'];
	}
	if(isset($args['lightbox_h']) &&  '' !== $args['lightbox_h'] ) {
	    $lightbox_settings[] = $args['lightbox_h'] . $args['lightbox_h_unit'];
	}
	return implode('|', $lightbox_settings);
   }
   
   
   public static function getLinkParams(array $args,$link=''){
	$attr = array();
	if(isset($args['no_follow'])  && 'yes' === $args['no_follow']){
	    $attr['rel']='nofollow';
	}
	if($args['link']=== 'permalink' ){
	    $attr['href'] = $link!==''?$link:get_permalink();
	}
	elseif($args['link']==='custom' && !empty($args['custom_link'])){
	    $attr['href'] = esc_url( $args['custom_link']);
	}
	elseif($args['link']==='media'){
	    $attr['href'] = get_the_post_thumbnail_url();
	    $attr['class'] = ' themify_lightbox';
	}
	if(isset($attr['href'])){
	    if ($args['open_link']=== 'newtab') {
		$attr['target']='_blank';
	    }
	    elseif($args['open_link']=== 'lightbox'){
		$attr['class'] = ' themify_lightbox';
		if ((isset($args['lightbox_w']) && '' !== $args['lightbox_w']) || (isset($args['lightbox_h']) && '' !== $args['lightbox_h'])) {
		    $attr['data-zoom-config'] = self::getLightBoxParams($args);
		}
	    }
	}
	return $attr;
   }
   
   
   public static function isAjaxAddToCart(){
	   static $is=null;
	   if($is===null){
	   $is='yes' === get_option( 'woocommerce_enable_ajax_add_to_cart' );
	   }
	   return $is;
   }
   
   public static function getDateFormat(array $args){
		if (isset($args['format']) && 'def' !== $args['format']) {
	    if('custom' === $args['format']){
		$format= !empty($args['custom'])?$args['custom']:'';
	    }
	    else{
		$format=$args['format'];
	    }
	}
	else{
	    $format = '';
	}
	return $format;
   }
   
   public static  function localize_predesigned_templates( $data ) {
	if ( (is_admin() && Tbp_Templates::$post_type=== get_post_type()) || ( Themify_Builder_Model::is_front_builder_activate()  && Tbp_Templates::$post_type=== get_post_type( Themify_Builder::$builder_active_id ) ) ) {
	    $data['paths']['layouts_index'] = '';
	}
	$data['i18n']['label']['fall_b']=__( 'Fallback Image', 'themify' );
	return $data;
	}

	public static function load_predesigned_templates() {
		$items = array();
		$remote_url = Tbp_Templates::getTemplateTypeUrl( array('per_page'=>100) );
		$request = wp_remote_get( $remote_url );
		if ( !is_wp_error( $request ) ) {
			$response = json_decode( wp_remote_retrieve_body( $request ), true );
			if ( !empty( $response ) && is_array( $response ) ) {
				foreach ( $response as $item ) {
					$new_item['id'] = $item['id'];
					$new_item['slug'] = $item['slug'];
					$new_item['title'] = html_entity_decode( $item['title']['rendered'] );
					$new_item['thumbnail'] = !empty( $item['tbp_image_full'] ) ? $item['tbp_image_full'] : TBP_URL . '/admin/img/template-placeholder.png';
					$new_item['data'] = $item['template_builder_content'];
					$new_item['category'] = str_replace( '_', ' ', $item['template_type'] );
					$items[] = $new_item;
				}
			}
			$response=$request=null;
		}
		$selected = isset( $_POST['action'] ) && !empty( $_POST['id'] ) && 'tb_load_predesigned_layouts' === $_POST['action'] ? str_replace( '_', ' ', get_post_meta( $_POST['id'], 'tbp_template_type', true ) ) : '';
		return array( 'data' => $items, 'selected' => $selected );
	}

	/**
	 * apply filter on module panel categories to show only related categories to current template
	 *
	 * @since     1.0.0
	 */
	public static function module_categories( $categories ) {
		$is_admin = is_admin();
		if(Themify_Builder::$builder_active_id){
		    $id = Themify_Builder::$builder_active_id;
		}elseif($is_admin===true){
		    $id =Themify_Builder_Model::get_ID();
		}
		if(empty($id)){
		    return $categories;
		}
		$template_location = get_post_meta( $id, 'tbp_template_type', true );
		$template_location = empty($template_location) ? get_post_type($id) : $template_location;
		switch ( $template_location ) {
			case 'archive':
				unset( $categories['product_single'] );
				if($is_admin===true){
				    unset( $categories['single']);
				}
				else{
				    $categories = $categories+array( 'single' =>__('Single','themify'));//for AAP
				}
				$categories = array( 'archive' => __( 'Archive', 'themify' ) ) + $categories;
				break;
			case 'product_archive':
				unset( $categories['single']);
				if($is_admin===true){
				    unset( $categories['product_single']);
				}
				else{
				    $categories = $categories+array( 'product_single' =>__('Product Single','themify'));//for AAP
				}
				$categories = array( 'product_archive' => __( 'Product Archive', 'themify' ) ) + $categories;
				break;
			case 'single':
			case 'page':
			case 'post':
				unset( $categories['product_single'] );
				$single = array( 'single' =>__('Single','themify'));
				$categories = 'single' === $template_location ? $single + $categories : $categories + $single;
				break;
			case 'product':
			case 'product_single':
				unset( $categories['single'] );
				$product_single = array( 'product_single' =>__('Product Single','themify'));
				$categories = 'product_single' === $template_location ? $product_single + $categories : $categories + $product_single;
				break;
			case 'header':
			case 'footer':
				unset( $categories['product_single'], $categories['single'] );
				$categories = array( 'site' => $categories['site'] ) + $categories;
				break;
		}
		return array_unique( $categories );
	}
	
	/**
	* Get Modules, some modules can be disabled from Themify settings.
	* @param string $slug 
	* @param string $get
	* @return array
	*/
	public static function get_module_settings($slug,$get='default'){
	    if(!isset(Themify_Builder_Model::$modules[$slug])){
		static $allModules = null;
		static $isLoaded=array();
		if($allModules===null){
		    $allModules = Themify_Builder_Model::get_modules('all');
		}
		if(!isset($isLoaded[$slug])){
		    require_once( $allModules[$slug]['dirname'] . '/' . $allModules[$slug]['basename'] );
		    $isLoaded[$slug] = get_class(Themify_Builder_Model::$modules[$slug]);
		}
		if(isset(Themify_Builder_Model::$modules[$slug])){
		    $module = Themify_Builder_Model::$modules[$slug];
		    unset(Themify_Builder_Model::$modules[$slug]);
		}
		else{
		    $module = new $isLoaded[$slug];
		}
	    }
	    else{
		$module = Themify_Builder_Model::$modules[$slug];
	    }
	    $data= $get==='default'?$module->get_default_settings():($get==='options'?$module->get_options():$module->get_styling());
	    $module=null;
	    return $data;
	}
	
	/**
	* Disable PTB loop archive
	* @return void
	*/
	public static function disable_ptb_loop(){
		if(function_exists( 'run_ptb' )){
			PTB_Public::get_instance()->disable_ptb(true);
		}
	}

	/**
	 * Return PTB custom fields of certain type(s) across all PTB post types
	 *
	 * @param $type string|array
	 * @return array
	 */
	function get_ptb_fields_by_type( $type ) {
		$options = array();
		if ( ! class_exists( 'PTB' ) ) {
			return $options;
		}
		$type = (array) $type;
		$ptb = PTB::$options->get_custom_post_types();
		foreach ( $ptb as $post_type_key => $post_type ) {
			if ( is_array( $post_type->meta_boxes ) ) {
				foreach ( $post_type->meta_boxes as $key => $field ) {
					if ( in_array( $field['type'], $type ) ) {
						$label = PTB_Utils::get_label( $post_type->plural_label );
						$name = PTB_Utils::get_label( $field['name'] );
						$options[ "{$post_type_key}:{$key}" ] = sprintf( '%s: %s', $label, $name );
					}
				}
			}
		}

		return $options;
	}

	public static function is_multilingual() {
		if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Returns the current language code
	 *
	 * @since 1.0.0
	 *
	 * @return string the language code, e.g. "en"
	 */
	public static function get_current_language_code() {
		
		static $language_code = false;
		if($language_code){
			return $language_code;
		}
		if (defined('ICL_LANGUAGE_CODE')) {

			$language_code = ICL_LANGUAGE_CODE;
		} elseif (function_exists('qtrans_getLanguage')) {

			$language_code = qtrans_getLanguage();
		}
		if (!$language_code) {
			$language_code = substr(get_bloginfo('language'), 0, 2);
		}
		$language_code = strtolower(trim($language_code));

		return $language_code;
	}

	/**
	 * Returns the current language code
	 *
	 * @since 1.0.0
	 *
	 * @return string the language code, e.g. "en"
	 */
	public static function get_default_language_code() {

		static $language_code=false;
		if($language_code!==false){
			return $language_code;
		}
		global $sitepress;
		if (isset($sitepress)) {
			$language_code = $sitepress->get_default_language();
		}

		$language_code = empty($language_code) ? substr(get_bloginfo('language'), 0, 2) : $language_code;
		$language_code = strtolower(trim($language_code));

		return $language_code;
	}

	public static function get_all_languages() {
		static $languages = array();
		if ( ! empty( $languages ) ) {
			return $languages;
		}
		if (defined('ICL_LANGUAGE_CODE')) {
			global $sitepress;
			$lng = self::get_current_language_code();
			foreach ( $sitepress->get_active_languages() as $key => $l ) {
				if ( $lng == $key ) {
					$languages[ $key ]['selected'] = true;
				}
				$languages[ $key ]['name'] = $l['native_name'];
			}
		} elseif (function_exists('qtrans_getLanguage')) {

			$languages = qtrans_getSortedLanguages();
		} else {

			$all_lang = self::get_default_language_code();
			$languages[$all_lang]['name'] = '';
			$languages[$all_lang]['selected'] = true;
		}

		return $languages;
	}
}