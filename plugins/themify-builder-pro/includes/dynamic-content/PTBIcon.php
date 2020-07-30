<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PTBIcon extends Tbp_Dynamic_Item {

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
		return __( 'PTB Custom Fields (Icons)', 'themify' );
	}

	function get_value( $args = array() ) {
		$value = '';

		$args = wp_parse_args( $args, array(
			'size' => 'medium',
		) );

		if ( ! empty( $args['field'] ) ) {
			$field_name = explode( ':', $args['field'] );
			$cf_value = get_post_meta( get_the_ID(), "ptb_{$field_name[1]}", true );
			if ( isset( $cf_value['icon'] ) && is_array( $cf_value['icon'] ) ) {
			    $value .= "<ul class='ptb_extra_icons ptb_extra_icons_{$args['size']}'>";
				foreach ( $cf_value['icon'] as $index => $icon ) {
					$icon = themify_get_icon( $icon );
					$color = isset( $cf_value['color'][ $index ] ) ? "style='color: {$cf_value['color'][ $index ]};'" : '';
					$value .= "<li class='ptb_extra_icon'>";
					if ( ! empty( $cf_value['url'][ $index ] ) ) {
						$value .= "<a href='{$cf_value['url'][ $index ]}' {$color} class='ptb_extra_icon_link'>";
					}
					$value .= "<i {$color} class='{$icon}'></i>";
					if ( ! empty( $cf_value['label'][ $index ] ) ) {
						$value .= "<span {$color} class='ptb_extra_icon_label'>{$cf_value['label'][ $index ]}</span>";
					}
					if ( ! empty( $cf_value['url'][ $index ] ) ) {
						$value .= "</a>";
					}
					$value .= "</li>";
				}
			    $value .= "</ul>";
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
			array(
				'label' => __( 'Icon Size', 'themify' ),
				'id' => 'size',
				'type' => 'select',
				'options' => array(
					'medium' => __( 'Medium', 'themify' ),
					'small' => __( 'Small', 'themify' ),
					'large' => __( 'Large', 'themify' ),
				),
			),
		);
	}
}