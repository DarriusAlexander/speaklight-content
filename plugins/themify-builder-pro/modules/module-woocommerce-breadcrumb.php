<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Module Name: WooCommerce Breadcrumb
 * Description: 
 */

class TB_WooCommerce_Breadcrumb_Module extends Themify_Builder_Component_Module {

    function __construct() {
		parent::__construct(array(
		    'name' => __('WooCommerce Breadcrumb', 'tbp'),
		    'slug' => 'woocommerce-breadcrumb',
			'category' => array('product_single')
		));
    }

    public function get_options() {
		return array(
			array(
				'id' => 'sep',
				'type' => 'text',
				'label' => __('Separator', 'themify')
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
						self::get_font_family(' .woocommerce-breadcrumb', 'f_f'),
						self::get_color_type(' .woocommerce-breadcrumb','', 'f_c_t', 'f_c', 'f_g_c'),
						self::get_font_size(' .woocommerce-breadcrumb', 'f_s'),
						self::get_line_height(' .woocommerce-breadcrumb', 'l_h'),
						self::get_letter_spacing(' .woocommerce-breadcrumb', 'l_s'),
						self::get_text_align(' .woocommerce-breadcrumb', 't_a'),
						self::get_text_transform(' .woocommerce-breadcrumb', 't_t'),
						self::get_font_style(' .woocommerce-breadcrumb', 'f_st', 'f_w'),
						self::get_text_decoration(' .woocommerce-breadcrumb', 't_d_r'),
						self::get_text_shadow(' .woocommerce-breadcrumb','t_sh'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_font_family(' .woocommerce-breadcrumb', 'f_f_h','h'),
						self::get_color_type(' .woocommerce-breadcrumb:hover','', 'f_c_t_h', 'f_c_h', 'f_g_c_h','h'),
						self::get_font_size(' .woocommerce-breadcrumb', 'f_s', '', 'h'),
						self::get_line_height(' .woocommerce-breadcrumb', 'l_h', 'h'),
						self::get_letter_spacing(' .woocommerce-breadcrumb', 'l_s', 'h'),
						self::get_text_align(' .woocommerce-breadcrumb', 't_a', 'h'),
						self::get_text_transform(' .woocommerce-breadcrumb', 't_t', 'h'),
						self::get_font_style(' .woocommerce-breadcrumb', 'f_st', 'f_w', 'h'),
						self::get_text_decoration(' .woocommerce-breadcrumb', 't_d_r', 'h'),
						self::get_text_shadow(' .woocommerce-breadcrumb','t_sh','h'),
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
						self::get_text_decoration(' a', 't_d')
					)
					),
					'h' => array(
					'options' => array(
						self::get_color(' a', 'l_c',null, null, 'hover'),
						self::get_text_decoration(' a', 't_d', 'h')
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

		return array(
			'type' => 'tabs',
			'options' => array(
				'g' => array(
					'options' => $general
				)
			)
		);
	}

	public function get_default_settings() {
		return array(
			'sep' => '/'
		);
	}

	public function get_visual_type() {
		return 'ajax';
    }

    public function get_category() {
		return array( 'product' );
	}

}

if (themify_is_woocommerce_active()) {
	Themify_Builder_Model::register_module('TB_WooCommerce_Breadcrumb_Module');
}
