<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PTBAccordion extends Tbp_Dynamic_Item {

	function is_available() {
		return function_exists( 'run_ptb' );
	}

	function get_category() {
		return 'ptb';
	}

	function get_type() {
		return array( 'wp_editor' );
	}

	function get_label() {
		return __( 'PTB Custom Fields (Accordion)', 'themify' );
	}

	function get_value( $args = array() ) {
		$value = '';

		if ( ! empty( $args['field'] ) ) {
			$field_name = explode( ':', $args['field'] );
			$cf_value = get_post_meta( get_the_ID(), "ptb_{$field_name[1]}", true );
			if ( isset( $cf_value['title'] ) && is_array( $cf_value['title'] ) ) {
				$value .= "<div class='ptb_module ptb_accordion ptb_film_accordion_field'>";
					$value .= "<div class='ptb_extra_accordion ptb_extra_film_accordion_field'>";
						foreach ( $cf_value['title'] as $index => $title ) {
							$body = apply_filters( 'themify_builder_module_content', $cf_value['body'][ $index ] );
							$value .= "<div class='ptb_accordion_title'>{$title}</div>";
							$value .= "<div class='ptb_accordion_panel'>{$body}</div>";
						}
					$value .= "</div>";
			    $value .= "</div>";
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
					if ( $field['type'] === 'accordion' ) {
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