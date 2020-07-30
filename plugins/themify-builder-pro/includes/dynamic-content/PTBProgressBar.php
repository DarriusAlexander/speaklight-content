<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PTBProgressBar extends Tbp_Dynamic_Item {

	function is_available() {
		return function_exists( 'run_ptb' );
	}

	function get_category() {
		return 'ptb';
	}

	function get_type() {
		return array( 'text', 'textarea', 'wp_editor', 'number', 'range' );
	}

	function get_label() {
		return __( 'PTB Custom Fields (Progress Bar)', 'themify' );
	}

	function get_value( $args = array() ) {
		$value = '';
		if ( ! empty( $args['field'] ) ) {
			$show = isset( $args['show'] ) ? $args['show'] : 'value';
			list( $post_type, $field_name, $option_id ) = explode( ':', $args['field'] );
			$cf_value = get_post_meta( get_the_ID(), "ptb_{$field_name}", true );
			if ( $show === 'label' ) {
				$ptb = PTB::get_option()->get_options();
				if ( isset( $ptb['cpt'][ $post_type ]['meta_boxes'][ $field_name ]['options'] ) ) {
					$lang = PTB_Utils::get_current_language_code();
					foreach( $ptb['cpt'][ $post_type ]['meta_boxes'][ $field_name ]['options'] as $option ) {
						if ( $option_id === $option['id'] ) {
							$value = $option[ $lang ];
						}
					}
				}
			} else {
				if ( isset( $cf_value[ $option_id ] ) ) {
					$value = $cf_value[ $option_id ];
				}
			}
		}

		return $value;
	}

	function get_options() {
		$options = array();

		/* collect "progress_bar" field types in all post types */
		$ptb = PTB::$options->get_custom_post_types();
		foreach ( $ptb as $post_type_key => $post_type ) {
			if ( is_array( $post_type->meta_boxes ) ) {
				foreach ( $post_type->meta_boxes as $key => $field ) {
					if ( $field['type'] === 'progress_bar' && is_array( $field['options'] ) ) {
						$post_type_label = PTB_Utils::get_label( $post_type->plural_label );
						$field_name = PTB_Utils::get_label( $field['name'] );
						$lang = PTB_Utils::get_current_language_code();
						foreach ( $field['options'] as $option ) {
							$options[ "{$post_type_key}:{$key}:{$option['id']}" ] = sprintf( '%s: %s: %s', $post_type_label, $field_name, $option[ $lang ] );
						}
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
					'value' => __( 'Value', 'themify' ),
					'label' => __( 'Label', 'themify' ),
				),
			),
		);
	}
}