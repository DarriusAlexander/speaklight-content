<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */

class Tbp_Dynamic_Item_PTBDate extends Tbp_Dynamic_Item {

	function is_available() {
		return function_exists( 'run_ptb' );
	}

	function get_category() {
		return 'ptb';
	}

	function get_type() {
		return array( 'date' );
	}

	function get_label() {
		return __( 'PTB Custom Fields (Date)', 'themify' );
	}

	function get_value( $args = array() ) {
		$value = '';
		$args = wp_parse_args( $args, array(
			'show' => 'start_date',
		) );

		if ( ! empty( $args['field'] ) ) {
			list( $post_type, $field_name ) = explode( ':', $args['field'] );
			$value = get_post_meta( get_the_ID(), "ptb_{$field_name}", true );

			// 'range' date fields
			if ( is_array( $value ) ) {
				$value = $value[ $args['show'] ];
			}
		}

		return $value;
	}

	function get_options() {
		$options = array();

		/* collect "text" field types in all post types */
		$ptb = PTB::$options->get_custom_post_types();
		foreach ( $ptb as $post_type_key => $post_type ) {
			if ( is_array( $post_type->meta_boxes ) ) {
				foreach ( $post_type->meta_boxes as $key => $field ) {
					if ( $field['type'] === 'event_date' ) {
					    $label = PTB_Utils::get_label( $post_type->plural_label );
					    $name = PTB_Utils::get_label( $field['name'] );
					    $options[ "{$post_type_key}:{$key}" ] = sprintf( '%s: %s', $label, $name );
					}
				}
			}
		}

		return array(
			array(
				'label' => __( 'Field', 'themify' ),
				'id' => 'field',
				'type' => 'select',
				'options' => $options,
			),
			array(
				'label' => __( 'Show', 'themify' ),
				'id' => 'show',
				'type' => 'select',
				'options' => array(
					'start_date' => __( 'Start Date', 'themify' ),
					'end_date' => __( 'End Date', 'themify' ),
				),
			),
		);
	}
}