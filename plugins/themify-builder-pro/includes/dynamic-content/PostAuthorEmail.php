<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PostAuthorEmail extends Tbp_Dynamic_Item {

	function get_category() {
		return 'post';
	}

	function get_type() {
		return array( 'text', 'textarea', 'wp_editor', 'url' );
	}

	function get_label() {
		return __( 'Post Author Email', 'themify' );
	}

	function get_value( $args = array() ) {
		$value = '';
		$the_query = Tbp_Utils::get_actual_query();
		if($the_query===null || $the_query->have_posts()){
		    if($the_query!==null){
			$the_query->the_post();
		    }
		    $user_id = get_post_field( 'post_author');
		    $value = get_the_author_meta( 'email', $user_id );
		}
		if($the_query!==null){
		    wp_reset_postdata();
		}
		if ( ! empty( $value ) ) {
			$mailto = isset( $args['mailto'] ) && $args['mailto'] === 'y' ? 'mailto:' : '';
			$value = $mailto . $value;
		}
		return $value;
	}

	function get_options() {
		return array(
			array(
				'label' => __( 'Prepend "mailto:"?', 'themify' ),
				'id' => 'mailto',
				'type' => 'select',
				'options' => array(
					'n' => __( 'No', 'themify' ),
					'y' => __( 'Yes', 'themify' ),
				),
			)
		);
	}
}
