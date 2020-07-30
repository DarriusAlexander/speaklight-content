<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PTBCheckbox extends Tbp_Dynamic_Item {

	function is_available() {
		return function_exists( 'run_ptb' );
	}

	function get_category() {
		return 'ptb';
	}

	function get_type() {
		return array( 'text', 'textarea', 'wp_editor' );
	}

	function get_label() {
		return __( 'PTB Custom Fields (Checkbox)', 'themify' );
	}

	function get_value( $args = array() ) {
		$values = array();
		$sep = isset( $args['sep'] ) ? $args['sep'] : '';
		if ( ! empty( $args['field'] ) ) {
			list( $post_type, $field_name ) = explode( ':', $args['field'] );
			$cf_value = (array) get_post_meta( get_the_ID(), "ptb_{$field_name}", true );
			$ptb = PTB::get_option()->get_options();
			if ( isset( $ptb['cpt'][ $post_type ]['meta_boxes'][ $field_name ]['options'] ) ) {
				$lang = PTB_Utils::get_current_language_code();
				foreach( $ptb['cpt'][ $post_type ]['meta_boxes'][ $field_name ]['options'] as $option ) {
					if ( in_array( $option['id'], $cf_value )  ) {
						$values[] = $option[ $lang ];
					}
				}
			}
		}

		return join( $sep, $values );
	}

	function get_options() {
		$options = array();

		/* collect "text" field types in all post types */
		$ptb = PTB::$options->get_custom_post_types();
		foreach ( $ptb as $post_type_key => $post_type ) {
			if ( is_array( $post_type->meta_boxes ) ) {
				foreach ( $post_type->meta_boxes as $key => $field ) {
					if ( $field['type'] === 'checkbox' ) {
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
				'label' => __( 'Separator', 'themify' ),
				'id' => 'sep',
				'type' => 'text',
				'help' => __( 'Character to separate items when multiple options are selected.', 'themify' ),
			),
		);
	}
}