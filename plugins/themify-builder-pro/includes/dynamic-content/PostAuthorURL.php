<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PostAuthorURL extends Tbp_Dynamic_Item {

	function get_category() {
		return 'post';
	}

	function get_type() {
		return array( 'text', 'textarea', 'wp_editor', 'url' );
	}

	function get_label() {
		return __( 'Post Author URL', 'themify' );
	}

	function get_value( $args = array() ) {
		$display = isset( $args['display'] ) ? $args['display'] : 'url';
		$value = '';
		$the_query = Tbp_Utils::get_actual_query();
		if($the_query===null || $the_query->have_posts()){
		    if($the_query!==null){
			$the_query->the_post();
		    }
		    $user_id = get_post_field( 'post_author');
		    if ( $args['display'] === 'url' ) {
				$value = get_the_author_meta( 'url', $user_id );
		    } else {
				$value = get_author_posts_url( $user_id );
		    }
		}
		if($the_query!==null){
		    wp_reset_postdata();
		}
		return $value;
	}

	function get_options() {
		return array(
			array(
				'label' => __( 'Display', 'themify' ),
				'id' => 'display',
				'type' => 'select',
				'options' => array(
					'url' => __( 'Website', 'themify' ),
					'archive' => __( 'Author Archive Page', 'themify' ),
				),
			)
		);
	}
}
