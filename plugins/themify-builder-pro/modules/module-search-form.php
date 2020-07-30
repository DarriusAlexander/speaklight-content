<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Module Name: Search Form
 * Description: 
 */

class TB_Search_Form_Module extends Themify_Builder_Component_Module {

    function __construct() {
		parent::__construct(array(
		    'name' => __('Search Form', 'themify'),
		    'slug' => 'search-form',
			'category' => array('site')
		));
    }

    public function get_options() {
		return array(
			array(
				'id' => 'placeholder',
				'type' => 'text',
				'label' => __('Placeholder', 'themify')
			),
			array(
				'type' => 'query_posts',
				'id' => 'post_type',
				'all' => true,
				'exclude' => false
			),
			array(
				'id'      => 'button',
				'type'    => 'toggle_switch',
				'label'   => __( 'Button', 'themify' ),
				'options'   => array(
					'on'  => array( 'name' => 'yes', 'value' => 's' ),
					'off' => array( 'name' => 'no', 'value' => 'hi' ),
				),
				'binding' => array(
					'checked' => array( 'show' => array( 'icon' ) ),
					'not_checked' => array( 'hide' => array( 'icon', 'button_t' ) ),
				)
			),
			array(
				'id' => 'icon',
				'type' => 'select',
				'label' => '',
				'options' => array(
					'icon' => __('Icon', 'themify'),
					'text' => __('Text', 'themify'),
				),
				'binding' => array(
					'icon' => array( 'hide' => array( 'button_t' ) ),
					'text' => array( 'show' => array( 'button_t' ) ),
				)
			),
			array(
				'id' => 'button_t',
				'type' => 'text',
				'label' => ''
			),
			array(
				'id'      => 'button_icon',
				'type'    => 'toggle_switch',
				'label'   => __( 'Search Icon', 'themify' ),
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
						self::get_font_family('.module .searchform', 'f_f_g'),
						self::get_color_type('.module .searchform','', 'f_c_t_g',  'f_c_g', 'f_g_c_g'),
						self::get_font_size('.module .searchform', 'f_s_g', ''),
						self::get_line_height('.module .searchform', 'l_h_g'),
						self::get_letter_spacing('.module .searchform', 'l_s_g'),
						self::get_text_align('.module .searchform', 't_a_g'),
						self::get_text_transform('.module .searchform', 't_t_g'),
						self::get_font_style('.module .searchform', 'f_st_g', 'f_w_g'),
						self::get_text_decoration('.module .searchform', 't_d_r_g'),
						self::get_text_shadow('.module .searchform','t_sh_g','h'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_font_family('.module .searchform', 'f_f_g_h'),
						self::get_color_type('.module .searchform','', 'f_c_t_g_h',  'f_c_g_h', 'f_g_c_g_h'),
						self::get_font_size('.module .searchform', 'f_s_g', '', 'h'),
						self::get_line_height('.module .searchform', 'l_h_g', 'h'),
						self::get_letter_spacing('.module .searchform', 'l_s_g', 'h'),
						self::get_text_align('.module .searchform', 't_a_g', 'h'),
						self::get_text_transform('.module .searchform', 't_t_g', 'h'),
						self::get_font_style('.module .searchform', 'f_st_g', 'f_w_g', 'h'),
						self::get_text_decoration('.module .searchform', 't_d_r_g', 'h'),
						self::get_text_shadow('.module .searchform','t_sh_g','h'),
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

		$inputs = array(
		    self::get_expand('bg', array(
			   self::get_tab(array(
			       'n' => array(
				   'options' => array(
				       self::get_color(' .searchform input', 'b_c_i', 'bg_c', 'background-color'),
				   )
			       ),
			       'h' => array(
				   'options' => array(
				         self::get_color(' .searchform input', 'b_c_i', 'bg_c', 'background-color','h'),
				   )
			       )
			   ))
		    )),
		    self::get_expand('f', array(
			self::get_tab(array(
			    'n' => array(
				'options' => array(
				    self::get_font_family(array(' .searchform input', ' .searchform input::placeholder'),'f_f_i'),
				    self::get_color(array(' .searchform input', ' .searchform input::placeholder'), 'f_c_i'),
				    self::get_font_size(array(' .searchform input', ' .searchform input::placeholder'),'f_s_i'),
				    self::get_line_height(array(' .searchform input', ' .searchform input::placeholder'),'l_h_i'),
					self::get_text_transform(array(' .searchform input', ' .searchform input::placeholder'),'t_tf_i'),
					self::get_text_shadow(array(' .searchform input', ' .searchform input::placeholder'),'t_sh_i'),
				)
			    ),
			    'h' => array(
				'options' => array(
				    self::get_font_family(array(' .searchform input', ' .searchform input::placeholder'),'f_f_i','h'),
				    self::get_color(array(' .searchform input:hover', ' .searchform input:hover::placeholder'), 'f_c_i_h',null,null,''),
				    self::get_font_size(array(' .searchform input', ' .searchform input::placeholder'),'f_s_i','','h'),
				    self::get_line_height(array(' .searchform input', ' .searchform input::placeholder'),'l_h_i', 'h'),
					self::get_text_transform(array(' .searchform input', ' .searchform input::placeholder'),'t_tf_i','h'),
					self::get_text_shadow(array(' .searchform input', ' .searchform input::placeholder'),'t_sh_i','h'),
				)
			    )
			))
		    )),
		    // Border
		    self::get_expand('b', array(
			self::get_tab(array(
			    'n' => array(
				'options' => array(
				    self::get_border(' .searchform input','in_b')
				)
			    ),
			    'h' => array(
				'options' => array(
				    self::get_border(' .searchform input','in_b','h')
				)
			    )
			))
		    )),
			// Padding
			self::get_expand('p', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_padding(' .searchform input', 'in_p')
				)
				),
				'h' => array(
				'options' => array(
					self::get_padding(' .searchform input', 'in_p', 'h')
				)
				)
			))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_margin(' .searchform input', 'in_m')
					)
					),
					'h' => array(
					'options' => array(
						self::get_margin(' .searchform input', 'in_m', 'h')
					)
					)
				))
			)),
			// Width
			self::get_expand('w', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_width(' .searchform input', 'in_w')
						)
					),
					'h' => array(
						'options' => array(
							self::get_width(' .searchform input', 'in_w', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .searchform input', 'in_r_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .searchform input', 'in_r_c', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .searchform input', 'in_b_sh')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .searchform input', 'in_b_sh', 'h')
						)
					)
				))
			)),
		);
		
		$search_button = array(
		    
		    self::get_expand('bg', array(
			   self::get_tab(array(
			       'n' => array(
				   'options' => array(
				       self::get_color(' .searchform button', 'b_c_s', 'bg_c', 'background-color')
				   )
			       ),
			       'h' => array(
				   'options' => array(
				        self::get_color(' .searchform button', 'b_c_s', 'bg_c', 'background-color','h')
				   )
			       )
			   ))
		    )),
		    self::get_expand('f', array(
			self::get_tab(array(
			    'n' => array(
				'options' => array(
				    self::get_font_family(' .searchform button' ,'f_f_s'),
				    self::get_color( ' .searchform button', 'f_c_s'),
				    self::get_font_size( ' .searchform button','f_s_s'),
				    self::get_line_height( ' .searchform button','l_h_s'),
					self::get_text_transform(' .searchform button' ,'t_tf_s'),
					self::get_text_shadow(' .searchform button' ,'t_sh_s'),
				)
			    ),
			    'h' => array(
				'options' => array(
				    self::get_font_family(' .searchform button' ,'f_f_s','h'),
				    self::get_color( ' .searchform button', 'f_c_s',null,null,'h'),
				    self::get_font_size( ' .searchform button','f_s_s','','h'),
				    self::get_line_height( ' .searchform button','l_h_s', 'h'),
					self::get_text_transform(' .searchform button' ,'t_tf_s','h'),
					self::get_text_shadow(' .searchform button' ,'t_sh_s','h'),
				)
			    )
			))
		    )),
		    // Border
		    self::get_expand('b', array(
			self::get_tab(array(
			    'n' => array(
				'options' => array(
				    self::get_border(' .searchform button','b_s')
				)
			    ),
			    'h' => array(
				'options' => array(
				    self::get_border(' .searchform button','b_s','h')
				)
			    )
			))
		    )),
			// Padding
			self::get_expand('p', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_padding(' .searchform button', 'p_sd')
				)
				),
				'h' => array(
				'options' => array(
					self::get_padding(' .searchform button', 'p_sd', 'h')
				)
				)
			))
			)),
			// Margin
			self::get_expand('m', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_margin(' .searchform button', 'm_sd')
				)
				),
				'h' => array(
				'options' => array(
					self::get_margin(' .searchform button', 'm_sd', 'h')
				)
				)
			))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .searchform button', 'r_c_sd')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .searchform button', 'r_c_sd', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .searchform button', 's_sd')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .searchform button', 's_sd', 'h')
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
				'i' => array(
					'label' => __('Search Input', 'themify'),
					'options' => $inputs
				),
				's_b' => array(
					'label' => __('Button', 'builder-contact'),
					'options' => $search_button
				)
			)
		);
	}

	public function get_default_settings() {
		return array(
			'placeholder' => __( 'Search', 'themify' ),
			'post_type' => 'any',
			'icon' => 'icon',
			'button' => 'yes',
			'button_t' => __( 'Search', 'themify' )
		);
	}


	public function get_visual_type() {
		return 'ajax';
    }

    public function get_category() {
		return array( 'general' );
	}

}

Themify_Builder_Model::register_module('TB_Search_Form_Module');
