<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_Option extends Tbp_Dynamic_Item {

	function get_category() {
		return 'advanced';
	}

	function get_type() {
		return array( 'text', 'textarea', 'wp_editor', 'selectSearch', 'url' );
	}

	function get_label() {
		return __( 'Option', 'themify' );
	}

	function get_value( $args = array() ) {
		$value = ! empty( $args['option_name'] )?get_option( $args['option_name'] ):'';
		return ! empty( $value )? $value : '';
	}

	function get_options() {
		return array(
			array(
				'label' => __( 'Option Name', 'themify' ),
				'id' => 'option_name',
				'type' => 'text',
				'help' => sprintf( __( 'You can see a list of options in <a href="%s" target="_blank">Options admin page</a>.', 'themify' ), admin_url( 'options.php' ) )
			)
		);
	}
}
