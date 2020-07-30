<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PostFeaturedImage extends Tbp_Dynamic_Item {

	function get_category() {
		return 'post';
	}

	function get_type() {
		return array( 'image', 'url' );
	}

	function get_label() {
		return __( 'Post Featured Image', 'themify' );
	}

	function get_value( $args = array() ) {
		$size = isset($args['size'])?$args['size']:'thumbnail';
		if(empty($args['post_id'])){
		    $the_query = Tbp_Utils::get_actual_query();
		    if($the_query===null || $the_query->have_posts()){
			if($the_query!==null){
			    $the_query->the_post();
			}
			$value = get_the_post_thumbnail_url(null,$size);
		    }
		    if($the_query!==null){
			wp_reset_postdata();
		    }
		}
		else{
		    $value = get_the_post_thumbnail_url($args['post_id'],$size);
		}
		return !empty($value)?$value:'';
	}

	function get_options() {
		return array(
			array(
				'label' => __( 'Size', 'themify' ),
				'id' => 'size',
				'type' => 'select',
				'options' => themify_get_image_sizes_list( false ),
			),
			array(
				'label' => __( 'Post ID', 'themify' ),
				'id' => 'post_id',
				'type' => 'number',
				'help' => __( 'Leave empty to get the data from current post in the loop.', 'themify' ),
			),
		);
	}
}
