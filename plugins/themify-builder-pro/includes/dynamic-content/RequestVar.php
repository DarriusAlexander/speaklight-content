<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_RequestVar extends Tbp_Dynamic_Item {

	function get_category() {
		return 'advanced';
	}

	function get_type() {
		return array( 'text', 'textarea', 'wp_editor', 'selectSearch' );
	}

	function get_label() {
		return __( 'Request Variable', 'themify' );
	}

	function get_value( $args = array() ) {
		$args = wp_parse_args( $args, array(
			'method' => 'get',
			'name' => '',
		) );

		$value = '';
		if ( $args['method'] === 'get' && isset( $_GET[ $args['name'] ] ) ) {
			$value = $_GET[ $args['name'] ];
		} elseif ( $args['method'] === 'post' && isset( $_POST[ $args['name'] ] ) ) {
			$value = $_POST[ $args['name'] ];
		}

		return $value;
	}

	function get_options() {
		return array(
			array(
				'label' => __( 'Method', 'themify' ),
				'id' => 'method',
				'type' => 'select',
				'options' => array(
					'get' => __( 'GET', 'themify' ),
					'post' => __( 'POST', 'themify' ),
				),
			),
			array(
				'label' => __( 'Name', 'themify' ),
				'id' => 'name',
				'type' => 'text',
			)
		);
	}
}
