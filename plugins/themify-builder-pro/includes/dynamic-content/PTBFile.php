<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PTBFile extends Tbp_Dynamic_Item {

	function is_available() {
		return function_exists( 'run_ptb' );
	}

	function get_category() {
		return 'ptb';
	}

	function get_type() {
		return array( 'text', 'textarea', 'wp_editor', 'url', 'file' );
	}

	function get_label() {
		return __( 'PTB Custom Fields (File)', 'themify' );
	}

	function get_value( $args = array() ) {
		$value = '';
		if ( ! empty( $args['field'] ) ) {
			$show = isset( $args['show'] ) ? $args['show'] : 'url';
			$n = ! empty( $args['n'] ) ? ( (int) $args['n'] - 1 ) : 0;
			$field_name = explode( ':', $args['field'] );
			$cf_value = get_post_meta( get_the_ID(), "ptb_{$field_name[1]}", true );
			if ( isset( $cf_value[ $show ][ $n ] ) && is_array( $cf_value[ $show ] ) ) {
			    $value = $cf_value[ $show ][ $n ];
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
					if ( $field['type'] === 'file' ) {
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
					'url' => __( 'File URL', 'themify' ),
					'title' => __( 'Title', 'themify' ),
				),
			),
			array(
				'label' => __( '# Item', 'themify' ),
				'id' => 'n',
				'type' => 'text',
				'help' => __( 'Number of item in repeatable fields to show.', 'themify' ),
			),
		);
	}
}