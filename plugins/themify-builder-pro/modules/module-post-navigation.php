<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Module Name: Post Navigation
 * Description: 
 */

class TB_Post_Navigation_Module extends Themify_Builder_Component_Module {

    function __construct() {
		parent::__construct(array(
		    'name' => __('Post Navigation', 'themify'),
		    'slug' => 'post-navigation',
			'category' => array('single')
		));
    }

    public function get_options() {
		return array(
			array(
				'id'      => 'labels',
				'type'    => 'toggle_switch',
				'label'   => __( 'Labels', 'themify' ),
				'options'   => array(
					'on'  => array( 'name' => 'yes', 'value' => 's' ),
					'off' => array( 'name' => 'no', 'value' => 'hi' ),
				),
				'binding' => array(
					'checked' => array( 'show' => array( 'prev_label', 'next_label' ) ),
					'not_checked' => array( 'hide' => array( 'prev_label', 'next_label' ) ),
				)
			),
			array(
				'id' => 'prev_label',
				'type' => 'text',
				'label' => __('Previous Label', 'themify'),
			),
			array(
				'id' => 'next_label',
				'type' => 'text',
				'label' => __('Next Label', 'themify'),
			),
			array(
				'id'      => 'arrows',
				'type'    => 'toggle_switch',
				'label'   => __( 'Arrows', 'themify' ),
				'options'   => array(
					'on'  => array( 'name' => 'yes', 'value' => 'en' ),
					'off' => array( 'name' => 'no', 'value' => 'dis' ),
			),
				'binding' => array(
					'checked' => array( 'show' => array( 'prev_arrow', 'next_arrow' ) ),
					'not_checked' => array( 'hide' => array( 'prev_arrow', 'next_arrow' ) ),
				),
			),
			array(
				'id' => 'prev_arrow',
				'type' => 'icon',
				'label' => __('Previous Arrow', 'themify')
			),
			array(
				'id' => 'next_arrow',
				'type' => 'icon',
				'label' => __('Next Arrow', 'themify')
			),
			array(
				'id'      => 'same_cat',
				'type'    => 'toggle_switch',
				'label'   => __( 'In Same Category', 'themify' ),
				'options'   => 'simple',
				'help'    => __('Show posts in the same category or taxonomy term.', 'themify')
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
						self::get_color_type(array(' .tbp_post_navigation_arrow',' a'),'', 'f_c_t_g',  'f_c_g', 'f_g_c_g'),
						self::get_font_size('', 'f_s_g', ''),
						self::get_line_height(array('', ' .tbp_post_navigation_title'), 'l_h_g'),
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
						self::get_color_type(array(' a:hover .tbp_post_navigation_arrow',' a:hover'),'', 'f_c_t_g_h',  'f_c_g_h', 'f_g_c_g_h'),
						self::get_font_size('', 'f_s_g', '', 'h'),
						self::get_line_height(array('', ' .tbp_post_navigation_title'), 'l_h_g', 'h'),
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
						self::get_color('.module a', 'l_c'),
						self::get_text_decoration('.module a', 't_d_l')
					)
					),
					'h' => array(
					'options' => array(
						self::get_color('.module a:hover', 'l_c_h',null, null, ''),
						self::get_text_decoration('.module:hover .tbp_post_navigation_label', 't_d_l_h', '')
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

		$arrows = array(
			// Background
			self::get_expand('bg', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_color(' .tbp_post_navigation_arrow', 'b_c_aw', 'bg_c', 'background-color')
				)
				),
				'h' => array(
				'options' => array(
					self::get_color(' a:hover .tbp_post_navigation_arrow', 'b_c_aw_h', 'bg_c', 'background-color', '')
				)
				)
			))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color_type(' .tbp_post_navigation_arrow','', 'f_c_t_aw',  'f_c_aw', 'f_g_c_aw'),
						self::get_font_size(' .tbp_post_navigation_arrow', 'f_s_aw', ''),
					)
					),
					'h' => array(
					'options' => array(
						self::get_color_type(' a:hover .tbp_post_navigation_arrow','', 'f_c_t_aw_h',  'f_c_aw_h', 'f_g_c_aw_h'),
						self::get_font_size(' a:hover .tbp_post_navigation_arrow', 'f_s_aw_h', '', ''),
					)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_padding(' .tbp_post_navigation_arrow', 'p_aw')
				)
				),
				'h' => array(
				'options' => array(
					self::get_padding(' a:hover .tbp_post_navigation_arrow', 'p_aw_h', '')
				)
				)
			))
			)),
			// Margin
			self::get_expand('m', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_margin(' .tbp_post_navigation_arrow', 'm_aw')
				)
				),
				'h' => array(
				'options' => array(
					self::get_margin(' a:hover .tbp_post_navigation_arrow', 'm_aw_h', '')
				)
				)
			))
			)),
			// Border
			self::get_expand('b', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_border(' .tbp_post_navigation_arrow', 'b_aw')
				)
				),
				'h' => array(
				'options' => array(
					self::get_border(' a:hover .tbp_post_navigation_arrow', 'b_aw_h', '')
				)
				)
			))
			)),
			// Width
			self::get_expand('w', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_width(' .tbp_post_navigation_arrow', 'w_aw')
						)
					),
					'h' => array(
						'options' => array(
							self::get_width(' a:hover .tbp_post_navigation_arrow', 'w_aw_h', '')
						)
					)
				))
			)),
			// Height
			self::get_expand('ht', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_height(' .tbp_post_navigation_arrow', 'h_aw')
						)
					),
					'h' => array(
						'options' => array(
							self::get_height(' a:hover .tbp_post_navigation_arrow', 'h_aw_h', '')
						)
					)
				))
			)),			
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .tbp_post_navigation_arrow', 'r_c_aw')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' a:hover .tbp_post_navigation_arrow', 'r_c_aw_h', '')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .tbp_post_navigation_arrow', 'sh_aw')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' a:hover .tbp_post_navigation_arrow', 'sh_aw_h', '')
						)
					)
				))
			))
		);

		$labels = array(
			// Background
			self::get_expand('bg', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_color(' .tbp_post_navigation_label', 'b_c_l', 'bg_c', 'background-color')
				)
				),
				'h' => array(
				'options' => array(
					self::get_color(' a:hover .tbp_post_navigation_label', 'b_c_l_h', 'bg_c', 'background-color', '')
				)
				)
			))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_font_family(' .tbp_post_navigation_label', 'f_f_l'),
						self::get_color_type('.module .tbp_post_navigation_label','', 'f_c_t_l',  'f_c_l', 'f_g_c_l'),
						self::get_font_size(' .tbp_post_navigation_label', 'f_s_l'),
						self::get_line_height(' .tbp_post_navigation_label', 'l_h_l'),
						self::get_letter_spacing(' .tbp_post_navigation_label', 'l_s_l'),
						self::get_text_transform(' .tbp_post_navigation_label', 't_t_l'),
						self::get_font_style(' .tbp_post_navigation_label', 'f_sy_l', 'f_w_l'),
						self::get_text_decoration('.module .tbp_post_navigation_label', 't_d_lb'),
						self::get_text_shadow(' .tbp_post_navigation_label', 't_sh_l'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_font_family(' a:hover .tbp_post_navigation_label', 'f_f_l_h', ''),
						self::get_color_type('.module a:hover .tbp_post_navigation_label','', 'f_c_t_l_h',  'f_c_l_h', 'f_g_c_l_h', ''),
						self::get_font_size(' a:hover .tbp_post_navigation_label', 'f_s_l_h', '', ''),
						self::get_line_height(' a:hover .tbp_post_navigation_label', 'l_h_l_h', ''),
						self::get_letter_spacing(' a:hover .tbp_post_navigation_label', 'l_s_l_h', ''),
						self::get_text_transform(' a:hover .tbp_post_navigation_label', 't_t_l_h', ''),
						self::get_font_style(' a:hover .tbp_post_navigation_label', 'f_sy_l_h', 'f_w_l_h', ''),
						self::get_text_decoration('.module a:hover .tbp_post_navigation_label', 't_d_lb_h', ''),
						self::get_text_shadow(' a:hover .tbp_post_navigation_label', 't_sh_l_h',''),
					)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_padding(' .tbp_post_navigation_label', 'p_l')
				)
				),
				'h' => array(
				'options' => array(
					self::get_padding(' a:hover .tbp_post_navigation_label', 'p_l_h', '')
				)
				)
			))
			)),
			// Margin
			self::get_expand('m', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_margin(' .tbp_post_navigation_label', 'm_l')
				)
				),
				'h' => array(
				'options' => array(
					self::get_margin(' a:hover .tbp_post_navigation_label', 'm_l_h', '')
				)
				)
			))
			)),
			// Border
			self::get_expand('b', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_border(' .tbp_post_navigation_label', 'b_l')
				)
				),
				'h' => array(
				'options' => array(
					self::get_border(' a:hover .tbp_post_navigation_label', 'b_l_h', '')
				)
				)
			))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .tbp_post_navigation_label', 'r_c_l')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' a:hover .tbp_post_navigation_label', 'r_c_l_h', '')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .tbp_post_navigation_label', 'sh_l')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' a:hover .tbp_post_navigation_label', 'sh_l_h', '')
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
				'a' => array(
					'label' => __('Arrows', 'themify'),
					'options' => $arrows
				),
				'l' => array(
					'label' => __('Labels', 'themify'),
					'options' => $labels
				)
			)
		);
	}

	public function get_default_settings() {
		return array(
			'labels' => 'yes',
			'prev_label' => __( 'Previous Post', 'themify' ),
			'next_label' => __( 'Next Post', 'themify' ),
			'arrows' => 'yes',
			'same_cat' => 'no'
		);
	}

	public function get_visual_type() {
		return 'ajax';
	}

    public function get_category() {
		return array( 'single' );
	}

}

Themify_Builder_Model::register_module('TB_Post_Navigation_Module');
