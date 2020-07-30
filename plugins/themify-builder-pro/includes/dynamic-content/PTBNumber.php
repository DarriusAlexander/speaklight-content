<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PTBNumber extends Tbp_Dynamic_Item {

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
		return __( 'PTB Custom Fields (Number)', 'themify' );
	}

	function get_value( $args = array() ) {
		$value='';
		if(!empty($args['field'])){
		    $the_query = Tbp_Utils::get_actual_query();
		    if($the_query===null || $the_query->have_posts()){
			if($the_query!==null){
			    $the_query->the_post();
			}

				$field_name = explode( ':', $args['field'] );
				$value = get_post_meta(get_the_ID(), "ptb_{$field_name[1]}", true );

				$display = isset( $args['decimals'] ) ? $args['decimals'] : 'from';
				$decimals = isset( $args['decimals'] ) ? (int) $args['decimals'] : 2;
				$dec_point = isset( $args['dec_point'] ) ? $args['dec_point'] : '.';
				$thousands_sep = isset( $args['thousands_sep'] ) ? $args['thousands_sep'] : ',';
				if ( is_array( $value ) ) { // range input
					$value = $value[ $display ];
				}
				$value = number_format( floatval( $value ), $decimals, $dec_point, $thousands_sep );

		    }
		    if($the_query!==null){
			wp_reset_postdata();
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
					
					if ( $field['type'] === 'number' ) {
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
				'label' => __( 'Display', 'themify' ),
				'id' => 'display',
				'type' => 'select',
				'options' => array(
					'from' => __( 'From', 'themify' ),
					'to' => __( 'To', 'themify' ),
				),
				'help' => __( 'In Range inputs, select to display the From value or To value.', 'themify' ),
			),
			array(
				'label' => __( 'Decimals', 'themify' ),
				'id' => 'decimals',
				'type' => 'text',
			),
			array(
				'label' => __( 'Decimal Point', 'themify' ),
				'id' => 'dec_point',
				'type' => 'text',
			),
			array(
				'label' => __( 'Thousands Separator', 'themify' ),
				'id' => 'thousands_sep',
				'type' => 'text',
			),
		);
	}
}