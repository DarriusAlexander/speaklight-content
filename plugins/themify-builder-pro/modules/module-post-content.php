<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Module Name: Post Content
 * Description: 
 */

class TB_Post_Content_Module extends Themify_Builder_Component_Module {

    function __construct() {
		parent::__construct(array(
		    'name' => __('Post Content', 'themify'),
		    'slug' => 'post-content',
			'category' => array('single')
		));
    }

    public function get_options() {
		return array(
			array(
				'id' => 'content_type',
				'type' => 'radio',
				'label' => __('Display', 'themify'),
				'options' => array(
					array( 'name' => __( 'Full Content', 'themify' ), 'value' => 'full' ),
					array( 'name' => __( 'Excerpt', 'themify' ), 'value' => 'excerpt' )
				),
				'binding' => array(
					'full' => array( 'hide' => array( 'excerpt_length' ),'show' => array( 'more_text' ) ),
					'excerpt' => array( 'hide' => array( 'more_text' ),'show' => array( 'excerpt_length' ) )
				)
			),
			array(
				'id' => 'more_text',
				'type' => 'text',
				'wrap_class' => 'tbp_except_single_template',
				'label' => __('More Text', 'themify'),
			),
			array(
				'id' => 'excerpt_length',
				'type' => 'number',
				'control'=>array(
				  'event'=>'change'  
				),
				'label' => __('Excerpt Length', 'themify')
			),
			array(
				'id' => 'drop_cap',
				'label' => __('Drop-Cap', 'themify'),
				'type' => 'toggle_switch',
				'options' => array(
					'on' => array('name'=>'dropcap','value' =>'en'),
					'off' => array('name'=>'', 'value' =>'dis')
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
						self::get_font_family('', 'f_f'),
						self::get_color_type(' .tb_text_wrap','', 'f_c_t',  'f_c', 'f_g_c'),
						self::get_font_size('', 'f_s'),
						self::get_line_height('', 'l_h'),
						self::get_letter_spacing('', 'l_s'),
						self::get_text_align('', 't_a'),
						self::get_text_transform('', 't_t'),
						self::get_font_style('', 'f_st', 'f_w'),
						self::get_text_decoration('', 't_d_r'),
						self::get_text_shadow(' .tb_text_wrap','t_sh'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_font_family('', 'f_f_h'),
						self::get_color_type(':hover .tb_text_wrap','', 'f_c_t_h',  'f_c_h', 'f_g_c_h'),
						self::get_font_size('', 'f_s', '', 'h'),
						self::get_line_height('', 'l_h', 'h'),
						self::get_letter_spacing('', 'l_s', 'h'),
						self::get_text_align('', 't_a', 'h'),
						self::get_text_transform('', 't_t', 'h'),
						self::get_font_style('', 'f_st', 'f_w', 'h'),
						self::get_text_decoration('', 't_d_r', 'h'),
						self::get_text_shadow(':hover .tb_text_wrap','t_sh','h'),
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
			! method_exists( $this, 'get_max_height' ) ? array() :
			// Height & Min Height
			self::get_expand('ht', array(
				self::get_height(),
				self::get_min_height(),
				self::get_max_height()
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

		$dropcap = array(
			// Background
			self::get_expand('bg', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_color(array(' .tb_text_dropcap > .tb_text_wrap:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap > :first-child:first-letter'), 'd_b_c', 'bg_c', 'background-color')
				)
				),
				'h' => array(
				'options' => array(
					self::get_color(array(' .tb_text_dropcap > .tb_text_wrap:hover:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap:hover > :first-child:first-letter'), 'd_b_c_h', 'bg_c', 'background-color')
				)
				)
			))
			)),
			// Font
			self::get_expand('f', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_font_family(array(' .tb_text_dropcap > .tb_text_wrap:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap > :first-child:first-letter'), 'd_f_f'),
					self::get_color(array(' .tb_text_dropcap > .tb_text_wrap:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap > :first-child:first-letter'), 'd_f_c'),
					self::get_font_size(array(' .tb_text_dropcap > .tb_text_wrap:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap > :first-child:first-letter'), 'd_f_s'),
					self::get_line_height(array(' .tb_text_dropcap > .tb_text_wrap:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap > :first-child:first-letter'), 'd_l_h'),
					self::get_text_transform(array(' .tb_text_dropcap > .tb_text_wrap:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap > :first-child:first-letter'), 'd_t_t'),
					self::get_font_style(array(' .tb_text_dropcap > .tb_text_wrap:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap > :first-child:first-letter'), 'd_f_st', 'd_f_b'),
					self::get_text_decoration(array(' .tb_text_dropcap > .tb_text_wrap:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap > :first-child:first-letter'), 'd_t_d'),
					self::get_text_shadow(array(' .tb_text_dropcap > .tb_text_wrap:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap > :first-child:first-letter'), 'd_t_sh')
				)
				),
				'h' => array(
				'options' => array(
					self::get_font_family(array(' > .tb_text_wrap:hover:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap:hover:first-child:first-letter'), 'd_f_f_h'),
					self::get_color(array(' > .tb_text_wrap:hover:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap:hover:first-child:first-letter'), 'd_f_c_h'),
					self::get_font_size(array(' > .tb_text_wrap:hover:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap:hover:first-child:first-letter'), 'd_f_s_h'),
					self::get_line_height(array(' > .tb_text_wrap:hover:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap:hover:first-child:first-letter'), 'd_l_h_h'),
					self::get_text_transform(array(' > .tb_text_wrap:hover:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap:hover:first-child:first-letter'), 'd_t_t_h'),
					self::get_font_style(array(' > .tb_text_wrap:hover:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap:hover:first-child:first-letter'), 'd_f_st_h', 'd_f_b_h'),
					self::get_text_decoration(array(' > .tb_text_wrap:hover:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap:hover:first-child:first-letter'), 'd_t_d_h'),
					self::get_text_shadow(array(' > .tb_text_wrap:hover:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap:hover:first-child:first-letter'), 'd_t_sh_h')
				)
				)
			))
			)),
			// Padding
			self::get_expand('p', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_padding(array(' .tb_text_dropcap > .tb_text_wrap:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap > :first-child:first-letter'), 'd_p')
				)
				),
				'h' => array(
				'options' => array(
					self::get_padding(array(' .tb_text_dropcap > .tb_text_wrap:hover:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap:hover:first-child:first-letter'), 'd_p_h')
				)
				)
			))
			)),
			// Margin
			self::get_expand('m', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_margin(array(' .tb_text_dropcap > .tb_text_wrap:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap > :first-child:first-letter'), 'd_m')
				)
				),
				'h' => array(
				'options' => array(
					self::get_margin(array(' .tb_text_dropcap > .tb_text_wrap:hover:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap:hover:first-child:first-letter'), 'd_m_h')
				)
				)
			))
			
			)),
			// Border
			self::get_expand('b', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_border(array(' .tb_text_dropcap > .tb_text_wrap:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap > :first-child:first-letter'), 'd_b')
				)
				),
				'h' => array(
				'options' => array(
					self::get_border(array(' .tb_text_dropcap > .tb_text_wrap:hover:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap:hover:first-child:first-letter'), 'd_b_h')
				)
				)
			))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(array(' .tb_text_dropcap > .tb_text_wrap:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap > :first-child:first-letter'), 'r_c_dp')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(array(' .tb_text_dropcap > .tb_text_wrap:hover:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap:hover > :first-child:first-letter'), 'r_c_dp_h', '')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(array(' .tb_text_dropcap > .tb_text_wrap:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap > :first-child:first-letter'), 'sh_dp')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(array(' .tb_text_dropcap > .tb_text_wrap:hover:first-child:first-letter', ' .tb_text_dropcap > .tb_text_wrap:hover > :first-child:first-letter'), 'sh_dp_h', '')
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
				'd' => array(
					'label' => __('Drop-Cap', 'themify'),
					'options' => $dropcap
				)
			)
		);
	}

	public function get_default_settings() {
		return array(
			'content_type' => 'full',
			'more_text' => __('Read More','themify')
		);
	}

	public function get_visual_type() {
		return 'ajax';
    }

    public function get_category() {
		return array( 'single', 'archive', 'page' );
	}

}

Themify_Builder_Model::register_module('TB_Post_Content_Module');
