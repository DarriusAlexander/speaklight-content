<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PostCommentCount extends Tbp_Dynamic_Item {

	function get_category() {
		return 'post';
	}

	function get_type() {
		return array( 'text', 'textarea', 'wp_editor' );
	}

	function get_label() {
		return __( 'Comment Count', 'themify' );
	}

	function get_value( $args = array() ) {
		if(empty($args['post_id'])){
		    $the_query = Tbp_Utils::get_actual_query();
		    if($the_query===null || $the_query->have_posts()){
			if($the_query!==null){
			    $the_query->the_post();
			}
			$value = get_comments_number();
		    }
		    if($the_query!==null){
			wp_reset_postdata();
		    }
		}
		else{
		    $value = get_comments_number( $args['post_id'] );
		}
		return $value;
	}
}
