<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Module Name: Upsell Products
 * Description: 
 */

class TB_Upsell_Products_Module extends Themify_Builder_Component_Module {

    function __construct() {
		parent::__construct(array(
		    'name' => __('Upsell Products', 'themify'),
		    'slug' => 'upsell-products',
			'category' => array('product_single')
		));
    }

    public function get_options() {
		return array(
			array(
				'id' => 'heading',
				'type' => 'text',
				'label' => __('Heading', 'themify')
			),
			array(
				'id' => 'layout',
				'type' => 'layout',
				'label' => __('Layout', 'themify'),
				'mode' => 'sprite',
				'control'=>array(
				    'classSelector'=>'.tbp_posts_wrap'
				),
				'options' => array(
					array('img' => 'grid2', 'value' => 'grid2', 'label' => __('Grid 2', 'themify')),
					array('img' => 'grid3', 'value' => 'grid3', 'label' => __('Grid 3', 'themify')),
					array('img' => 'grid4', 'value' => 'grid4', 'label' => __('Grid 4', 'themify'))
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
						self::get_image('', 'b_i','bg_c','b_r','b_p')
						)
					),
					'h' => array(
						'options' => array(
							self::get_image('', 'b_i','bg_c','b_r','b_p', 'h')
						)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_font_family('', 'f_f_g'),
						self::get_color_type('','', 'f_c_t_g',  'f_c_g', 'f_g_c_g'),
						self::get_font_size('', 'f_s_g', ''),
						self::get_line_height('', 'l_h_g'),
						self::get_letter_spacing('', 'l_s_g'),
						self::get_text_align('', 't_a_g'),
						self::get_text_transform('', 't_t_g'),
						self::get_font_style('', 'f_st_g', 'f_w_g'),
						self::get_text_decoration('', 't_d_r_g'),
						self::get_text_shadow('','t_sh_g','h'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_font_family('', 'f_f_g_h'),
						self::get_color_type('','', 'f_c_t_g_h',  'f_c_g_h', 'f_g_c_g_h'),
						self::get_font_size('', 'f_s_g', '', 'h'),
						self::get_line_height('', 'l_h_g', 'h'),
						self::get_letter_spacing('', 'l_s_g', 'h'),
						self::get_text_align('', 't_a_g', 'h'),
						self::get_text_transform('', 't_t_g', 'h'),
						self::get_font_style('', 'f_st_g', 'f_w_g', 'h'),
						self::get_text_decoration('', 't_d_r_g', 'h'),
						self::get_text_shadow('','t_sh_g','h'),
					)
					)
				))
			)),
			// Link
			self::get_expand('l', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color(' a', 'l_c'),
						self::get_text_decoration(' a', 't_d_l')
					)
					),
					'h' => array(
					'options' => array(
						self::get_color(' a', 'l_c',null, null, 'hover'),
						self::get_text_decoration(' a', 't_d_l', 'h')
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
							'options' => count($a = self::get_blend('','fl'))>2 ? array($a) : $a
						),
						'h' => array(
							'options' => count($a = self::get_blend('','fl_h','h'))>2 ? array($a + array('ishover'=>true)) : $a
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
			// Height & Min Height
			! method_exists( $this, 'get_max_height' ) ? array() :
			self::get_expand('ht', array(
				self::get_height('', 'g_h'),
				self::get_min_height('', 'g_m_h'),
				self::get_max_height('', 'g_m_h')
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
			)),
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
			)),
			// Display
			self::get_expand('disp', self::get_display())
		);
		
		$container = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color(' .upsells.products .product', 'b_c_cnt', 'bg_c', 'background-color')
					)
					),
					'h' => array(
					'options' => array(
						self::get_color(' .upsells.products .product', 'b_c_cnt', 'bg_c', 'background-color', 'h')
					)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_padding(' .upsells.products .product', 'p_cnt')
					)
					),
					'h' => array(
					'options' => array(
						self::get_padding(' .upsells.products .product', 'p_cnt', 'h')
					)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_heading_margin_multi_field(' .upsells.products .product', '', 'top', '', 'rp_ctn'),
						self::get_heading_margin_multi_field(' .upsells.products .product', '', 'bottom', '', 'rp_ctn')
					)
					),
					'h' => array(
					'options' => array(
						self::get_heading_margin_multi_field(' .upsells.products .product:hover', '', 'top', '', 'rp_ctn_h'),
						self::get_heading_margin_multi_field(' .upsells.products .product:hover', '', 'bottom', '', 'rp_ctn_h')
					)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_border(' .upsells.products .product', 'b_cnt')
					)
					),
					'h' => array(
					'options' => array(
						self::get_border(' .upsells.products .product', 'b_cnt', 'h')
					)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .upsells.products .product', 'r_c_cnt')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .upsells.products .product', 'r_c_cnt', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .upsells.products .product', 'sh_cnt')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .upsells.products .product', 'sh_cnt', 'h')
						)
					)
				))
			))
		);
		
		$rp_title = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color('.module .upsells > h2', 'b_c_rp_t', 'bg_c', 'background-color')
					)
					),
					'h' => array(
					'options' => array(
						self::get_color('.module .upsells > h2', 'b_c_rp_t_h', 'bg_c', 'background-color', 'h')
					)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_font_family('.module .upsells > h2', 'f_f_rp_t'),
						self::get_color('.module .upsells > h2', 'f_c_rp_t'),
						self::get_font_size('.module .upsells > h2', 'f_s_rp_t', ''),
						self::get_line_height('.module .upsells > h2', 'l_h_rp_t'),
						self::get_letter_spacing('.module .upsells > h2', 'l_s_rp_t'),
						self::get_text_transform('.module .upsells > h2', 't_t_rp_t'),
						self::get_font_style('.module .upsells > h2', 'f_st_rp_t', 'f_w_rp_t'),
						self::get_text_decoration('.module .upsells > h2', 't_d_r_rp_t'),
						self::get_text_shadow('.module .upsells > h2','t_sh_rp_t'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_font_family('.module .upsells > h2', 'f_f_rp_t', 'h'),
						self::get_color('.module .upsells > h2', 'f_c_rp_t',null, null, 'h'),
						self::get_font_size('.module .upsells > h2', 'f_s_rp_t', '', 'h'),
						self::get_line_height('.module .upsells > h2', 'l_h_rp_t', 'h'),
						self::get_letter_spacing('.module .upsells > h2', 'l_s_rp_t', 'h'),
						self::get_text_transform('.module .upsells > h2', 't_t_rp_t', 'h'),
						self::get_font_style('.module .upsells > h2', 'f_st_rp_t', 'f_w_rp_t', 'h'),
						self::get_text_decoration('.module .upsells > h2', 't_d_r_rp_t', 'h'),
						self::get_text_shadow('.module .upsells > h2','t_sh_rp_t', 'h'),
					)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_padding('.module .upsells > h2', 'p_rp_t')
					)
					),
					'h' => array(
					'options' => array(
						self::get_padding('.module .upsells > h2', 'p_rp_t', 'h')
					)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_heading_margin_multi_field('.module .upsells > h2', '', 'top', '', 'rp_t'),
						self::get_heading_margin_multi_field('.module .upsells > h2', '', 'bottom', '', 'rp_t')
					)
					),
					'h' => array(
					'options' => array(
						self::get_heading_margin_multi_field('.woocommerce .upsells > h2:hover', '', 'top', '', 'rp_t_h'),
						self::get_heading_margin_multi_field('.woocommerce .upsells > h2:hover', '', 'bottom', '', 'rp_t_h')
					)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_border('.module .upsells > h2', 'b_rp_t')
					)
					),
					'h' => array(
					'options' => array(
						self::get_border('.module .upsells > h2', 'b_rp_t', 'h')
					)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius('.module .upsells > h2', 'r_c_rp_t')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius('.module .upsells > h2', 'r_c_rp_t', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow('.module .upsells > h2', 'sh_rp_t')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow('.module .upsells > h2', 'sh_rp_t', 'h')
						)
					)
				))
			))
		);

		$sale_price = array(
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(' .product .price, .product p.price ins, .product span.price ins', 'f_f_s_p'),
							self::get_color_type(array(' .product .price', ' .product p.price ins', ' .product span.price ins'),'', 'f_c_t_s_p',  'f_c_s_p', 'f_g_c_s_p'),
							self::get_font_size(' .product .price, .product p.price ins, .product span.price ins', 'f_s_s_p', ''),
							self::get_line_height(' .product .price, .product p.price ins, .product span.price ins', 'l_h_s_p'),
							self::get_letter_spacing(' .product .price, .product p.price ins, .product span.price ins', 'l_s_s_p'),
							self::get_text_align(' .product .price, .product p.price ins, .product span.price ins', 't_a_s_p'),
							self::get_text_transform(' .product .price, .product p.price ins, .product span.price ins', 't_t_s_p'),
							self::get_font_style(' .product .price, .product p.price ins, .product span.price ins', 'f_st_s_p', 'f_w_s_p'),
							self::get_text_decoration(' .product .price, .product p.price ins, .product span.price ins', 't_d_r_s_p'),
							self::get_text_shadow(' .product .price, .product p.price ins,  .product span.price ins','t_sh_s_p','h'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(' .product .price, .product p.price ins, .product span.price ins', 'f_f_s_p_h'),
							self::get_color_type(array(' .product .price', ' .product p.price ins', ' .product span.price ins'),'', 'f_c_t_s_p_h',  'f_c_s_p_h', 'f_g_c_s_p_h'),
							self::get_font_size(' .product .price, .product p.price ins, .product span.price ins', 'f_s_s_p', '', 'h'),
							self::get_line_height(' .product .price, .product p.price ins, .product span.price ins', 'l_h_s_p', 'h'),
							self::get_letter_spacing(' .product .price, .product p.price ins, .product span.price ins', 'l_s_s_p', 'h'),
							self::get_text_align(' .product .price, .product p.price ins, .product span.price ins', 't_a_s_p', 'h'),
							self::get_text_transform(' .product .price, .product p.price ins, .product span.price ins', 't_t_s_p', 'h'),
							self::get_font_style(' .product .price, .product p.price ins, .product span.price ins', 'f_st_s_p', 'f_w_s_p', 'h'),
							self::get_text_decoration(' .product .price, .product p.price ins, .product span.price ins', 't_d_r_s_p', 'h'),
							self::get_text_shadow(' .product .price, .product p.price ins, .product span.price ins','t_sh_s_p','h'),
						)
					)
				))
			)),
		);

		$sale_badge = array(
			// Background
			self::get_expand('bg', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_color('.module .onsale', 'b_c_s_b', 'bg_c', 'background-color')
				)
				),
				'h' => array(
				'options' => array(
					self::get_color('.module .onsale', 'b_c_s_b', 'bg_c', 'background-color', 'h')
				)
				)
			))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color('.module .onsale', 'f_c_s_b'),
						self::get_font_size('.module .onsale', 'f_s_s_b', ''),
					)
					),
					'h' => array(
					'options' => array(
						self::get_color('.module .onsale', 'f_c_s_b', 'h'),
						self::get_font_size('.module .onsale', 'f_s_s_b', '', 'h'),
					)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_padding('.module .onsale', 'p_s_b')
				)
				),
				'h' => array(
				'options' => array(
					self::get_padding('.module .onsale', 'p_s_b', 'h')
				)
				)
			))
			)),
			// Margin
			self::get_expand('m', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_margin('.module .onsale', 'm_s_b')
				)
				),
				'h' => array(
				'options' => array(
					self::get_margin('.module .onsale', 'm_s_b', 'h')
				)
				)
			))
			)),
			// Border
			self::get_expand('b', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_border('.module .onsale', 'b_s_b')
				)
				),
				'h' => array(
				'options' => array(
					self::get_border('.module .onsale', 'b_s_b', 'h')
				)
				)
			))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius('.module .onsale', 'r_c_s_b')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius('.module .onsale', 'r_c_s_b', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow('.module .onsale', 'sh_s_b')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow('.module .onsale', 'sh_s_b', 'h')
						)
					)
				))
			))

		);

		$add_to_cart = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color('.module .upsells .add_to_cart_button', 'b_c_atc_btn', 'bg_c', 'background-color')
					)
					),
					'h' => array(
					'options' => array(
						self::get_color('.module .upsells .add_to_cart_button', 'b_c_atc_btn', 'bg_c', 'background-color', 'h')
					)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_font_family('.module .upsells .add_to_cart_button', 'f_f_atc_btn'),
						self::get_color('.module .upsells .add_to_cart_button', 'f_c_atc_btn'),
						self::get_font_size('.module .upsells .add_to_cart_button', 'f_s_atc_btn', ''),
						self::get_line_height('.module .upsells .add_to_cart_button', 'l_h_atc_btn'),
						self::get_letter_spacing('.module .upsells .add_to_cart_button', 'l_s_atc_btn'),
						self::get_text_transform('.module .upsells .add_to_cart_button', 't_t_atc_btn'),
						self::get_font_style('.module .upsells .add_to_cart_button', 'f_st_atc_btn', 'f_w_atc_btn'),
						self::get_text_decoration('.module .upsells .add_to_cart_button', 't_d_r_atc_btn'),
						self::get_text_shadow('.module .upsells .add_to_cart_button','t_sh_atc_btn'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_font_family('.module .upsells .add_to_cart_button', 'f_f_atc_btn', 'h'),
						self::get_color('.module .upsells .add_to_cart_button', 'f_c_atc_btn',null, null, 'h'),
						self::get_font_size('.module .upsells .add_to_cart_button', 'f_s_atc_btn', '', 'h'),
						self::get_line_height('.module .upsells .add_to_cart_button', 'l_h_atc_btn', 'h'),
						self::get_letter_spacing('.module .upsells .add_to_cart_button', 'l_s_atc_btn', 'h'),
						self::get_text_transform('.module .upsells .add_to_cart_button', 't_t_atc_btn', 'h'),
						self::get_font_style('.module .upsells .add_to_cart_button', 'f_st_atc_btn', 'f_w_atc_btn', 'h'),
						self::get_text_decoration('.module .upsells .add_to_cart_button', 't_d_r_atc_btn', 'h'),
						self::get_text_shadow('.module .upsells .add_to_cart_button','t_sh_atc_btn', 'h'),
					)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_padding('.module .upsells .add_to_cart_button', 'p_atc_btn')
					)
					),
					'h' => array(
					'options' => array(
						self::get_padding('.module .upsells .add_to_cart_button', 'p_atc_btn', 'h')
					)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_margin('.module .upsells .add_to_cart_button', 'm_atc_btn')
					)
					),
					'h' => array(
					'options' => array(
						self::get_margin('.module .upsells .add_to_cart_button', 'm_atc_btn', 'h')
					)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_border('.module .upsells .add_to_cart_button', 'b_atc_btn')
					)
					),
					'h' => array(
					'options' => array(
						self::get_border('.module .upsells .add_to_cart_button', 'b_atc_btn', 'h')
					)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius('.module .upsells .add_to_cart_button', 'r_c_atc_btn')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius('.module .upsells .add_to_cart_button', 'r_c_atc_btn', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow('.module .upsells .add_to_cart_button', 'sh_atc_btn')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow('.module .upsells .add_to_cart_button', 'sh_atc_btn', 'h')
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
				'c' => array(
					'label' => __('Container', 'themify'),
					'options' => $container
				),
				'rp_h' => array(
					'label' => __('Heading', 'themify'),
					'options' => $rp_title
				),
				's' => array(
					'label' => __('Price', 'themify'),
					'options' => $sale_price
				),
				's_b' => array(
					'label' => __('Sale Badge', 'themify'),
					'options' => $sale_badge
				),
				'a_c_b' => array(
					'label' => __('Add to Cart', 'themify'),
					'options' => $add_to_cart
				)
			)
		);
	}

	public function get_default_settings() {
		return array(
			'heading' => __( 'You may also like...', 'themify' ),
			'layout' => 'grid3'
		);
	}

	public function get_visual_type() {
		return 'ajax';
    }

    public function get_category() {
		return array( 'product' );
	}

}

if ( class_exists( 'WooCommerce' ) ) {
	Themify_Builder_Model::register_module('TB_Upsell_Products_Module');
}
