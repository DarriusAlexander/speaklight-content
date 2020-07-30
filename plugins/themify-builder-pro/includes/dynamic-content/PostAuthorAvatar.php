<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PostAuthorAvatar extends Tbp_Dynamic_Item {

	function get_category() {
		return 'post';
	}

	function get_type() {
		return array( 'image' );
	}

	function get_label() {
		return __( 'Post Author Avatar', 'themify' );
	}

	function get_value( $args = array() ) {
		$size=isset( $args['size'] ) ?(int)$args['size'] :96;
		$the_query = Tbp_Utils::get_actual_query();
		$value='';
		if($the_query===null || $the_query->have_posts()){
		    if($the_query!==null){
			$the_query->the_post();
		    }
		    $user_id = get_post_field( 'post_author');
		    $value = get_avatar_url( $user_id, array( 'size' =>$size) );
		}
		if($the_query!==null){
		    wp_reset_postdata();
		}
		return $value;
	}

	function get_options() {
		return array(
			array(
				'label' => __( 'Size', 'themify' ),
				'id' => 'size',
				'type' => 'number',
				'class'=>'large',
				'help' => __( 'Height and width of the avatar in pixels. Default is 96.', 'themify' )
			),
		);
	}
}
