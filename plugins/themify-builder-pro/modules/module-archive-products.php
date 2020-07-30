<?php
if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

/**
 * Module Name: Archive Products
 * Description:
 */

class TB_Archive_Products_Module extends Themify_Builder_Component_Module {

	function __construct() {
		parent::__construct(array(
			'name' => __('Archive Products', 'themify'),
			'slug' => 'archive-products',
			'category' => array('product_archive')
		));
	}

	public function get_options() {
		$post_image = Tbp_Utils::get_module_settings('product-image','options');
		$position=null;
		foreach($post_image as $k=>$v){
		    if($v['type']==='tbp_custom_css'){
			unset($post_image[$k]);
		    }
		    elseif(isset($v['id']) && $v['id']==='badge_pos'){
			$position=$k;
		    }
		}
		if($position===null){
		    $position=$k;
		}
		array_splice($post_image, $position, 0, array(array(
		    'type'=>'advacned_link'
		)));
		$post_title = Tbp_Utils::get_module_settings('product-title','options');
		foreach($post_title as $k=>$v){
		    if($v['type']==='tbp_custom_css'){
			unset($post_title[$k]);
		    }
		}
		$post_meta = Tbp_Utils::get_module_settings('product-meta','options');
		foreach($post_meta as $k=>$v){
		    if($v['type']==='tbp_custom_css'){
			unset($post_meta[$k]);
		    }
		}
		
		$post_content = Tbp_Utils::get_module_settings('product-description','options');
		foreach($post_content as $k=>$v){
		    if($v['type']==='tbp_custom_css'){
			unset($post_content[$k]);
		    }
		}
		$addToCart = Tbp_Utils::get_module_settings('add-to-cart','options');
		foreach($addToCart as $k=>$v){
		    if($v['type']==='tbp_custom_css'){
			unset($addToCart[$k]);
		    }
		}
		return array(
			array(
				'id' => 'layout_product',
				'type' => 'layout',
				'label' => __('Product Layout', 'themify'),
				'mode' => 'sprite',
				'control'=>array(
				    'classSelector'=>'.builder-posts-wrap'
				),
				'options' => array(
					array('img' => 'list_post', 'value' => 'list-post', 'label' => __('List Product', 'themify')),
					array('img' => 'grid2', 'value' => 'grid2', 'label' => __('Grid 2', 'themify')),
					array('img' => 'grid3', 'value' => 'grid3', 'label' => __('Grid 3', 'themify')),
					array('img' => 'grid4', 'value' => 'grid4', 'label' => __('Grid 4', 'themify')),
					array('img' => 'list_thumb_image', 'value' => 'list-thumb-image', 'label' => __('List Thumb Image', 'themify')),
					array('img' => 'grid2_thumb', 'value' => 'grid2-thumb', 'label' => __('Grid 2 Thumb', 'themify'))
				)
			),
			array(
				'id'      => 'masonry',
				'type'    => 'toggle_switch',
				'label'   => __( 'Masonry', 'themify' ),
				'options'   => array(
					'on'  => array( 'name' => 'yes', 'value' => 'en' ),
					'off' => array( 'name' => 'no', 'value' => 'dis' ),
				)
			),
			array(
				'id'      => 'sort',
				'type'    => 'toggle_switch',
				'label'   => __( 'Product Sort', 'themify' ),
				'options'   => array(
					'on'  => array( 'name' => 'yes', 'value' => 's' ),
					'off' => array( 'name' => 'no', 'value' => 'hi' ),
				),
			),
			array(
				'id'      => 'pagination',
				'type'    => 'toggle_switch',
				'label'   => __( 'Pagination', 'themify' ),
				'options'   => array(
					'on'  => array( 'name' => 'yes', 'value' => 's' ),
					'off' => array( 'name' => 'no', 'value' => 'hi' ),
				),
				'binding' => array(
					'checked' => array( 'show' => array( 'pagination_option','per_page' ) ),
					'not_checked' => array( 'hide' => array( 'pagination_option','per_page' ) ),
				)
			),
			array(
				'id' => 'pagination_option',
				'type' => 'select',
				'label' => '',
				'options' => array(
					'numbers' => __('Numbers', 'themify'),
					'link' => __('Next/Prev Link', 'themify'),
				),
				'binding' => array(
					'numbers' => array( 'hide' => array( 'next_link', 'prev_link' ) ),
					'link' => array( 'show' => array( 'next_link', 'prev_link' ) ),
				)
			),
			array(
				'id' => 'per_page',
				'type' => 'number',
				'label' => __('Products Per Page', 'themify')
			),
			array(
				'id' => 'offset',
				'type' => 'number',
				'label' => __('Offset', 'themify')
			),
			array(
				'id' => 'next_link',
				'type' => 'text',
				'label' => __('Next Link', 'themify')
			),
			array(
				'id' => 'prev_link',
				'type' => 'text',
				'label' => __('Prev Link', 'themify')
			),
			array(
				'id' => 'no_found',
				'type' => 'text',
				'label' => __('No Products Found', 'themify'),
				'control'=>false
			),
			array(
				'id' => 'archive_products',
				'type' => 'toggleable_fields',
				'options' => array(
					'image' =>array(
					    'label'   => __( 'Product Image', 'themify' ),
					    'options' => $post_image,
					),
					't' => array(
						'label'   => __('Product Title', 'themify'),
						'options' => $post_title
					),
					'p_meta' => array(
						'label' => __('Product Meta', 'themify'),
						'options' => $post_meta
					),
					'p_desc' => array(
						'label' => __('Product Description', 'themify'),
						'options' => $post_content
					),
					'p_price' => array(
						'label'   => __( 'Price', 'themify' )
					),
					'p_rating' => array(
						'label'   => __( 'Rating', 'themify' )
					),
					'add_to_c' => array(
						'label'   => __( 'Add To Cart', 'themify' ),
						'options' =>$addToCart
					)
				)
			),
			array('type' => 'tbp_custom_css')
		);
	}

