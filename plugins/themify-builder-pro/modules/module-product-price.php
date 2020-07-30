<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Module Name: Product Price
 * Description: 
 */

class TB_Product_Price_Module extends Themify_Builder_Component_Module {

    function __construct() {
		parent::__construct(array(
		    'name' => __('Product Price', 'themify'),
		    'slug' => 'product-price',
			'category' => array('product_single')
		));
    }

    public function get_options() {
		return array(
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
						self::get_font_family('.module .price', 'f_f_g'),
						self::get_color_type('.module .price','', 'f_c_t_g',  'f_c_g', 'f_g_c_g'),
						self::get_font_size('.module .price', 'f_s_g', ''),
						self::get_line_height('.module .price', 'l_h_g'),
						self::get_letter_spacing('.module .price', 'l_s_g'),
						self::get_text_align('.module .price', 't_a_g'),
						self::get_text_transform('.module .price', 't_t_g'),
						self::get_font_style('.module .price', 'f_st_g', 'f_w_g'),
						self::get_text_decoration('.module .price', 't_d_r_g'),
						self::get_text_shadow('.module .price','t_sh_g','h'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_font_family('.module .price', 'f_f_g_h'),
						self::get_color_type('.module .price','', 'f_c_t_g_h',  'f_c_g_h', 'f_g_c_g_h'),
						self::get_font_size('.module .price', 'f_s_g', '', 'h'),
						self::get_line_height('.module .price', 'l_h_g', 'h'),
						self::get_letter_spacing('.module .price', 'l_s_g', 'h'),
						self::get_text_align('.module .price', 't_a_g', 'h'),
						self::get_text_transform('.module .price', 't_t_g', 'h'),
						self::get_font_style('.module .price', 'f_st_g', 'f_w_g', 'h'),
						self::get_text_decoration('.module .price', 't_d_r_g', 'h'),
						self::get_text_shadow('.module .price','t_sh_g','h'),
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
			! method_exists( $this, 'get_max_height' ) ? array() :
			// Height & Min Height
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
			// Position
			self::get_expand('po', array( self::get_css_position())),
			// Display
			self::get_expand('disp', self::get_display())
		);

		$sale_price = array(
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_font_family(' p.price ins', 'f_f_s_p'),
						self::get_color_type(array(' p.price ins'),'', 'f_c_t_s_p',  'f_c_s_p', 'f_g_c_s_p'),
						self::get_font_size(' p.price ins', 'f_s_s_p', ''),
						self::get_line_height(' p.price ins', 'l_h_s_p'),
						self::get_letter_spacing(' p.price ins', 'l_s_s_p'),
						self::get_font_style(' p.price ins', 'f_st_s_p', 'f_w_s_p'),
						self::get_text_shadow(' p.price ins','t_sh_s_p'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_font_family(' p.price ins', 'f_f_s_p_h'),
						self::get_color_type(array(' p.price ins'),'', 'f_c_t_s_p_h',  'f_c_s_p_h', 'f_g_c_s_p_h'),
						self::get_font_size(' p.price ins', 'f_s_s_p', '', 'h'),
						self::get_line_height(' p.price ins', 'l_h_s_p', 'h'),
						self::get_letter_spacing(' p.price ins', 'l_s_s_p', 'h'),
						self::get_font_style(' p.price ins', 'f_st_s_p', 'f_w_s_p', 'h'),
						self::get_text_shadow(' p.price ins','t_sh_s_p','h'),
					)
					)
				))
			)),
		);

		return array(
			'type' => 'tabs',
			'options' => array(
				'g' => array(
					'options' => $general
				),
				's' => array(
					'label' => __('Sale Price', 'themify'),
					'options' => $sale_price
				)
			)
		);
	}



	public function get_visual_type() {
		return 'ajax';
    }

    public function get_category() {
		return array( 'product' );
	}

}

if ( themify_is_woocommerce_active()) {
	Themify_Builder_Model::register_module('TB_Product_Price_Module');
}
