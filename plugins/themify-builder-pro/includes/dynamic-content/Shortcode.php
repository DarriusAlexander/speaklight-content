<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_Shortcode extends Tbp_Dynamic_Item {

	function get_category() {
		return 'general';
	}

	function get_type() {
		return array( 'text', 'textarea', 'wp_editor', 'url' );
	}

	function get_label() {
		return __( 'Shortcode', 'themify' );
	}

	function get_value( $args = array() ) {
		return !empty($args['shortcode'])?do_shortcode( $args['shortcode'] ):'';
	}

	function get_options() {
		return array(
			array(
				'label' => __( 'Shortcode', 'themify' ),
				'id' => 'shortcode',
				'type' => 'textarea'
			)
		);
	}
}
