<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PostDate extends Tbp_Dynamic_Item {

	function get_category() {
		return 'post';
	}

	function get_type() {
		return array( 'text', 'textarea', 'wp_editor' );
	}

	function get_label() {
		return __( 'Post Published Date', 'themify' );
	}

	function get_value( $args = array() ) {
	    if (!isset($args['date_format']) || $args['date_format'] === 'default' ) {
		    $date_format = get_option( 'date_format' );
	    } elseif ( $args['date_format'] === 'custom' ) {
		    $date_format = isset($args['custom_date_format'])?$args['custom_date_format']:'';
	    } else {
		    $date_format = $args['date_format'];
	    }
	    if(empty($args['post_id'])){
		$the_query = Tbp_Utils::get_actual_query();
		if($the_query===null || $the_query->have_posts()){
		    if($the_query!==null){
			$the_query->the_post();
		    }
		    $value = get_the_date($date_format);
		}
		if($the_query!==null){
		    wp_reset_postdata();
		}
	    }
	    else{
		$value = get_the_date( $date_format,$args['post_id'] );
	    }
	    return $value;
		
	}

	function get_options() {
		return array(
			array(
				'label' => __( 'Date Format', 'themify' ),
				'id' => 'date_format',
				'type' => 'select',
				'options' => array(
					'default' => __( 'Default', 'themify' ),
					'F j, Y' => date_i18n( 'F j, Y' ),
					'Y-m-d'  => date_i18n( 'Y-m-d' ),
					'm/d/Y'  => date_i18n( 'm/d/Y' ),
					'd/m/Y'  => date_i18n( 'd/m/Y' ),
					'custom' => __( 'Custom', 'themify' ),
				),
				'binding' =>array(
				  'not_empty'=>array('hide'=>array('custom_date_format')),
				  'custom'=>array('show'=>array('custom_date_format'))
				)
			),
			array(
				'label' => __( 'Custom Date Format', 'themify' ),
				'id' => 'custom_date_format',
				'type' => 'text',
				'help' => sprintf( __( 'For information on how to format date and time see <a href="%s" target="_blank">Codex</a>.', 'themify' ), 'https://codex.wordpress.org/Formatting_Date_and_Time' )
			),
		);
	}
}
