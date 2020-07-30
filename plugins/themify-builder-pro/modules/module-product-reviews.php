<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Module Name: Product Reviews
 * Description: 
 */

class TB_Product_Reviews_Module extends Themify_Builder_Component_Module {
    
    public static $hasDescription=false;
    public static $hasAdditionaly=false;
    public static $hasReviews=false;
    public static $elId=null;
    public static $singleTab=false;

    function __construct() {
		parent::__construct(array(
		    'name' => __('Product Review Tabs', 'themify'),
		    'slug' => 'product-reviews',
		    'category' => array('product_single')
		));
    }

    public function get_options() {
		return array(
			array(
				'id' => 'description',
				'type' => 'toggle_switch',
				'label' => __('Description', 'themify'),
				'options'   => array(
					'on'  => array( 'name' => 'yes', 'value' => 's' ),
					'off' => array( 'name' => 'no', 'value' => 'hi' ),
				)
			),
		    array(
				'id' => 'additionaly',
				'type' => 'toggle_switch',
				'label' => __('Additional information', 'themify'),
				'options'   => array(
					'on'  => array( 'name' => 'yes', 'value' => 's' ),
					'off' => array( 'name' => 'no', 'value' => 'hi' ),
				)
			),
			array(
				'id' => 'reviews',
				'type' => 'toggle_switch',
				'label' => __('Reviews', 'themify'),
				'default' => 'on',
				'options'   => array(
					'on'  => array( 'name' => 'yes', 'value' => 's' ),
					'off' => array( 'name' => 'no', 'value' => 'hi' ),
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
						self::get_font_family(' .product', 'f_f_g'),
						self::get_color_type(' .product','', 'f_c_t_g',  'f_c_g', 'f_g_c_g'),
						self::get_font_size(' .product', 'f_s_g', ''),
						self::get_line_height(' .product', 'l_h_g'),
						self::get_letter_spacing(' .product', 'l_s_g'),
						self::get_text_align(' .product', 't_a_g'),
						self::get_text_transform(' .product', 't_t_g'),
						self::get_font_style(' .product', 'f_st_g', 'f_w_g'),
						self::get_text_decoration(' .product', 't_d_r_g'),
						self::get_text_shadow(' .product','t_sh_g'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_font_family(' .product', 'f_f_g_h'),
						self::get_color_type(' .product','', 'f_c_t_g_h',  'f_c_g_h', 'f_g_c_g_h'),
						self::get_font_size(' .product', 'f_s_g', '', 'h'),
						self::get_line_height(' .product', 'l_h_g', 'h'),
						self::get_letter_spacing(' .product', 'l_s_g', 'h'),
						self::get_text_align(' .product', 't_a_g', 'h'),
						self::get_text_transform(' .product', 't_t_g', 'h'),
						self::get_font_style(' .product', 'f_st_g', 'f_w_g', 'h'),
						self::get_text_decoration(' .product', 't_d_r_g', 'h'),
						self::get_text_shadow(' .product','t_sh_g_h','h'),
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
			// Display
			self::get_expand('disp', self::get_display())
		);
		
		$tab_title = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color('.module .product .woocommerce-tabs .tabs li', 'b_c_r_t', 'bg_c', 'background-color')
					)
					),
					'h' => array(
					'options' => array(
						self::get_color('.module .product .woocommerce-tabs .tabs li', 'b_c_r_t', 'bg_c', 'background-color', 'h')
					)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_font_family('.module .product .woocommerce-tabs .tabs li a', 'f_f_r_t'),
						self::get_color('.module .product .woocommerce-tabs .tabs li a', 'f_c_r_t'),
						self::get_font_size('.module .product .woocommerce-tabs .tabs li a', 'f_s_r_t', ''),
						self::get_line_height('.module .product .woocommerce-tabs .tabs li a', 'l_h_r_t'),
						self::get_letter_spacing('.module .product .woocommerce-tabs .tabs li a', 'l_s_r_t'),
						self::get_text_transform('.module .product .woocommerce-tabs .tabs li a', 't_t_r_t'),
						self::get_font_style('.module .product .woocommerce-tabs .tabs li a', 'f_st_r_t', 'f_w_r_t'),
						self::get_text_decoration('.module .product .woocommerce-tabs .tabs li a', 't_d_r_r_t'),
						self::get_text_shadow('.module .product .woocommerce-tabs .tabs li a','t_sh_r_t'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_font_family('.module .product .woocommerce-tabs .tabs li a', 'f_f_r_t', 'h'),
						self::get_color('.module .product .woocommerce-tabs .tabs li a', 'f_c_r_t',null, null, 'h'),
						self::get_font_size('.module .product .woocommerce-tabs .tabs li a', 'f_s_r_t', '', 'h'),
						self::get_line_height('.module .product .woocommerce-tabs .tabs li a', 'l_h_r_t', 'h'),
						self::get_letter_spacing('.module .product .woocommerce-tabs .tabs li a', 'l_s_r_t', 'h'),
						self::get_text_transform('.module .product .woocommerce-tabs .tabs li a', 't_t_r_t', 'h'),
						self::get_font_style('.module .product .woocommerce-tabs .tabs li a', 'f_st_r_t', 'f_w_r_t', 'h'),
						self::get_text_decoration('.module .product .woocommerce-tabs .tabs li a', 't_d_r_r_t', 'h'),
						self::get_text_shadow('.module .product .woocommerce-tabs .tabs li a','t_sh_r_t', 'h'),
					)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_padding('.module .product .woocommerce-tabs .tabs li', 'p_r_t')
					)
					),
					'h' => array(
					'options' => array(
						self::get_padding('.module .product .woocommerce-tabs .tabs li', 'p_r_t', 'h')
					)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_margin('.module .product .woocommerce-tabs .tabs li', 'm_r_t')
					)
					),
					'h' => array(
					'options' => array(
						self::get_margin('.module .product .woocommerce-tabs .tabs li', 'm_r_t', 'h')
					)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_border('.module .product .woocommerce-tabs .tabs li', 'b_r_t')
					)
					),
					'h' => array(
					'options' => array(
						self::get_border('.module .product .woocommerce-tabs .tabs li', 'b_r_t', 'h')
					)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius('.module .product .woocommerce-tabs .tabs li', 'r_c_r_t')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius('.module .product .woocommerce-tabs .tabs li', 'r_c_r_t', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow('.module .product .woocommerce-tabs .tabs li', 'sh_r_t')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow('.module .product .woocommerce-tabs .tabs li', 'sh_r_t', 'h')
						)
					)
				))
			))
		);

		$tab_title_active = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color('.module .product .woocommerce-tabs .tabs li.active', 'b_c_r_t_a', 'bg_c', 'background-color')
					)
					),
					'h' => array(
					'options' => array(
						self::get_color('.module .product .woocommerce-tabs .tabs li.active', 'b_c_r_t_a', 'bg_c', 'background-color', 'h')
					)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_font_family('.module .product .woocommerce-tabs .tabs li.active a', 'f_f_r_t_a'),
						self::get_color('.module .product .woocommerce-tabs .tabs li.active a', 'f_c_r_t_a'),
						self::get_font_size('.module .product .woocommerce-tabs .tabs li.active a', 'f_s_r_t_a', ''),
						self::get_line_height('.module .product .woocommerce-tabs .tabs li.active a', 'l_h_r_t_a'),
						self::get_letter_spacing('.module .product .woocommerce-tabs .tabs li.active a', 'l_s_r_t_a'),
						self::get_text_transform('.module .product .woocommerce-tabs .tabs li.active a', 't_t_r_t_a'),
						self::get_font_style('.module .product .woocommerce-tabs .tabs li.active a', 'f_st_r_t_a', 'f_w_r_t_a'),
						self::get_text_decoration('.module .product .woocommerce-tabs .tabs li.active a', 't_d_r_r_t_a'),
						self::get_text_shadow('.module .product .woocommerce-tabs .tabs li.active a','t_sh_r_t_a'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_font_family('.module .product .woocommerce-tabs .tabs li.active a', 'f_f_r_t_a', 'h'),
						self::get_color('.module .product .woocommerce-tabs .tabs li.active a', 'f_c_r_t_a',null, null, 'h'),
						self::get_font_size('.module .product .woocommerce-tabs .tabs li.active a', 'f_s_r_t_a', '', 'h'),
						self::get_line_height('.module .product .woocommerce-tabs .tabs li.active a', 'l_h_r_t_a', 'h'),
						self::get_letter_spacing('.module .product .woocommerce-tabs .tabs li.active a', 'l_s_r_t_a', 'h'),
						self::get_text_transform('.module .product .woocommerce-tabs .tabs li.active a', 't_t_r_t_a', 'h'),
						self::get_font_style('.module .product .woocommerce-tabs .tabs li.active a', 'f_st_r_t_a', 'f_w_r_t_a', 'h'),
						self::get_text_decoration('.module .product .woocommerce-tabs .tabs li.active a', 't_d_r_r_t_a', 'h'),
						self::get_text_shadow('.module .product .woocommerce-tabs .tabs li.active a','t_sh_r_t_a', 'h'),
					)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_padding('.module .product .woocommerce-tabs .tabs li.active', 'p_r_t_a')
					)
					),
					'h' => array(
					'options' => array(
						self::get_padding('.module .product .woocommerce-tabs .tabs li.active', 'p_r_t_a', 'h')
					)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_margin('.module .product .woocommerce-tabs .tabs li.active', 'm_r_t_a')
					)
					),
					'h' => array(
					'options' => array(
						self::get_margin('.module .product .woocommerce-tabs .tabs li.active', 'm_r_t_a', 'h')
					)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_border('.module .product .woocommerce-tabs .tabs li.active', 'b_r_t_a')
					)
					),
					'h' => array(
					'options' => array(
						self::get_border('.module .product .woocommerce-tabs .tabs li.active', 'b_r_t_a', 'h')
					)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius('.module .product .woocommerce-tabs .tabs li.active', 'r_c_r_t_a')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius('.module .product .woocommerce-tabs .tabs li.active', 'r_c_r_t_a', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow('.module .product .woocommerce-tabs .tabs li.active', 'sh_r_t_a')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow('.module .product .woocommerce-tabs .tabs li.active', 'sh_r_t_a', 'h')
						)
					)
				))
			))
		);

		$tab_content = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color('.module .product .woocommerce-tabs .panel', 'b_c_r_t_c', 'bg_c', 'background-color')
					)
					),
					'h' => array(
					'options' => array(
						self::get_color('.module .product .woocommerce-tabs .panel', 'b_c_r_t_c', 'bg_c', 'background-color', 'h')
					)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_font_family('.module .product .woocommerce-tabs .panel', 'f_f_r_t_c'),
						self::get_color('.module .product .woocommerce-tabs .panel', 'f_c_r_t_c'),
						self::get_font_size('.module .product .woocommerce-tabs .panel', 'f_s_r_t_c', ''),
						self::get_line_height('.module .product .woocommerce-tabs .panel', 'l_h_r_t_c'),
						self::get_letter_spacing('.module .product .woocommerce-tabs .panel', 'l_s_r_t_c'),
						self::get_text_align('.module .product .woocommerce-tabs .panel', 't_a_r_t_c'),
						self::get_text_transform('.module .product .woocommerce-tabs .panel', 't_t_r_t_c'),
						self::get_font_style('.module .product .woocommerce-tabs .panel', 'f_st_r_t_c', 'f_w_r_t_c'),
						self::get_text_decoration('.module .product .woocommerce-tabs .panel', 't_d_r_r_t_c'),
						self::get_text_shadow('.module .product .woocommerce-tabs .panel', 't_sh_r_t_c'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_font_family('.module .product .woocommerce-tabs .panel', 'f_f_r_t_c', 'h'),
						self::get_color('.module .product .woocommerce-tabs .panel', 'f_c_r_t_c',null, null, 'h'),
						self::get_font_size('.module .product .woocommerce-tabs .panel', 'f_s_r_t_c', '', 'h'),
						self::get_line_height('.module .product .woocommerce-tabs .panel', 'l_h_r_t_c', 'h'),
						self::get_letter_spacing('.module .product .woocommerce-tabs .panel', 'l_s_r_t_c', 'h'),
						self::get_text_align('.module .product .woocommerce-tabs .panel', 't_a_r_t_c', 'h'),
						self::get_text_transform('.module .product .woocommerce-tabs .panel', 't_t_r_t_c', 'h'),
						self::get_font_style('.module .product .woocommerce-tabs .panel', 'f_st_r_t_c', 'f_w_r_t_c', 'h'),
						self::get_text_decoration('.module .product .woocommerce-tabs .panel', 't_d_r_r_t_c', 'h'),
						self::get_text_shadow('.module .product .woocommerce-tabs .panel', 't_sh_r_t_c', 'h'),
					)
					)
				))
			)),
			// Link
			self::get_expand('l', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color('.module .product .woocommerce-tabs .panel a', 'l_c'),
						self::get_text_decoration('.module .product .woocommerce-tabs .panel a', 't_d_l')
					)
					),
					'h' => array(
					'options' => array(
						self::get_color('.module .product .woocommerce-tabs .panel a', 'l_c',null, null, 'hover'),
						self::get_text_decoration('.module .product .woocommerce-tabs .panel a', 't_d_l', 'h')
					)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_padding('.module .product .woocommerce-tabs .panel', 'p_r_t_c')
					)
					),
					'h' => array(
					'options' => array(
						self::get_padding('.module .product .woocommerce-tabs .panel', 'p_r_t_c', 'h')
					)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_margin('.module .product .woocommerce-tabs .panel', 'm_r_t_c')
					)
					),
					'h' => array(
					'options' => array(
						self::get_margin('.module .product .woocommerce-tabs .panel', 'm_r_t_c', 'h')
					)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_border('.module .product .woocommerce-tabs .panel', 'b_r_t_c')
					)
					),
					'h' => array(
					'options' => array(
						self::get_border('.module .product .woocommerce-tabs .panel', 'b_r_t_c', 'h')
					)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius('.module .product .woocommerce-tabs .panel', 'r_c_r_t_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius('.module .product .woocommerce-tabs .panel', 'r_c_r_t_c', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow('.module .product .woocommerce-tabs .panel', 'sh_r_t_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow('.module .product .woocommerce-tabs .panel', 'sh_r_t_c', 'h')
						)
					)
				))
			))
		);
		
		$review_form_container = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color('.module .product .woocommerce-tabs .panel .woocommerce-Reviews', 'b_c_r_f_ctr', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color('.module .product .woocommerce-tabs .panel .woocommerce-Reviews"]', 'b_c_r_f_ctr', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(array('.module .product .woocommerce-tabs .panel .woocommerce-Reviews', '.module .product .woocommerce-tabs .panel .woocommerce-Reviews p'), 'f_f_r_f_ctr'),
							self::get_color(array('.module .product .woocommerce-tabs .panel .woocommerce-Reviews', '.module .product .woocommerce-tabs .panel .woocommerce-Reviews p'), 'f_c_r_f_ctr'),
							self::get_font_size(array('.module .product .woocommerce-tabs .panel .woocommerce-Reviews', '.module .product .woocommerce-tabs .panel .woocommerce-Reviews p'), 'f_s_r_f_ctr', ''),
							self::get_line_height(array('.module .product .woocommerce-tabs .panel .woocommerce-Reviews', '.module .product .woocommerce-tabs .panel .woocommerce-Reviews p'), 'l_h_r_f_ctr'),
							self::get_letter_spacing(array('.module .product .woocommerce-tabs .panel .woocommerce-Reviews', '.module .product .woocommerce-tabs .panel .woocommerce-Reviews p'), 'l_s_r_f_ctr'),
							self::get_text_align(array('.module .product .woocommerce-tabs .panel .woocommerce-Reviews', '.module .product .woocommerce-tabs .panel .woocommerce-Reviews p'), 't_a_r_f_ctr'),
							self::get_text_transform(array('.module .product .woocommerce-tabs .panel .woocommerce-Reviews', '.module .product .woocommerce-tabs .panel .woocommerce-Reviews p'), 't_t_r_f_ctr'),
							self::get_font_style(array('.module .product .woocommerce-tabs .panel .woocommerce-Reviews', '.module .product .woocommerce-tabs .panel .woocommerce-Reviews p'), 'f_st_r_f_ctr', 'f_w_r_f_ctr'),
							self::get_text_decoration(array('.module .product .woocommerce-tabs .panel .woocommerce-Reviews', '.module .product .woocommerce-tabs .panel .woocommerce-Reviews p'), 't_d_r_r_f_ctr'),
							self::get_text_shadow(array('.module .product .woocommerce-tabs .panel .woocommerce-Reviews', '.module .product .woocommerce-tabs .panel .woocommerce-Reviews p'),'t_sh_r_f_ctr'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(array('.module .product .woocommerce-tabs .panel .woocommerce-Reviews', '.module .product .woocommerce-tabs .panel .woocommerce-Reviews p'), 'f_f_r_f_ctr', 'h'),
							self::get_color(array('.module .product .woocommerce-tabs .panel .woocommerce-Reviews', '.module .product .woocommerce-tabs .panel .woocommerce-Reviews p'), 'f_c_r_f_ctr',null, null, 'h'),
							self::get_font_size(array('.module .product .woocommerce-tabs .panel .woocommerce-Reviews', '.module .product .woocommerce-tabs .panel .woocommerce-Reviews p'), 'f_s_r_f_ctr', '', 'h'),
							self::get_line_height(array('.module .product .woocommerce-tabs .panel .woocommerce-Reviews', '.module .product .woocommerce-tabs .panel .woocommerce-Reviews p'), 'l_h_r_f_ctr', 'h'),
							self::get_letter_spacing(array('.module .product .woocommerce-tabs .panel .woocommerce-Reviews', '.module .product .woocommerce-tabs .panel .woocommerce-Reviews p'), 'l_s_r_f_ctr', 'h'),
							self::get_text_align(array('.module .product .woocommerce-tabs .panel .woocommerce-Reviews', '.module .product .woocommerce-tabs .panel .woocommerce-Reviews p'), 't_a_r_f_ctr', 'h'),
							self::get_text_transform(array('.module .product .woocommerce-tabs .panel .woocommerce-Reviews', '.module .product .woocommerce-tabs .panel .woocommerce-Reviews p'), 't_t_r_f_ctr', 'h'),
							self::get_font_style(array('.module .product .woocommerce-tabs .panel .woocommerce-Reviews', '.module .product .woocommerce-tabs .panel .woocommerce-Reviews p'), 'f_st_r_f_ctr', 'f_w_r_f_ctr', 'h'),
							self::get_text_decoration(array('.module .product .woocommerce-tabs .panel .woocommerce-Reviews', '.module .product .woocommerce-tabs .panel .woocommerce-Reviews p'), 't_d_r_r_f_ctr', 'h'),
							self::get_text_shadow(array('.module .product .woocommerce-tabs .panel .woocommerce-Reviews', '.module .product .woocommerce-tabs .panel .woocommerce-Reviews p'),'t_sh_r_f_ctr', 'h'),
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_padding('.module .product .woocommerce-tabs .panel .woocommerce-Reviews', 'p_r_f_ctr')
					)
					),
					'h' => array(
					'options' => array(
						self::get_padding('.module .product .woocommerce-tabs .panel .woocommerce-Reviews', 'p_r_f_ctr', 'h')
					)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_margin('.module .product .woocommerce-tabs .panel .woocommerce-Reviews', 'm_r_f_ctr')
					)
					),
					'h' => array(
					'options' => array(
						self::get_margin('.module .product .woocommerce-tabs .panel .woocommerce-Reviews', 'm_r_f_ctr', 'h')
					)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_border('.module .product .woocommerce-tabs .panel .woocommerce-Reviews', 'b_r_f_ctr')
					)
					),
					'h' => array(
					'options' => array(
						self::get_border('.module .product .woocommerce-tabs .panel .woocommerce-Reviews', 'b_r_f_ctr', 'h')
					)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius('.module .product .woocommerce-tabs .panel .woocommerce-Reviews', 'r_c_r_f_ctr')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius('.module .product .woocommerce-tabs .panel .woocommerce-Reviews', 'r_c_r_f_ctr', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow('.module .product .woocommerce-tabs .panel .woocommerce-Reviews', 'sh_r_f_ctr')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow('.module .product .woocommerce-tabs .panel .woocommerce-Reviews', 'sh_r_f_ctr', 'h')
						)
					)
				))
			))
		);
		
		$review_form = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color(array('.module .product .woocommerce-tabs .panel input[type="text"]', '.module .product .woocommerce-tabs .panel input[type="email"]', '.module .product .woocommerce-tabs .panel textarea'), 'b_c_r_f', 'bg_c', 'background-color')
					)
					),
					'h' => array(
					'options' => array(
						self::get_color(array('.module .product .woocommerce-tabs .panel input[type="text"]', '.module .product .woocommerce-tabs .panel input[type="email"]', '.module .product .woocommerce-tabs .panel textarea'), 'b_c_r_f', 'bg_c', 'background-color', 'h')
					)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_font_family(array('.module .product .woocommerce-tabs .panel input[type="text"]', '.module .product .woocommerce-tabs .panel input[type="email"]', '.module .product .woocommerce-tabs .panel textarea', '.module #commentform p label'), 'f_f_r_f'),
						self::get_color(array('.module .product .woocommerce-tabs .panel input[type="text"]', '.module .product .woocommerce-tabs .panel input[type="email"]', '.module .product .woocommerce-tabs .panel textarea', '.module #commentform p label'), 'f_c_r_f'),
						self::get_font_size(array('.module .product .woocommerce-tabs .panel input[type="text"]', '.module .product .woocommerce-tabs .panel input[type="email"]', '.module .product .woocommerce-tabs .panel textarea', '.module #commentform p label'), 'f_s_r_f', ''),
						self::get_line_height(array('.module .product .woocommerce-tabs .panel input[type="text"]', '.module .product .woocommerce-tabs .panel input[type="email"]', '.module .product .woocommerce-tabs .panel textarea', '.module #commentform p label'), 'l_h_r_f'),
						self::get_letter_spacing(array('.module .product .woocommerce-tabs .panel input[type="text"]', '.module .product .woocommerce-tabs .panel input[type="email"]', '.module .product .woocommerce-tabs .panel textarea', '.module #commentform p label'), 'l_s_r_f'),
						self::get_text_align(array('.module .product .woocommerce-tabs .panel input[type="text"]', '.module .product .woocommerce-tabs .panel input[type="email"]', '.module .product .woocommerce-tabs .panel textarea', '.module #commentform p label'), 't_a_r_f'),
						self::get_text_transform(array('.module .product .woocommerce-tabs .panel input[type="text"]', '.module .product .woocommerce-tabs .panel input[type="email"]', '.module .product .woocommerce-tabs .panel textarea', '.module #commentform p label'), 't_t_r_f'),
						self::get_font_style(array('.module .product .woocommerce-tabs .panel input[type="text"]', '.module .product .woocommerce-tabs .panel input[type="email"]', '.module .product .woocommerce-tabs .panel textarea', '.module #commentform p label'), 'f_st_r_f', 'f_w_r_f'),
						self::get_text_decoration(array('.module .product .woocommerce-tabs .panel input[type="text"]', '.module .product .woocommerce-tabs .panel input[type="email"]', '.module .product .woocommerce-tabs .panel textarea', '.module #commentform p label'), 't_d_r_r_f'),
						self::get_text_shadow(array('.module .product .woocommerce-tabs .panel input[type="text"]', '.module .product .woocommerce-tabs .panel input[type="email"]', '.module .product .woocommerce-tabs .panel textarea', '.module #commentform p label'),'t_sh_r_f'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_font_family(array('.module .product .woocommerce-tabs .panel input[type="text"]', '.module .product .woocommerce-tabs .panel input[type="email"]', '.module .product .woocommerce-tabs .panel textarea', '.module #commentform p label'), 'f_f_r_f', 'h'),
						self::get_color(array('.module .product .woocommerce-tabs .panel input[type="text"]', '.module .product .woocommerce-tabs .panel input[type="email"]', '.module .product .woocommerce-tabs .panel textarea', '.module #commentform p label'), 'f_c_r_f',null, null, 'h'),
						self::get_font_size(array('.module .product .woocommerce-tabs .panel input[type="text"]', '.module .product .woocommerce-tabs .panel input[type="email"]', '.module .product .woocommerce-tabs .panel textarea', '.module #commentform p label'), 'f_s_r_f', '', 'h'),
						self::get_line_height(array('.module .product .woocommerce-tabs .panel input[type="text"]', '.module .product .woocommerce-tabs .panel input[type="email"]', '.module .product .woocommerce-tabs .panel textarea', '.module #commentform p label'), 'l_h_r_f', 'h'),
						self::get_letter_spacing(array('.module .product .woocommerce-tabs .panel input[type="text"]', '.module .product .woocommerce-tabs .panel input[type="email"]', '.module .product .woocommerce-tabs .panel textarea', '.module #commentform p label'), 'l_s_r_f', 'h'),
						self::get_text_align(array('.module .product .woocommerce-tabs .panel input[type="text"]', '.module .product .woocommerce-tabs .panel input[type="email"]', '.module .product .woocommerce-tabs .panel textarea', '.module #commentform p label'), 't_a_r_f', 'h'),
						self::get_text_transform(array('.module .product .woocommerce-tabs .panel input[type="text"]', '.module .product .woocommerce-tabs .panel input[type="email"]', '.module .product .woocommerce-tabs .panel textarea', '.module #commentform p label'), 't_t_r_f', 'h'),
						self::get_font_style(array('.module .product .woocommerce-tabs .panel input[type="text"]', '.module .product .woocommerce-tabs .panel input[type="email"]', '.module .product .woocommerce-tabs .panel textarea', '.module #commentform p label'), 'f_st_r_f', 'f_w_r_f', 'h'),
						self::get_text_decoration(array('.module .product .woocommerce-tabs .panel input[type="text"]', '.module .product .woocommerce-tabs .panel input[type="email"]', '.module .product .woocommerce-tabs .panel textarea', '.module #commentform p label'), 't_d_r_r_f', 'h'),
						self::get_text_shadow(array('.module .product .woocommerce-tabs .panel input[type="text"]', '.module .product .woocommerce-tabs .panel input[type="email"]', '.module .product .woocommerce-tabs .panel textarea', '.module #commentform p label'),'t_sh_r_f', 'h'),
					)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_padding(array('.module .product .woocommerce-tabs .panel input[type="text"]', '.module .product .woocommerce-tabs .panel input[type="email"]', '.module .product .woocommerce-tabs .panel textarea'), 'p_r_f')
					)
					),
					'h' => array(
					'options' => array(
						self::get_padding(array('.module .product .woocommerce-tabs .panel input[type="text"]', '.module .product .woocommerce-tabs .panel input[type="email"]', '.module .product .woocommerce-tabs .panel textarea'), 'p_r_f', 'h')
					)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_margin(array('.module .product .woocommerce-tabs .panel input[type="text"]', '.module .product .woocommerce-tabs .panel input[type="email"]', '.module .product .woocommerce-tabs .panel textarea'), 'm_r_f')
					)
					),
					'h' => array(
					'options' => array(
						self::get_margin(array('.module .product .woocommerce-tabs .panel input[type="text"]', '.module .product .woocommerce-tabs .panel input[type="email"]', '.module .product .woocommerce-tabs .panel textarea'), 'm_r_f', 'h')
					)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_border(array('.module .product .woocommerce-tabs .panel input[type="text"]', '.module .product .woocommerce-tabs .panel input[type="email"]', '.module .product .woocommerce-tabs .panel textarea'), 'b_r_f')
					)
					),
					'h' => array(
					'options' => array(
						self::get_border(array('.module .product .woocommerce-tabs .panel input[type="text"]', '.module .product .woocommerce-tabs .panel input[type="email"]', '.module .product .woocommerce-tabs .panel textarea'), 'b_r_f', 'h')
					)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(array('.module .product .woocommerce-tabs .panel input[type="text"]', '.module .product .woocommerce-tabs .panel input[type="email"]', '.module .product .woocommerce-tabs .panel textarea'), 'r_c_r_f')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(array('.module .product .woocommerce-tabs .panel input[type="text"]', '.module .product .woocommerce-tabs .panel input[type="email"]', '.module .product .woocommerce-tabs .panel textarea'), 'r_c_r_f', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(array('.module .product .woocommerce-tabs .panel input[type="text"]', '.module .product .woocommerce-tabs .panel input[type="email"]', '.module .product .woocommerce-tabs .panel textarea'), 'sh_r_f')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(array('.module .product .woocommerce-tabs .panel input[type="text"]', '.module .product .woocommerce-tabs .panel input[type="email"]', '.module .product .woocommerce-tabs .panel textarea'), 'sh_r_f', 'h')
						)
					)
				))
			))
		);

		$review_form_button = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color('.module #respond input#submit', 'b_c_r_f_b', 'bg_c', 'background-color')
					)
					),
					'h' => array(
					'options' => array(
						self::get_color('.module #respond input#submit', 'b_c_r_f_b', 'bg_c', 'background-color', 'h')
					)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_font_family('.module #respond input#submit', 'f_f_r_f_b'),
						self::get_color('.module #respond input#submit', 'f_c_r_f_b'),
						self::get_font_size('.module #respond input#submit', 'f_s_r_f_b', ''),
						self::get_line_height('.module #respond input#submit', 'l_h_r_f_b'),
						self::get_letter_spacing('.module #respond input#submit', 'l_s_r_f_b'),
						self::get_text_transform('.module #respond input#submit', 't_t_r_f_b'),
						self::get_font_style('.module #respond input#submit', 'f_st_r_f_b', 'f_w_r_f_b'),
						self::get_text_decoration('.module #respond input#submit', 't_d_r_r_f_b'),
						self::get_text_shadow('.module #respond input#submit','t_sh_r_f_b'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_font_family('.module #respond input#submit', 'f_f_r_f_b', 'h'),
						self::get_color('.module #respond input#submit', 'f_c_r_f_b',null, null, 'h'),
						self::get_font_size('.module #respond input#submit', 'f_s_r_f_b', '', 'h'),
						self::get_line_height('.module #respond input#submit', 'l_h_r_f_b', 'h'),
						self::get_letter_spacing('.module #respond input#submit', 'l_s_r_f_b', 'h'),
						self::get_text_transform('.module #respond input#submit', 't_t_r_f_b', 'h'),
						self::get_font_style('.module #respond input#submit', 'f_st_r_f_b', 'f_w_r_f_b', 'h'),
						self::get_text_decoration('.module #respond input#submit', 't_d_r_r_f_b', 'h'),
						self::get_text_shadow('.module #respond input#submit','t_sh_r_f_b', 'h'),
					)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_padding('.module #respond input#submit', 'p_r_f_b')
					)
					),
					'h' => array(
					'options' => array(
						self::get_padding('.module #respond input#submit', 'p_r_f_b', 'h')
					)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_margin('.module #respond input#submit', 'm_r_f_b')
					)
					),
					'h' => array(
					'options' => array(
						self::get_margin('.module #respond input#submit', 'm_r_f_b', 'h')
					)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_border('.module #respond input#submit', 'b_r_f_b')
					)
					),
					'h' => array(
					'options' => array(
						self::get_border('.module #respond input#submit', 'b_r_f_b', 'h')
					)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius('.module #respond input#submit', 'r_c_r_f_b')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius('.module #respond input#submit', 'r_c_r_f_b', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow('.module #respond input#submit', 'sh_r_f_b')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow('.module #respond input#submit', 'sh_r_f_b', 'h')
						)
					)
				))
			))
		);

		$review_stars = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color('.module .stars a', 'b_c_r_s', 'bg_c', 'background-color')
					)
					),
					'h' => array(
					'options' => array(
						self::get_color('.module .stars a', 'b_c_r_s', 'bg_c', 'background-color', 'h')
					)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color('.module .stars a', 'f_c_r_s'),
						self::get_font_size('.module .stars a', 'f_s_r_s', ''),
						self::get_line_height('.module .stars a', 'l_h_r_s'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_color('.module .stars:hover a', 'f_c_r_s_h'),
						self::get_font_size('.module .stars a', 'f_s_r_s', '', 'h'),
						self::get_line_height('.module .stars a', 'l_h_r_s', 'h'),
					)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_padding(array('.module .stars a', '.module .stars a:before'), 'p_r_s')
					)
					),
					'h' => array(
					'options' => array(
						self::get_padding(array('.module .stars a:hover', '.module .stars a:hover:before'), 'p_r_s_h', '')
					)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_margin('.module .stars a', 'm_r_s')
					)
					),
					'h' => array(
					'options' => array(
						self::get_margin('.module .stars a', 'm_r_s', 'h')
					)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_border('.module .stars a', 'b_r_s')
					)
					),
					'h' => array(
					'options' => array(
						self::get_border('.module .stars a', 'b_r_s', 'h')
					)
					)
				))
			))
		);

		$tab_title_wrapper = array(
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_padding('.module .wc-tabs', 'p_t_t_w')
					)
					),
					'h' => array(
					'options' => array(
						self::get_padding('.module .wc-tabs', 'p_t_t_w_h', 'h')
					)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_margin('.module .wc-tabs', 'm_t_t_w')
					)
					),
					'h' => array(
					'options' => array(
						self::get_margin('.module .wc-tabs', 'm_t_t_w', 'h')
					)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_border('.module .wc-tabs:before', 'b_t_t_w')
					)
					),
					'h' => array(
					'options' => array(
						self::get_border('.module .wc-tabs:hover:before', 'b_t_t_w_h', '')
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
				't_t_wrapper' => array(
					'label' => __('Tab', 'themify'),
					'options' => $tab_title_wrapper
				),
				'title' => array(
					'label' => __('Tab Title', 'themify'),
					'options' => $tab_title
				),
				'title_active' => array(
					'label' => __('Tab Active Title', 'themify'),
					'options' => $tab_title_active
				),
				'content' => array(
					'label' => __('Tab Content', 'themify'),
					'options' => $tab_content
				),
				'review_form_container' => array(
					'label' => __('Review Forms', 'themify'),
					'options' => $review_form_container
				),
				'review_form' => array(
					'label' => __('Review Inputs', 'themify'),
					'options' => $review_form
				),
				'review_form_button' => array(
					'label' => __('Review Form Button', 'themify'),
					'options' => $review_form_button
				),
				'review_stars' => array(
					'label' => __('Review Stars', 'themify'),
					'options' => $review_stars
				)
			)
		);
	}

	public function get_default_settings() {
		return array(
			'description' => 'yes',
			'additionaly' => 'yes',
			'reviews' => 'yes'
		);
	}


	public function get_visual_type() {
		return 'ajax';
    }

    public function get_category() {
		return array( 'product' );
    }
	
	public static function tbp_product_description_tab(){
		global $ThemifyBuilder;
		$isLoop=$ThemifyBuilder->in_the_loop===true;
		$ThemifyBuilder->in_the_loop = true;
		woocommerce_product_description_tab();
		$ThemifyBuilder->in_the_loop = $isLoop;
	}

	public static function getTabs( $tabs ) {

		if ( self::$hasDescription === true && isset( $tabs['description'] ) ) {
			$tabs['description']['callback'] = array( __CLASS__, 'tbp_product_description_tab' );
			$tabs[ 'description-' . self::$elId ] = $tabs['description'];
		}
		if ( self::$hasAdditionaly === true && isset( $tabs['additional_information'] ) ) {
			$tabs[ 'additional_information-' . self::$elId ] = $tabs['additional_information'];
		}
		if ( self::$hasReviews !== true && isset( $tabs['reviews'] ) ) {
			unset($tabs['reviews']);
		}
		unset( $tabs['description'], $tabs['additional_information'] );
		self::$singleTab = count($tabs) === 1;
		return $tabs;
	}

}

if ( themify_is_woocommerce_active() ) {
	Themify_Builder_Model::register_module('TB_Product_Reviews_Module');
}
