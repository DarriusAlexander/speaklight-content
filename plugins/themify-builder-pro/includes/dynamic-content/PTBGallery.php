<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PTBGallery extends Tbp_Dynamic_Item {

	function is_available() {
		return function_exists( 'run_ptb' );
	}

	function get_category() {
		return 'ptb';
	}

	function get_type() {
		return array( 'gallery', 'wp_editor' );
	}

	function get_label() {
		return __( 'PTB Custom Fields (Gallery)', 'themify' );
	}

	function get_value( $args = array() ) {
		$value = '';
		if ( ! empty( $args['field'] ) ) {
			$field_name = explode( ':', $args['field'] );
			$value = get_post_meta( get_the_ID(), "ptb_{$field_name[1]}", true );
			if ( isset( $value['url'] ) && is_array( $value['url'] ) ) {
			    $ids = array_filter( array_map( 'attachment_url_to_postid', $value['url'] ) );
			    $value = '[gallery ids="' . implode( ',', $ids ) . '"]';
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
					if ( $field['type'] === 'gallery' || $field['type'] === 'slider' ) {
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