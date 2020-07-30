<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Module Name: Post Meta
 * Description: 
 */

class TB_Post_Meta_Module extends Themify_Builder_Component_Module {

    function __construct() {
		parent::__construct(array(
		    'name' => __('Post Meta', 'themify'),
		    'slug' => 'post-meta',
			'category' => array('single')
		));
    }

    public function get_options() {
		return array(
			array(
				'id' => 'tab_content_post_meta',
			    'type' => 'sortable_fields',
			    'label' => __('Post Meta', 'themify'),
						'options' => array(
				    'date' => array(
					    'label' => __('Date', 'themify'),
					),
				    'time' => array(
					    'label' => __('Time', 'themify'),
					),
				    'author' => array(
					    'label' => __('Author', 'themify'),
					),
				    'comments' => array(
						'label' => __('Comments', 'themify'),
					),
				    'terms' => array(
					    'label' => __('Terms', 'themify'),
					),
					),
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
						self::get_color_type(array(' .tbp_post_meta', ' .tbp_post_meta a'),'', 'f_c_t',  'f_c', 'f_g_c'),
						self::get_font_size('', 'f_s'),
						self::get_line_height('', 'l_h'),
						self::get_letter_spacing('', 'l_s'),
						self::get_text_align(' .tbp_post_meta', 't_a'),
						self::get_text_transform('', 't_t'),
						self::get_font_style('', 'f_st', 'f_w'),
						self::get_text_decoration('', 't_d_r'),
						self::get_text_shadow('','t_sh'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_font_family('', 'f_f_h'),
						self::get_color_type(array(' .tbp_post_meta:hover', ' .tbp_post_meta a:hover'),'', 'f_c_t_h',  'f_c_h', 'f_g_c_h'),
						self::get_font_size('', 'f_s', '', 'h'),
						self::get_line_height('', 'l_h', 'h'),
						self::get_letter_spacing('', 'l_s', 'h'),
						self::get_text_align(' .tbp_post_meta', 't_a', 'h'),
						self::get_text_transform('', 't_t', 'h'),
						self::get_font_style('', 'f_st', 'f_w', 'h'),
						self::get_text_decoration('', 't_d_r', 'h'),
						self::get_text_shadow('','t_sh','h'),
					)
					)
				))
			)),
			// Link
			self::get_expand('l', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color_type(' .tbp_post_meta a','', 'l_c_t',  'l_c', 'l_g_c'),
						self::get_text_decoration(' .tbp_post_meta a', 't_d')
					)
					),
					'h' => array(
					'options' => array(
						self::get_color_type(' .tbp_post_meta a:hover','', 'l_c_t_h',  'l_c_h', 'l_g_c_h'),
						self::get_text_decoration(' .tbp_post_meta a', 't_d', 'h')
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
			// Position
			self::get_expand('po', array( self::get_css_position())),
			// Display
			self::get_expand('disp', self::get_display())
		);
		
		$date = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color(' .entry-date', 'b_c_d', 'bg_c', 'background-color')
					)
					),
					'h' => array(
					'options' => array(
						self::get_color(' .entry-date', 'b_c_d', 'bg_c', 'background-color')
					)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_font_family(' .entry-date', 'f_f_d'),
						self::get_color_type(' .entry-date','', 'f_c_t_d',  'f_c_d', 'f_g_c_d'),
						self::get_font_size(' .entry-date', 'f_s_d'),
						self::get_line_height(' .entry-date', 'l_h_d'),
						self::get_letter_spacing(' .entry-date', 'l_s_d'),
						self::get_text_align(' .entry-date', 't_a_d'),
						self::get_text_transform(' .entry-date', 't_t_d'),
						self::get_font_style(' .entry-date', 'f_st_d', 'f_w_d'),
						self::get_text_decoration(' .entry-date', 't_d_r_d'),
						self::get_text_shadow(' .entry-date','t_sh_d'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_font_family(' .entry-date', 'f_f_d_h'),
						self::get_color_type(' .entry-date','', 'f_c_t_d_h',  'f_c_d_h', 'f_g_c_d_h'),
						self::get_font_size(' .entry-date', 'f_s_d', '', 'h'),
						self::get_line_height(' .entry-date', 'l_h_d', 'h'),
						self::get_letter_spacing(' .entry-date', 'l_s_d', 'h'),
						self::get_text_align(' .entry-date', 't_a_d', 'h'),
						self::get_text_transform(' .entry-date', 't_t_d', 'h'),
						self::get_font_style(' .entry-date', 'f_st_d', 'f_w_d', 'h'),
						self::get_text_decoration(' .entry-date', 't_d_r_d', 'h'),
						self::get_text_shadow(' .entry-date','t_sh_d','h'),
					)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_padding(' .entry-date', 'p_d')
					)
					),
					'h' => array(
					'options' => array(
						self::get_padding(' .entry-date', 'p_d', 'h')
					)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_margin(' .entry-date', 'm_d')
					)
					),
					'h' => array(
					'options' => array(
						self::get_margin(' .entry-date', 'm_d', 'h')
					)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_border(' .entry-date', 'b_d')
					)
					),
					'h' => array(
					'options' => array(
						self::get_border(' .entry-date', 'b_d', 'h')
					)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .entry-date', 'r_c_d')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .entry-date', 'r_c_d', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .entry-date', 'sh_d')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .entry-date', 'sh_d', 'h')
						)
					)
				))
			)),

			// Month
			self::get_expand('Month', array_merge(
				self::get_display(' .tbp_post_month','m_disp'),
				array(self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_font_family(' .entry-date .tbp_post_month', 'f_f_d_m'),
						self::get_color_type(' .entry-date .tbp_post_month','', 'f_c_t_d_m',  'f_c_d_m', 'f_g_c_d_m'),
						self::get_font_size(' .entry-date .tbp_post_month', 'f_s_d_m'),
						self::get_line_height(' .entry-date .tbp_post_month', 'l_h_d_m'),
						self::get_letter_spacing(' .entry-date .tbp_post_month', 'l_s_d_m'),
						self::get_text_align(' .entry-date .tbp_post_month', 't_a_d_m'),
						self::get_text_transform(' .entry-date .tbp_post_month', 't_t_d_m'),
						self::get_font_style(' .entry-date .tbp_post_month', 'f_st_d_m', 'f_w_d_m'),
						self::get_text_decoration(' .entry-date .tbp_post_month', 't_d_r_d_m'),
						self::get_text_shadow(' .entry-date .tbp_post_month','t_sh_d_m'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_font_family(' .entry-date .tbp_post_month', 'f_f_d_m_h'),
						self::get_color_type(' .entry-date .tbp_post_month','', 'f_c_t_d_m_h',  'f_c_d_m_h', 'f_g_c_d_m_h'),
						self::get_font_size(' .entry-date .tbp_post_month', 'f_s_d_m', '', 'h'),
						self::get_line_height(' .entry-date .tbp_post_month', 'l_h_d_m', 'h'),
						self::get_letter_spacing(' .entry-date .tbp_post_month', 'l_s_d_m', 'h'),
						self::get_text_align(' .entry-date .tbp_post_month', 't_a_d_m', 'h'),
						self::get_text_transform(' .entry-date .tbp_post_month', 't_t_d_m', 'h'),
						self::get_font_style(' .entry-date .tbp_post_month', 'f_st_d_m', 'f_w_d_m', 'h'),
						self::get_text_decoration(' .entry-date .tbp_post_month', 't_d_r_d_m', 'h'),
						self::get_text_shadow(' .entry-date .tbp_post_month','t_sh_d_m','h'),
					)
					)
				)))
			)),

			// Day
			self::get_expand('Day', array_merge(
				self::get_display(' .tbp_post_day','d_disp'),
				array(self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_font_family(' .entry-date .tbp_post_day', 'f_f_d_d'),
						self::get_color_type(' .entry-date .tbp_post_day','', 'f_c_t_d_d',  'f_c_d_d', 'f_g_c_d_d'),
						self::get_font_size(' .entry-date .tbp_post_day', 'f_s_d_d'),
						self::get_line_height(' .entry-date .tbp_post_day', 'l_h_d_d'),
						self::get_letter_spacing(' .entry-date .tbp_post_day', 'l_s_d_d'),
						self::get_text_align(' .entry-date .tbp_post_day', 't_a_d_d'),
						self::get_text_transform(' .entry-date .tbp_post_day', 't_t_d_d'),
						self::get_font_style(' .entry-date .tbp_post_day', 'f_st_d_d', 'f_w_d_d'),
						self::get_text_decoration(' .entry-date .tbp_post_day', 't_d_r_d_d'),
						self::get_text_shadow(' .entry-date .tbp_post_day','t_sh_d_d'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_font_family(' .entry-date .tbp_post_day', 'f_f_d_d_h'),
						self::get_color_type(' .entry-date .tbp_post_day','', 'f_c_t_d_d_h',  'f_c_d_d_h', 'f_g_c_d_d_h'),
						self::get_font_size(' .entry-date .tbp_post_day', 'f_s_d_d', '', 'h'),
						self::get_line_height(' .entry-date .tbp_post_day', 'l_h_d_d', 'h'),
						self::get_letter_spacing(' .entry-date .tbp_post_day', 'l_s_d_d', 'h'),
						self::get_text_align(' .entry-date .tbp_post_day', 't_a_d_d', 'h'),
						self::get_text_transform(' .entry-date .tbp_post_day', 't_t_d_d', 'h'),
						self::get_font_style(' .entry-date .tbp_post_day', 'f_st_d_d', 'f_w_d_d', 'h'),
						self::get_text_decoration(' .entry-date .tbp_post_day', 't_d_r_d_d', 'h'),
						self::get_text_shadow(' .entry-date .tbp_post_day','t_sh_d_d','h'),
					)
					)
				)))
			)),

			// Year
			self::get_expand('Year', array_merge(
				self::get_display(' .tbp_post_year','y_disp'),
				array(self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_font_family(' .entry-date .tbp_post_year', 'f_f_d_y'),
						self::get_color_type(' .entry-date .tbp_post_year','', 'f_c_t_d_y',  'f_c_d_y', 'f_g_c_d_y'),
						self::get_font_size(' .entry-date .tbp_post_year', 'f_s_d_y'),
						self::get_line_height(' .entry-date .tbp_post_year', 'l_h_d_y'),
						self::get_letter_spacing(' .entry-date .tbp_post_year', 'l_s_d_y'),
						self::get_text_align(' .entry-date .tbp_post_year', 't_a_d_y'),
						self::get_text_transform(' .entry-date .tbp_post_year', 't_t_d_y'),
						self::get_font_style(' .entry-date .tbp_post_year', 'f_st_d_y', 'f_w_d_y'),
						self::get_text_decoration(' .entry-date .tbp_post_year', 't_d_r_d_y'),
						self::get_text_shadow(' .entry-date .tbp_post_year','t_sh_d_y'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_font_family(' .entry-date .tbp_post_year', 'f_f_d_y_h'),
						self::get_color_type(' .entry-date .tbp_post_year','', 'f_c_t_d_y_h',  'f_c_d_y_h', 'f_g_c_d_y_h'),
						self::get_font_size(' .entry-date .tbp_post_year', 'f_s_d_y', '', 'h'),
						self::get_line_height(' .entry-date .tbp_post_year', 'l_h_d_y', 'h'),
						self::get_letter_spacing(' .entry-date .tbp_post_year', 'l_s_d_y', 'h'),
						self::get_text_align(' .entry-date .tbp_post_year', 't_a_d_y', 'h'),
						self::get_text_transform(' .entry-date .tbp_post_year', 't_t_d_y', 'h'),
						self::get_font_style(' .entry-date .tbp_post_year', 'f_st_d_y', 'f_w_d_y', 'h'),
						self::get_text_decoration(' .entry-date .tbp_post_year', 't_d_r_d_y', 'h'),
						self::get_text_shadow(' .entry-date .tbp_post_year','t_sh_d_y','h'),
					)
					)
				)))
			)),

		);

		$comments = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color(' .tbp_post_meta_comments a', 'b_c_ct', 'bg_c', 'background-color')
					)
					),
					'h' => array(
					'options' => array(
						self::get_color(' .tbp_post_meta_comments a', 'b_c_ct', 'bg_c', 'background-color')
					)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_font_family(' .tbp_post_meta_comments a', 'f_f_ct'),
						self::get_color_type(' .tbp_post_meta_comments a','', 'f_c_t_ct',  'f_c_ct', 'f_g_c_ct'),
						self::get_font_size(' .tbp_post_meta_comments a', 'f_s_ct'),
						self::get_line_height(' .tbp_post_meta_comments a', 'l_h_ct'),
						self::get_letter_spacing(' .tbp_post_meta_comments a', 'l_s_ct'),
						self::get_text_align(' .tbp_post_meta_comments a', 't_a_ct'),
						self::get_text_transform(' .tbp_post_meta_comments a', 't_t_ct'),
						self::get_font_style(' .tbp_post_meta_comments a', 'f_st_ct', 'f_w_ct'),
						self::get_text_decoration(' .tbp_post_meta_comments a', 't_d_r_ct'),
						self::get_text_shadow(' .tbp_post_meta_comments a','t_sh_ct'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_font_family(' .tbp_post_meta_comments a', 'f_f_ct_h'),
						self::get_color_type(' .tbp_post_meta_comments a','', 'f_c_t_ct_h',  'f_c_ct_h', 'f_g_c_ct_h'),
						self::get_font_size(' .tbp_post_meta_comments a', 'f_s_ct', '', 'h'),
						self::get_line_height(' .tbp_post_meta_comments a', 'l_h_ct', 'h'),
						self::get_letter_spacing(' .tbp_post_meta_comments a', 'l_s_ct', 'h'),
						self::get_text_align(' .tbp_post_meta_comments a', 't_a_ct', 'h'),
						self::get_text_transform(' .tbp_post_meta_comments a', 't_t_ct', 'h'),
						self::get_font_style(' .tbp_post_meta_comments a', 'f_st_ct', 'f_w_ct', 'h'),
						self::get_text_decoration(' .tbp_post_meta_comments a', 't_d_r_ct', 'h'),
						self::get_text_shadow(' .tbp_post_meta_comments a','t_sh_ct','h'),
					)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_padding(' .tbp_post_meta_comments a', 'p_ct')
					)
					),
					'h' => array(
					'options' => array(
						self::get_padding(' .tbp_post_meta_comments a', 'p_ct', 'h')
					)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_margin(' .tbp_post_meta_comments a', 'm_ct')
					)
					),
					'h' => array(
					'options' => array(
						self::get_margin(' .tbp_post_meta_comments a', 'm_ct', 'h')
					)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_border(' .tbp_post_meta_comments a', 'b_ct')
					)
					),
					'h' => array(
					'options' => array(
						self::get_border(' .tbp_post_meta_comments a', 'b_ct', 'h')
					)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .tbp_post_meta_comments a', 'r_c_ct')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .tbp_post_meta_comments a', 'r_c_ct', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .tbp_post_meta_comments a', 'sh_ct')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .tbp_post_meta_comments a', 'sh_ct', 'h')
						)
					)
				))
			)),
		);
		
		$categories = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color(' .tbp_post_meta_terms a', 'b_c_cg', 'bg_c', 'background-color')
					)
					),
					'h' => array(
					'options' => array(
						self::get_color(' .tbp_post_meta_terms a', 'b_c_cg', 'bg_c', 'background-color', 'h')
					)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_font_family(' .tbp_post_meta_terms a', 'f_f_cg'),
						self::get_color_type(' .tbp_post_meta_terms a','', 'f_c_t_cg',  'f_c_cg', 'f_g_c_cg'),
						self::get_font_size(' .tbp_post_meta_terms a', 'f_s_cg'),
						self::get_line_height(' .tbp_post_meta_terms a', 'l_h_cg'),
						self::get_letter_spacing(' .tbp_post_meta_terms a', 'l_s_cg'),
						self::get_text_align(' .tbp_post_meta_terms a', 't_a_cg'),
						self::get_text_transform(' .tbp_post_meta_terms a', 't_t_cg'),
						self::get_font_style(' .tbp_post_meta_terms a', 'f_st_cg', 'f_w_cg'),
						self::get_text_decoration(' .tbp_post_meta_terms a', 't_d_r_cg'),
						self::get_text_shadow(' .tbp_post_meta_terms a','t_sh_cg'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_font_family(' .tbp_post_meta_terms a', 'f_f_cg_h'),
						self::get_color_type(' .tbp_post_meta_terms a:hover','', 'f_c_t_cg_h',  'f_c_cg_h', 'f_g_c_cg_h', 'h'),
						self::get_font_size(' .tbp_post_meta_terms a', 'f_s_cg', '', 'h'),
						self::get_line_height(' .tbp_post_meta_terms a', 'l_h_cg', 'h'),
						self::get_letter_spacing(' .tbp_post_meta_terms a', 'l_s_cg', 'h'),
						self::get_text_align(' .tbp_post_meta_terms a', 't_a_cg', 'h'),
						self::get_text_transform(' .tbp_post_meta_terms a', 't_t_cg', 'h'),
						self::get_font_style(' .tbp_post_meta_terms a', 'f_st_cg', 'f_w_cg', 'h'),
						self::get_text_decoration(' .tbp_post_meta_terms a', 't_d_r_cg', 'h'),
						self::get_text_shadow(' .tbp_post_meta_terms a','t_sh_cg','h'),
					)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_padding(' .tbp_post_meta_terms a', 'p_cg')
					)
					),
					'h' => array(
					'options' => array(
						self::get_padding(' .tbp_post_meta_terms a', 'p_cg', 'h')
					)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_margin(' .tbp_post_meta_terms a', 'm_cg')
					)
					),
					'h' => array(
					'options' => array(
						self::get_margin(' .tbp_post_meta_terms a', 'm_cg', 'h')
					)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_border(' .tbp_post_meta_terms a', 'b_cg')
					)
					),
					'h' => array(
					'options' => array(
						self::get_border(' .tbp_post_meta_terms a', 'b_cg', 'h')
					)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .tbp_post_meta_terms a', 'r_c_cg')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .tbp_post_meta_terms a', 'r_c_cg', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .tbp_post_meta_terms a', 'sh_cg')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .tbp_post_meta_terms a', 'sh_cg', 'h')
						)
					)
				))
			)),
		);

		$divider = array(
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_margin(' .tbp_post_meta > span:after', 'm_dr')
					)
					),
					'h' => array(
					'options' => array(
						self::get_margin(' .tbp_post_meta > span:hover:after', 'm_dr_h', '')
					)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_border(' .tbp_post_meta > span:after', 'b_dr')
					)
					),
					'h' => array(
					'options' => array(
						self::get_border(' .tbp_post_meta > span:hover:after', 'b_dr_h', '')
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
				'd' => array(
					'label' => __('Date', 'themify'),
					'options' => $date
				),
				'p_c' => array(
					'label' => __('Comments', 'themify'),
					'options' => $comments
				),
				'p_cg' => array(
					'label' => __('Terms Link', 'themify'),
					'options' => $categories
				),
				'p_d' => array(
					'label' => __('Divider', 'themify'),
					'options' => $divider
				)
			)
		);
	}

	public function get_default_settings() {
		return array(
			'tab_content_post_meta' => array(
				array(
					'type' => 'date',
					'show' => true,
					'val' => array(
						'format' => 'def'
					)
				),
				array(
					'type' => 'comments',
					'show' => true,
					'val' => array(
						'no' => __('No Comments', 'themify'),
						'one' => __('One Comments', 'themify'),
						'comments' => __('%s Comments', 'themify'),
						'l' => 'yes'
					)
				),
				array(
					'type' => 'author',
					'val' => array(
						'p_s' => 32,
						'a_p' => 'no',
						'l' => 'yes'
					)
				),
				array(
					'type' => 'terms',
					'show' => true,
					'val' => array(
						'post_type' => 'post',
						'taxonomy' => 'category',
						'sep' => ',',
						'l' => 'yes'
					)
				),
			)
		);
	}

	public function get_visual_type() {
		return 'ajax';
	}

    public function get_category() {
		return array( 'archive', 'single' );
	}

}

Themify_Builder_Model::register_module('TB_Post_Meta_Module');
