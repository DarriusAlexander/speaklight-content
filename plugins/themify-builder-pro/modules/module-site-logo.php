<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Module Name: Site Logo
 * Description: 
 */

class TB_Site_Logo_Module extends Themify_Builder_Component_Module {

    function __construct() {
		parent::__construct(array(
		    'name' => __('Site Logo', 'themify'),
		    'slug' => 'site-logo',
			'category' => array('site')
		));
    }

    public function get_options() {
		return array(
			array(
				'id' => 'display',
				'type' => 'radio',
				'label' => __('Display', 'themify'),
				'options' => array(
					array( 'name' => __('Logo Text', 'themify'), 'value' => 'text' ),
					array( 'name' => __('Image', 'themify'), 'value' => 'image' )
				),
				'binding' => array(
					'text' => array( 'hide' => array( 'url_image', 'width_image', 'height_image' ) ),
					'image' => array( 'show' => array( 'url_image', 'width_image', 'height_image' ) )
				)
			),
			array(
				'id' => 'url_image',
				'type' => 'image',
				'label' => __('Logo Image', 'themify')
		    ),
			array(
				'id' => 'width_image',
				'type' => 'range',
				'label' => __('Image Width', 'themify'),
				'class' => 'xsmall',
				'units' => array(
					'px' => array(
						'min' => 0,
						'max' => 1500,
					)
				)
			),
			array(
				'id' => 'height_image',
				'type' => 'range',
				'label' => __('Image Height', 'themify'),
				'class' => 'xsmall',
				'units' => array(
					'px' => array(
						'min' => 0,
						'max' => 1500,
					)
				)
			),
			array(
				'id' => 'link',
				'type' => 'radio',
				'label' => __('Link', 'themify'),
				'wrap_class' => ' tb_compact_radios',
				'options' => array(
					array( 'name' => __('Site URL', 'themify'), 'value' => 'siteurl' ),
					array( 'name' => __('Custom', 'themify'), 'value' => 'custom' ),
					array( 'name' => __('None', 'themify'), 'value' => 'none' )
				),
				'binding' => array(
					'siteurl' => array( 'hide' => array( 'custom_url' ) ),
					'custom' => array( 'show' => array( 'custom_url' ) ),
					'none' => array( 'hide' => array( 'custom_url' ) )
				)
			),
			array(
				'id' => 'custom_url',
				'type' => 'url',
				'label' => __('Custom URL', 'themify')
			),
			array(
				'id' => 'html_tag',
				'type' => 'select',
				'label' => __('HTML Tag', 'themify'),
				'options' => array(
					'' => '',
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
						self::get_font_family(array('','.module h1','.module h2','.module h3','.module h4','.module h5','.module h6',' p'), 'f_f_g'),
						self::get_color_type(array(' .site-logo-inner', ' a'),'', 'f_c_t',  'f_c', 'f_g_c'),
						self::get_font_size(array('','.module h1','.module h2','.module h3','.module h4','.module h5','.module h6',' p'), 'f_s_g', ''),
						self::get_line_height(array('','.module h1','.module h2','.module h3','.module h4','.module h5','.module h6',' p'), 'l_h_g'),
						self::get_letter_spacing(array('','.module h1','.module h2','.module h3','.module h4','.module h5','.module h6',' p'), 'l_s_g'),
						self::get_text_align(array('','.module h1','.module h2','.module h3','.module h4','.module h5','.module h6',' p'), 't_a_g'),
						self::get_text_transform(array('','.module h1','.module h2','.module h3','.module h4','.module h5','.module h6',' p'), 't_t_g'),
						self::get_font_style(array('','.module h1','.module h2','.module h3','.module h4','.module h5','.module h6',' p'), 'f_st_g', 'f_w_g'),
						self::get_text_decoration(array('','.module h1','.module h2','.module h3','.module h4','.module h5','.module h6',' p'), 't_d_r_g'),
						self::get_text_shadow(array('','.module h1','.module h2','.module h3','.module h4','.module h5','.module h6',' p'),'t_sh_g'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_font_family(array('','.module:hover h1','.module:hover h2','.module:hover h3','.module:hover h4','.module:hover h5',':hover h6',':hover p'), 'f_f_g_h', ''),
						self::get_color_type(array(' .site-logo-inner:hover', ':hover a'),'', 'f_c_t_h',  'f_c_h', 'f_g_c_h',''),
						self::get_font_size(array('','.module:hover h1','.module:hover h2','.module:hover h3','.module:hover h4','.module:hover h5',':hover h6',':hover p'), 'f_s_g_h', '', ''),
						self::get_line_height(array('.module:hover h1','.module:hover h2','.module:hover h3','.module:hover h4','.module:hover h5','.module:hover h6',':hover p'), 'l_h_g_h', ''),
						self::get_letter_spacing(array('.module:hover h1','.module:hover h2','.module:hover h3','.module:hover h4','.module:hover h5','.module:hover h6',':hover p'), 'l_s_g_h', ''),
						self::get_text_align(array('.module:hover h1','.module:hover h2','.module:hover h3','.module:hover h4','.module:hover h5','.module:hover h6',':hover p'), 't_a_g_h', ''),
						self::get_text_transform(array('.module:hover h1','.module:hover h2','.module:hover h3','.module:hover h4','.module:hover h5','.module:hover h6',':hover p'), 't_t_g_h', ''),
						self::get_font_style(array('.module:hover h1','.module:hover h2','.module:hover h3','.module:hover h4','.module:hover h5','.module:hover h6',':hover p'), 'f_st_g_h', 'f_w_g_h', ''),
						self::get_text_decoration(array('.module:hover h1','.module:hover h2','.module:hover h3','.module:hover h4','.module:hover h5','.module:hover h6',':hover p'), 't_d_r_g_h', ''),
						self::get_text_shadow(array('.module:hover h1','.module:hover h2','.module:hover h3','.module:hover h4','.module:hover h5','.module:hover h6',':hover p'),'t_sh_g_h',''),
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
						self::get_color('.module a', 'l_c',null, null, 'hover'),
						self::get_text_decoration('.module a', 't_d_l', 'h')
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
							'options' => count($a = self::get_blend(array(' > h1',' > h2',' > h3',' > h4',' > h5',' > h6',' > p'),'fl'))>2 ? array($a) : $a
						),
						'h' => array(
							'options' => count($a = self::get_blend(array(' > h1',' > h2',' > h3',' > h4',' > h5',' > h6',' > p'),'fl_h','h'))>2 ? array($a + array('ishover'=>true)) : $a
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
			self::get_expand(sprintf(__('Heading %s', 'themify'), $i), array(
				self::get_tab(array(
				'n' => array(
					'options' => array(
					self::get_font_family('.module ' . $selector, 'f_f_' . $h),
					self::get_color_type(array('.module ' . $selector, '.module ' .$selector .' a'),'','f_c_t_' . $h, 'f_c_' . $h, 'f_g_c_' . $h),
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
					self::get_color_type('.module ' . $selector .':hover a','', 'f_c_t_' . $h.'_h', 'f_c_' . $h.'_h', 'f_g_c_' . $h.'_h'),
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

		$image = array(
			// Background
			self::get_expand('bg', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_color(' img', 'b_c_i', 'bg_c', 'background-color')
				)
				),
				'h' => array(
				'options' => array(
					self::get_color(' img', 'b_c_i', 'bg_c', 'background-color', 'h')
				)
				)
			))
			)),
			// Padding
			self::get_expand('p', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_padding(' img', 'p_i')
				)
				),
				'h' => array(
				'options' => array(
					self::get_padding(' img', 'p_i', 'h')
				)
				)
			))
			)),
			// Margin
			self::get_expand('m', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_margin(' img', 'm_i')
				)
				),
				'h' => array(
				'options' => array(
					self::get_margin(' img', 'm_i', 'h')
				)
				)
			))
			)),
			// Border
			self::get_expand('b', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_border(' img', 'b_i')
				)
				),
				'h' => array(
				'options' => array(
					self::get_border(' img', 'b_i', 'h')
				)
				)
			))
			)),
			// Filter
			self::get_expand('f_l',
				array(
					self::get_tab(array(
						'n' => array(
							'options' => count($a = self::get_blend(' img','','fl_img'))>2 ? array($a) : $a
						),
						'h' => array(
							'options' => count($a = self::get_blend(' img','','fl_img_h','h'))>2 ? array($a + array('ishover'=>true)) : $a
						)
					))
				)
			),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' img', 'r_c_i')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' img', 'r_c_i', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' img', 'sh_i')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' img', 'sh_i', 'h')
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
				'head' => array(
					'options' => $heading
				),
				'i' => array(
					'label' => __('Image', 'themify'),
					'options' => $image
				)
			)
		);
	}

	public function get_default_settings() {
		return array(
			'width_image' 		=> 100,
			'height_image' 		=> 100,
			'html_tag'         	=> ''
		);
	}

	public function get_visual_type() {
		return 'ajax';
    }

    public function get_category() {
		return array( 'site' );
	}

}

Themify_Builder_Model::register_module('TB_Site_Logo_Module');
