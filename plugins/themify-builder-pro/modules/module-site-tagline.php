<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Module Name: Site Tagline
 * Description: 
 */

class TB_Site_Tagline_Module extends Themify_Builder_Component_Module {

    function __construct() {
		parent::__construct(array(
		    'name' => __('Site Tagline', 'themify'),
		    'slug' => 'site-tagline',
			'category' => array('site')
		));
    }

    public function get_options() {
		return array(
			array(
				'id' => 'link',
				'type' => 'url',
				'label' => __('Tagline Link', 'themify')
			),
			array(
				'id' => 'html_tag',
				'type' => 'select',
				'label' => __('HTML Tag', 'themify'),
				'options' => array(
					'div' => 'DIV',
					'h1' =>'H1',
					'h2' =>'H2',
					'h3' =>'H3',
					'h4' =>'H4',
					'h5' =>'H5',
					'h6' =>'H6',
					'p' =>'P'
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
						self::get_font_family('.module .tbp_site_tagline_heading', 'f_f_g'),
						self::get_color_type('.module .tbp_site_tagline_heading','', 'f_c_t_g',  'f_c_g', 'f_g_c_g'),
						self::get_font_size('.module .tbp_site_tagline_heading', 'f_s_g', ''),
						self::get_line_height('.module .tbp_site_tagline_heading', 'l_h_g'),
						self::get_letter_spacing('.module .tbp_site_tagline_heading', 'l_s_g'),
						self::get_text_align('.module .tbp_site_tagline_heading', 't_a_g'),
						self::get_text_transform('.module .tbp_site_tagline_heading', 't_t_g'),
						self::get_font_style('.module .tbp_site_tagline_heading', 'f_st_g', 'f_w_g'),
						self::get_text_decoration('.module .tbp_site_tagline_heading', 't_d_r_g'),
						self::get_text_shadow('.module .tbp_site_tagline_heading','t_sh_g','h'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_font_family('.module .tbp_site_tagline_heading', 'f_f_g_h', 'h'),
						self::get_color_type('.module .tbp_site_tagline_heading:hover','', 'f_c_t_g_h',  'f_c_g_h', 'f_g_c_g_h', 'h'),
						self::get_font_size('.module .tbp_site_tagline_heading', 'f_s_g', '', 'h'),
						self::get_line_height('.module .tbp_site_tagline_heading', 'l_h_g', 'h'),
						self::get_letter_spacing('.module .tbp_site_tagline_heading', 'l_s_g', 'h'),
						self::get_text_align('.module .tbp_site_tagline_heading', 't_a_g', 'h'),
						self::get_text_transform('.module .tbp_site_tagline_heading', 't_t_g', 'h'),
						self::get_font_style('.module .tbp_site_tagline_heading', 'f_st_g', 'f_w_g', 'h'),
						self::get_text_decoration('.module .tbp_site_tagline_heading', 't_d_r_g', 'h'),
						self::get_text_shadow('.module .tbp_site_tagline_heading','t_sh_g','h'),
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

		$heading = array();

		for ($i = 1; $i <= 6; ++$i) {
			$h = 'h' . $i;
			$selector = $h;
			if($i === 3){
			$selector.=':not(.module-title)';
			}
			$heading = array_merge($heading, array(
			self::get_expand(sprintf(__('Heading %s Font', 'themify'), $i), array(
				self::get_tab(array(
				'n' => array(
					'options' => array(
					self::get_font_family('.module ' . $selector, 'f_f_' . $h),
					self::get_color_type('.module ' .$selector,'','f_c_t_' . $h, 'f_c_' . $h, 'f_g_c_' . $h),
					self::get_font_size(' ' . $h, 'f_s_' . $h),
					self::get_line_height(' ' . $h, 'l_h_' . $h),
					self::get_letter_spacing(' ' . $h, 'l_s_' . $h),
					self::get_text_transform(' ' . $h, 't_t_' . $h),
					self::get_font_style(' ' . $h, 'f_st_' . $h, 'f_w_' . $h),
					self::get_text_shadow('.module ' .$selector, 't_sh' . $h),
					// Heading  Margin
					self::get_heading_margin_multi_field('', $h, 'top'),
					self::get_heading_margin_multi_field('', $h, 'bottom')
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
				'head' => array(
					'options' => $heading
				)
			)
		);
	}

	public function get_visual_type() {
		return 'ajax';
    }

    public function get_category() {
		return array( 'site' );
	}

}

Themify_Builder_Model::register_module('TB_Site_Tagline_Module');
