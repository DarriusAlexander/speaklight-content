<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Module Name: Product Image
 * Description: 
 */

class TB_Product_Image_Module extends Themify_Builder_Component_Module {

    function __construct() {
		parent::__construct(array(
		    'name' => __('Product Image', 'themify'),
		    'slug' => 'product-image',
			'category' => array('product_single')
		));
    }

    public function get_options() {
	    return array(
		    array(
			    'id' => 'image_w',
			    'type' => 'number',
			    'control'=>array(
				'event'=>'change'
			    ),
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
			    'control'=>array(
				'event'=>'change'
			    ),
			    'label' => __('Image Height', 'themify')
		    ),
			array(
				'id' => 'thumb_image',
				'type' => 'layout',
				'label' => __('Thumbnail Layout', 'themify'),
				'mode' => 'sprite',
				'options' => array(
					array('img' => 'thumb_img_bottom', 'value' => 'thumb-img-bottom', 'label' => __('Default', 'themify')),
					array('img' => 'thumb_img_left', 'value' => 'thumb-img-left', 'label' => __('Thumb Image Left', 'themify')),
				)
			),
			array(
				'id' => 'thumb_image_w',
				'type' => 'number',
				'control'=>array(
					'event'=>'change'
				),
				'label' => __('Thumbnail', 'themify'),
				'after' => __('Width', 'themify')
			),
			array(
				'id' => 'thumb_image_h',
				'type' => 'number',
				'control'=>array(
					'event'=>'change'
				),
				'label' => '',
				'after' => __('Height', 'themify')
			),
		    array(
			    'id' => 'appearance_image',
			    'type' => 'checkbox',
			    'label' => __('Appearance', 'themify'),
			    'img_appearance'=>true
		    ),
		    array(
			    'id'      => 'sale_b',
			    'type'    => 'toggle_switch',
			    'label' => __( 'Sale Badge', 'themify' ),
			    'options'   => array(
				    'on'  => array( 'name' => 'yes', 'value' => 's' ),
				    'off' => array( 'name' => 'no', 'value' => 'hi' ),
			    ),
			    'binding' => array(
				    'checked' => array(
					    'show' => array( 'badge_pos' )
				    ),
				    'not_checked' => array(
					    'hide' => array( 'badge_pos' )
				    )
			    )
		    ),
		    array(
			    'label' => '',
			    'after' => __( 'Badge Position', 'themify' ),
			    'id' => 'badge_pos',
			    'type' => 'select',
			    'options' => array(
				    'left' => __( 'Left', 'themify' ),
				    'right'  => __( 'Right', 'themify' )
			    )
		    ),
		    array(
			    'type'    => 'fallback'
		    ),
			array(
				'id'      => 'zoom',
				'type'    => 'toggle_switch',
				'label' => __( 'Image Zoom', 'themify' ),
				'default' => 'on',
				'options'   => array(
					'on'  => array( 'name' => 'yes', 'value' => 'en' ),
					'off' => array( 'name' => 'no', 'value' => 'dis' ),
				)
			),
		    array(
			    'id'      => 'hover_image',
			    'type'    => 'toggle_switch',
			    'label' => __( 'Product Image Hover', 'themify' ),
			    'options'   => array(
				    'on'  => array( 'name' => 'yes', 'value' => 'en' ),
				    'off' => array( 'name' => 'no', 'value' => 'dis' ),
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
						self::get_image('.module img', 'b_i','bg_c','b_r','b_p')
					)
					),
					'h' => array(
					'options' => array(
						self::get_image('.module img', 'b_i','bg_c','b_r','b_p', 'h')
					)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_padding('.module img', 'p')
					)
					),
					'h' => array(
					'options' => array(
						self::get_padding('.module img', 'p', 'h')
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
							'options' => count($a = self::get_blend(' img','fl'))>2 ? array($a) : $a
						),
						'h' => array(
							'options' => count($a = self::get_blend(' img','fl_h','h'))>2 ? array($a + array('ishover'=>true)) : $a
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
							self::get_box_shadow('.module .woocommerce-product-gallery__wrapper', 'sh')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow('.module .woocommerce-product-gallery__wrapper', 'sh', 'h')
						)
					)
				))
			)),
			// Position
			self::get_expand('po', array( self::get_css_position())),
			// Display
			self::get_expand('disp', self::get_display())
		);

		$sale_badge = array(
			// Background
			self::get_expand('bg', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_color('.module .onsale', 'b_c_s_b', 'bg_c', 'background-color')
				)
				),
				'h' => array(
				'options' => array(
					self::get_color('.module .onsale', 'b_c_s_b', 'bg_c', 'background-color', 'h')
				)
				)
			))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color('.module .onsale', 'f_c_s_b'),
						self::get_font_size('.module .onsale', 'f_s_s_b', ''),
					)
					),
					'h' => array(
					'options' => array(
						self::get_color('.module .onsale', 'f_c_s_b', 'h'),
						self::get_font_size('.module .onsale', 'f_s_s_b', '', 'h'),
					)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_padding('.module .onsale', 'p_s_b')
				)
				),
				'h' => array(
				'options' => array(
					self::get_padding('.module .onsale', 'p_s_b', 'h')
				)
				)
			))
			)),
			// Margin
			self::get_expand('m', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_margin('.module .onsale', 'm_s_b')
				)
				),
				'h' => array(
				'options' => array(
					self::get_margin('.module .onsale', 'm_s_b', 'h')
				)
				)
			))
			)),
			// Border
			self::get_expand('b', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_border('.module .onsale', 'b_s_b')
				)
				),
				'h' => array(
				'options' => array(
					self::get_border('.module .onsale', 'b_s_b', 'h')
				)
				)
			))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius('.module .onsale', 'r_c_s_b')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius('.module .onsale', 'r_c_s_b', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow('.module .onsale', 'sh_s_b')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow('.module .onsale', 'sh_s_b', 'h')
						)
					)
				))
			))

		);
		

		$thumbnails = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color('.module div.product div.images .flex-control-thumbs li img', 'b_c_tb', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color('.module div.product div.images .flex-control-thumbs li img', 'b_c_tb', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding('.module div.product div.images .flex-control-thumbs li img', 'p_tb')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding('.module div.product div.images .flex-control-thumbs li img', 'p_tb', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin('.module div.product div.images .flex-control-thumbs li', 'm_tb')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin('.module div.product div.images .flex-control-thumbs li', 'm_tb', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border('.module div.product div.images .flex-control-thumbs li img', 'b_tb')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border('.module div.product div.images .flex-control-thumbs li img', 'b_tb', 'h')
						)
					)
				))
			)),
			// Width
			self::get_expand('w', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_width('.module div.product div.images .flex-control-thumbs li', 'w_tb')
						)
					),
					'h' => array(
						'options' => array(
							self::get_width('.module div.product div.images .flex-control-thumbs li', 'w_tb', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius('.module div.product div.images .flex-control-thumbs li img', 'r_c_tb')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius('.module div.product div.images .flex-control-thumbs li img', 'r_c_tb', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow('.module div.product div.images .flex-control-thumbs li img', 'sh_tb')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow('.module div.product div.images .flex-control-thumbs li img', 'sh_tb', 'h')
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
				'tb' => array(
					'label' => __('Thumbnails', 'themify'),
					'options' => $thumbnails
				),
				's' => array(
					'label' => __('Sale Badge', 'themify'),
					'options' => $sale_badge
				),
			)
		);
	}

	public function get_default_settings() {
		return array(
			'lightbox_w_unit' => '%',
			'lightbox_h_unit' => '%',
			'sale_b' => 'yes',
			'zoom' => 'yes'
		);
	}

	public function get_visual_type() {
		return 'ajax';
    }

    public function get_category() {
		return array( 'product' );
	}

}

if ( themify_is_woocommerce_active()) {
	Themify_Builder_Model::register_module('TB_Product_Image_Module');
}
