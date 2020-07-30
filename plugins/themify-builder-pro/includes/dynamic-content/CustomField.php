<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_CustomField extends Tbp_Dynamic_Item {

	function get_category() {
		return 'advanced';
	}

	function get_type() {
		return array( 'text', 'textarea', 'image', 'wp_editor', 'selectSearch', 'url', 'custom_css' );
	}

	function get_label() {
		return __( 'Custom Field', 'themify' );
	}

	function get_value( $args = array() ) {
		$value = '';
		if(!empty($args['custom_field'])){
		    $the_query = Tbp_Utils::get_actual_query();
		    if($the_query===null || $the_query->have_posts()){
			if($the_query!==null){
			    $the_query->the_post();
			}
			$value = get_post_meta( get_the_id(), $args['custom_field'], true );
			if ( isset( $args['custom_field_shortcode'] ) && $args['custom_field_shortcode'] === 'yes' ) {
			    $value = do_shortcode( $value );
			}
		    }
		    if($the_query!==null){
			wp_reset_postdata();
		    }
		}
		return $value;
	}

	function get_options() {
		return array(
			array(
				'label' => __( 'Custom Field', 'themify' ),
				'id' => 'custom_field',
				'type' => 'autocomplete',
				'dataset' => 'custom_fields',
			),
			array(
				'label' => __( 'Enable Shortcodes', 'themify' ),
				'id' => 'custom_field_shortcode',
				'type' => 'select',
				'options' => array(
					'no' => __( 'No', 'themify' ),
					'yes' => __( 'Yes', 'themify' ),
				),
				'help' => __( 'Enable parsing shortcodes in the custom field value.', 'themify' ),
			)
		);
	}
}
