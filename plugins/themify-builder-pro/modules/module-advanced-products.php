<?php
if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

/**
 * Module Name:Advanced Archive Products
 * Description:
 */

class TB_Advanced_Products_Module extends Themify_Builder_Component_Module {
	
	public static $builder_id=null;
    
	function __construct() {
		parent::__construct(array(
			'name' => __('Advanced Archive Products', 'themify'),
			'slug' => 'advanced-products',
			'category' => array('product_archive')
		));
	}

	public function get_options() {
		$opt = Tbp_Utils::get_module_settings('archive-products','options');
		foreach($opt as $k=>$op){
		    if(isset($op['id']) && $op['id']==='archive_products'){
			$opt[$k]=array(
				'id'      => 'builder_content',
				'type'    => 'tbp_advanched_layout',
				'control'=>false
			);
		    }
		}
		return $opt;
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
						self::get_font_family(array(' .product', '.module .title', ' .tbp_posts_wrap a:not(.post-edit-link)', ' p', ' button'), 'f_f_g'),
						self::get_color_type(array(' .product', ' p', ' button'),'', 'f_c_t_g',  'f_c_g', 'f_g_c_g'),
						self::get_font_size(array(' .tbp_posts_wrap a:not(.post-edit-link)', ' p', ' button'), 'f_s_g'),
						self::get_line_height(array(' .tbp_posts_wrap a:not(.post-edit-link)', ' p', ' button'), 'l_h_g'),
						self::get_letter_spacing(array(' .tbp_posts_wrap a:not(.post-edit-link)', ' p', ' button'), 'l_s_g'),
						self::get_text_align(array(' .product', '.module .title', ' .tbp_posts_wrap a:not(.post-edit-link)', ' p', ' button'), 't_a_g'),
						self::get_text_transform(array(' .product', '.module .title', ' .tbp_posts_wrap a:not(.post-edit-link)', ' p', ' button'), 't_t_g'),
						self::get_font_style(array(' .product', '.module .title', ' .tbp_posts_wrap a:not(.post-edit-link)', ' p', ' button'), 'f_g', 'f_b'),
						self::get_text_shadow(array(' .tbp_posts_wrap a:not(.post-edit-link)', ' p', ' button'), 't_sh'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_font_family(array(' .product', '.module .title', ' .tbp_posts_wrap a:not(.post-edit-link)', ' p', ' button'), 'f_f_g', 'h'),
						self::get_color_type(array(' .product', ' p', ' button'),'', 'f_c_t_g',  'f_c_g', 'f_g_c_g', 'h'),
						self::get_font_size(array(' .tbp_posts_wrap a:not(.post-edit-link)', ' p', ' button'), 'f_s_g', 'h'),
						self::get_line_height(array(' .tbp_posts_wrap a:not(.post-edit-link)', ' p', ' button'), 'l_h_g', 'h'),
						self::get_letter_spacing(array(' .tbp_posts_wrap a:not(.post-edit-link)', ' p', ' button'), 'l_s_g', 'h'),
						self::get_text_align(array(' .product', '.module .title', ' .tbp_posts_wrap a:not(.post-edit-link)', ' p', ' button'), 't_a_g', 'h'),
						self::get_text_transform(array(' .product', '.module .title', ' .tbp_posts_wrap a:not(.post-edit-link)', ' p', ' button'), 't_t_g', 'h'),
						self::get_font_style(array(' .product', '.module .title', ' .tbp_posts_wrap a:not(.post-edit-link)', ' p', ' button'), 'f_g', 'f_b', 'h'),
						self::get_text_shadow(array(' .tbp_posts_wrap a:not(.post-edit-link)', ' p', ' button'), 't_sh', 'h'),
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
		
		$aap_container = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color(' .product', 'b_c_aap_cn', 'bg_c', 'background-color')
					)
					),
					'h' => array(
					'options' => array(
						self::get_color(' .product', 'b_c_aap_cn', 'bg_c', 'background-color', 'h')
					)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_padding(' .product', 'p_aap_cn')
					)
					),
					'h' => array(
					'options' => array(
						self::get_padding(' .product', 'p_aap_cn', 'h')
					)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_heading_margin_multi_field(' .products .product', '', 'top', 'article'),
						self::get_heading_margin_multi_field(' .products .product', '', 'bottom', 'article')
					)
					),
					'h' => array(
					'options' => array(
						self::get_heading_margin_multi_field(' .products .product', '', 'top', 'article', 'h'),
						self::get_heading_margin_multi_field(' .products .product', '', 'bottom', 'article', 'h')
					)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_border(' .product', 'p_aap_cn')
					)
					),
					'h' => array(
					'options' => array(
						self::get_border(' .product', 'p_aap_cn', 'h')
					)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .product', 'r_c_aap_cn')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .product', 'r_c_aap_cn', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .product', 'sh_aap_cn')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .product', 'sh_aap_cn', 'h')
						)
					)
				))
			)),
		);

		$pg_container = array(
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

		$pg_numbers = array(
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

		$pg_a_numbers = array(
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

		$aap_sort = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color(' .woocommerce-ordering select', 'b_c_aap_st', 'bg_c', 'background-color')
					)
					),
					'h' => array(
					'options' => array(
						self::get_color(' .woocommerce-ordering select', 'b_c_aap_st', 'bg_c', 'background-color', 'h')
					)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_text_align(' .woocommerce-ordering select', 't_a_aap_st'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_text_align(' .woocommerce-ordering select', 't_a_aap_st', 'h'),
					)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_padding(' .woocommerce-ordering select', 'p_aap_st')
					)
					),
					'h' => array(
					'options' => array(
						self::get_padding(' .woocommerce-ordering select', 'p_aap_st', 'h')
					)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_margin(' .woocommerce-ordering select', 'm_aap_st')
					)
					),
					'h' => array(
					'options' => array(
						self::get_margin(' .woocommerce-ordering select', 'm_aap_st', 'h')
					)
					)
				)),
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_border(' .woocommerce-ordering select', 'b_aap_st')
					)
					),
					'h' => array(
					'options' => array(
						self::get_border(' .woocommerce-ordering select', 'b_aap_st', 'h')
					)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .woocommerce-ordering select', 'r_c_aap_st')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .woocommerce-ordering select', 'r_c_aap_st', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .woocommerce-ordering select', 'sh_aap_st')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .woocommerce-ordering select', 'sh_aap_st', 'h')
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
				'pro_sort' => array(
					'label' => __('Product Sort', 'themify'),
					'options' => $aap_sort
				),
				'pro_cnt' => array(
					'label' => __('Product Container', 'themify'),
					'options' => $aap_container
				),
				'pg_c' => array(
					'label' => __('Pagination Container', 'themify'),
					'options' => $pg_container
				),
				'pg_n' => array(
					'label' => __('Pagination Numbers', 'themify'),
					'options' => $pg_numbers
				),
				'pg_a_n' => array(
					'label' => __('Pagination Active', 'themify'),
					'options' => $pg_a_numbers
				)
			)
		);
	}

	public function get_default_settings() {
		$arr = array();
		$defaultModules=array(
		    'product-image',
		    'product-title',
		    'product-meta',
		    'product-description'
		);
		foreach($defaultModules as $m){
		    if(isset(Themify_Builder_Model::$modules[$m])){
			$arr[] = array(
			    'mod_name'=>$m,
			    'mod_settings'=>Themify_Builder_Model::$modules[$m]->get_default_settings()
			);
		    }
		}
		$defaultModules=null;
		$default =Tbp_Utils::get_module_settings('archive-products');
		unset($default['archive_products']);
		$default['builder_content']=array(
		    array(
			'cols'=>array(
			    array(
				'grid_class'=>'col-full',
				'modules'=>$arr
			    )
			)
		    )
		);
		return $default;
	}


	public function get_visual_type() {
		return 'ajax';
	}

	public function get_category() {
		return array( 'product' );
	}

}

if ( themify_is_woocommerce_active() ) {
    Themify_Builder_Model::register_module('TB_Advanced_Products_Module');
}
