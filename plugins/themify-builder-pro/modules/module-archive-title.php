<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Module Name: Archive Title
 * Description: 
 */

class TB_Archive_Title_Module extends Themify_Builder_Component_Module {

    function __construct() {
		parent::__construct(array(
		    'name' => __('Archive Title', 'themify'),
		    'slug' => 'archive-title',
			'category' => array('archive','product_archive')
		));
    }

    public function get_options() {
		return array(
			array(
				'id' => 'html_tag',
				'type' => 'select',
				'label' => __('HTML Tag', 'themify'),
				'options' => array(
					'h1' => __('H1', 'themify'),
					'h2' => __('H2', 'themify'),
					'h3' => __('H3', 'themify'),
					'h4' => __('H4', 'themify'),
					'h5' => __('H5', 'themify'),
					'h6' => __('H6', 'themify'),
					'div' => __('div', 'themify'),
					'p' => __('p', 'themify')
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
							self::get_image()
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
						self::get_font_family(array('.module h1', '.module h2', '.module h3', '.module h4', '.module h5', '.module h6', ' p'), 'f_f'),
						self::get_color_type(array('.module h1', '.module h2', '.module h3', '.module h4', '.module h5', '.module h6', ' p'),'', 'f_c_t',  'f_c', 'f_g_c'),
						self::get_font_size(array('.module h1', '.module h2', '.module h3', '.module h4', '.module h5', '.module h6', ' p'), 'f_s'),
						self::get_line_height(array('.module h1', '.module h2', '.module h3', '.module h4', '.module h5', '.module h6', ' p'), 'l_h'),
						self::get_letter_spacing(array('.module h1', '.module h2', '.module h3', '.module h4', '.module h5', '.module h6', ' p'), 'l_s'),
						self::get_text_align(array('.module h1', '.module h2', '.module h3', '.module h4', '.module h5', '.module h6', ' p'), 't_a'),
						self::get_text_transform(array('.module h1', '.module h2', '.module h3', '.module h4', '.module h5', '.module h6', ' p'), 't_t'),
						self::get_font_style(array('.module h1', '.module h2', '.module h3', '.module h4', '.module h5', '.module h6', ' p'), 'f_st', 'f_w'),
						self::get_text_decoration(array('.module h1', '.module h2', '.module h3', '.module h4', '.module h5', '.module h6', ' p'), 't_d_r'),
						self::get_text_shadow(array('.module h1', '.module h2', '.module h3', '.module h4', '.module h5', '.module h6', ' p'),'t_sh'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_font_family(array('.module h1', '.module h2', '.module h3', '.module h4', '.module h5', '.module h6', ' p'), 'f_f_h'),
						self::get_color_type(array('.module h1', '.module h2', '.module h3', '.module h4', '.module h5', '.module h6', ' p'),'', 'f_c_t_h',  'f_c_h', 'f_g_c_h'),
						self::get_font_size(array('.module h1', '.module h2', '.module h3', '.module h4', '.module h5', '.module h6', ' p'), 'f_s', '', 'h'),
						self::get_line_height(array('.module h1', '.module h2', '.module h3', '.module h4', '.module h5', '.module h6', ' p'), 'l_h', 'h'),
						self::get_letter_spacing(array('.module h1', '.module h2', '.module h3', '.module h4', '.module h5', '.module h6', ' p'), 'l_s', 'h'),
						self::get_text_align(array('.module h1', '.module h2', '.module h3', '.module h4', '.module h5', '.module h6', ' p'), 't_a', 'h'),
						self::get_text_transform(array('.module h1', '.module h2', '.module h3', '.module h4', '.module h5', '.module h6', ' p'), 't_t', 'h'),
						self::get_font_style(array('.module h1', '.module h2', '.module h3', '.module h4', '.module h5', '.module h6', ' p'), 'f_st', 'f_w', 'h'),
						self::get_text_decoration(array('.module h1', '.module h2', '.module h3', '.module h4', '.module h5', '.module h6', ' p'), 't_d_r', 'h'),
						self::get_text_shadow(array('.module h1', '.module h2', '.module h3', '.module h4', '.module h5', '.module h6', ' p'),'t_sh','h'),
					)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding()
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
							self::get_margin()
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
							self::get_border()
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
								self::get_border_radius()
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
								self::get_box_shadow()
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
			// Position
			self::get_expand('po', array( self::get_css_position())),
			// Display
			self::get_expand('disp', self::get_display())
		);

		$title = array();

		for ($i = 1; $i <= 6; ++$i) {
			$h = 'h' . $i;
			$selector = $h;
			if($i === 3){
			$selector.=':not(.module-title)';
			}
			$title = array_merge($title, array(
			self::get_expand(sprintf(__('Heading %s Font', 'themify'), $i), array(
				self::get_tab(array(
				'n' => array(
					'options' => array(
					self::get_font_family('.module ' . $selector, 'f_f' . $h),
					self::get_color_type('.module ' .$selector,'','f_c_t_' . $h, 'f_c_' . $h, 'f_g_c_' . $h),
					self::get_font_size('.module ' . $h, 'f_s_' . $h),
					self::get_line_height('.module ' . $h, 'l_h_' . $h),
					self::get_letter_spacing('.module ' . $h, 'l_s_' . $h),
					self::get_text_transform('.module ' . $h, 't_t_' . $h),
					self::get_font_style('.module ' . $h, 'f_st_' . $h, 'f_w_' . $h),
					self::get_text_shadow('.module ' .$selector, 't_sh' . $h),
					// Heading  Margin
					self::get_heading_margin_multi_field('.module', $h, 'top'),
					self::get_heading_margin_multi_field('.module', $h, 'bottom')
					)
				),
				'h' => array(
					'options' => array(
					self::get_font_family('.module:hover ' . $selector, 'f_f_' . $h.'_h'),
					self::get_color_type('.module:hover ' . $selector,'', 'f_c_t_' . $h.'_h', 'f_c_' . $h.'_h', 'f_g_c_' . $h.'_h'),
					self::get_font_size('.module ' . $h, 'f_s_' . $h, '', 'h'),
					self::get_line_height('.module ' . $h, 'l_h_' . $h, 'h'),
					self::get_letter_spacing('.module ' . $h, 'l_s_' . $h, 'h'),
					self::get_text_transform('.module ' . $h, 't_t_' . $h, 'h'),
					self::get_font_style('.module ' . $h, 'f_st_' . $h, 'f_w_' . $h, 'h'),
					self::get_text_shadow('.module:hover ' . $selector, 't_sh' . $h,'h'),
					// Heading  Margin
					self::get_heading_margin_multi_field('.module', $h, 'top', 'h'),
					self::get_heading_margin_multi_field('.module', $h, 'bottom', 'h')
					)
				)
				))
			))
			));
		}


		return array(
			'type' => 'tabs',
			'options' => array(
				'g' => array(
					'options' => $general
				),
				't' => array(
					'label' => __('Heading', 'themify'),
					'options' => $title
				)
			)
		);
	}

	public function get_default_settings() {
		return array(
			'html_tag' => 'h2'
		);
	}

	public function get_visual_type() {
		return 'ajax';
    }

    public function get_category() {
		return array( 'archive' );
	}

}

Themify_Builder_Model::register_module('TB_Archive_Title_Module');