	public function get_styling() {
		$general = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color('', 'b_c_g', 'bg_c', 'background-color')
					)
					),
					'h' => array(
					'options' => array(
						self::get_color('', 'b_c_g', 'bg_c', 'background-color', 'h')
					)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_font_family(array(' .product', '.module .tbp_title', ' a:not(.post-edit-link)', ' p', ' button'), 'f_f_g'),
						self::get_color_type(array(' .product', ' p', ' button'),'', 'f_c_t_g',  'f_c_g', 'f_g_c_g'),
						self::get_font_size(array(' a:not(.post-edit-link)', ' p', ' button'), 'f_s_g'),
						self::get_line_height(array(' a:not(.post-edit-link)', ' p', ' button'), 'l_h_g'),
						self::get_letter_spacing(array(' a:not(.post-edit-link)', ' p', ' button'), 'l_s_g'),
						self::get_text_align(array(' .product', '.module .tbp_title', ' a:not(.post-edit-link)', ' p', ' button'), 't_a_g'),
						self::get_text_transform(array(' .product', '.module .tbp_title', ' a:not(.post-edit-link)', ' p', ' button'), 't_t_g'),
						self::get_font_style(array(' .product', '.module .tbp_title', ' a:not(.post-edit-link)', ' p', ' button'), 'f_g', 'f_b'),
						self::get_text_shadow(array(' a:not(.post-edit-link)', ' p', ' button'), 't_sh'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_font_family(array(' .product', '.module .tbp_title', ' a:not(.post-edit-link)', ' p', ' button'), 'f_f_g', 'h'),
						self::get_color_type(array(' .product', ' p', ' button'),'', 'f_c_t_g',  'f_c_g', 'f_g_c_g', 'h'),
						self::get_font_size(array(' a:not(.post-edit-link)', ' p', ' button'), 'f_s_g', 'h'),
						self::get_line_height(array(' a:not(.post-edit-link)', ' p', ' button'), 'l_h_g', 'h'),
						self::get_letter_spacing(array(' a:not(.post-edit-link)', ' p', ' button'), 'l_s_g', 'h'),
						self::get_text_align(array(' .product', '.module .tbp_title', ' a:not(.post-edit-link)', ' p', ' button'), 't_a_g', 'h'),
						self::get_text_transform(array(' .product', '.module .tbp_title', ' a:not(.post-edit-link)', ' p', ' button'), 't_t_g', 'h'),
						self::get_font_style(array(' .product', '.module .tbp_title', ' a:not(.post-edit-link)', ' p', ' button'), 'f_g', 'f_b', 'h'),
						self::get_text_shadow(array(' a:not(.post-edit-link)', ' p', ' button'), 't_sh', 'h'),
					)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding('', 'p')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding('', 'p', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin('', 'm')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin('', 'm', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border('', 'b')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border('', 'b', 'h')
						)
					)
				))
			)),
			// Filter
			self::get_expand('f_l',
				array(
					self::get_tab(array(
						'n' => array(
							'options' => count($a = self::get_blend())>2 ? array($a) : $a
						),
						'h' => array(
							'options' => count($a = self::get_blend('','bl_m_h','h'))>2 ? array($a + array('ishover'=>true)) : $a
						)
					))
				)
			),
			// Width
			self::get_expand('w', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_width('', 'w')
						)
					),
					'h' => array(
						'options' => array(
							self::get_width('', 'w', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
					self::get_tab(array(
						'n' => array(
							'options' => array(
								self::get_border_radius('', 'r_c')
							)
						),
						'h' => array(
							'options' => array(
								self::get_border_radius('', 'r_c', 'h')
							)
						)
					))
				)
			),
			// Shadow
			self::get_expand('sh', array(
					self::get_tab(array(
						'n' => array(
							'options' => array(
								self::get_box_shadow('', 'sh')
							)
						),
						'h' => array(
							'options' => array(
								self::get_box_shadow('', 'sh', 'h')
							)
						)
					))
				)
			),
			// Display
			self::get_expand('disp', self::get_display())
		);

		$ap_container = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color(' .product', 'b_c_a_p_cn', 'bg_c', 'background-color')
					)
					),
					'h' => array(
					'options' => array(
						self::get_color(' .product', 'b_c_a_p_cn', 'bg_c', 'background-color', 'h')
					)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_padding(' .product', 'p_cn')
					)
					),
					'h' => array(
					'options' => array(
						self::get_padding(' .product', 'p_cn', 'h')
					)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_heading_margin_multi_field(' .products', 'li', 'top'),
						self::get_heading_margin_multi_field(' .products', 'li', 'bottom')
					)
					),
					'h' => array(
					'options' => array(
						self::get_heading_margin_multi_field(' .products', 'li', 'top', 'h'),
						self::get_heading_margin_multi_field(' .products', 'li', 'bottom', 'h')
					)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_border(' .product', 'b_cn')
					)
					),
					'h' => array(
					'options' => array(
						self::get_border(' .product', 'b_cn', 'h')
					)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .product', 'r_c_cn')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .product', 'r_c_cn', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .product', 'sh_cn')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .product', 'sh_cn', 'h')
						)
					)
				))
			)),
		);
		
		$ap_title = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color('.module .tbp_title', 'b_c_a_p_t', 'bg_c', 'background-color')
					)
					),
					'h' => array(
					'options' => array(
						self::get_color('.module .tbp_title', 'b_c_a_p_t', 'bg_c', 'background-color', 'h')
					)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
			self::get_tab(array(
				'n' => array(
					'options' => array(
						self::get_font_family(array('.module .tbp_title', '.module .tbp_title a'), 'f_f_a_p_t'),
						self::get_color(array('.module .tbp_title', '.module .tbp_title a'), 'f_c_a_p_t'),
						self::get_font_size('.module .tbp_title', 'f_s_a_p_t'),
						self::get_line_height('.module .tbp_title', 'l_h_a_p_t'),
						self::get_letter_spacing('.module .tbp_title', 'l_s_a_p_t'),
						self::get_text_transform('.module .tbp_title', 't_t_a_p_t'),
						self::get_font_style('.module .tbp_title', 'f_sy_a_p_t', 'f_w_a_p_t'),
						self::get_text_decoration('.module .tbp_title', 't_d_a_p_t'),
						self::get_text_shadow(array('.module .tbp_title', '.module .tbp_title a'), 't_sh_a_p_t'),
					)
				),
				'h' => array(
					'options' => array(
						self::get_font_family(array('.module .tbp_title', '.module .tbp_title a'), 'f_f_a_p_t', 'h'),
						self::get_color(array('.module .tbp_title', '.module .tbp_title a'), 'f_c_a_p_t', null, null, 'hover'),
						self::get_font_size('.module .tbp_title', 'f_s_a_p_t', '', 'h'),
						self::get_line_height('.module .tbp_title', 'l_h_a_p_t', 'h'),
						self::get_letter_spacing('.module .tbp_title', 'l_s_a_p_t', 'h'),
						self::get_text_transform('.module .tbp_title', 't_t_a_p_t', 'h'),
						self::get_font_style('.module .tbp_title', 'f_sy_a_p_t', 'f_w_a_p_t', 'h'),
						self::get_text_decoration('.module .tbp_title', 't_d_a_p_t', 'h'),
						self::get_text_shadow(array('.module .tbp_title', '.module .tbp_title a'), 't_sh_a_p_t','h'),
					)
				)
			))
			)),
			// Padding
			self::get_expand('p', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_padding('.module .tbp_title', 'p_a_p_t')
				)
				),
				'h' => array(
				'options' => array(
					self::get_padding('.module .tbp_title', 'p_a_p_t', 'h')
				)
				)
			))
			)),
			// Margin
			self::get_expand('m', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_margin('.module .tbp_title', 'm_a_p_t'),
				)
				),
				'h' => array(
				'options' => array(
					self::get_margin('.module .tbp_title', 'm_a_p_t', 'h'),
				)
				)
			))
			)),
			// Border
			self::get_expand('b', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_border('.module .tbp_title', 'b_a_p_t')
				)
				),
				'h' => array(
				'options' => array(
					self::get_border('.module .tbp_title', 'b_a_p_t', 'h')
				)
				)
			))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow('.module .tbp_title', 'sh_a_p_t')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow('.module .tbp_title', 'sh_a_p_t', 'h')
						)
					)
				))
			)),
		);

		$ap_image = array(
			// Background
			self::get_expand('bg', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_color(' .product-image img', 'b_c_a_p_i', 'bg_c', 'background-color')
				)
				),
				'h' => array(
				'options' => array(
					self::get_color(' .product-image img', 'b_c_a_p_i', 'bg_c', 'background-color', 'h')
				)
				)
			))
			)),
			// Padding
			self::get_expand('p', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_padding(' .product-image img', 'p_a_p_i')
				)
				),
				'h' => array(
				'options' => array(
					self::get_padding(' .product-image img', 'p_a_p_i', 'h')
				)
				)
			))
			)),
			// Margin
			self::get_expand('m', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_margin(' .product-image', 'm_a_p_i')
				)
				),
				'h' => array(
				'options' => array(
					self::get_margin(' .product-image', 'm_a_p_i', 'h')
				)
				)
			))
			)),
			// Border
			self::get_expand('b', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_border(' .product-image img', 'b_a_p_i')
				)
				),
				'h' => array(
				'options' => array(
					self::get_border(' .product-image img', 'b_a_p_i', 'h')
				)
				)
			))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .product-image img', 'r_c_a_p_i')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .product-image img', 'r_c_a_p_i', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .product-image img', 'sh_a_p_i')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .product-image img', 'sh_a_p_i', 'h')
						)
					)
				))
			))
		);

		$ap_description = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color(array(' .product-description', ' .woocommerce-product-details__short-description'), 'b_c_a_p_c', 'bg_c', 'background-color')
					)
					),
					'h' => array(
					'options' => array(
						self::get_color(array(' .product-description', ' .woocommerce-product-details__short-description'), 'b_c_a_p_c', 'bg_c', 'background-color', 'h')
					)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_font_family(array(' .product-description', ' .product-description p', ' .woocommerce-product-details__short-description', ' .woocommerce-product-details__short-description p'), 'f_f_a_p_c'),
						self::get_color(array(' .product-description', ' .product-description p', ' .woocommerce-product-details__short-description', ' .woocommerce-product-details__short-description p'), 'f_c_a_p_c'),
						self::get_font_size(array(' .product-description', ' .product-description p', ' .woocommerce-product-details__short-description', ' .woocommerce-product-details__short-description p'), 'f_s_a_p_c'),
						self::get_line_height(array(' .product-description', ' .product-description p', ' .woocommerce-product-details__short-description', ' .woocommerce-product-details__short-description p'), 'l_h_a_p_c'),
						self::get_text_align(array(' .product-description', ' .product-description p', ' .woocommerce-product-details__short-description', ' .woocommerce-product-details__short-description p'), 't_a_a_p_c'),
						self::get_text_shadow(array(' .product-description', ' .product-description p', ' .woocommerce-product-details__short-description', ' .woocommerce-product-details__short-description p'), 't_sh_a_p_c'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_font_family(array(' .product-description', ' .product-description p', ' .woocommerce-product-details__short-description', ' .woocommerce-product-details__short-description p'), 'f_f_a_p_c','h'),
						self::get_color(array(' .product-description', ' .product-description p', ' .woocommerce-product-details__short-description', ' .woocommerce-product-details__short-description p'), 'f_c_a_p_c', null,null, 'h'),
						self::get_font_size(array(' .product-description', ' .product-description p', ' .woocommerce-product-details__short-description', ' .woocommerce-product-details__short-description p'), 'f_s_a_p_c', '', 'h'),
						self::get_line_height(array(' .product-description', ' .product-description p', ' .woocommerce-product-details__short-description', ' .woocommerce-product-details__short-description p'), 'l_h_a_p_c', 'h'),
						self::get_text_align(array(' .product-description', ' .product-description p', ' .woocommerce-product-details__short-description', ' .woocommerce-product-details__short-description p'), 't_a_a_p_c', 'h'),
						self::get_text_shadow(array(' .product-description', ' .product-description p', ' .woocommerce-product-details__short-description', ' .woocommerce-product-details__short-description p'), 't_sh_a_p_c','h'),
					)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_padding(array(' .product-description', ' .woocommerce-product-details__short-description'), 'p_a_p_c')
					)
					),
					'h' => array(
					'options' => array(
						self::get_padding(array(' .product-description', ' .woocommerce-product-details__short-description'), 'p_a_p_c', 'h')
					)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_margin(array(' .product-description', ' .woocommerce-product-details__short-description'), 'm_a_p_c')
					)
					),
					'h' => array(
					'options' => array(
						self::get_margin(array(' .product-description', ' .woocommerce-product-details__short-description'), 'm_a_p_c', 'h')
					)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_border(array(' .product-description', ' .woocommerce-product-details__short-description'), 'b_a_p_c')
					)
					),
					'h' => array(
					'options' => array(
						self::get_border(array(' .product-description', ' .woocommerce-product-details__short-description'), 'b_a_p_c', 'h')
					)
					)
				))
			))
		);

		$ap_price = array(
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_font_family(array('.module .price', ' .product-price ins span', ' .product-price del span'), 'f_f_p'),
						self::get_color_type(array('.module .price', ' .product-price ins span', ' .product-price del span'), 'f_c_t_p',  'f_c_p', 'f_g_c_p'),
						self::get_font_size(array('.module .price', ' .product-price ins span', ' .product-price del span'), 'f_s_p', ''),
						self::get_line_height(array('.module .price', ' .product-price ins span', ' .product-price del span'), 'l_h_p'),
						self::get_letter_spacing(array('.module .price', ' .product-price ins span', ' .product-price del span'), 'l_s_p'),
						self::get_text_align(array('.module .price', ' .product-price ins span', ' .product-price del span'), 't_a_p'),
						self::get_text_transform(array('.module .price', ' .product-price ins span', ' .product-price del span'), 't_t_p'),
						self::get_font_style(array('.module .price', ' .product-price ins span', ' .product-price del span'), 'f_st_p', 'f_w_p'),
						self::get_text_decoration(array('.module .price', ' .product-price ins span', ' .product-price del span'), 't_d_r_p'),
						self::get_text_shadow(array('.module .price', ' .product-price ins span', ' .product-price del span'),'t_sh_p'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_font_family(array('.module .price', ' .product-price ins span', ' .product-price del span'), 'f_f_p', 'h'),
						self::get_color_type(array('.module .price', ' .product-price ins span', ' .product-price del span'), 'f_c_t_p',  'f_c_p', 'f_g_c_p', 'h'),
						self::get_font_size(array('.module .price', ' .product-price ins span', ' .product-price del span'), 'f_s_p', '', 'h'),
						self::get_line_height(array('.module .price', ' .product-price ins span', ' .product-price del span'), 'l_h_p', 'h'),
						self::get_letter_spacing(array('.module .price', ' .product-price ins span', ' .product-price del span'), 'l_s_p', 'h'),
						self::get_text_align(array('.module .price', ' .product-price ins span', ' .product-price del span'), 't_a_p', 'h'),
						self::get_text_transform(array('.module .price', ' .product-price ins span', ' .product-price del span'), 't_t_p', 'h'),
						self::get_font_style(array('.module .price', ' .product-price ins span', ' .product-price del span'), 'f_st_p', 'f_w_p', 'h'),
						self::get_text_decoration(array('.module .price', ' .product-price ins span', ' .product-price del span'), 't_d_r_p', 'h'),
						self::get_text_shadow(array('.module .price', ' .product-price ins span', ' .product-price del span'),'t_sh_p', 'h'),
					)
					)
				))
			))
		);
		
		$ap_rating = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color('.module .star-rating', 'b_c_ap_r', 'bg_c', 'background-color')
					)
					),
					'h' => array(
					'options' => array(
						self::get_color('.module .star-rating', 'b_c_ap_r', 'bg_c', 'background-color', 'h')
					)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color('.module .star-rating', 'f_c_ap_r'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_color('.module .star-rating', 'f_c_g_ap_r', 'h'),
					)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_padding('.module .star-rating', 'p_ap_r')
					)
					),
					'h' => array(
					'options' => array(
						self::get_padding('.module .star-rating', 'p_ap_r', 'h')
					)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_margin('.module .star-rating', 'm_ap_r')
					)
					),
					'h' => array(
					'options' => array(
						self::get_margin('.module .star-rating', 'm_ap_r', 'h')
					)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_border('.module .star-rating', 'b_ap_r')
					)
					),
					'h' => array(
					'options' => array(
						self::get_border('.module .star-rating', 'b_ap_r', 'h')
					)
					)
				))
			))
		);
		
		$ap_add_to_cart = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color('.module .add_to_cart_button', 'b_c_ap_atc', 'bg_c', 'background-color')
					)
					),
					'h' => array(
					'options' => array(
						self::get_color('.module .add_to_cart_button', 'b_c_ap_atc', 'bg_c', 'background-color', 'h')
					)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_font_family('.module .add_to_cart_button', 'f_f_ap_atc'),
						self::get_color_type('.module .product .add_to_cart_button','', 'f_c_t_ap_atc',  'f_c_ap_atc', 'f_c_g_ap_atc'),
						self::get_font_size('.module .product .add_to_cart_button', 'f_s_ap_atc', ''),
						self::get_line_height('.module .add_to_cart_button', 'l_h_ap_atc'),
						self::get_letter_spacing('.module .add_to_cart_button', 'l_s_ap_atc'),
						self::get_text_align('.module .add_to_cart_button', 't_a_ap_atc'),
						self::get_text_transform('.module .add_to_cart_button', 't_t_ap_atc'),
						self::get_font_style('.module .add_to_cart_button', 'f_st_ap_atc', 'f_w_ap_atc'),
						self::get_text_decoration('.module .add_to_cart_button', 't_d_r_ap_atc'),
						self::get_text_shadow('.module .add_to_cart_button','t_sh_ap_atc'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_font_family('.module .add_to_cart_button', 'f_f_ap_act', 'h'),
						self::get_color_type('.module .product .add_to_cart_button:hover','', 'f_c_t_ap_act_h',  'f_c_ap_act_h', 'f_c_g_ap_act_h', 'h'),
						self::get_font_size('.module .product .add_to_cart_button', 'f_s_ap_act', '', 'h'),
						self::get_line_height('.module .add_to_cart_button', 'l_h_ap_act', 'h'),
						self::get_letter_spacing('.module .add_to_cart_button', 'l_s_ap_act', 'h'),
						self::get_text_align('.module .add_to_cart_button', 't_a_ap_act', 'h'),
						self::get_text_transform('.module .add_to_cart_button', 't_t_ap_act', 'h'),
						self::get_font_style('.module .add_to_cart_button', 'f_st_ap_act', 'f_w_ap_act', 'h'),
						self::get_text_decoration('.module .add_to_cart_button', 't_d_r_ap_act', 'h'),
						self::get_text_shadow('.module .add_to_cart_button','t_sh_ap_act', 'h'),
					)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_padding('.module .add_to_cart_button', 'p_ap_act')
					)
					),
					'h' => array(
					'options' => array(
						self::get_padding('.module .add_to_cart_button', 'p_ap_act', 'h')
					)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_margin('.module .product .add_to_cart_button', 'm_ap_act')
					)
					),
					'h' => array(
					'options' => array(
						self::get_margin('.module .product .add_to_cart_button', 'm_ap_act', 'h')
					)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_border('.module .add_to_cart_button', 'b_ap_act')
					)
					),
					'h' => array(
					'options' => array(
						self::get_border('.module .add_to_cart_button', 'b_ap_act', 'h')
					)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius('.module .add_to_cart_button', 'r_c_ap_act')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius('.module .add_to_cart_button', 'r_c_ap_act', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow('.module .add_to_cart_button', 'sh_ap_act')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow('.module .add_to_cart_button', 'sh_ap_act', 'h')
						)
					)
				))
			)),
			// Quantity
			self::get_expand(__('Quantity', 'themify'), array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color(' .cart .quantity .qty', 'b_c_atc_q', 'bg_c', 'background-color'),
						self::get_color(' .cart .quantity .qty', 'c_atc_q'),
						self::get_padding(' .cart .quantity .qty', 'p_atc_q'),
						self::get_margin(' .cart .quantity', 'm_atc_q'),
						self::get_border(' .cart .quantity .qty', 'b_atc_q'),
						self::get_width(' .cart .quantity .qty', 'w_atc_q'),
						self::get_height(' .cart .quantity .qty', 'h_atc_q'),
						self::get_border_radius(' .cart .quantity .qty', 'r_c_atc_q'),
						self::get_box_shadow(' .cart .quantity .qty', 'sh_atc_q')
					)
					),
					'h' => array(
					'options' => array(
						self::get_color(' .cart .quantity .qty:hover', 'b_c_atc_q_h', 'bg_c', 'background-color', null, 'h'),
						self::get_color(' .cart .quantity .qty', 'c_atc_q_h', null, null, 'h'),
						self::get_padding(' .cart .quantity .qty', 'p_atc_q', 'h'),
						self::get_margin(' .cart .quantity', 'm_atc_q', 'h'),
						self::get_border(' .cart .quantity .qty', 'b_atc_q', 'h'),
						self::get_width(' .cart .quantity .qty', 'w_atc_q', 'h'),
						self::get_height(' .cart .quantity .qty', 'h_atc_q', 'h'),
						self::get_border_radius(' .cart .quantity .qty', 'r_c_atc_q', 'h'),
						self::get_box_shadow(' .cart .quantity .qty', 'sh_atc_q', 'h')
					)
					)
				)),
			))
		);
		
		$ap_pg_container = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color(' .pagenav', 'b_c_pg_c', 'bg_c', 'background-color')
					)
					),
					'h' => array(
					'options' => array(
						self::get_color(' .pagenav', 'b_c_pg_c', 'bg_c', 'background-color', 'h')
					)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_font_family(' .pagenav', 'f_f_pg_c'),
						self::get_color(' .pagenav', 'f_c_pg_c'),
						self::get_font_size(' .pagenav', 'f_s_pg_c'),
						self::get_line_height(' .pagenav', 'l_h_pg_c'),
						self::get_letter_spacing(' .pagenav', 'l_s_pg_c'),
						self::get_text_align(' .pagenav', 't_a_pg_c'),
						self::get_font_style(' .pagenav', 'f_st_pg_c', 'f_b_pg_c'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_font_family(' .pagenav', 'f_f_pg_c', 'h'),
						self::get_color(' .pagenav', 'f_c_pg_c','h'),
						self::get_font_size(' .pagenav', 'f_s_pg_c', '', 'h'),
						self::get_line_height(' .pagenav', 'l_h_pg_c', 'h'),
						self::get_letter_spacing(' .pagenav', 'l_s_pg_c', 'h'),
						self::get_text_align(' .pagenav', 't_a_pg_c', 'h'),
						self::get_font_style(' .pagenav', 'f_st_pg_c', 'f_b_pg_c', 'h'),
					)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_padding(' .pagenav', 'p_pg_c')
					)
					),
					'h' => array(
					'options' => array(
						self::get_padding(' .pagenav', 'p_pg_c', 'h')
					)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_margin(' .pagenav', 'm_pg_c')
					)
					),
					'h' => array(
					'options' => array(
						self::get_margin(' .pagenav', 'm_pg_c', 'h')
					)
					)
				)),
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_border(' .pagenav', 'b_pg_c')
					)
					),
					'h' => array(
					'options' => array(
						self::get_border(' .pagenav', 'b_pg_c', 'h')
					)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .pagenav', 'r_c_pg_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .pagenav', 'r_c_pg_c', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .pagenav', 'sh_pg_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .pagenav', 'sh_pg_c', 'h')
						)
					)
				))
			))
		);

		$ap_pg_numbers = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color(' .pagenav a', 'b_c_pg_n', 'bg_c', 'background-color')
					)
					),
					'h' => array(
					'options' => array(
						self::get_color(' .pagenav a', 'b_c_pg_n', 'bg_c', 'background-color', 'h')
					)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_font_family(' .pagenav a', 'f_f_pg_n'),
						self::get_color(' .pagenav a', 'f_c_pg_n'),
						self::get_font_size(' .pagenav a', 'f_s_pg_n'),
						self::get_line_height(' .pagenav a', 'l_h_pg_n'),
						self::get_letter_spacing(' .pagenav a', 'l_s_pg_n'),
						self::get_text_align(' .pagenav a', 't_a_pg_n'),
						self::get_font_style(' .pagenav a', 'f_st_pg_n', 'f_b_pg_n'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_font_family(' .pagenav a', 'f_f_pg_n', 'h'),
						self::get_color(' .pagenav a', 'f_c_pg_n','h'),
						self::get_font_size(' .pagenav a', 'f_s_pg_n', '', 'h'),
						self::get_line_height(' .pagenav a', 'l_h_pg_n', 'h'),
						self::get_letter_spacing(' .pagenav a', 'l_s_pg_n', 'h'),
						self::get_text_align(' .pagenav a', 't_a_pg_n', 'h'),
						self::get_font_style(' .pagenav a', 'f_st_pg_n', 'f_b_pg_n', 'h'),
					)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_padding(' .pagenav a', 'p_pg_n')
					)
					),
					'h' => array(
					'options' => array(
						self::get_padding(' .pagenav a', 'p_pg_n', 'h')
					)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_margin(' .pagenav a', 'm_pg_n')
					)
					),
					'h' => array(
					'options' => array(
						self::get_margin(' .pagenav a', 'm_pg_n', 'h')
					)
					)
				)),
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_border(' .pagenav a', 'b_pg_n')
					)
					),
					'h' => array(
					'options' => array(
						self::get_border(' .pagenav a', 'b_pg_n', 'h')
					)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .pagenav a', 'r_c_pg_n')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .pagenav a', 'r_c_pg_n', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .pagenav a', 'sh_pg_n')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .pagenav a', 'sh_pg_n', 'h')
						)
					)
				))
			))
		);

		$ap_pg_a_numbers = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color(' .pagenav .current', 'b_c_pg_a_n', 'bg_c', 'background-color')
					)
					),
					'h' => array(
					'options' => array(
						self::get_color(' .pagenav .current', 'b_c_pg_a_n', 'bg_c', 'background-color', 'h')
					)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_font_family(' .pagenav .current', 'f_f_pg_a_n'),
						self::get_color(' .pagenav .current', 'f_c_pg_a_n'),
						self::get_font_size(' .pagenav .current', 'f_s_pg_a_n'),
						self::get_line_height(' .pagenav .current', 'l_h_pg_a_n'),
						self::get_letter_spacing(' .pagenav .current', 'l_s_pg_a_n'),
						self::get_text_align(' .pagenav .current', 't_a_pg_a_n'),
						self::get_font_style(' .pagenav .current', 'f_st_pg_a_n', 'f_b_pg_a_n'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_font_family(' .pagenav .current', 'f_f_pg_a_n', 'h'),
						self::get_color(' .pagenav .current', 'f_c_pg_a_n','h'),
						self::get_font_size(' .pagenav .current', 'f_s_pg_a_n', '', 'h'),
						self::get_line_height(' .pagenav .current', 'l_h_pg_a_n', 'h'),
						self::get_letter_spacing(' .pagenav .current', 'l_s_pg_a_n', 'h'),
						self::get_text_align(' .pagenav .current', 't_a_pg_a_n', 'h'),
						self::get_font_style(' .pagenav .current', 'f_st_pg_a_n', 'f_b_pg_a_n', 'h'),
					)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_padding(' .pagenav .current', 'p_pg_a_n')
					)
					),
					'h' => array(
					'options' => array(
						self::get_padding(' .pagenav .current', 'p_pg_a_n', 'h')
					)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_margin(' .pagenav .current', 'm_pg_a_n')
					)
					),
					'h' => array(
					'options' => array(
						self::get_margin(' .pagenav .current', 'm_pg_a_n', 'h')
					)
					)
				)),
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_border(' .pagenav .current', 'b_pg_a_n')
					)
					),
					'h' => array(
					'options' => array(
						self::get_border(' .pagenav .current', 'b_pg_a_n', 'h')
					)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .pagenav .current', 'r_c_pg_a_n')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .pagenav .current', 'r_c_pg_a_n', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .pagenav .current', 'sh_pg_a_n')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .pagenav .current', 'sh_pg_a_n', 'h')
						)
					)
				))
			))
		);

		$ap_meta = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color(' .product_meta', 'b_c_ap_m', 'bg_c', 'background-color')
					)
					),
					'h' => array(
					'options' => array(
						self::get_color(' .product_meta', 'b_c_ap_m', 'bg_c', 'background-color', 'h')
					)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(array(' .product_meta', ' .product_meta a'), 'f_f_ap_m'),
							self::get_color(array(' .product_meta', ' .product_meta a'), 'f_c_ap_m'),
							self::get_font_size(' .product_meta', 'f_s_ap_m'),
							self::get_line_height(' .product_meta', 'l_h_ap_m'),
							self::get_letter_spacing(' .product_meta', 'l_s_ap_m'),
							self::get_text_transform(' .product_meta', 't_t_ap_m'),
							self::get_font_style(' .product_meta', 'f_sy_ap_m', 'f_w_ap_m'),
							self::get_text_decoration(' .product_meta', 't_d_ap_m'),
							self::get_text_shadow(array(' .product_meta', ' .product_meta a'), 't_sh_ap_m'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(array(' .product_meta', ' .product_meta a'), 'f_f_ap_m', 'h'),
							self::get_color(array(' .product_meta', ' .product_meta a'), 'f_c_ap_m', null, null, 'hover'),
							self::get_font_size(' .product_meta', 'f_s_ap_m', '', 'h'),
							self::get_line_height(' .product_meta', 'l_h_ap_m', 'h'),
							self::get_letter_spacing(' .product_meta', 'l_s_ap_m', 'h'),
							self::get_text_transform(' .product_meta', 't_t_ap_m', 'h'),
							self::get_font_style(' .product_meta', 'f_sy_ap_m', 'f_w_ap_m', 'h'),
							self::get_text_decoration(' .product_meta', 't_d_ap_m', 'h'),
							self::get_text_shadow(array(' .product_meta', ' .product_meta a'), 't_sh_ap_m','h'),
						)
					)
				))
			)),
			// Link
			self::get_expand('l', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color('.module .product_meta a', 'l_c'),
						self::get_text_decoration('.module .product_meta a', 't_d_l')
					)
					),
					'h' => array(
					'options' => array(
						self::get_color('.module .product_meta a', 'l_c',null, null, 'hover'),
						self::get_text_decoration('.module .product_meta a', 't_d_l', 'h')
					)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .product_meta', 'p_ap_m')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .product_meta', 'p_ap_m', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_margin(' .product_meta', 'm_ap_m'),
				)
				),
				'h' => array(
				'options' => array(
					self::get_margin(' .product_meta', 'm_ap_m', 'h'),
				)
				)
			))
			)),
			// Border
			self::get_expand('b', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_border(' .product_meta', 'b_ap_m')
				)
				),
				'h' => array(
				'options' => array(
					self::get_border(' .product_meta', 'b_ap_m', 'h')
				)
				)
			))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .product_meta', 'sh_ap_m')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .product_meta', 'sh_ap_m', 'h')
						)
					)
				))
			)),
		);

		$ap_sort = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color(' .woocommerce-ordering select', 'b_c_ap_st', 'bg_c', 'background-color')
					)
					),
					'h' => array(
					'options' => array(
						self::get_color(' .woocommerce-ordering select', 'b_c_ap_st', 'bg_c', 'background-color', 'h')
					)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_text_align(' .woocommerce-ordering select', 't_a_ap_st'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_text_align(' .woocommerce-ordering select', 't_a_ap_st', 'h'),
					)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_padding(' .woocommerce-ordering select', 'p_ap_st')
					)
					),
					'h' => array(
					'options' => array(
						self::get_padding(' .woocommerce-ordering select', 'p_ap_st', 'h')
					)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_margin(' .woocommerce-ordering select', 'm_ap_st')
					)
					),
					'h' => array(
					'options' => array(
						self::get_margin(' .woocommerce-ordering select', 'm_ap_st', 'h')
					)
					)
				)),
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_border(' .woocommerce-ordering select', 'b_ap_st')
					)
					),
					'h' => array(
					'options' => array(
						self::get_border(' .woocommerce-ordering select', 'b_ap_st', 'h')
					)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .woocommerce-ordering select', 'r_c_ap_st')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .woocommerce-ordering select', 'r_c_ap_st', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .woocommerce-ordering select', 'sh_ap_st')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .woocommerce-ordering select', 'sh_ap_st', 'h')
						)
					)
				))
			))
		);

		return array(
			'type' => 'tabs',
			'options' => array(
				'g' => array(
					'options' => $general
				),
				'sort' => array(
					'label' => __('Product Sort', 'themify'),
					'options' => $ap_sort
				),
				'co' => array(
					'label' => __('Container', 'themify'),
					'options' => $ap_container
				),
				't' => array(
					'label' => __('Title', 'themify'),
					'options' => $ap_title
				),
				'mt' => array(
					'label' => __('Meta', 'themify'),
					'options' => $ap_meta
				),
				'i' => array(
					'label' => __('Image', 'themify'),
					'options' => $ap_image
				),
				'd' => array(
					'label' => __('Description', 'themify'),
					'options' => $ap_description
				),
				'p' => array(
					'label' => __('Price', 'themify'),
					'options' => $ap_price
				),
				'r' => array(
					'label' => __('Rating', 'themify'),
					'options' => $ap_rating
				),
				'ac' => array(
					'label' => __('Add to Cart', 'themify'),
					'options' => $ap_add_to_cart
				),
				'pg_c' => array(
					'label' => __('Pagination Container', 'themify'),
					'options' => $ap_pg_container
				),
				'pg_n' => array(
					'label' => __('Pagination Numbers', 'themify'),
					'options' => $ap_pg_numbers
				),
				'pg_a_n' => array(
					'label' => __('Pagination Active', 'themify'),
					'options' => $ap_pg_a_numbers
				)
			)
		);
	}

	public function get_default_settings() {
		$args= array(
			'layout_product' => 'grid3',
			'pagination' => 'yes',
			'order' => 'desc',
			'orderby'=>'ID',
			'next_link'=>__('Newer Entries', 'themify'),
			'prev_link'=>__('Older Entries', 'themify'),
			'no_found'=>__('No Products Found','themify'),
			'archive_products' => array(
				'image' => array(
					'on' => '1',
					'val' => array()
				),
				't' => array(
					'on' => '1',
					'val' => array()
				),
				'p_meta' => array(
					'on' => '0',
					'val' => array()
				),
				'p_desc' => array(
					'on' => '1',
					'val' => array()
				),
				'p_price' => array(
					'on' => '1'
				),
				'p_rating' => array(
					'on' => '0'
				),
				'add_to_c' => array(
					'on' => '1',
					'val' => array(
						'quantity' => 'no',
						'label' => __('Add To Cart', 'themify'),
						'fullwidth' => 'no'
					)
				)
			)
		);
		$defaults = array('image'=>'product-image','t'=>'product-title','p_meta'=>'product-meta','p_desc'=>'product-description');
		foreach($defaults as $k=>$v){
		    $args['archive_products'][$k]['val'] = Tbp_Utils::get_module_settings($v);
		}
		return $args;
	}


	public function get_visual_type() {
		return 'ajax';
	}

	public function get_category() {
		return array( 'product' );
	}

}
if ( themify_is_woocommerce_active() ) {
	Themify_Builder_Model::register_module('TB_Archive_Products_Module');
}
