<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PTBRatingAsText extends Tbp_Dynamic_Item {

	function is_available() {
		return function_exists( 'run_ptb' );
	}

	function get_category() {
		return 'ptb';
	}

	function get_type() {
		return array( 'text', 'textarea' );
	}

	function get_label() {
		return __( 'PTB Custom Fields (Rating)', 'themify' );
	}

	function get_value( $args = array() ) {
		$value = '';
		if ( ! empty( $args['field'] ) ) {
			$show = isset( $args['show'] ) ? $args['show'] : 'total';
			list( $post_type, $field_name ) = explode( ':', $args['field'] );
			$cf_value = get_post_meta( get_the_ID(), "ptb_{$field_name}", true );
			if ( is_string( $cf_value ) ) {
				$value = $cf_value;
			} else if ( is_array( $cf_value ) && isset( $cf_value[ $show ] ) ) {
				$value = $cf_value[ $show ];
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
					if ( $field['type'] === 'rating' ) {
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
					'total' => __( 'Vote Result', 'themify' ),
					'count' => __( 'Vote Counts', 'themify' ),
				),
			),
		);
	}
}