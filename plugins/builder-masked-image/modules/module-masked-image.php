<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Module Name: Image Pro
 * Description: 
 */
class TB_masked_Image_Module extends Themify_Builder_Component_Module {
	function __construct() {
		parent::__construct(array(
			'name' => __( 'Masked Image', 'builder-masked-image' ),
			'slug' => 'masked-image',
			'category' => array('addon')
		));
	}

	function get_assets() {
		$instance = Builder_Masked_Image::get_instance();
		return array(
			'selector' => '.module.module-masked-image',
			'css' => themify_enque($instance->url.'assets/style.css'),
			'js' => themify_enque($instance->url.'assets/scripts.js'),
			'ver' => $instance->version
		);
	}

	public function get_options() {
		$path = Builder_Masked_Image::get_instance()->url;

		$options = array(
			array(
				'id' => 'mod_title_image',
				'type' => 'title'
			),
			self::get_seperator(),
			array(
				'id' => 'style_image',
				'type' => 'layout',
				'label' => __('Image Alignment', 'builder-masked-image'),
				'mode' => 'sprite',
				'options' => array(
					array('img' => 'image_top', 'value' => 'image-top', 'label' => __('Image Top', 'builder-masked-image')),
					array('img' => 'image_left', 'value' => 'image-left', 'label' => __('Image Left', 'builder-masked-image')),
					array('img' => 'image_right', 'value' => 'image-right', 'label' => __('Image Right', 'builder-masked-image')),
					array('img' => 'image_center', 'value' => 'image-center', 'label' => __('Centered Image', 'builder-masked-image')),
				)
			),
			array(
				'id' => 'url_image',
				'type' => 'image',
				'label' => __( 'Image URL', 'builder-masked-image' ),
				'class' => 'fullwidth'
			),
			array(
				'id' => 'mask_type',
				'type' => 'radio',
				'label' => __( 'Mask Type', 'builder-masked-image' ),
				'options' => array(
				    array('name'=>__( 'Image', 'builder-masked-image' ),'value'=>'image'),
				    array('name'=>__( 'Icon', 'builder-masked-image' ),'value'=>'icon')
				),
				'option_js' => true
			),
			array(
				'id' => 'mask_preset',
				'type' => 'layout',
				'label' => __('Mask Image', 'builder-masked-image'),
				'mode' => 'sprite',
				'wrap_class' => 'tb_group_element_image',
				'options' => array(
					array('img' => $path . 'assets/presets/badge.svg', 'value' => $path . 'assets/presets/badge.svg', 'label' => __('badge.svg', 'builder-masked-image')),
					array('img' => $path . 'assets/presets/award-badge.svg', 'value' => $path . 'assets/presets/award-badge.svg', 'label' => __('award-badge.svg', 'builder-masked-image')),
					array('img' => $path . 'assets/presets/clover.svg', 'value' => $path . 'assets/presets/clover.svg', 'label' => __('clover.svg', 'builder-masked-image')),
					array('img' => $path . 'assets/presets/comment.svg', 'value' => $path . 'assets/presets/comment.svg', 'label' => __('comment.svg', 'builder-masked-image')),
					array('img' => $path . 'assets/presets/hand.svg', 'value' => $path . 'assets/presets/hand.svg', 'label' => __('hand.svg', 'builder-masked-image')),
					array('img' => $path . 'assets/presets/love.svg', 'value' => $path . 'assets/presets/love.svg', 'label' => __('love.svg', 'builder-masked-image')),
					array('img' => $path . 'assets/presets/paint-splash.svg', 'value' => $path . 'assets/presets/paint-splash.svg', 'label' => __('paint-splash.svg', 'builder-masked-image')),
					array('img' => $path . 'assets/presets/price-tag.svg', 'value' => $path . 'assets/presets/price-tag.svg', 'label' => __('price-tag.svg', 'builder-masked-image')),
					array('img' => $path . 'assets/presets/rocket.svg', 'value' => $path . 'assets/presets/rocket.svg', 'label' => __('rocket.svg', 'builder-masked-image')),
					array('img' => $path . 'assets/presets/sixtagon.svg', 'value' => $path . 'assets/presets/sixtagon.svg', 'label' => __('sixtagon.svg', 'builder-masked-image')),
					array('img' => $path . 'assets/presets/star.svg', 'value' => $path . 'assets/presets/star.svg', 'label' => __('star.svg', 'builder-masked-image')),
					array('img' => $path . 'assets/presets/thumb-up.svg', 'value' => $path . 'assets/presets/thumb-up.svg', 'label' => __('thumb-up.svg', 'builder-masked-image')),
					array('img' => $path . 'assets/presets/tilt-left.svg', 'value' => $path . 'assets/presets/tilt-left.svg', 'label' => __('tilt-left.svg', 'builder-masked-image')),
					array('img' => $path . 'assets/presets/tilt-right.svg', 'value' => $path . 'assets/presets/tilt-right.svg', 'label' => __('tilt-right.svg', 'builder-masked-image')),
					array('img' => $path . 'assets/presets/water-drop.svg', 'value' => $path . 'assets/presets/water-drop.svg', 'label' => __('water-drop.svg', 'builder-masked-image')),
					array('img' => $path . 'assets/presets/wing.svg', 'value' => $path . 'assets/presets/wing.svg', 'label' => __('wing.svg', 'builder-masked-image')),
				),
				'control' => false
			),
			array(
				'id' => 'mask_image',
				'type' => 'image',
				'label' => __('Mask Image URL', 'builder-masked-image'),
				'class' => 'fullwidth',
				'wrap_class' => 'tb_group_element_image',
				'help' => __( 'Use a PNG or SVG image, transparent pixels are treated as mask. Alpha channel is supported.', 'builder-masked-image' ),
			),
			array(
				'id' => 'mask_icon',
				'type' => 'icon',
				'label' => '',
				'wrap_class' => 'tb_group_element_icon',
				'class' => 'fullwidth',
			),
			array(
				'id' => 'mask_icon_data',
				'type' => 'textarea',
				'label' => ''
			),
			array(
				'id' => 'mask_feather',
				'type' => 'text',
				'label' => __('Mask Feather (blur)', 'builder-masked-image'),
				'class' => 'xsmall',
				'after' => 'px'
			),
			array(
				'id' => 'mask_flip',
				'type' => 'select',
				'label' =>  __( 'Flip Mask Image', 'builder-masked-image' ),
				'options' => array(
					'none' => __( 'None', 'builder-masked-image' ),
					'horizontal' => __( 'Horizontal', 'builder-masked-image' ),
					'vertical' => __( 'Vertical', 'builder-masked-image' ),
					'both' => __( 'Both', 'builder-masked-image' ),
				)
			),
			array(
				'id' => 'image_size_image',
				'type' => 'select',
				'label' =>  __( 'Image Size', 'builder-masked-image' ),
				'hide' => !Themify_Builder_Model::is_img_php_disabled(),
				'image_size' => true
			),
					array(
						'id' => 'width_image',
						'type' => 'number',
						'label' => 'w',
						'after' => 'px',
					),
					array(
						'id' => 'auto_fullwidth',
						'type' => 'checkbox',
						'label' => '',
						'wrap_class' => 'auto_fullwidth',
						'options'=>array(array('name'=>'1','value'=>__('Auto fullwidth', 'builder-masked-image'))),
			),
			array(
				'id' => 'height_image',
				'type' => 'number',
				'label' => 'ht',
				'after' => 'px'
			),
			array(
				'id' => 'title_image',
				'type' => 'text',
				'label' => __('Image Title', 'builder-masked-image'),
				'class' => 'fullwidth',
				'control' => array(
					'selector'=>'.image-title'
				)
			),
			array(
				'id' => 'link_image',
				'type' => 'url',
				'label' => __('Image Link', 'builder-masked-image'),
				'class' => 'fullwidth',
				'binding' => array(
					'empty' => array(
						'hide' => array('param_image', 'image_zoom_icon', 'lightbox_size')
					),
					'not_empty' => array(
						'show' => array('param_image', 'image_zoom_icon', 'lightbox_size')
					)
				)
			),
			array(
				'id' => 'param_image',
				'type' => 'radio',
				'label' => __('Open Link In', 'builder-masked-image'),
				'link_type' => true,
				'new_line' => false,
				'option_js' => true,
				'control' => false
			),
			array(
				'type' => 'multi',
				'label' => __('Lightbox Dimension', 'builder-masked-image'),
				'options' => array(
					array(
						'id' => 'lightbox_width',
						'type' => 'number',
						'label' => 'w',
						'control' => false
					),
					array(
						'id' => 'lightbox_size_unit_width',
						'type' => 'select',
						'label' => __( 'Units', 'builder-masked-image' ),
						'options' => array(
							'pixels' => __('px ', 'builder-masked-image'),
							'percents' => __('%', 'builder-masked-image')
						),
						'control' => false
					),
					array(
						'id' => 'lightbox_height',
						'type' => 'number',
						'label' => 'ht',
						'control' => false
					),
					array(
						'id' => 'lightbox_size_unit_height',
						'type' => 'select',
						'label' => __( 'Units', 'builder-masked-image' ),
						'options' => array(
							'pixels' => __('px ', 'builder-masked-image'),
							'percents' => __('%', 'builder-masked-image')
						),
						'control' => false
					)
				),
				'wrap_class' => 'tb_group_element_lightbox'
			),
			array(
				'id' => 'caption_image',
				'type' => 'textarea',
				'label' => __('Image Caption', 'builder-masked-image'),
				'class' => 'fullwidth',
				'control' => array(
					'selector'=>'.image-caption'
				),
				'binding' => array(
					'empty' => array(
						'hide' => array('gutter_group')
					),
					'not_empty' => array(
						'show' => array('gutter_group')
					)
				),
			),
					array(
						'id' => 'gutter_image',
						'type' => 'number',
						'label' => __('Horizontal Gap', 'builder-masked-image'),
						'after' => 'px'
					),
					array(
						'id' => 'gutter_h_image',
						'type' => 'number',
						'label' => __('Vertical Gap', 'builder-masked-image'),
						'after' => 'px'
					),
			array(
				'id' => 'alt_image',
				'type' => 'text',
				'label' => __('Image Alt Tag', 'builder-masked-image'),
				'class' => 'fullwidth',
				'control' => false
			),
			array(
			    'id' => 'css_image',
			    'type' => 'custom_css'
			),
			array('type' => 'custom_css_id')
		);
		return $options;
	}

