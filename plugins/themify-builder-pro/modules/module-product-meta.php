<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Module Name: Product Meta
 * Description: 
 */

class TB_Product_Meta_Module extends Themify_Builder_Component_Module {

    function __construct() {
		parent::__construct(array(
		    'name' => __('Product Meta', 'themify'),
		    'slug' => 'product-meta',
			'category' => array('product_single')
		));
    }

    public function get_options() {
		return array(
			array(
				'id'      => 'enable_cat',
				'type'    => 'toggle_switch',
				'label'   => __( 'Category', 'themify' ),
				'options'   => array(
					'on'  => array( 'name' => 'yes', 'value' => 's' ),
					'off' => array( 'name' => 'no', 'value' => 'hi' ),
				),
				'binding' => array(
					'checked' => array( 'show' => array( 'cat' ) ),
					'not_checked' => array( 'hide' => array( 'cat' ) ),
				)
			),
			array(
				'id' => 'cat',
				'type' => 'text',
				'label' => '',
				'control'=>array(
				    'event'=>'change'
				),
				'after' => __('Category Label', 'themify')
			),
			array(
				'id'      => 'enable_tag',
				'type'    => 'toggle_switch',
				'label'   => __( 'Tag', 'themify' ),
				'options'   => array(
					'on'  => array( 'name' => 'yes', 'value' => 's' ),
					'off' => array( 'name' => 'no', 'value' => 'hi' ),
				),
				'binding' => array(
					'checked' => array( 'show' => array( 'tag' ) ),
					'not_checked' => array( 'hide' => array( 'tag' ) ),
				)
			),
			array(
				'id' => 'tag',
				'type' => 'text',
				'label' => '',
				'control'=>array(
				    'event'=>'change'
				),
				'after' => __('Tag Label', 'themify')
			),
			array(
				'id'      => 'enable_sku',
				'type'    => 'toggle_switch',
				'label'   => __( 'SKU', 'themify' ),
				'options'   => array(
					'on'  => array( 'name' => 'yes', 'value' => 's' ),
					'off' => array( 'name' => 'no', 'value' => 'hi' ),
				),
				'binding' => array(
					'checked' => array( 'show' => array( 'sku' ) ),
					'not_checked' => array( 'hide' => array( 'sku' ) ),
				)
			),
			array(
				'id' => 'sku',
				'type' => 'text',
				'label' => '',
				'control'=>array(
				    'event'=>'change'
				),
				'after' => __('SKU Label', 'themify')
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
						self::get_font_family(' .product_meta', 'f_f_g'),
						self::get_color_type(array(' .product_meta',' .product_meta a'),'', 'f_c_t_g',  'f_c_g', 'f_g_c_g'),
						self::get_font_size(' .product_meta', 'f_s_g', ''),
						self::get_line_height(' .product_meta', 'l_h_g'),
						self::get_letter_spacing(array(' .product_meta span',' .product_meta a'), 'l_s_g'),
						self::get_text_align(' .product_meta', 't_a_g'),
						self::get_text_transform(array(' .product_meta',' .product_meta a'),'', 't_t_g'),
						self::get_font_style(' .product_meta', 'f_st_g', 'f_w_g'),
						self::get_text_decoration(' .product_meta', 't_d_r_g'),
						self::get_text_shadow(' .product_meta','t_sh_g','h'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_font_family(' .product_meta', 'f_f_g_h'),
						self::get_color_type(array(' .product_meta:hover',' .product_meta:hover a'),'', 'f_c_t_g_h',  'f_c_g_h', 'f_g_c_g_h'),
						self::get_font_size(' .product_meta', 'f_s_g', '', 'h'),
						self::get_line_height(' .product_meta', 'l_h_g', 'h'),
						self::get_letter_spacing(array(' .product_meta span',' .product_meta a'), 'l_s_g', 'h'),
						self::get_text_align(' .product_meta', 't_a_g', 'h'),
						self::get_text_transform(array(' .product_meta:hover',' .product_meta:hover a'), 't_t_g_h'),
						self::get_font_style(' .product_meta', 'f_st_g', 'f_w_g', 'h'),
						self::get_text_decoration(' .product_meta', 't_d_r_g', 'h'),
						self::get_text_shadow(' .product_meta','t_sh_g','h'),
					)
					)
				))
			)),
			// Link
			self::get_expand('l', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color(' .product_meta a', 'l_c'),
						self::get_text_decoration(' .product_meta a', 't_d_l')
					)
					),
					'h' => array(
					'options' => array(
						self::get_color(' .product_meta a', 'l_c',null, null, 'hover'),
						self::get_text_decoration(' .product_meta a', 't_d_l', 'h')
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
			'enable_cat' => 'yes',
			'cat' => __( 'Category', 'themify' ),
			'enable_tag' => 'yes',
			'tag' => __( 'Tag', 'themify' ),
			'enable_sku' => 'yes',
			'sku' => __( 'SKU', 'themify' )
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
	Themify_Builder_Model::register_module('TB_Product_Meta_Module');
}
