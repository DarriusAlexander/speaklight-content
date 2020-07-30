<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_CurrentDate extends Tbp_Dynamic_Item {

	function get_category() {
		return 'general';
	}

	function get_type() {
		return array( 'text', 'textarea', 'wp_editor' );
	}

	function get_label() {
		return __( 'Current Date & Time', 'themify' );
	}

	function get_value( $args = array() ) {
		$args = wp_parse_args( $args, array(
			'date_format' => 'F j, Y',
			'custom_date_format' => '',
		) );
		return $args['date_format'] === 'custom' ? date_i18n( $args['custom_date_format'] ):date_i18n( $args['date_format'] );
	}

	function get_options() {
		return array(
			array(
				'label' => __( 'Date Format', 'themify' ),
				'id' => 'date_format',
				'type' => 'select',
				'options' => array(
					'F j, Y' => date_i18n( 'F j, Y' ),
					'Y-m-d'  => date_i18n( 'Y-m-d' ),
					'm/d/Y'  => date_i18n( 'm/d/Y' ),
					'd/m/Y'  => date_i18n( 'd/m/Y' ),
					'custom' => __( 'Custom', 'themify' ),
				),
				'binding' =>array(
				  'not_empty' => array( 'hide' => array( 'custom_date_format' ) ),
				  'custom' => array( 'show' => array( 'custom_date_format' ) )
				)
			),
			array(
				'label' => __( 'Custom Date Format', 'themify' ),
				'id' => 'custom_date_format',
				'type' => 'text',
				'help' => sprintf( __( 'For information on how to format date and time see <a href="%s" target="_blank">Codex</a>.', 'themify' ), 'https://codex.wordpress.org/Formatting_Date_and_Time' )
			),
		);
	}
}
