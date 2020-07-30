<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Module Name: Featured Image
 * Description: 
 */

class TB_Featured_Image_Module extends Themify_Builder_Component_Module {

    function __construct() {
		parent::__construct(array(
		    'name' => __('Featured Image', 'themify'),
		    'slug' => 'featured-image',
		    'category' => array('single')
		));
    }

    public function get_options() {
		return array(
			array(
				'id' => 'image_w',
				'type' => 'number',
				'label' => __('Image Width', 'themify')
			),
			array(
				'id' => 'auto_fullwidth',
				'type' => 'checkbox',
				'label' => '',
				'options' => array(array('name' => '1', 'value' => __('Auto fullwidth image', 'themify'))),
				'wrap_class' => 'auto_fullwidth'
			),
			array(
				'id' => 'image_h',
				'type' => 'number',
				'label' => __('Image Height', 'themify')
			),
			array(
				'id' => 'appearance_image',
				'type' => 'checkbox',
				'label' => __('Appearance', 'themify'),
				'img_appearance'=>true
			),
			array(
			    'type'=>'advacned_link'
			),
	                array(
			    'type'    => 'fallback'
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
						self::get_image(' img', 'b_i','bg_c','b_r','b_p')
					)
					),
					'h' => array(
					'options' => array(
						self::get_image(' img', 'b_i','bg_c','b_r','b_p', 'h')
					)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_padding(' img', 'p')
					)
					),
					'h' => array(
					'options' => array(
						self::get_padding(' img', 'p', 'h')
					)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_margin(' img', 'm')
					)
					),
					'h' => array(
					'options' => array(
						self::get_margin(' img', 'm', 'h')
					)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_border(' img', 'b')
					)
					),
					'h' => array(
					'options' => array(
						self::get_border(' img', 'b', 'h')
					)
					)
				))
			)),
			// Filter
			self::get_expand('f_l',
				array(
					self::get_tab(array(
						'n' => array(
							'options' => count($a = self::get_blend(' img'))>2 ? array($a) : $a
						),
						'h' => array(
							'options' => count($a = self::get_blend(' img','bl_m_h','h'))>2 ? array($a + array('ishover'=>true)) : $a
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
							self::get_border_radius(' img', 'r_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' img', 'r_c', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' img', 'sh')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' img', 'sh', 'h')
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
			'lightbox_w_unit' => '%',
			'lightbox_h_unit' => '%',
			'fallback_s' => 'no'
		);
	}

	public function get_visual_type() {
		return 'ajax';
    }

    public function get_category() {
		return array( 'single', 'archive', 'page' );
	}

}

Themify_Builder_Model::register_module('TB_Featured_Image_Module');
