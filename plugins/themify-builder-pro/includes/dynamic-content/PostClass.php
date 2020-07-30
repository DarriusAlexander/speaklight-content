<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PostClass extends Tbp_Dynamic_Item {

	function get_category() {
		return 'post';
	}

	function get_type() {
		return array( 'custom_css' );
	}

	function get_label() {
		return __( 'Post CSS Class', 'themify' );
	}

	function get_value( $args = array() ) {
		if(empty($args['post_id'])){
		    $the_query = Tbp_Utils::get_actual_query();
		    if($the_query===null || $the_query->have_posts()){
			if($the_query!==null){
			    $the_query->the_post();
			}
			$value = get_post_class('');
		    }
		    if($the_query!==null){
			wp_reset_postdata();
		    }
		}
		else{
		    $value = get_post_class('', $args['post_id'] );
		}
		return implode( ' ', $value );
	}

	function get_options() {
		return array(
			array(
				'label' => __( 'Post ID', 'themify' ),
				'id' => 'post_id',
				'type' => 'number',
				'help' => __( 'Leave empty to get the data from current post in the loop.', 'themify' ),
			),
		);
	}
}
