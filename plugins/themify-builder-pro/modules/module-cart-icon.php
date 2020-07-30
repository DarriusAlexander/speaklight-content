<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Module Name: Cart Icon
 * Description: 
 */

class TB_Cart_Icon_Module extends Themify_Builder_Component_Module {

    function __construct() {
		parent::__construct(array(
		    'name' => __('Cart Icon', 'themify'),
		    'slug' => 'cart-icon',
		    'category' => array('site')
		));
    }

    public function get_options() {
		return array(
			array(
				'id' => 'icon',
				'type' => 'icon',
				'label' => __('Icon', 'themify')
			),
			array(
				'id' => 'style',
				'label' => __( 'Style', 'themify' ),
				'type' => 'select',
				'options' => array(
					'slide' => __( 'Slide', 'themify' ),
					'dropdown' => __( 'Drop Down', 'themify' )
				)
			),
			array(
				'id' => 'bubble',
				'label' => __('Bubble', 'themify'),
				'type' => 'toggle_switch',
				'options' => array(
					'on' => array('name'=>'on','value' =>'show'),
					'off' => array('name'=>'off', 'value' =>'')
				)
			),
			array(
				'id' => 'sub_total',
				'label' => __('Sub Total', 'themify'),
				'type' => 'toggle_switch',
				'options' => array(
					'on' => array('name'=>'on','value' =>'show'),
					'off' => array('name'=>'off', 'value' =>'')
				)
			),
			array(
				'id' => 'alignment',
				'label' => __( 'Alignment', 'themify' ),
				'type' => 'icon_radio',
				'options' => array(
					array( 'value' => 'left', 'name' => __( 'Left', 'themify' ), 'icon' => '<span class="ti-align-left"></span>' ),
					array( 'value' => 'center', 'name' => __( 'Center', 'themify' ), 'icon' => '<span class="ti-align-center"></span>' ),
					array( 'value' => 'right', 'name' => __( 'Right', 'themify' ), 'icon' => '<span class="ti-align-right"></span>' )
				),
			),
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
						self::get_color_type(array('',' .tbp_shop_cart_icon:before'),'', 'f_c_t_g',  'f_c_g', 'f_g_c_g'),
						self::get_font_size('', 'f_s_g', ''),
						self::get_line_height('', 'l_h_g'),
						self::get_letter_spacing('', 'l_s_g'),
						self::get_text_transform('', 't_t_g'),
						self::get_font_style('', 'f_st_g', 'f_w_g'),
						self::get_text_decoration('', 't_d_r_g'),
						self::get_text_shadow('','t_sh_g','h'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_font_family('', 'f_f_g_h'),
						self::get_color_type(array('',' .tbp_shop_cart_icon:before'),'', 'f_c_t_g_h',  'f_c_g_h', 'f_g_c_g_h'),
						self::get_font_size('', 'f_s_g', '', 'h'),
						self::get_line_height('', 'l_h_g', 'h'),
						self::get_letter_spacing('', 'l_s_g', 'h'),
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

		$sub_total = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color(' .woocommerce-Price-amount', 'b_c_c_s_t', 'bg_c', 'background-color')
					)
					),
					'h' => array(
					'options' => array(
						self::get_color(' .woocommerce-Price-amount', 'b_c_c_s_t', 'bg_c', 'background-color', 'h')
					)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_font_family(' .woocommerce-Price-amount', 'f_f_c_s_t'),
						self::get_color(' .woocommerce-Price-amount', 'f_c_c_s_t'),
						self::get_font_size(' .woocommerce-Price-amount', 'f_s_c_s_t'),
						self::get_letter_spacing(' .woocommerce-Price-amount', 'l_s_c_s_t'),
						self::get_line_height(' .woocommerce-Price-amount', 'l_h_c_s_t'),
						self::get_font_style(' .woocommerce-Price-amount', 'f_st_c_s_t', 'f_w_c_s_t'),
						self::get_text_shadow(' .woocommerce-Price-amount', 't_sh_c_s_t'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_font_family(' .woocommerce-Price-amount', 'f_f_c_s_t', 'h'),
						self::get_color(' .woocommerce-Price-amount', 'f_c_c_s_t', null,null, 'h'),
						self::get_font_size(' .woocommerce-Price-amount', 'f_s_c_s_t', 'h'),
						self::get_letter_spacing(' .woocommerce-Price-amount', 'l_s_c_s_t', 'h'),
						self::get_line_height(' .woocommerce-Price-amount', 'l_h_c_s_t', 'h'),
						self::get_font_style(' .woocommerce-Price-amount', 'f_st_c_s_t', 'f_w_c_s_t', 'h'),
						self::get_text_shadow(' .woocommerce-Price-amount', 't_sh_c_s_t', 'h'),
					)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_padding(' .woocommerce-Price-amount', 'p_c_s_t')
					)
					),
					'h' => array(
					'options' => array(
						self::get_padding(' .woocommerce-Price-amount', 'p_c_s_t', 'h')
					)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_margin(' .woocommerce-Price-amount', 'm_c_s_t'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_margin(' .woocommerce-Price-amount', 'm_c_s_t', 'h'),
					)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_border(' .woocommerce-Price-amount', 'b_c_s_t')
					)
					),
					'h' => array(
					'options' => array(
						self::get_border(' .woocommerce-Price-amount', 'b_c_s_t', 'h')
					)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .woocommerce-Price-amount', 'r_c_c_s_t')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .woocommerce-Price-amount', 'r_c_c_s_t', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .woocommerce-Price-amount', 'sh_c_s_t')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .woocommerce-Price-amount', 'sh_c_s_t', 'h')
						)
					)
				))
			)),
		);

		$icon = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color(' .tbp_shop_cart_icon', 'b_c_c_i_i', 'bg_c', 'background-color')
					)
					),
					'h' => array(
					'options' => array(
						self::get_color(' .tbp_shop_cart_icon', 'b_c_c_i_i', 'bg_c', 'background-color', 'h')
					)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color(array(' .tbp_shop_cart_icon',' .tbp_shop_cart_icon:before'), 'f_c_c_i_i'),
						self::get_font_size(' .tbp_shop_cart_icon', 'f_s_c_i_i'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_color(array(' .tbp_shop_cart_icon',' .tbp_shop_cart_icon:before'), 'f_c_c_i_i', null,null, 'h'),
						self::get_font_size(' .tbp_shop_cart_icon', 'f_s_c_i_i', '', 'h'),
					)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_padding(' .tbp_shop_cart_icon', 'p_c_i_i')
					)
					),
					'h' => array(
					'options' => array(
						self::get_padding(' .tbp_shop_cart_icon', 'p_c_i_i', 'h')
					)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_margin(' .tbp_shop_cart_icon', 'm_c_i_i'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_margin(' .tbp_shop_cart_icon', 'm_c_i_i', 'h'),
					)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_border(' .tbp_shop_cart_icon', 'b_c_i_i')
					)
					),
					'h' => array(
					'options' => array(
						self::get_border(' .tbp_shop_cart_icon', 'b_c_i_i', 'h')
					)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .tbp_shop_cart_icon', 'r_c_c_i_i')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .tbp_shop_cart_icon', 'r_c_c_i_i', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .tbp_shop_cart_icon', 'sh_c_i_i')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .tbp_shop_cart_icon', 'sh_c_i_i', 'h')
						)
					)
				))
			)),
		);

		$bubble = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color(' .tbp_cart_count', 'b_c_c_i_b', 'bg_c', 'background-color')
					)
					),
					'h' => array(
					'options' => array(
						self::get_color(' .tbp_cart_count', 'b_c_c_i_b', 'bg_c', 'background-color', 'h')
					)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_font_family(' .tbp_cart_count', 'f_f_c_i_b'),
						self::get_color(' .tbp_cart_count', 'f_c_c_i_b'),
						self::get_font_size(' .tbp_cart_count', 'f_s_c_i_b'),
						self::get_letter_spacing(' .tbp_cart_count', 'l_s_c_i_b'),
						self::get_line_height(' .tbp_cart_count', 'l_h_c_i_b'),
						self::get_font_style(' .tbp_cart_count', 'f_st_c_i_b', 'f_w_c_i_b'),
						self::get_text_shadow(' .tbp_cart_count', 't_sh_c_i_b'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_font_family(' .tbp_cart_count', 'f_f_c_i_b','h'),
						self::get_color(' .tbp_cart_count', 'f_c_c_i_b', null,null, 'h'),
						self::get_font_size(' .tbp_cart_count', 'f_s_c_i_b', '', 'h'),
						self::get_letter_spacing(' .tbp_cart_count', 'l_s_c_i_b', 'h'),
						self::get_line_height(' .tbp_cart_count', 'l_h_c_i_b', 'h'),
						self::get_font_style(' .tbp_cart_count', 'f_st_c_i_b', 'f_w_c_i_b', 'h'),
						self::get_text_shadow(' .tbp_cart_count', 't_sh_c_i_b','h'),
					)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_padding(' .tbp_cart_count', 'p_c_i_b')
					)
					),
					'h' => array(
					'options' => array(
						self::get_padding(' .tbp_cart_count', 'p_c_i_b', 'h')
					)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_margin(' .tbp_cart_count', 'm_c_i_b'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_margin(' .tbp_cart_count', 'm_c_i_b', 'h'),
					)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_border(' .tbp_cart_count', 'b_c_i_b')
					)
					),
					'h' => array(
					'options' => array(
						self::get_border(' .tbp_cart_count', 'b_c_i_b', 'h')
					)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .tbp_cart_count', 'r_c_c_i_b')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .tbp_cart_count', 'r_c_c_i_b', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .tbp_cart_count', 'sh_c_i_b')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .tbp_cart_count', 'sh_c_i_b', 'h')
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
				's_t' => array(
					'label' => __('Sub Total', 'themify'),
					'options' => $sub_total
				),
				's_i' => array(
					'label' => __('Icon', 'themify'),
					'options' => $icon
				),
				'b_b' => array(
					'label' => __('Bubble', 'themify'),
					'options' => $bubble
				)
			)
		);
	}

	public function get_default_settings() {
		return array(
			'icon' => 'ti-shopping-cart'
		);
	}

	public function get_visual_type() {
		return 'ajax';
    }

    public function get_category() {
		return array( 'product' );
	}

}

if ( themify_is_woocommerce_active() ) {
	Themify_Builder_Model::register_module('TB_Cart_Icon_Module');
}