	public function get_default_settings() {
		$url = Builder_Masked_Image::get_instance()->url;
		return array(
			'url_image' => $url . 'assets/sample-image.jpg',
			'mask_image' => $url. 'assets/presets/badge.svg',
                        'mask_flip'=>'none',
			'width_image' => 350,
			'height_image' => 275,
			'title_image' => __( 'Image Title', 'builder-masked-image' ),
			'overlay_effect' => 'fadeIn',
			'image_alignment' => 'image_alignment_left'
		);
	}

	public function get_styling() {
		/*START temp solution when the addon is new,the FW is old 09.03.19*/
		if(version_compare(THEMIFY_VERSION, '4.5', '<')){
		    return array(); 
		}
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
					self::get_font_family(array(' ', ' .image-title')),
					self::get_color_type(array('.module .image-title','.module .image-caption')),
					self::get_font_size(),
					self::get_line_height(),
					self::get_letter_spacing(),
					self::get_text_align(),
					self::get_text_transform(),
					self::get_font_style(),
					self::get_text_decoration('','text_decoration_regular'),
                    self::get_text_shadow(array(' ', ' .image-title')),
				    )
				),
				'h' => array(
				    'options' => array(
					self::get_font_family(array(' ', ' .image-title'),'f_f','h'),
					self::get_color_type(array('.module .image-title','.module .image-caption'),'h'),
					self::get_font_size('','f_s','','h'),
					self::get_line_height('','l_h','h'),
					self::get_letter_spacing('','l_s','h'),
					self::get_text_align('','t_a','h'),
					self::get_text_transform('','t_t','h'),
					self::get_font_style('','f_st','f_w','h'),
					self::get_text_decoration('','t_d_r','h'),
                    self::get_text_shadow(array(' ', ' .image-title'),'t_sh','h'),
				    )
				)
			    ))
			)),
			// Link
			self::get_expand('l', array(
			    self::get_tab(array(
				'n' => array(
				    'options' => array(
					self::get_color(' a','link_color'),
					self::get_text_decoration(' a')
				    )
				),
				'h' => array(
				    'options' => array(
					self::get_color(' a','link_color',null,null,'hover'),
					self::get_text_decoration(' a', 't_d','h')
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
                // Height & Min Height
				self::get_expand('ht', array(
						self::get_height(),
						self::get_min_height(),
						self::get_max_height()
					)
				),				// Rounded Corners
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
		);

		$image_title = array(
			// Font
			self::get_expand('f', array(
			    self::get_tab(array(
				'n' => array(
				    'options' => array(
					self::get_font_family(array(' .image-title', ' .image-title a'), 'font_family_title'),
					self::get_color(array(' .image-title', ' .image-title a'), 'font_color_title'),
					self::get_font_size(' .image-title', 'font_size_title'),
					self::get_line_height(' .image-title', 'line_height_title'),
					self::get_letter_spacing(' .image-title', 'letter_spacing_title'),
					self::get_text_transform(' .image-title', 'text_transform_title'),
					self::get_font_style(' .image-title', 'font_style_title','font_title_bold'),
                    self::get_text_shadow(array(' .image-title', ' .image-title a'), 't_sh_i_t'),
				    )
				),
				'h' => array(
				    'options' => array(
					self::get_font_family(array(' .image-title', ' .image-title a'), 'f_f_t'),
					self::get_color(array(' .image-title', ' .image-title a'), 'font_color_title',null,null,'hover'),
					self::get_font_size(' .image-title', 'f_s_t','','h'),
					self::get_line_height(' .image-title', 'l_h_t','h'),
					self::get_letter_spacing(' .image-title', 'l_s_t','h'),
					self::get_text_transform(' .image-title', 't_t_t','h'),
					self::get_font_style(' .image-title', 'f_st_t','f_t_b','h'),
                    self::get_text_shadow(array(' .image-title', ' .image-title a'), 't_sh_i_t','h'),
				    )
				)
			    ))
			)),
			// Padding
			self::get_expand('p', array(
			    self::get_tab(array(
				'n' => array(
				    'options' => array(
					self::get_padding(' .image-title', 'image_title_padding')
				    )
				),
				'h' => array(
				    'options' => array(
					self::get_padding(' .image-title', 'i_t_p','h')
				    )
				)
			    ))
			)),
			// Margin
			self::get_expand('m', array(
			    self::get_tab(array(
				'n' => array(
				    'options' => array(
					self::get_margin(' .image-title', 'i_t_m')
				    )
				),
				'h' => array(
				    'options' => array(
					self::get_margin(' .image-title', 'i_t_m','h')
				    )
				)
			    ))
			))
		);

		$image_caption = array(
			// Font
			self::get_expand('f', array(
			    self::get_tab(array(
				'n' => array(
				    'options' => array(
					self::get_font_family(' .image-caption', 'font_family_caption'),
					self::get_color(' .image-caption', 'font_color_caption'),
					self::get_font_size(' .image-caption', 'font_size_caption'),
					self::get_line_height(' .image-caption', 'line_height_caption'),
                    self::get_text_shadow(' .image-caption', 't_sh_i_c'),
				    )
				),
				'h' => array(
				    'options' => array(
					self::get_font_family(' .image-caption', 'f_f_c','h'),
					self::get_color(' .image-caption', 'f_c_c',null,null,'h'),
					self::get_font_size(' .image-caption', 'f_s_c','','h'),
					self::get_line_height(' .image-caption', 'l_h_c','h'),
                    self::get_text_shadow(' .image-caption', 't_sh_i_c','h'),
				    )
				)
			    ))
			)),
			// Padding
			self::get_expand('p', array(
			    self::get_tab(array(
				'n' => array(
				    'options' => array(
					self::get_padding(' .image-caption', 'image_caption_padding')
				    )
				),
				'h' => array(
				    'options' => array(
					self::get_padding(' .image-caption', 'i_c_p','h')
				    )
				)
			    ))
			)),
			// Margin
			self::get_expand('m', array(
			    self::get_tab(array(
				'n' => array(
				    'options' => array(
					self::get_margin(' .image-caption', 'image_caption_margin')
				    )
				),
				'h' => array(
				    'options' => array(
					self::get_margin(' .image-caption', 'i_c_m','h')
				    )
				)
			    ))
			))
		);

		return array('type' => 'tabs',
				'options' => array(
					'g' => array(
						'options' => $general
					),
					'm_t' => array(
						'options' => $this->module_title_custom_style()
					),
					't' => array(
						'label' => __('Image Title', 'builder-masked-image'),
						'options' => $image_title
					),
					'c' => array(
						'label' => __('Image Caption', 'builder-masked-image'),
						'options' => $image_caption
					)
				)
		);
	}

	protected function _visual_template() {
		$module_args = self::get_module_args();?>

		<# var fullwidth = data.auto_fullwidth == '1' && ( data.style_image == 'image-top' || data.style_image == 'image-center' ) ? 'auto_fullwidth' : ''; #>
		<div class="module module-<?php echo $this->slug; ?> {{ fullwidth }} {{ data.style_image }}">
                        <# if ( data.mod_title_image ) { #>
				<?php echo $module_args['before_title']; ?>{{{ data.mod_title_image }}}<?php echo $module_args['after_title']; ?>
			<# }
			var style='';
			if( ! fullwidth ) {
				style = 'width:' + ( data.width_image ? data.width_image + 'px;' : 'auto;' );
				style += 'height:' + ( data.height_image ? data.height_image + 'px;' : 'auto;' );
			}
			var image = '<img src="'+ data.url_image +'" style="' + style + '"/>',
							mask = data.mask_image;
			if ( data.mask_type == 'icon' && data.mask_icon_data !== '' ) {
				mask = 'data:image/svg+xml;charset=UTF-8,' + data.mask_icon_data;
			}
			#>
			<div class="bmi-image-wrap">
				<div class="bmi-image">
					<a href="{{ data.link_image }}">
						{{{ image }}}
						<canvas data-mask="{{ mask }}" data-gutter="{{ data.gutter_image }}" data-gutter-h="{{ data.gutter_h_image }}" data-transparency="{{ data.transparency_effect }}" data-mask-flip="{{ data.mask_flip }}" data-feather="{{ data.mask_feather }}"></canvas>
					</a>
				</div>
			</div>

			<# if ( data.caption_image || data.title_image ) { #>
			<div class="bmi-text-wrap"><div class="bmi-text"><# if ( data.caption_image || data.title_image ) { #><h3 class="image-title">{{{ data.title_image }}}</h3><# } #><div class="image-caption">{{{ data.caption_image }}}</div></div></div>
			<# } #>
		</div>
	<?php
	}
}

Themify_Builder_Model::register_module( 'TB_masked_Image_Module' );
