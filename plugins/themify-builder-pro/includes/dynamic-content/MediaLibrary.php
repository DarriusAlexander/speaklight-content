<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_MediaLibrary extends Tbp_Dynamic_Item {

	function get_category() {
		return 'general';
	}

	function get_type() {
		return array( 'image', 'url' );
	}

	function get_label() {
		return __( 'Item from Media Library', 'themify' );
	}

	function get_value( $args = array() ) {
		$value='';
		if(isset($args['attachment_id'])){
		    $value = wp_get_attachment_url( $args['attachment_id'] );
		    if(!$value){
			$value='';
		    }
		}
		return $value;
	}

	function get_options() {
		return array(
			array(
				'label' => __( 'ID', 'themify' ),
				'id' => 'attachment_id',
				'type' => 'number'
			),
		);
	}
}
