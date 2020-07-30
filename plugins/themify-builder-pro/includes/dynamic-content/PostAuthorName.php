<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PostAuthorName extends Tbp_Dynamic_Item {

	function get_category() {
		return 'post';
	}

	function get_type() {
		return array( 'text', 'textarea', 'wp_editor' );
	}

	function get_label() {
		return __( 'Post Author Name', 'themify' );
	}

	function get_value( $args = array() ) {
		$value='';
		$the_query = Tbp_Utils::get_actual_query();
		if($the_query===null || $the_query->have_posts()){
		    if($the_query!==null){
			$the_query->the_post();
		    }
		    $user_id = get_post_field( 'post_author');
			$value = get_the_author_meta( 'display_name', $user_id );
		}
		if($the_query!==null){
		    wp_reset_postdata();
		}
		return $value;
	}
}
