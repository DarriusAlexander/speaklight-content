<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PostAuthorMeta extends Tbp_Dynamic_Item {

	function get_category() {
		return 'post';
	}

	function get_type() {
		return array( 'text', 'textarea', 'wp_editor' );
	}

	function get_label() {
		return __( 'Post Author Meta', 'themify' );
	}

	function get_value( $args = array() ) {
		$value='';
		if(!empty($args['key'])){
		    $the_query = Tbp_Utils::get_actual_query();
		    if($the_query===null || $the_query->have_posts()){
			if($the_query!==null){
			    $the_query->the_post();
			}
			$user_id = get_post_field( 'post_author' );
			if(!empty($user_id)){
			    $value = get_user_meta( $user_id, $args['key'], true );
			}
		    }
		    if($the_query!==null){
			wp_reset_postdata();
		    }
		}
		return $value;
	}

	function get_options() {
		return array(
			array(
				'label' => __( 'Meta Key', 'themify' ),
				'id' => 'key',
				'type' => 'text',
			),
		);
	}
}
