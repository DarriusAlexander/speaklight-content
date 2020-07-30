<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Module Name: Fittext
 * Description: Display responsive heading
 */
class TB_Fittext_Module extends Themify_Builder_Component_Module {
	function __construct() {
		parent::__construct(array(
			'name' => __('FitText', 'builder-fittext'),
			'slug' => 'fittext',
			'category' => array('addon')
		));
	}

	function get_assets() {
		$instance = Builder_Fittext::get_instance();
		return array(
			'selector' => '.module-fittext, .module-type-fittext',
			'css' => themify_enque($instance->url . 'assets/style.css'),
			'js' => themify_enque($instance->url . 'assets/fittext.js'),
			'ver' => $instance->version,
			'external' => Themify_Builder_Model::localize_js( 'builderFittext', array(
				'webSafeFonts' => themify_get_web_safe_font_list( true )
			) )
		);
	}

	public function get_options() {
		return array(
			array(
				'id' => 'fittext_text',
				'type' => 'text',
				'label' => __('Text', 'builder-fittext'),
				'class' => 'fullwidth'
			),
			array(
				'id' => 'fittext_link',
				'type' => 'url',
				'label' => __('Link', 'builder-fittext'),
				'class' => 'fullwidth',
                                'binding' => array(
					'empty' => array(
						'hide' => array('fittext_params')
					),
					'not_empty' => array(
						'show' => array('fittext_params')
					)
				),
			),
			array(
				'id' => 'fittext_params',
				'type' => 'radio',
				'label' => '',
				'options' => array(
				    array('value'=>'','name'=>__('Same window', 'builder-button')),
				    array('value'=>'lightbox','name'=> __('Open link in lightbox', 'builder-button')),
				    array('value'=>'newtab','name'=>__('Open link in new tab', 'builder-button'))
				),
				'control' => false,
				'wrap_class' => 'tb_compact_radios',
			),
			array(
			    'id' => 'add_css_fittext',
			    'type' => 'custom_css'
			),
			array('type' => 'custom_css_id')
		);
	}

	public function get_default_settings() {
		return array(
			'fittext_text' => __( 'FitText Heading', 'builder-fittext' )
		);
	}


	public function get_styling() {
		/*START temp solution when the addon is new,the FW is old 09.03.19*/
		if(version_compare(THEMIFY_VERSION, '4.5', '<')){
		    return array(); 
		}
		return array(
			//bacground
			self::get_expand('bg', array(
			    self::get_tab(array(
				'n' => array(
				    'options' => array(
					self::get_color('', 'background_color','bg_c', 'background-color'),
				    )
				),
				'h' => array(
				    'options' => array(
					self::get_color('', 'bg_c', 'bg_c', 'background-color', 'h')
				    )
				)
			    ))
			)),
			self::get_expand('f', array(
			    self::get_tab(array(
				'n' => array(
				    'options' => array(
					self::get_font_family( array( '', ' span', ' a' )),
					self::get_color_type(array( ' span', ' a' )),
					self::get_text_align( array( '', ' span', ' a' )),
					self::get_text_transform(array( '', ' span', ' a' )),
					self::get_font_style(array( '', ' span', ' a' )),
                    self::get_text_shadow( array( '', ' span', ' a' )),
				    )
				),
				'h' => array(
				    'options' => array(
					self::get_color_type(array(' span', ' a' ),'h'),
						self::get_text_shadow( array( '', ' span', ' a' ),'t_sh','h'),

				    )
				)
			    ))
			)),
			self::get_expand('l', array(
			    self::get_tab(array(
				'n' => array(
				    'options' => array(
					self::get_color(' a','link_color'),
					self::get_text_decoration()
				    )
				),
				'h' => array(
				    'options' => array(
					self::get_color(' a','link_color',null,null,'hover'),
					self::get_text_decoration('', 't_d','h')
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
			),
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
	    );
	}

	protected function _visual_template() {
        ?>
	<#var f = data.font_family;
	    if(f && f!=='default'){
	    var w = data.font_family_w?data.font_family_w:'400,700';
		f+=':'+w;
	    }
	#>
	    <div class="module module-<?php echo $this->slug; ?> {{ data.add_css_fittext }}" data-font-family="<# print(f) #>">
		<# if( data.fittext_link ) { #><a href="#"><# } #>
		<span>{{{ data.fittext_text }}}</span>
		<# if( data.fittext_link ) { #></a><# } #>
	    </div>
	<?php
	}
}

Themify_Builder_Model::register_module( 'TB_Fittext_Module' );
