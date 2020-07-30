<?php
/**
 * Maps PTB's "icon" field type to "icon" field in Builder
 *
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PTBIconAsIcon extends Tbp_Dynamic_Item {

	function is_available() {
		return function_exists( 'run_ptb' );
	}

	function get_category() {
		return 'ptb';
	}

	function get_type() {
		return array( 'icon' );
	}

	function get_label() {
		return __( 'PTB Custom Fields', 'themify' );
	}

	function get_value( $args = array() ) {
		$value = '';
		if ( ! empty( $args['field'] ) ) {
			$show = 'icon';
			$field_name = explode( ':', $args['field'] );
			$cf_value = get_post_meta( get_the_ID(), "ptb_{$field_name[1]}", true );
			if ( isset( $cf_value[ $show ][0] ) && is_array( $cf_value[ $show ] ) ) {
			    $value = themify_get_icon( $cf_value[ $show ][0] );
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
					if ( $field['type'] === 'icon' ) {
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
		);
	}
}